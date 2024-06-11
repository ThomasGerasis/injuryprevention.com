<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\SiteUser;

class SiteUsers extends BaseController
{
	public function index()
	{
		if (is_null($this->session->get('siteUsersIndexPage'))) {
			$page = 1;
			$this->session->set('siteUsersIndexPage', 1);
		} else {
			$page = $this->session->get('siteUsersIndexPage');
		}
		if (!($this->session->get('siteUsersIndex'))) $this->session->set('siteUsersIndex', []);

		$data = [];
		$data['page'] = $page;
		$data['sessionData'] = $this->session->get('siteUsersIndex');

		$userModel = model(SiteUser::class);
		$data['count'] = $userModel->getCount($this->session->get('siteUsersIndex'));
		$data['list'] = $userModel->getPaginatedList($page, $this->session->get('siteUsersIndex'));

		$data['pageData'] = array('title' => 'Site users');

		return view('admin/header', $data)
			. view('admin/site_users/index', $data)
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
				$this->session->set('siteUsersIndex', []);
			} else {
				$this->session->set('siteUsersIndex', $_POST);
			}
		}
		if (!($this->session->get('siteUsersIndex'))) $this->session->set('siteUsersIndex', []);
		if (empty($page)) $page = 1;

		$this->session->set('siteUsersIndexPage', $page);
		$data = [];
		$data['page'] = $page;
		$data['sessionData'] = $this->session->get('siteUsersIndex');

		$userModel = model(SiteUser::class);

		$data['count'] = $userModel->getCount($this->session->get('siteUsersIndex'));
		$data['list'] = $userModel->getPaginatedList($page, $this->session->get('siteUsersIndex'));

		$resp['update_data'] = true;
		$resp['table_data'] = view('admin/site_users/_table_data', $data);
		echo json_encode($resp);
		return;
		die();
	}
	
	function exportResults()
	{
		if (!($this->session->get('siteUsersIndex'))) $this->session->set('siteUsersIndex', []);
		$userModel = model(SiteUser::class);
		$data['list'] = $userModel->getPaginatedList(NULL, $this->session->get('siteUsersIndex'));

		// force download  
		header('Content-Encoding: UTF-8');
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Type: application/vnd.ms-excel;charset=utf-8');
		header("Content-Disposition: attachment;filename=export_site_users.xls");
		echo view('admin/site_users/export_table_data',$data);
		return;
	}
	

	function deactivate($id)
	{
		$userModel = model(SiteUser::class);
		$response = $userModel->deactivate($id, $this->session->get('loggedUser')['id']);
		if ($response['response']) {
			$this->session->setFlashdata('success', $response['message']);
			rebuildCache('siteUser', $response['email'], 'deactivate');
		} else {
			$this->session->setFlashdata('error', $response['message']);
		}
		return redirect()->to('admin/siteUsers');
	}

	function activate($id)
	{
		$userModel = model(SiteUser::class);
		$response = $userModel->activate($id, $this->session->get('loggedUser')['id']);
		if ($response['response']) {
			$this->session->setFlashdata('success', $response['message']);
			rebuildCache('siteUser', $response['email'], 'activate');
		} else {
			$this->session->setFlashdata('error', $response['message']);
		}
		return redirect()->to('admin/siteUsers');
	}


	function makeSimple($id)
	{
		$userModel = model(SiteUser::class);
		$response = $userModel->turnSimple($id, $this->session->get('loggedUser')['id']);
		if ($response['response']) {
			$this->session->setFlashdata('success', $response['message']);
			rebuildCache('siteUser', $response['email'], 'makeSimple');
		} else {
			$this->session->setFlashdata('error', $response['message']);
		}
		return redirect()->to('admin/siteUsers');
	}

}
