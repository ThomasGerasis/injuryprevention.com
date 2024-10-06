<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;

use App\Models\Option;
use App\Models\EditingLock;

class SiteOptions extends BaseController
{
	public function getLock($name)
	{
		$editingLock = model(EditingLock::class);
		$editingLock->saveLock('option', $name, $this->session->get('loggedUser')['id'], time());
		return redirect()->to('admin/siteOptions/edit/' . $name);
	}

	function edit($name)
	{
		$editingLock = model(EditingLock::class);
		$optionModel = model(Option::class);
        $data = array();

		$data['option'] = $optionModel->where('name',$name)->first();
        if(empty($data['option'])){
			$data['option'] = array('name' => $name, 'value' => '');
        }
    
        $lock = $editingLock->getLock('option', $name);
        if (!empty($lock) && strtotime($lock['updated']) > (time() - 15) && $lock['user_id'] != $this->session->get('loggedUser')['id']) {
            $this->session->setFlashdata('error', 'Ο χρήστης ' . $lock['username'] . ' επεξεργάζεται τις ρυθμίσεις. Πατήστε <a href="' . base_url('siteOptions/getLock/' . $name) . '">εδώ</a> για να κάνετε ανάληψη.');
            return redirect()->to('admin/dashboard');
        }
        $editingLock->saveLock('option', $name, $this->session->get('loggedUser')['id'], time());
		
        if ($_POST) {
			$savedResponse = $optionModel->saveData($_POST, $this->session->get('loggedUser')['id'], $name);
			if ($savedResponse) {
			    rebuildCache('option', $name, 'update');
				$this->session->setFlashdata('success', 'Οι αλλαγές σας αποθηκεύτηκαν.');
				return redirect()->to('admin/siteOptions/' . $name);
			} else {
				$data['errors'] = $this->validator->getErrors();
			}
		}

		$data['pageData'] = array('title' => 'Edit options');


		// var_dump('edit_'.$name);
		echo view('admin/header', $data);
		echo view('admin/options/edit_'.$name, $data);
		echo view('admin/footer', ['tinymce' => true, 'loadJs' => ['editing_neweditor.js', 'custom_tinymce.js', 'edit_lock.js']]);
	}

}
