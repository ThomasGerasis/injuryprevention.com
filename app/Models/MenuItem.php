<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuItem extends Model
{
	protected $table = 'menu_items';
	protected $primaryKey = 'id';
	protected $allowedFields = ['type', 'order_num', 'title', 'image_id', 'relative_url', 'external_url'];
	private ActionLog $actionLogModel;

	public function __construct()
    {
        parent::__construct();
        $this->actionLogModel = model(ActionLog::class);
    }

	function saveItems($post_data, $user_id){
		$savedLinks = array();
		$this->db->transBegin();
		foreach($post_data['links'] as $link){
			if(empty($link['title'])) continue;
			$new_data = array(
				'type' => $link['type'],
				'order_num' => $link['order_num'],
				'title' => $link['title'],
				'image_id' => (empty($link['image_id'])?NULL:$link['image_id']),
				'relative_url' => (empty($link['relative_url'])?NULL:$link['relative_url']),
				'external_url' => (empty($link['relative_url'])?(empty($link['external_url'])?NULL:$link['external_url']):NULL),
			);
			if (!empty($link['id'])) {
				$this->update($link['id'], $new_data);
			} else {
				$link['id'] = $this->insert($new_data);
			}
			$savedLinks[] = $link['id'];
		}
		$linksToDelete = $this->db->table('menu_items');
		if (count($savedLinks)) {
			$linksToDelete = $linksToDelete->whereNotIn('id', $savedLinks);
		}
		$linksToDelete = $linksToDelete->delete();

		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return false;
		}
		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_id' => '1',
			'model_name' => 'MenuItem',
			'what' => 'Changed menu'
		);
		$this->actionLogModel->insert($actionData);
		$this->db->transCommit();
		return true;
	}
	
}
