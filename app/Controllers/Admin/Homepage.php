<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;

use App\Models\HomepageData;
use App\Models\EditingLock;
use App\Models\PageFaq;

class Homepage extends BaseController
{

	public function getLock()
	{
		$editingLock = model(EditingLock::class);
		$editingLock->saveLock('homepage', 1, $this->session->get('loggedUser')['id'], time());
		return redirect()->to('admin/homepage');
	}

	public function index()
	{
		$editingLock = model(EditingLock::class);
		$pageModel = model(HomepageData::class);
		$data = array();
	
		$lock = $editingLock->getLock('homepage', 1);
		if (!empty($lock) && strtotime($lock['updated']) > (time() - 15) && $lock['user_id'] != $this->session->get('loggedUser')['id']) {
			$this->session->setFlashdata('error', 'Ο χρήστης ' . $lock['username'] . ' επεξεργάζεται το home page. Πατήστε <a href="' . base_url('homepage/getLock') . '">εδώ</a> για να κάνετε ανάληψη.');
			return redirect()->back();
		}
		$data['page'] = $pageModel->find(1);
		$editingLock->saveLock('homepage', 1, $this->session->get('loggedUser')['id'], time());

		if ($_POST) {
			helper(['form']);
			if ($this->validate(array(
				'title' => array('label' => 'Title', 'rules' => 'required')
			))) {
				$savedResponse = $pageModel->saveData($_POST, $this->session->get('loggedUser')['id']);
				if ($savedResponse) {
					rebuildCache('homepage', 1, 'update');
					$this->session->setFlashdata('success', 'Οι αλλαγές σας αποθηκεύτηκαν.');
					return redirect()->to('admin/homepage');
				}
			} else {
				$data['errors'] = $this->validator->getErrors();
			}
		}
		$data['pageData'] = array('title' => 'Home page');

        $data['page_faqs'] = array();

        $pageFaqModel = model(PageFaq::class);
        $data['page_faqs'] = $pageFaqModel->where('page_id', 1)->orderBy('page_id ASC')->findAll();

		echo view('admin/header', $data);
		echo view('admin/edit_homepage', $data);
		echo view('admin/footer', ['tinymce' => true, 'loadJs' => ['editing_neweditor.js', 'custom_tinymce.js', 'edit_lock.js']]);
	}
}
