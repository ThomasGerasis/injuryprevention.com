<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;

use App\Models\FaqCategory;
use App\Models\EditingLock;

class FaqCategories extends BaseController
{
	public function index()
	{
		$categoryModel = model(FaqCategory::class);
		$data['list'] = $categoryModel->orderBy('order_num ASC')->findAll();

		$data['pageData'] = array('title' => 'FAQ categories');

		return view('admin/header', $data)
			. view('admin/faq_categories/index', $data)
			. view('admin/footer', []);
	}

	public function getLock($id)
	{
		$editingLock = model(EditingLock::class);
		$editingLock->saveLock('faqCategory', $id, $this->session->get('loggedUser')['id'], time());
		return redirect()->to('admin/faqCategories/edit/' . $id);
	}

	function edit($id = null)
	{
		$editingLock = model(EditingLock::class);
		$categoryModel = model(FaqCategory::class);
		$data = array();
		$data['category'] = array();
		if (!empty($id)) {
			$lock = $editingLock->getLock('faqCategory', $id);
			if (!empty($lock) && strtotime($lock['updated']) > (time() - 15) && $lock['user_id'] != $this->session->get('loggedUser')['id']) {
				$this->session->setFlashdata('error', 'Ο χρήστης ' . $lock['username'] . ' επεξεργάζεται το FAQ category. Πατήστε <a href="' . base_url('faqCategories/getLock/' . $id) . '">εδώ</a> για να κάνετε ανάληψη.');
				return redirect()->to('admin/faqCategories');
			}
			$data['category'] = $categoryModel->find($id);
			$editingLock->saveLock('faqCategory', $id, $this->session->get('loggedUser')['id'], time());
		}

		if ($_POST) {
			helper(['form']);
			if ($this->validate(array(
				'title' => array('label' => 'Title', 'rules' => 'required')
			))) {
				$savedResponse = $categoryModel->saveData($_POST, $this->session->get('loggedUser')['id'], $id);
				if ($savedResponse) {
					rebuildCache('faqCategories', 1, 'update');
					$this->session->setFlashdata('success', 'Οι αλλαγές σας αποθηκεύτηκαν.');
					return redirect()->to('admin//faqCategories');
				}
			} else {
				$data['errors'] = $this->validator->getErrors();
			}
		}
		
		$data['pageData'] = array('title' => (empty($id) ? 'New ' : 'Edit ').'FAQ category');

		echo view('admin/header', $data);
		echo view('admin/faq_categories/edit', $data);
		echo view('admin/footer', ['tinymce' => true, 'loadJs' => ['edit_lock.js']]);
	}

	function sortOrder()
	{
		$categoryModel = model(FaqCategory::class);
		if ($_POST) {
			$savedResponse = $categoryModel->saveSort($_POST, $this->session->get('loggedUser')['id']);
			if ($savedResponse) {
				rebuildCache('faqCategories', 1, 'update');
				$this->session->setFlashdata('success', 'Οι αλλαγές σας αποθηκεύτηκαν.');
			} else {
				$this->session->setFlashdata('error', 'Προέκυψε ένα πρόβλημα και οι αλλαγές σας δεν αποθηκεύτηκαν.');
			}
			return redirect()->to('admin//faqCategories');
		}
		$data = array();

		$data['categories'] = $categoryModel->orderBy('order_num ASC')->findAll();;

		$data['pageData'] = array('title' => 'FAQ categories ordering');

		echo view('admin/header', $data);
		echo view('admin/faq_categories/sort', $data);
		echo view('admin/footer', ['loadJs' => ['main/jquery-ui.min.js', 'custom_sort.js']]);
	}
}
