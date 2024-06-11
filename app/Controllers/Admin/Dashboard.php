<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Dashboard extends BaseController
{
	public function index()
	{
		$data = array();
		$data['pageData'] = array('title'=>'Dashboard');
        echo view('admin/header',$data);
		echo view('admin/dashboard',$data);
		echo view('admin/footer',$data);
	}
	
	function swapAdmin(){
		if(!empty($this->session->get('loggedUser')['swappedAdmin'])){
			$user = $this->session->get('loggedUser')['swappedAdmin'];
			$this->session->set('loggedUser', $user);
			return redirect()->to('admin/dashboard');
		}
		$this->session->setFlashdata('error', 'Page not found!');
		return redirect()->to('admin/dashboard');
	}
}
