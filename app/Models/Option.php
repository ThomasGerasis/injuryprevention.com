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
	
    function saveData($post_data, $user_id, $optionName)
	{
		$this->db->transBegin();

		$currentData = $this->where('name',$optionName)->first();
	
		$data = array('value' => (empty($post_data) ? NULL : json_encode($post_data)));

		if (empty($currentData)) {
			$data['name'] = $optionName;
			$id = $this->insert($data);
		}else{
			$id = $currentData['id'];	
			$this->update($id, $data);
		}

		$actionData = array(
			'user_id' => $user_id, 
            'model_id' => $id,
			'when' => date('Y-m-d H:i:s'), 
            'what' => 'Option',
			'model_name' => 'Update options'
		);

		$this->actionLogModel->insert($actionData);

		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return false;
		}

		$this->db->transCommit();
		return true;
	}
}
