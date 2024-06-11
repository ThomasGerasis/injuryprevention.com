<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\EditingLock;

class Articles extends BaseController
{
	public function index()
	{
		if (is_null($this->session->get('articleIndexPage'))) {
			$page = 1;
			$this->session->set('articleIndexPage', 1);
		} else {
			$page = $this->session->get('articleIndexPage');
		}
		if (!($this->session->get('articleIndex'))) $this->session->set('articleIndex', []);

		$data = [];
		$data['page'] = $page;
		$data['sessionData'] = $this->session->get('articleIndex');

		$articleModel = model(Article::class);
		$data['count'] = $articleModel->getCount($this->session->get('articleIndex'));
		$data['list'] = $articleModel->getPaginatedList($page, $this->session->get('articleIndex'));

		$categoryModel = model(ArticleCategory::class);
		$data['categories'] = $categoryModel->select('id, title')->findAll();

		$data['pageData'] = array('title' => 'Articles');

		return view('admin/header', $data)
			. view('admin/articles/index', $data)
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
				$this->session->set('articleIndex', []);
			} else {
				$this->session->set('articleIndex', $_POST);
			}
		}
		if (!($this->session->get('articleIndex'))) $this->session->set('articleIndex', []);
		if (empty($page)) $page = 1;

		$this->session->set('articleIndexPage', $page);
		$data = [];
		$data['page'] = $page;
		$data['sessionData'] = $this->session->get('articleIndex');

		$articleModel = model(Article::class);
		$data['count'] = $articleModel->getCount($this->session->get('articleIndex'));
		$data['list'] = $articleModel->getPaginatedList($page, $this->session->get('articleIndex'));

		$categoryModel = model(ArticleCategory::class);
		$data['categories'] = $categoryModel->select('id, title')->findAll();

		$resp['update_data'] = true;
		$resp['table_data'] = view('admin/articles/_table_data', $data);
		echo json_encode($resp);
		return;
		die();
	}

	function delete($id)
	{
		$editingLock = model(EditingLock::class);
		$lock = $editingLock->getLock('article', $id);
		if (!empty($lock) && strtotime($lock['updated']) > (time() - 15) && $lock['user_id'] != $this->session->get('loggedUser')['id']) {
			$this->session->setFlashdata('error', 'Ο χρήστης ' . $lock['username'] . ' επεξεργάζεται το article.');
			return redirect()->to('admin/articles');
		}
		$editingLock->saveLock('article', $id, $this->session->get('loggedUser')['id'], time());
		
		$articleModel = model(Article::class);
		$response = $articleModel->deleteRow($id, $this->session->get('loggedUser')['id']);
		if ($response['deleted']) {
			$this->session->setFlashdata('success', $response['message']);
		} else {
			$this->session->setFlashdata('error', $response['message']);
		}
		return redirect()->to('admin/articles');
	}

	function schedule($id)
	{
		$date_scheduled = $_POST['schedule_date'].' '.$_POST['schedule_time'].':00';
		if(strtotime($date_scheduled) < time()){
			echo json_encode(array(
				'resp' => false,
				'msg' => 'Please, specify an older date.'
			));
			return; die();
		}
		$articleModel = model(Article::class);
		$response = $articleModel->schedule($id, $this->session->get('loggedUser')['id'],$date_scheduled);
		if ($response['scheduled']) {
			$this->session->setFlashdata('success', $response['message']);
		} else {
			$this->session->setFlashdata('error', $response['message']);
		}
		echo json_encode(array('resp'=>$response['scheduled'], 'msg'=>$response['message']));
		return;
	}

	function unschedule($id)
	{
		$articleModel = model(Article::class);
		$response = $articleModel->unschedule($id, $this->session->get('loggedUser')['id']);
		if ($response['unscheduled']) {
			$this->session->setFlashdata('success', $response['message']);
		} else {
			$this->session->setFlashdata('error', $response['message']);
		}
		return redirect()->back();
	}

	function unpublish($id)
	{
		$articleModel = model(Article::class);
		$response = $articleModel->unpublish($id, $this->session->get('loggedUser')['id']);
		if ($response['unpublished']) {
			$this->session->setFlashdata('success', $response['message']);
			rebuildCache('article', $id, 'unpublish');
			rebuildCache('permalinks', 1, 'update');
			$articleData = $articleModel->find($id);
			if(!empty($articleData['article_category_id'])) rebuildCache('articleCategory', $articleData['article_category_id'], 'feed');
			rebuildCache('generalArticleCategory', '1', 'feed');
		} else {
			$this->session->setFlashdata('error', $response['message']);
		}
		return redirect()->to('admin/articles');
	}

	function publish($id)
	{
		$articleModel = model(Article::class);
		$response = $articleModel->publish($id, $this->session->get('loggedUser')['id']);
		if ($response['published']) {
			$this->session->setFlashdata('success', $response['message']);
			rebuildCache('article', $id, 'publish');
			rebuildCache('permalinks', 1, 'update');
		} else {
			$this->session->setFlashdata('error', $response['message']);
		}
		return redirect()->to('admin/articles');
	}

	public function getLock($id)
	{
		$editingLock = model(EditingLock::class);
		$editingLock->saveLock('article', $id, $this->session->get('loggedUser')['id'], time());
		return redirect()->to('admin/articles/edit/' . $id);
	}

	function edit($id = null)
	{
		$editingLock = model(EditingLock::class);
		$articleModel = model(Article::class);
		$data = array();
		$data['page'] = array();
		if (!empty($id)) {
			$lock = $editingLock->getLock('article', $id);
			if (!empty($lock) && strtotime($lock['updated']) > (time() - 15) && $lock['user_id'] != $this->session->get('loggedUser')['id']) {
				$this->session->setFlashdata('error', 'Ο χρήστης ' . $lock['username'] . ' επεξεργάζεται το article. Πατήστε <a href="' . base_url('admin/articles/getLock/' . $id) . '">εδώ</a> για να κάνετε ανάληψη.');
				return redirect()->to('admin/articles');
			}
			$data['page'] = $articleModel->find($id);
			$editingLock->saveLock('article', $id, $this->session->get('loggedUser')['id'], time());
		}

		if ($_POST) {
			helper(['form']);
			if ($this->validate(array(
				'title' => array('label' => 'Title', 'rules' => 'required'),
				'permalink' => array('label' => 'Permalink', 'rules' => 'required'),
				//'logo_image_id' => array('label' => 'Logo', 'rules' => 'required')
			))) {
				$savedResponse = $articleModel->saveData($_POST, $this->session->get('loggedUser')['id'], $id);
				if ($savedResponse) {
					if ($savedResponse['updateCache']) rebuildCache('article', $savedResponse['id'], 'update');
					if ($savedResponse['update_permalink_data']) rebuildCache('permalinks', 1, 'update');
					if(!empty($savedResponse['updateCategories'])){
						foreach($savedResponse['updateCategories'] as $category_id){
							rebuildCache('articleCategory', $category_id, 'feed');
						}
						rebuildCache('generalArticleCategory', '1', 'feed');
					}
					$this->session->setFlashdata('success', 'Οι αλλαγές σας αποθηκεύτηκαν.');
					return redirect()->to('admin/articles/edit/' . $savedResponse['id']);
				}
			} else {
				$data['errors'] = $this->validator->getErrors();
			}
		}
		$categoryModel = model(ArticleCategory::class);
		$data['categories'] = $categoryModel->select('id, title')->findAll();
		$data['pageData'] = array('title' => (empty($id) ? 'New ' : 'Edit ') . 'article');

		echo view('admin/header', $data);
		echo view('admin/articles/edit', $data);
		echo view('admin/footer', ['tinymce' => true, 'loadJs' => ['editing_neweditor.js', 'custom_tinymce.js', 'edit_lock.js']]);
	}
}
