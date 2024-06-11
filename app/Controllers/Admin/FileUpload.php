<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use CodeIgniter\Files\File;

class FileUpload extends BaseController
{
	protected $helpers = ['form'];
	
	public function do_upload_image($size = 'channel'){
		
		$validationRule = [
			'file_to_upload' => [
				'label' => 'Image File',
				'rules' => 'uploaded[file_to_upload]'
					. '|is_image[file_to_upload]'
					. '|mime_in[file_to_upload,image/jpg,image/jpeg,image/gif,image/png,image/webp,image/svg,image/svg+xml]'
					. '|max_size[file_to_upload,3072]',
			],
		];

		if(!$this->validate($validationRule)) {
			$resp = array('resp'=>'error', 'error' => $this->validator->getErrors());
			echo json_encode($resp); return; die();
		}

		$file = $this->request->getFile('file_to_upload');
		$original_name = $file->getClientName();
		$temp_name = explode('.',$original_name);
		$fn = '';
		for($i=0;$i<count($temp_name)-1;$i++){
			$temp_name[$i] = str_replace(' ','_',$temp_name[$i]);
			$temp_name[$i] = str_replace('\'','',$temp_name[$i]);
			$fn .= $temp_name[$i];
		}
		//log_message('error',config("ImagesConfig")->site_upload_path.'/tmp');
		if(!is_dir(config("ImagesConfig")->site_upload_path.'\tmp')){
			$create_folder = mkdir(config("ImagesConfig")->site_upload_path.'\tmp');
			if ($create_folder == FALSE)
			{
				$resp = array('resp'=>'error', 'error' => 'Folder tmp not found!');
				echo json_encode($resp); return; die();
			}
		}
		
		$target_folder = config("ImagesConfig")->site_upload_path.'/original_images';

		if (!is_dir($target_folder))
		{
			$create_folder = mkdir($target_folder);
			if ($create_folder == FALSE)
			{
				$resp = array('resp'=>'error', 'error' => 'Cannot create folder original_images');
				echo json_encode($resp); return; die();
			}
		}

		$imgModel = model(Image::class);
		$ext = $temp_name[count($temp_name)-1];
		$filename = false; $cc = 0; $oname = get_permalink($fn);
		while($filename == false){
			$filename = $imgModel->check_random_name($ext,$oname.($cc>0?'_'.$cc:''));
			$cc++;
		}
		$moved = $file->move(config("ImagesConfig")->site_upload_path.'/tmp', $filename.'.'.$ext);
		if(!$moved){
			$resp = array('resp'=>'error', 'error' => $this->upload->display_errors());
			echo json_encode($resp); return; die();
		}

		$source_file = config("ImagesConfig")->site_upload_path.'/tmp/'.$filename.'.'.$ext;
		$width = NULL;
		$height = NULL;
		if($ext == 'svg'){
			$xmlget = simplexml_load_string(file_get_contents($source_file));
			$xmlattributes = $xmlget->attributes();
			$width = (string) $xmlattributes->width; 
			$height = (string) $xmlattributes->height;
		}else{
			$info = \Config\Services::image()
				->withFile(realpath($source_file))
				->getFile()
				->getProperties(true);
			$width = @$info['width']; 
			$height = @$info['height'];
		}
		$new_image_data = array(
			'title' => $original_name,
			'file_name' => $filename.'.'.$ext,
			'mimetype' => $file->getClientMimeType(),
			'extension' => $ext,
			'width' => $width,
			'height' => $height,
			'added_by' => $this->session->get('loggedUser')['id'],
		);
		$image_id = $imgModel->insert($new_image_data);
		if(empty($image_id)){
			$resp = array('resp'=>'error', 'error' => 'Προέκυψε κάποιο πρόβλημα. Παρακαλώ δοκιμάστε αργότερα.');
			echo json_encode($resp); return; die();
		}
		$renamed = rename(config("ImagesConfig")->site_upload_path.'/tmp/'.$filename.'.'.$ext, config("ImagesConfig")->site_upload_path.'/original_images/'.$filename.'.'.$ext);
		if($renamed){
			$resp = array('resp'=>'ok', 'image_id'=>$image_id, 'image_id_fl'=>$filename.'.'.$ext, 'file_name' => get_image_url($filename.'.'.$ext,$size));
		}else{
			$resp = array('resp'=>'error', 'error'=>'Προέκυψε κάποιο πρόβλημα και η εικόνα δεν έχει ανέβει.');
		}
		
		echo json_encode($resp);
	}
	
}
