<?php

namespace App\Models;

use CodeIgniter\Model;

class FooterMenu extends Model
{
	protected $table = 'footer_menus';
	protected $primaryKey = 'id';
	protected $allowedFields = ['total_menu_items', 'order_num', 'title'];
	private ActionLog $actionLogModel;
    private FooterMenuItem $footerMenuItemModel;
	public function __construct()
    {
        parent::__construct();
        $this->actionLogModel = model(ActionLog::class);
        $this->footerMenuItemModel = model(FooterMenuItem::class);
    }

	public function saveMenus($postData, $userID)
    {
		$this->db->transBegin();
        $savedMenus = [];
        $savedLinks = [];

        if (isset($postData['menus'])) {
            foreach ($postData['menus'] as $menu) {
                if (empty($menu['title'])) {
                    continue;
                }
                $data = array(
                    'total_menu_items' => $menu['total_menu_items'] ?? '',
                    'order_num' => $menu['order_num'],
                    'title' => $menu['title']
                );

                if (!empty($menu['id'])) {
                    $this->update($menu['id'], $data);
                    $menuID = $menu['id'];
                } else {
                    $menuID = $this->insert($data);
                }
                $savedMenus[] = $menuID;

                if (isset($menu['links'])) {
                    foreach ($menu['links'] as $link) {
                        $newData = array(
                            'footer_menu_id' => $menuID,
                            'type' => $link['type'],
                            'order_num' => $link['order_num'],
                            'title' => $link['title'],
                            'image_id' => (empty($link['image_id']) ? null : $link['image_id']),
                            'relative_url' => (empty($link['relative_url']) ? null : $link['relative_url']),
                            'external_url' => (empty($link['relative_url']) ? (empty($link['external_url']) ? null : $link['external_url']) : null),
                        );

                        if (!empty($link['id'])) {
                            $this->footerMenuItemModel->update($link['id'], $newData);
                        } else {
                            $link['id'] = $this->footerMenuItemModel->insert($newData);
                        }
                        $savedLinks[] = $link['id'];
                    }
                }
            }
        }

        $linksToDelete = $this->db->table('footer_menu_items');
        if (count($savedLinks)) {
            $linksToDelete = $linksToDelete->whereNotIn('id', $savedLinks);
            $linksToDelete->delete();
        }

        $menusToDelete = $this->db->table('footer_menus');
        if (count($savedMenus)) {
              $menusToDelete = $menusToDelete->whereNotIn('id', $savedMenus);
              $menusToDelete->delete();
        }

		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return false;
		}

		$actionData = array(
			'user_id' => $userID, 
			'when' => date('Y-m-d H:i:s'), 
			'model_id' => '1',
			'model_name' => 'FooterMenu',
			'what' => 'Changed menu'
		);

		$this->actionLogModel->insert($actionData);
		$this->db->transCommit();
		return true;
	}
	
}
