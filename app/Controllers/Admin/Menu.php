<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;

use App\Models\MenuItem;
use App\Models\EditingLock;

class Menu extends BaseController
{
	public function index()
	{
		$editingLock = model(EditingLock::class);
		$lock = $editingLock->getLock('menuItems', '1');
		if (!empty($lock) && strtotime($lock['updated']) > (time() - 15) && $lock['user_id'] != $this->session->get('loggedUser')['id']) {
			$this->session->setFlashdata('error', 'Ο χρήστης ' . $lock['username'] . ' επεξεργάζεται το menu. Πατήστε <a href="' . base_url('menu/getLock') . '">εδώ</a> για να κάνετε ανάληψη.');
			return redirect()->to('admin/dashboard');
		}
		$editingLock->saveLock('menuItems', '1', $this->session->get('loggedUser')['id'], time());

		$menuModel = model(MenuItem::class);
		if ($_POST) {
			$savedResponse = $menuModel->saveItems($_POST, $this->session->get('loggedUser')['id']);
			if ($savedResponse) {
				rebuildCache('menuItems', 1, 'update');
				$this->session->setFlashdata('success', 'Οι αλλαγές σας αποθηκεύτηκαν.');
			} else {
				$this->session->setFlashdata('error', 'Προέκυψε ένα πρόβλημα και οι αλλαγές σας δεν αποθηκεύτηκαν.');
			}
			return redirect()->to('admin/menu');
		}

		$data = array();
		$data['links'] = $menuModel->orderBy('order_num', 'asc')->findAll();
		$data['pageData'] = array('title' => 'Menu');

		echo view('admin/header', $data);
		echo view('admin/menu/index', $data);
		echo view('admin/footer', array(
			'tinymce' => true,
			'loadJs' => array('desktop_menu.js')
		));
	}

	public function getLock()
	{
		$editingLock = model(EditingLock::class);
		$editingLock->saveLock('menuItems', '1', $this->session->get('loggedUser')['id'], time());
		return redirect()->to('admin/menu');
	}
}
