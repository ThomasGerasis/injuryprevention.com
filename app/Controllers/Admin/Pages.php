<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;

use App\Models\Page;
use App\Models\PageFaq;
use App\Models\EditingLock;

class Pages extends BaseController
{
	public function index()
	{
		if (is_null($this->session->get('pagesIndexPage'))) {
			$page = 1;
			$this->session->set('pagesIndexPage', 1);
		} else {
			$page = $this->session->get('pagesIndexPage');
		}
		if (!($this->session->get('pagesIndex'))) $this->session->set('pagesIndex', []);

		$data = [];
		$data['page'] = $page;
		$data['sessionData'] = $this->session->get('pagesIndex');

		$pageModel = model(Page::class);
		$data['count'] = $pageModel->getCount($this->session->get('pagesIndex'));
		$data['list'] = $pageModel->getPaginatedList($page, $this->session->get('pagesIndex'));

		$data['pageData'] = array('title' => 'Pages');

		return view('admin/header', $data)
			. view('admin/pages/index', $data)
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
				$this->session->set('pagesIndex', []);
			} else {
				$this->session->set('pagesIndex', $_POST);
			}
		}
		if (!($this->session->get('pagesIndex'))) $this->session->set('pagesIndex', []);
		if (empty($page)) $page = 1;

		$this->session->set('pagesIndexPage', $page);
		$data = [];
		$data['page'] = $page;
		$data['sessionData'] = $this->session->get('pagesIndex');

		$pageModel = model(Page::class);
		$data['count'] = $pageModel->getCount($this->session->get('pagesIndex'));
		$data['list'] = $pageModel->getPaginatedList($page, $this->session->get('pagesIndex'));

		$resp['update_data'] = true;
		$resp['table_data'] = view('admin/pages/_table_data', $data);
		echo json_encode($resp);
		return;
		die();
	}

	function delete($id)
	{
		$editingLock = model(EditingLock::class);
		$lock = $editingLock->getLock('page', $id);
		if (!empty($lock) && strtotime($lock['updated']) > (time() - 15) && $lock['user_id'] != $this->session->get('loggedUser')['id']) {
			$this->session->setFlashdata('error', 'Ο χρήστης ' . $lock['username'] . ' επεξεργάζεται το page.');
			return redirect()->to('admin/pages');
		}
		$editingLock->saveLock('page', $id, $this->session->get('loggedUser')['id'], time());

		$pageModel = model(Page::class);
		$response = $pageModel->deleteRow($id, $this->session->get('loggedUser')['id']);
		if ($response['deleted']) {
			$this->session->setFlashdata('success', $response['message']);
		} else {
			$this->session->setFlashdata('error', $response['message']);
		}
		return redirect()->to('admin/pages');
	}

	function unpublish($id)
	{
		$pageModel = model(Page::class);
		$response = $pageModel->unpublish($id, $this->session->get('loggedUser')['id']);
		if ($response['unpublished']) {
			$this->session->setFlashdata('success', $response['message']);
			rebuildCache('page', $id, 'unpublish');
			rebuildCache('permalinks', 1, 'update');
		} else {
			$this->session->setFlashdata('error', $response['message']);
		}
		return redirect()->to('admin/pages');
	}

	function publish($id)
	{
		$pageModel = model(Page::class);
		$response = $pageModel->publish($id, $this->session->get('loggedUser')['id']);
		if ($response['published']) {
			$this->session->setFlashdata('success', $response['message']);
			rebuildCache('page', $id, 'publish');
			rebuildCache('permalinks', 1, 'update');
		} else {
			$this->session->setFlashdata('error', $response['message']);
		}
		return redirect()->to('admin/pages');
	}

	public function getLock($id)
	{
		$editingLock = model(EditingLock::class);
		$editingLock->saveLock('page', $id, $this->session->get('loggedUser')['id'], time());
		return redirect()->to('admin/pages/edit/' . $id);
	}

	function edit($id = null)
	{
		$editingLock = model(EditingLock::class);
		$pageModel = model(Page::class);
		$data = array();
		$data['page'] = array();
		if (!empty($id)) {
			$lock = $editingLock->getLock('page', $id);
			if (!empty($lock) && strtotime($lock['updated']) > (time() - 15) && $lock['user_id'] != $this->session->get('loggedUser')['id']) {
				$this->session->setFlashdata('error', 'Ο χρήστης ' . $lock['username'] . ' επεξεργάζεται το page. Πατήστε <a href="' . base_url('pages/getLock/' . $id) . '">εδώ</a> για να κάνετε ανάληψη.');
				return redirect()->to('admin/pages');
			}
			$data['page'] = $pageModel->find($id);
			$editingLock->saveLock('page', $id, $this->session->get('loggedUser')['id'], time());
		}

        $data['pageData'] = array('title' => 'Edit Page');

		if ($_POST) {
			helper(['form']);
			if ($this->validate(array(
				'title' => array('label' => 'Title', 'rules' => 'required'),
				'permalink' => array('label' => 'Permalink', 'rules' => 'required'),
				//'logo_image_id' => array('label' => 'Logo', 'rules' => 'required')
			))) {
				$savedResponse = $pageModel->saveData($_POST, $this->session->get('loggedUser')['id'], $id);
				if ($savedResponse) {
					if ($savedResponse['updateCache']) rebuildCache('page', $savedResponse['id'], 'update');
					if ($savedResponse['update_permalink_data']) rebuildCache('permalinks', 1, 'update');
					$this->session->setFlashdata('success', 'Οι αλλαγές σας αποθηκεύτηκαν.');
					return redirect()->to('admin/pages/edit/' . $savedResponse['id']);
				}
			} else {
				$data['errors'] = $this->validator->getErrors();
			}
		}
		$data['page_faqs'] = array();
		if (!empty($id)) {
			$pageFaqModel = model(PageFaq::class);
			$data['page_faqs'] = $pageFaqModel->where('page_id', $id)->orderBy('page_id ASC')->findAll();
		}

		echo view('admin/header', $data);
		echo view('admin/pages/edit', $data);
		echo view('admin/footer', ['tinymce' => true, 'loadJs' => ['editing_neweditor.js', 'custom_tinymce.js', 'edit_lock.js']]);
	}
}
