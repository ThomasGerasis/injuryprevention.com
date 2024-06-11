<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\ArticleCategory;
use App\Models\EditingLock;

class ArticleCategories extends BaseController
{
	public function index()
	{
		if (is_null($this->session->get('articleCategoriesIndexPage'))) {
			$page = 1;
			$this->session->set('articleCategoriesIndexPage', 1);
		} else {
			$page = $this->session->get('articleCategoriesIndexPage');
		}
		if (!($this->session->get('articleCategoriesIndex'))) $this->session->set('articleCategoriesIndex', []);

		$data = [];
		$data['page'] = $page;
		$data['sessionData'] = $this->session->get('articleCategoriesIndex');

		$categoryModel = model(ArticleCategory::class);
		$data['count'] = $categoryModel->getCount($this->session->get('articleCategoriesIndex'));
		$data['list'] = $categoryModel->getPaginatedList($page, $this->session->get('articleCategoriesIndex'));

		$data['parentCategories'] = $categoryModel->select('id, title')->where('parent_id', '0')->orWhere('parent_id IS NULL')->findAll();

		$data['pageData'] = array('title' => 'Article categories');

		return view('admin/header', $data)
			. view('admin/articles_categories/index', $data)
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
				$this->session->set('articleCategoriesIndex', []);
			} else {
				$this->session->set('articleCategoriesIndex', $_POST);
			}
		}
		if (!($this->session->get('articleCategoriesIndex'))) $this->session->set('articleCategoriesIndex', []);
		if (empty($page)) $page = 1;

		$this->session->set('articleCategoriesIndexPage', $page);
		$data = [];
		$data['page'] = $page;
		$data['sessionData'] = $this->session->get('articleCategoriesIndex');

		$categoryModel = model(ArticleCategory::class);
		$data['count'] = $categoryModel->getCount($this->session->get('articleCategoriesIndex'));
		$data['list'] = $categoryModel->getPaginatedList($page, $this->session->get('articleCategoriesIndex'));

		$data['parentCategories'] = $categoryModel->select('id, title')->where('parent_id', '0')->orWhere('parent_id IS NULL')->findAll();

		$resp['update_data'] = true;
		$resp['table_data'] = view('articles_categories/_table_data', $data);
		echo json_encode($resp);
		return;
		die();
	}

	function unpublish($id)
	{
		$categoryModel = model(ArticleCategory::class);
		$response = $categoryModel->unpublish($id, $this->session->get('loggedUser')['id']);
		if ($response['unpublished']) {
			$this->session->setFlashdata('success', $response['message']);
			rebuildCache('articleCategory', $id, 'unpublish');
			rebuildCache('permalinks', 1, 'update');
		} else {
			$this->session->setFlashdata('error', $response['message']);
		}
		return redirect()->to('admin/articleCategories');
	}

	function publish($id)
	{
		$categoryModel = model(ArticleCategory::class);
		$response = $categoryModel->publish($id, $this->session->get('loggedUser')['id']);
		if ($response['published']) {
			$this->session->setFlashdata('success', $response['message']);
			rebuildCache('articleCategory', $id, 'publish');
			rebuildCache('permalinks', 1, 'update');
		} else {
			$this->session->setFlashdata('error', $response['message']);
		}
		return redirect()->to('admin/articleCategories');
	}

	public function getLock($id)
	{
		$editingLock = model(EditingLock::class);
		$editingLock->saveLock('articleCategory', $id, $this->session->get('loggedUser')['id'], time());
		return redirect()->to('admin/articleCategories/edit/' . $id);
	}

	function edit($id = null)
	{
		$editingLock = model(EditingLock::class);
		$categoryModel = model(ArticleCategory::class);
		$data = array();
		$data['page'] = array();
		if (!empty($id)) {
			$lock = $editingLock->getLock('articleCategory', $id);
			if (!empty($lock) && strtotime($lock['updated']) > (time() - 15) && $lock['user_id'] != $this->session->get('loggedUser')['id']) {
				$this->session->setFlashdata('error', 'Ο χρήστης ' . $lock['username'] . ' επεξεργάζεται το article category. Πατήστε <a href="' . base_url('articleCategories/getLock/' . $id) . '">εδώ</a> για να κάνετε ανάληψη.');
				return redirect()->to('admin/articleCategories');
			}
			$data['page'] = $categoryModel->find($id);
			$editingLock->saveLock('articleCategory', $id, $this->session->get('loggedUser')['id'], time());
		}

		if ($_POST) {
			helper(['form']);
			if ($this->validate(array(
				'title' => array('label' => 'Title', 'rules' => 'required'),
				'permalink' => array('label' => 'Permalink', 'rules' => 'required'),
				//'logo_image_id' => array('label' => 'Logo', 'rules' => 'required')
			))) {
				$savedResponse = $categoryModel->saveData($_POST, $this->session->get('loggedUser')['id'], $id);
				if ($savedResponse) {
					if ($savedResponse['updateCache']) rebuildCache('articleCategory', $savedResponse['id'], 'update');
					if ($savedResponse['update_permalink_data']) rebuildCache('permalinks', 1, 'update');
					$this->session->setFlashdata('success', 'Οι αλλαγές σας αποθηκεύτηκαν.');
					return redirect()->to('admin/articleCategories/edit/' . $savedResponse['id']);
				}
			} else {
				$data['errors'] = $this->validator->getErrors();
			}
		}
		$data['parentCategories'] = $categoryModel->select('id, title')->where('parent_id', '0')->orWhere('parent_id IS NULL')->findAll();
		$data['pageData'] = array('title' => (empty($id) ? 'New ' : 'Edit ') . 'article category');

		echo view('admin/header', $data);
		echo view('admin/articles_categories/edit', $data);
		echo view('admin/footer', ['tinymce' => true, 'loadJs' => ['editing_neweditor.js', 'custom_tinymce.js', 'edit_lock.js']]);
	}
}
