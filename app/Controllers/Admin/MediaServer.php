<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use CodeIgniter\Controller;

use App\Models\Image;

class MediaServer extends Controller
{
	protected $request;
	protected $helpers = ['url','misc'];
	
	public function index($folder = 'sqr200', $filename){
		
		$image_sizes = config("ImagesConfig")->image_sizes;
		if(!in_array($folder, array_keys($image_sizes))){
			log_message('error','cannot find size for: '.$folder);
			//return FALSE;
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
		helper('misc');

		$fil = explode('.',$filename);
		$file_extension = $fil[count($fil)-1];
		unset($fil[count($fil)-1]);
		$file_id = implode('.',$fil);
		
		$target_setup = $image_sizes[$folder];
		if($folder == 'customSize'){
			$fnn = explode('_',$file_id);
			if(count($fnn) >= 3 && is_numeric($fnn[count($fnn)-1]) && is_numeric($fnn[count($fnn)-2])){
				$folder = $file_extension;
				unset($fnn[count($fnn)-1]);
				unset($fnn[count($fnn)-1]);
				$file_id = implode('_',$fnn);
				$target_setup =  array('width' => (int) $fnn[1],'h_height' => (int) $fnn[2],'l_height' => (int) $fnn[2]);
			}
		}
		
		$target_folder = config("ImagesConfig")->site_upload_path.'/'.$folder;
		if (!is_dir($target_folder))
		{
			$create_folder = mkdir($target_folder);
			if ($create_folder == FALSE)
			{
				log_message('error','cannot mkdir for '.$target_folder);
				//return FALSE;
				throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
			}
		}

		$target_file = $folder.'/'.$file_id.'.'.$file_extension;
		$target_file_abs = config("ImagesConfig")->site_upload_path.'/'.$target_file;
		if (is_file($target_file_abs)){
			$mimetype = get_image_mime($target_file_abs);
			$this->response->setHeader('Content-Type', $mimetype);
			$this->response->setHeader('Expires', 'Sat, 26 Jul 2025 05:00:00 GMT');
			serve_image($target_file_abs);
			return; die();
		}

		$ImageModel = model(Image::class);
		$image_data = $ImageModel->get_by_filename($file_id.'.'.$file_extension);
		if(empty($image_data)){
			log_message('error','cannot get image in database '.$file_id.'.'.$file_extension);
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$source_file = config("ImagesConfig")->site_upload_path.'/original_images/'.$file_id.'.'.$file_extension;
		if (!is_file($source_file)){
			log_message('error','original file not found');
			//return FALSE;
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
		
		if($file_extension == 'svg' || $file_extension == 'gif' || $folder == 'original'){
			$file_copied = copy($source_file, $target_file_abs);
			if($file_copied){
				$this->response->setHeader('Content-Type', $image_data['mimetype']);
				$this->response->setHeader('Expires', 'Sat, 26 Jul 2025 05:00:00 GMT');
				serve_image($target_file_abs);
				return;
				//log_message('error','image served');
			}else{
				log_message('error','image not copied');
				//return FALSE;
				throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
			}
		}

		$info = \Config\Services::image()
			->withFile(realpath($source_file))
			->getFile()
			->getProperties(true);
		$w = $info['width'];
		$h = $info['height'];	
		
		$focus = 'center';
		$resize = TRUE;
		$new_w = $target_setup['width'];
		$new_h = $target_setup['h_height'];
		
		if ($w == $new_w && $h == $new_h)
		{
			$file_copied = copy($source_file, $target_file_abs);
			if(!$file_copied){
				log_message('error','source_file not copied');
				//return FALSE;
				throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
			}
			$resize = FALSE;
		}
		else if ($w<$new_w || $h<$new_h)
		{
			// first scale up!!!
			if ($w < $h) {
				$resize_w = $w * $new_h / $h;
				$resize_h = $new_h;	
			}
			else {
				$resize_w = $new_w;
				$resize_h = $h * $new_w / $w;
			}
		}
		else
		{
			if ($w < $h) {
				$resize_w = $w * $new_h / $h;
				$resize_h = $new_h;	
			}
			else {
				$resize_w = $new_w;
				$resize_h = $h * $new_w / $w;
			}
		}
		if ($resize == TRUE)
		{
			$image = \Config\Services::image()
				->withFile(realpath($source_file))
				->fit($resize_w, $resize_h, 'center')
				//->resize($resize_w, $resize_h, true)
				->crop($new_w, $new_h, ($resize_w - $new_w) / 2, ($resize_h - $new_h) / 2)
				->save($target_file_abs);
				
		}

		$mime_type = get_image_mime($target_file_abs);
		$this->response->setHeader('Content-Type', $mime_type);
		$this->response->setHeader('Expires', 'Sat, 26 Jul 2025 05:00:00 GMT');
		serve_image($target_file_abs);

	}
	
}
