<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;

use App\Libraries\User as LibrariesUser;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\EditingLock;

class Users extends BaseController
{
	private LibrariesUser $user;
	private User $userModel;
	private UserGroup $userGroupModel;

	public function __construct()
	{
		parent::initController(service('request'), service('response'), service('logger'));
		$this->user = new LibrariesUser($this->session->get('loggedUser'));
		$this->userModel = model(User::class);
		$this->userGroupModel = model(UserGroup::class);
	}

	public function index()
	{
		if (empty($page)) {
			if (is_null($this->session->get('usersIndexPage'))) {
				$page = 1;
				$this->session->set('usersIndexPage', 1);
			} else {
				$page = $this->session->get('usersIndexPage');
			}
		}
		if (!($this->session->get('usersIndex'))) $this->session->set('usersIndex', []);

		$data = [];
		$data['page'] = $page;
		$data['sessionData'] = $this->session->get('usersIndex');

		$userModel = model(User::class);
		$data['userGroups'] = $userModel->getGroupsForSelect(true);
		$data['count'] = $userModel->get_count($this->session->get('usersIndex'));
		$data['list'] = $userModel->get_paginated_list($page, $this->session->get('usersIndex'));
		$data['pageData'] = array('title' => 'Backend users');

		return view('admin/header', $data)
			. view('admin/users/index', $data)
			. view('admin/footer', [
				'loadJs' => ['ci_datatables.js']
			]);
	}

	function getPaginatedList($page = null)
	{
		if (isset($_POST) && count($_POST)) {
			if (!empty($_POST['page'])) $page = $_POST['page'];
			if (isset($_POST['resetForm'])) {
				$this->session->set('usersIndex', []);
			} else {
				$this->session->set('usersIndex', $_POST);
			}
		}
		if (!($this->session->get('usersIndex'))) $this->session->set('usersIndex', []);
		if (empty($page)) $page = 1;

		$this->session->set('usersIndexPage', $page);
		$data = [];
		$data['page'] = $page;
		$data['sessionData'] = $this->session->get('usersIndex');
		$userModel = model(User::class);
		$data['userGroups'] = $userModel->getGroupsForSelect(true);
		$data['count'] = $userModel->get_count($this->session->get('usersIndex'));
		$data['list'] = $userModel->get_paginated_list($page, $this->session->get('usersIndex'));
		
		$resp['update_data'] = true;
		$resp['table_data'] = view('admin/users/table_data', $data);
		echo json_encode($resp);
		return;
	}

	function deactivate($id)
	{
		$userModel = model(User::class);
		$response = $userModel->deactivate($id, $this->session->get('loggedUser')['id']);
		if ($response['response']) {
			$this->session->setFlashdata('success', $response['message']);
		} else {
			$this->session->setFlashdata('error', $response['message']);
		}
		return redirect()->to('admin/users');
	}

	function activate($id)
	{
		$userModel = model(User::class);
		$response = $userModel->activate($id, $this->session->get('loggedUser')['id']);
		if ($response['response']) {
			$this->session->setFlashdata('success', $response['message']);
		} else {
			$this->session->setFlashdata('error', $response['message']);
		}
		return redirect()->to('admin/users');
	}

	public function getLock($id)
	{
		$editingLock = model(EditingLock::class);
		$editingLock->saveLock('user', $id, $this->session->get('loggedUser')['id'], time());
		return redirect()->to('admin/users/edit/' . $id);
	}

	public function add()
	{
		$userModel = model(User::class);
		$data = [];
		$data['user'] = [
			'username' => old('username'),
			'email' => old('email'),
			'is_admin' => old('is_admin'),
		];

		$data['userGroups'] = $userModel->getGroupsForSelect(true);

		$data['pageData'] = ['title' => 'Νέος User'];

		return view('admin/header', $data)
			. view('admin/users/edit', $data)
			. view('admin/footer', [
				'tinymce' => true,
				'loadJs' => ['plugins/sliders/nouislider.min.js', 'basic_form.js', 'update_lock.js']
			]);
	}

	public function attemptAdd()
	{
		$isAdmin = $this->user->isAdmin();

		if (!$isAdmin) {
			return redirect()->to("/users");
		}

		if (!$this->validate('addUser')) {
			return redirect()
				->to('/users/add')
				->withInput()
				->with('error', implode(' ', $this->validator->getErrors()));
		}

		$userModel = model(User::class);

		$data = [
			'username' => $this->request->getPost('username'),
			'email' => $this->request->getPost('email'),
			'is_admin' => $this->request->getPost('is_admin'),
			'is_active' => (empty($this->request->getPost('is_active')) ? 0 : 1),
			'date_added' => date('Y-m-d H:i:s')
		];
		
		if (!$userModel->insert($data)) {
			return redirect()
				->to('admin/users/add')
				->withInput()
				->with('error', 'Could not save new user, please try again');
		}

		return redirect()
			->to('admin/users')
			->with('success', 'User created successfully!');
	}

	public function edit($id = null)
	{
		$editingLock = model(EditingLock::class);
		$userModel = model(User::class);
		$data = [];
		$data['user'] = [];
		if (!empty($id)) {
			$lock = $editingLock->getLock('admin/user', $id);
			if (!empty($lock) && strtotime($lock['updated']) > (time() - 15) && $lock['user_id'] != $this->session->get('loggedUser')['id']) {
				$this->session->setFlashdata('error', 'Ο χρήστης ' . $lock['username'] . ' επεξεργάζεται τον user. Πατήστε <a href="' . base_url('users/getLock/' . $id) . '">εδώ</a> για να κάνετε ανάληψη.');
				return redirect()->to('admin/users/index');
			}
			$data['user'] = $userModel->getWithGroups($id);
			$editingLock->saveLock('admin/user', $id, $this->session->get('loggedUser')['id'], time());
		}

		$data['userGroups'] = $userModel->getGroupsForSelect(true);
		$data['pageData'] = array('title' => (empty($id) ? 'New ' : 'Edit ').'backend user');

		echo view('admin/header', $data);
		echo view('admin/users/edit', $data);
		echo view('admin/footer', array(
			'tinymce' => true,
			'loadJs' => array('plugins/sliders/nouislider.min.js', 'basic_form.js', 'update_lock.js')
		));
	}

	public function attemptEdit($id)
	{
		$isAdmin = $this->user->isAdmin();

		if (!$isAdmin) {
			return redirect()->to("/users/edit/$id");
		}

		if (!$this->validate('editUser')) {
			return redirect()
				->to("admin/users/edit/$id")
				->withInput()
				->with('error', implode(' ', $this->validator->getErrors()));
		}

		$data = [
			'is_admin' => $this->request->getPost('is_admin'),
			'username' => $this->request->getPost('username'),
			'is_active' => (empty($this->request->getPost('is_active')) ? 0 : 1),
		];

		$this->db->transBegin();

		$this->userModel->update($id, $data);

		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return false;
		}

		$this->userModel->updateUserGroups($id, $this->request->getPost());

		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return false;
		}

		$this->db->transCommit();

		$this->session->setFlashdata('success', 'Οι αλλαγές σας αποθηκεύτηκαν.');
		return redirect()->to("admin/users/edit/$id")->with('success', 'Οι αλλαγές σας αποθηκεύτηκαν.');
	}
}
