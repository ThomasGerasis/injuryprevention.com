<?php

namespace App\Models;

use CodeIgniter\Model;

class FaqCategory extends Model
{
	protected $table = 'faq_categories';
	protected $primaryKey = 'id';
	protected $allowedFields = ['title', 'order_num'];
	private ActionLog $actionLogModel;
	
	public function __construct()
    {
        parent::__construct();
        $this->actionLogModel = model(ActionLog::class);
    }

	function saveData($post_data, $user_id, $id = null)
	{
		$this->db->transBegin();

		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_name' => 'FaqCategory'
		);

		$data = array(
			'title' => $post_data['title'],
			'order_num' => $post_data['order_num']
		);
		if (empty($id)) {
			$id = $this->insert($data);
			$actionData['model_id'] = $id;
			$actionData['what'] = 'Created FAQ category';			
		} else {
			$this->update($id, $data);
			$actionData['model_id'] = $id;
			$actionData['what'] = 'Update FAQ category';
		}

		$this->actionLogModel->insert($actionData);

		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return false;
		}

		$this->db->transCommit();
		return array('id' => $id);
	}

	function saveSort($post_data, $user_id){
		$this->db->transBegin();
		foreach($post_data['category'] as $category_id=>$order_num){
			$this->update($category_id, array('order_num'=>$order_num));
		}
		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return false;
		}
		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_id' => '1',
			'model_name' => 'FaqCategory',
			'what' => 'Changed FAQ categories ordering'
		);
		$this->actionLogModel->insert($actionData);
		$this->db->transCommit();
		return true;
	}
}
