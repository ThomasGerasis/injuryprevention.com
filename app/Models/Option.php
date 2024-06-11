<?php

namespace App\Models;

use CodeIgniter\Model;

class Option extends Model
{
	protected $table = 'options';
	protected $primaryKey = 'id';
	protected $allowedFields = ['name', 'value'];
	private ActionLog $actionLogModel;
	
	public function __construct()
    {
        parent::__construct();
        $this->actionLogModel = model(ActionLog::class);
    }
	
    function saveData($post_data, $user_id, $id)
	{
		$this->db->transBegin();

		$actionData = array(
			'user_id' => $user_id, 
            'model_id' => $id,
			'when' => date('Y-m-d H:i:s'), 
            'what' => 'Option',
			'model_name' => 'Update options'
		);

		$data = array('value' => (empty($post_data) ? NULL : json_encode($post_data)));
		$this->update($id, $data);
		$this->actionLogModel->insert($actionData);

		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return false;
		}

		$this->db->transCommit();
		return true;
	}
}
