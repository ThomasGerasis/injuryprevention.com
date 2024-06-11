<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;

use App\Models\Image;
use App\Models\EditingLock;

class MediaLibrary extends BaseController
{
	public function index()
	{
		if (is_null($this->session->get('imagesIndexPage'))) {
			$page = 1;
			$this->session->set('imagesIndexPage', 1);
		} else {
			$page = $this->session->get('imagesIndexPage');
		}
		if (!($this->session->get('imagesIndex'))) $this->session->set('imagesIndex', []);

		$data = [];
		$data['page'] = $page;
		$data['sessionData'] = $this->session->get('imagesIndex');

		$dbModel = model(Image::class);
		$data['count'] = $dbModel->getCount($this->session->get('imagesIndex'));
		$data['list'] = $dbModel->getPaginatedList($page, $this->session->get('imagesIndex'));

		$data['pageData'] = array('title' => 'Media library');

		return view('admin/header', $data)
			. view('admin/media_library/index', $data)
			. view('admin/footer', [
				'load_datetime' => true,
				'loadJs' => ['ci_datatables.js']
			]);
	}

	function getPaginatedList($page = null)
	{
		if (isset($_POST) && count($_POST)) {
			if (!empty($_POST['page'])) $page = $_POST['page'];
			if (isset($_POST['resetForm'])) {
				$this->session->set('imagesIndex', []);
			} else {
				$this->session->set('imagesIndex', $_POST);
			}
		}
		if (!($this->session->get('imagesIndex'))) $this->session->set('imagesIndex', []);
		if (empty($page)) $page = 1;

		$this->session->set('imagesIndexPage', $page);
		$data = [];
		$data['page'] = $page;
		$data['sessionData'] = $this->session->get('imagesIndex');

		$dbModel = model(Image::class);
		$data['count'] = $dbModel->getCount($this->session->get('imagesIndex'));
		$data['list'] = $dbModel->getPaginatedList($page, $this->session->get('imagesIndex'));

		$resp['update_data'] = true;
		$resp['table_data'] = view('admin/media_library/_table_data', $data);
		echo json_encode($resp);
		return;
		die();
	}

    function update($id)
	{
		$dbModel = model(Image::class);
		$dbModel->update($id, array(
            'title' => $_POST['title'],
            'seo_alt' => $_POST['seo_alt'],
            'seo_description' => $_POST['seo_description'],
        ));
        rebuildCache('image', $id, 'update');
		echo json_encode(array('saved'=>true));die();
	}


    function delete($id)
	{
		$dbModel = model(Image::class);
		$dbModel->delete($id);
        rebuildCache('image', $id, 'delete');
		return redirect()->to('admin/mediaLibrary');
	}
    
}