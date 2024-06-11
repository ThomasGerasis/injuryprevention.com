<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;

use App\Models\MultiUseContent;
use App\Models\EditingLock;

class MultiUseContents extends BaseController
{
	public function index()
	{
		if (is_null($this->session->get('MultiUseContentsIndexPage'))) {
			$page = 1;
			$this->session->set('MultiUseContentsIndexPage', 1);
		} else {
			$page = $this->session->get('MultiUseContentsIndexPage');
		}
		if (!($this->session->get('MultiUseContentsIndex'))) $this->session->set('MultiUseContentsIndex', []);

		$data = [];
		$data['page'] = $page;
		$data['sessionData'] = $this->session->get('MultiUseContentsIndex');

		$multiModel = model(MultiUseContent::class);
		$data['count'] = $multiModel->getCount($this->session->get('MultiUseContentsIndex'));
		$data['list'] = $multiModel->getPaginatedList($page, $this->session->get('MultiUseContentsIndex'));

		$data['pageData'] = array('title' => 'Multi use content');

		return view('admin/header', $data)
			. view('admin/multi_use_content/index', $data)
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
				$this->session->set('MultiUseContentsIndex', []);
			} else {
				$this->session->set('MultiUseContentsIndex', $_POST);
			}
		}
		if (!($this->session->get('MultiUseContentsIndex'))) $this->session->set('MultiUseContentsIndex', []);
		if (empty($page)) $page = 1;

		$this->session->set('MultiUseContentsIndexPage', $page);
		$data = [];
		$data['page'] = $page;
		$data['sessionData'] = $this->session->get('MultiUseContentsIndex');

		$multiModel = model(MultiUseContent::class);
		$data['count'] = $multiModel->getCount($this->session->get('MultiUseContentsIndex'));
		$data['list'] = $multiModel->getPaginatedList($page, $this->session->get('MultiUseContentsIndex'));

		$resp['update_data'] = true;
		$resp['table_data'] = view('admin/multi_use_content/_table_data', $data);
		echo json_encode($resp);
		return;
		die();
	}

	public function getLock($id)
	{
		$editingLock = model(EditingLock::class);
		$editingLock->saveLock('multiUseContent', $id, $this->session->get('loggedUser')['id'], time());
		return redirect()->to('admin//multiUseContents/edit/' . $id);
	}

	function duplicate($id) {
		$multiModel = model(MultiUseContent::class);
		$multiModel->duplicate($id,$this->session->get('loggedUser')['id']);
		$this->session->setFlashdata('success', 'Το multi use shortcode αντιγράφηκε.');
		return redirect()->to('admin//multiUseContents');
	}

	function delete($id) {
		$editingLock = model(EditingLock::class);
		$lock = $editingLock->getLock('multiUseContent', $id);
		if (!empty($lock) && strtotime($lock['updated']) > (time() - 15) && $lock['user_id'] != $this->session->get('loggedUser')['id']) {
			$this->session->setFlashdata('error', 'Ο χρήστης ' . $lock['username'] . ' επεξεργάζεται το multi use shortcode.');
			return redirect()->to('admin//multiUseContents');
		}
		$editingLock->saveLock('multiUseContent', $id, $this->session->get('loggedUser')['id'], time());
		
		$multiModel = model(MultiUseContent::class);
		$multiModel->delete($id);
		$this->session->setFlashdata('success', 'Το multi use shortcode διαγράφηκε.');
		rebuildCache('multiUseContent', $id, 'delete');
		return redirect()->to('admin//multiUseContents');
	}

	function edit($id = null)
	{
		$editingLock = model(EditingLock::class);
		$multiModel = model(MultiUseContent::class);
		$data = array();
		$data['page'] = array();
		if (!empty($id)) {
			$lock = $editingLock->getLock('multiUseContent', $id);
			if (!empty($lock) && strtotime($lock['updated']) > (time() - 15) && $lock['user_id'] != $this->session->get('loggedUser')['id']) {
				$this->session->setFlashdata('error', 'Ο χρήστης ' . $lock['username'] . ' επεξεργάζεται το multi use shortcode. Πατήστε <a href="' . base_url('multiUseContents/getLock/' . $id) . '">εδώ</a> για να κάνετε ανάληψη.');
				return redirect()->to('admin//multiUseContents');
			}
			$data['page'] = $multiModel->find($id);
			$editingLock->saveLock('multiUseContent', $id, $this->session->get('loggedUser')['id'], time());
		}

		if ($_POST) {
			helper(['form']);
			if ($this->validate(array(
				'title' => array('label' => 'Title', 'rules' => 'required'),
				//'content' => array('label' => 'Type', 'rules' => ''),
			))) {
				$savedResponse = $multiModel->saveData($_POST, $this->session->get('loggedUser')['id'], $id);
				if ($savedResponse) {
					rebuildCache('multiUseContent', $savedResponse['id'], 'update');
					$this->session->setFlashdata('success', 'Οι αλλαγές σας αποθηκεύτηκαν.');
					return redirect()->to('admin//multiUseContents/edit/' . $savedResponse['id']);
				}
			} else {
				$data['errors'] = $this->validator->getErrors();
			}
		}
		$data['pageData'] = array('title' => (empty($id) ? 'New ' : 'Edit ') . 'multi use shortcode');

		echo view('admin/header', $data);
		echo view('admin/multi_use_content/edit', $data);
		echo view('admin/footer', ['tinymce' => true, 'loadJs' => ['editing_neweditor.js', 'custom_tinymce.js', 'edit_lock.js']]);
	}
}
