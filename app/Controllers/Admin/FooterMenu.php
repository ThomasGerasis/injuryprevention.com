<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;

use App\Models\EditingLock;
use App\Models\FooterMenu as FooterMenuModel;
use App\Models\FooterMenuItem as FooterMenuItemModel;

class FooterMenu extends BaseController
{

    private FooterMenuModel $footerMenuModel;
    private FooterMenuItemModel $footerMenuItemModel;

    public function __construct()
    {
        $this->footerMenuModel = model(FooterMenuModel::class);
        $this->footerMenuItemModel = model(FooterMenuItemModel::class);
    }

    public function index()
    {
        $editingLock = model(EditingLock::class);
        $lock = $editingLock->getLock('footerMenuItems', '1');
        if (!empty($lock) && strtotime($lock['updated']) > (time() - 15) && $lock['user_id'] != $this->session->get('loggedUser')['id']) {
            $this->session->setFlashdata('error', 'Ο χρήστης ' . $lock['username'] . ' επεξεργάζεται το menu. Πατήστε <a href="' . base_url('footerMenu/getLock') . '">εδώ</a> για να κάνετε ανάληψη.');
            return redirect()->to('admin/dashboard');
        }
        $editingLock->saveLock('footerMenuItems', '1', $this->session->get('loggedUser')['id'], time());

        if ($_POST) {
            $savedResponse = $this->footerMenuModel->saveMenus($_POST, $this->session->get('loggedUser')['id']);
            if ($savedResponse) {
                rebuildCache('footerMenuItems', 1, 'update');
                $this->session->setFlashdata('success', 'Οι αλλαγές σας αποθηκεύτηκαν.');
            } else {
                $this->session->setFlashdata('error', 'Προέκυψε ένα πρόβλημα και οι αλλαγές σας δεν αποθηκεύτηκαν.');
            }
            return redirect()->to('admin/footerMenu');
        }

        $data = array();
        $data['menus'] = $this->footerMenuModel->orderBy('order_num', 'asc')->findAll();

        foreach ($data['menus'] as $i=>$menu) {
            //$data['menu_links'][$menu['id']] = $this->footerMenuItemModel->getMenuLinks($menu['id']);
            $data['menus'][$i]['links'] = $this->footerMenuItemModel->getMenuLinks($menu['id']);
        }

        $data['pageData'] = array('title' => 'Footer Menu');

        return view('admin/header', $data) . view('admin/menu/footer_menu_index', $data) . view('admin/footer', [
                'tinymce' => true,
                'loadJs' => array('footer_menu.js')
        ]);
    }

    public function getLock()
    {
        $editingLock = model(EditingLock::class);
        $editingLock->saveLock('footerMenuItems', '1', $this->session->get('loggedUser')['id'], time());
        return redirect()->to('admin/footerMenu');
    }
}
