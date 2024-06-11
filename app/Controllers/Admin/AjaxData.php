<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AjaxData extends BaseController
{

	public function fixImageSizes(){
		$imageModel = model(Image::class);
		$images = $imageModel->orderBy('id', 'asc')->findAll();
		foreach($images as $image){
			$source_file = config("ImagesConfig")->site_upload_path.'/original_images/'.$image['file_name'];
			if (!is_file($source_file)){
				continue;
			}
			if($image['extension'] == 'svg'){
				$xmlget = simplexml_load_string(file_get_contents($source_file));
				$xmlattributes = $xmlget->attributes();
				$width = (string) $xmlattributes->width; 
				$height = (string) $xmlattributes->height;
				if(!empty($width) && !empty($height)){
					$imageModel->update($image['id'],array('width'=>$width,'height'=>$height));
				}
			}else{
				$info = \Config\Services::image()
					->withFile(realpath($source_file))
					->getFile()
					->getProperties(true);
				if(!empty($info['width']) && !empty($info['height'])){
					$imageModel->update($image['id'],array('width'=>$info['width'],'height'=>$info['height']));
				}
			}
		}
	}

	public function getPermalink($check_id = NULL){
		if (!$this->request->isAJAX()) {
            $resp = array('resp'=>false,'msg'=>'Δεν επιτρέπεται η πρόσβαση');
			echo json_encode($resp);return;die();
        }
		if (empty($_POST['value'])) {
            $resp = array('resp'=>false,'msg'=>'Δεν έχετε δώσει κείμενο.');
			echo json_encode($resp);return;die();
        }
		$ex_permalink = check_permalink(get_permalink($_POST['value']),$check_id);
		echo json_encode($ex_permalink);return;die();
	}
	
    public function editLock(){
        if (!$this->request->isAJAX()) {
            $resp = array('resp'=>false,'msg'=>'Δεν επιτρέπεται η πρόσβαση');
			echo json_encode($resp);return;die();
        }
		if (empty($_POST['type']) || empty($_POST['type_id'])) {
            $resp = array('resp'=>false,'msg'=>'Προέκυψε ένα πρόβλημα στην αποθήκευση locking.');
			echo json_encode($resp);return;die();
        }
		$editingLock = model(EditingLock::class);
		$existing_lock = $editingLock->getLock($_POST['type'],$_POST['type_id']);
		if(!empty($existing_lock) && $existing_lock['user_id'] != $this->session->get('loggedUser')['id']){
			echo json_encode(array('resp'=>false,'msg'=>'Ο χρήστης '.$existing_lock['username'].' έχει κάνει ανάληψη.'));return;die();
		}
		$editingLock->saveLock($_POST['type'],$_POST['type_id'],$this->session->get('loggedUser')['id'],time());
		echo json_encode(array('resp'=>true));return;die();
	}
	
	public function imageBank(){
		if (!$this->request->isAJAX()) {
            $resp = array('resp'=>false,'msg'=>'Δεν επιτρέπεται η πρόσβαση');
			echo json_encode($resp);return;die();
        }
		$dbModel = model(Image::class);
		$s_array = array();
		if(!empty($_POST['term']) || !empty($_POST['page'])){
			$page = (empty($_POST['page'])?1:$_POST['page']);
			if(!empty($_POST['term'])) $s_array['term'] = $_POST['term'];
			$images = $dbModel->getPaginatedList($page,$s_array);
			echo view('admin/media_library/image_bank_content', array('images'=>$images));
		}else{			
			$images = $dbModel->getPaginatedList(1,$s_array);
			echo view('admin/media_library/image_bank', array('images'=>$images));
		}
		return;die();
	}
	

	public function getTinymceInsertImage(){
		$data = array();
		echo view('admin/widgets/tinymce_header', $data);
		echo view('admin/widgets/tinymce_image', $data);
		$data['load_full'] = true;
		$data['load_js'] = array('custom_tinymce_image.js');
		echo view('admin/widgets/tinymce_footer', $data);
	}

	public function getTinymceShortcode($shortcode){
		$data = array();
		$data['hide_close'] = true;
		$data['load_full'] = true;
		$data['shortcode'] = $shortcode;
		echo view('admin/widgets/tinymce_header', $data);
		echo view('admin/widgets/tinymce_shortcode_attrs', $data);
		$data['load_js'] = array('tinymce_shortcodes.js');
		echo view('admin/widgets/tinymce_footer', $data);
	}

	public function getJsonShortcodeAttrs(){
		$values = array();
		if(isset($_POST['tabs'])){
			foreach($_POST['tabs'] as $i=>$tab){
				$tab_value = array();
				foreach($tab as $k=>$v){
					if(empty($v)) continue;
					if($k == 'article_ids'){ //multiple tokeninput
						if(is_string($v) && strpos($v,'__token|token__') !== false){
							$v = explode('__token|token__',$v);
						}else{
							$v = array(0=>$v);
						}
					}
					$tab_value[$k] = $v;
				}
				$values['tabs'][$i] = $tab_value;
			}
		}else{
			foreach($_POST as $k=>$v){
				if(empty($v)) continue;
				if($k == 'article_ids'){ //multiple tokeninput
					if(is_string($v) && strpos($v,'__token|token__') !== false){
						$v = explode('__token|token__',$v);
					}else{
						$v = array(0=>$v);
					}
				}
				$values[$k] = $v;
			}
		}
		return json_encode($values);die();
	}

	public function getTinymceShortcodeAttrs($shortcode,$partial = false){
		$shortcodes = getShortcodes();
		if(!isset($shortcodes[$shortcode])){
			echo 'Δεν βρέθηκε το shortcode.';return;die();
		}
		$data = array(
			'values'=>(empty($_POST['json_data']) ? array() : json_decode($_POST['json_data'],true)),
			'shortcode'=>$shortcode,
			'shortcode_attrs'=>$shortcodes[$shortcode],
			'shortcode_values'=>array()
		);
		$shortcode_fields = (empty($shortcodes[$shortcode]['tabbed_content']) ? $shortcodes[$shortcode]['attrs'] : $shortcodes[$shortcode]['attrs']['tabs']['attrs']);
		
		//load values for tokeninput fields
		$dataValues = (empty($shortcodes[$shortcode]['tabbed_content']) ? array('tabs'=>array(0 => $data['values'])) : $data['values']);
		$token_values = array();
		if(!empty($shortcode_fields) && !empty($dataValues['tabs'])){
			foreach($dataValues['tabs'] as $tabId=>$tabValues){
				foreach($shortcode_fields as $tid=>$tattrs){
					if(!empty($tabValues[$tid]) && !empty($tattrs['dataSourceType'])){
						//print_r($data['values'][$tid]); continue;
						switch($tattrs['dataSourceType']){
							/*case 'library': $newLibrary = '\App\\Libraries\\'.$tattrs['sourceName'];
								$loadedLib = new $newLibrary;
								$data['shortcode_values'][$attr_id] = call_user_func(array($loadedLib, $tattrs['sourceFunction']));
								break;*/
							case 'model': $newModel = '\App\\Models\\'.$tattrs['dataSourceName'];
								$loadedModel = new $newModel;
								if($tattrs['multiple']){
									foreach($tabValues[$tid] as $aid){
										if(isset($token_values[$tid][$aid]) || empty($aid)) break;
										$loadedValues = $loadedModel->find($aid);
										if(empty($loadedValues)) continue;
										$token_values[$tid][$aid] = $loadedValues[$tattrs['dataSourceField']];
									}
								}else{
									if(isset($token_values[$tid][$tabValues[$tid]])) break;
									$loadedValues = $loadedModel->find($data['values'][$tid]);
									if(!empty($loadedValues)) $token_values[$tid][$tabValues[$tid]] = $loadedValues[$tattrs['dataSourceField']];
								}
								break;
							default: break;
						}
					}
				}
			}
		}
		$data['token_values'] = $token_values;
		
		foreach($shortcode_fields as $attr_id=>$tattrs){
			if(!empty($data['shortcode_values'][$attr_id])) continue;
			if(!empty($tattrs['sourceType'])){
				switch($tattrs['sourceType']){
					case 'library': $newLibrary =  '\App\\Libraries\\'.$tattrs['sourceName'];
						$loadedLib = new $newLibrary;
						$data['shortcode_values'][$attr_id] = call_user_func(array($loadedLib, $tattrs['sourceFunction']));
						break;
					case 'model': $newModel =  '\App\\Models\\'.$tattrs['sourceName'];
						$loadedModel = new $newModel;
						$data['shortcode_values'][$attr_id] = call_user_func(array($loadedModel, $tattrs['sourceFunction']));
						break;
					default: break;
				}
			}
		}
		
		$data['hide_close'] = true;
		$data['load_full'] = true;
		if(!$partial) echo view('admin/widgets/tinymce_header', $data);
		echo view('admin/widgets/shortcode_attrs', $data);
		if(!$partial){
			$data['load_js'] = array('tinymce_shortcodes.js');
			echo view('admin/widgets/tinymce_footer', $data);
		}
		//return;die();
	}

	public function getIframeSrc(){

		if (!$this->request->isAJAX()) {
            $resp = array('resp'=>false,'msg'=>'Δεν επιτρέπεται η πρόσβαση');
			echo json_encode($resp);return;die();
        }
		if (empty($_POST['iframe'])) {
            $resp = array('resp'=>false,'msg'=>'Παρακαλώ εισάγετε το html του iframe.');
			echo json_encode($resp);return;die();
        }

		$iframe = $_POST['iframe'];
		if(!preg_match('/src="([^"]+)"/', $iframe, $match)){
			if(preg_match('/src=\'([^"]+)\'/', $iframe, $match)){
			}else{
				$resp = array('resp'=>false,'msg'=>'To html του iframe δεν είναι σωστό.');
				echo json_encode($resp);return;die();
			}
		}
		$src = $match[1];
		$resp = array('resp'=>true,'src'=>$src);
		echo json_encode($resp);return;die();
	}
	
}
