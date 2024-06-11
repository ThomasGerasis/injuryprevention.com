<?php
namespace App\Models;

use CodeIgniter\Model;

class SiteUser extends Model
{
	protected $table = 'site_users';
	protected $primaryKey = 'id';
	protected $allowedFields = ['email','firstname', 'lastname' ,'is_active', 'date_added',
	'last_connected_provider',
	'last_connected_provider_token',
	'last_connected_time',
	'last_connected_ip',
    ];
	
	private ActionLog $actionLogModel;
	
	public function __construct()
    {
        parent::__construct();
        $this->actionLogModel = model(ActionLog::class);
    }

	function fillParams($builder, $params = null)
    {
        if (is_array($params) && count($params)) {

            if (isset($params['is_active']) && $params['is_active'] != 'all') {
                $builder->where('is_active', $params['is_active']);
           	}

			if (isset($params['is_moderator']) && $params['is_moderator'] != 'all') {
                $builder->where('is_moderator', $params['is_moderator']);
           	}

			if (isset($params['filter_type']) && $params['filter_type'] != 'all') {
				switch($params['filter_type']){
					case 'isModerator': $builder->where('is_moderator', '1'); break;
					case 'isSimple': $builder->where('is_moderator', '0'); break;
					case 'isStreamer': $builder->where('is_streamer', '1'); break;
				}
           	}

			if (!empty($params['date_from'])) {
                $builder->where('date_added >=', date_to_db($params['date_from']).' 00:00:00');
           	}
			
            if (isset($params['term']) && !empty($params['term'])) {
                $builder->groupStart()
//                    ->like('username', $params['term'])
                    ->orLike('email', $params['term'])
                    ->orLike('firstname', $params['term'])
					->orLike('lastname', $params['term'])
                    ->groupEnd();
            }
        }
        return $builder;
    }

    function getCount($params = null)
    {
        $builder = $this->builder('site_users')->select('count(*) as total_count');
        $builder = $this->fillParams($builder, $params);
        $query = $builder->get();
        $row = $query->getRowArray();
        return $row['total_count'];
    }

    function getPaginatedList($page = null, $params = array())
    {
        if (empty($params['sortingColumn'])) $params['sortingColumn'] = 'email';
        if (empty($params['sortingType'])) $params['sortingType'] = 'asc';

        $builder = $this->builder('site_users');
        $builder = $this->fillParams($builder, $params);
        if (!empty($page)) $builder->limit(PAGE_LENGTH, ($page - 1) * PAGE_LENGTH);
		$sort = $params['sortingColumn'].' '.$params['sortingType'];
		$builder->orderBy($sort);
        $query = $builder->get();
        return $query->getResultArray();
    }

	function deactivate($id, $user_id)
	{
		$userRow = $this->find($id);
		if(empty($userRow['is_active'])){
			return array('response'=>false,'message'=>'The site user is already inactive.');
		}
		$this->db->transBegin();
		$this->update($id, array('is_active'=>'0'));
		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return array('response'=>false,'message'=>'The site user was not deactivated.');
		}
		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_id' => $id,
			'model_name' => 'SiteUser',
			'what' => 'Deactivate'
		);
		$this->actionLogModel->insert($actionData);
		$this->db->transCommit();
		return array('response'=>true,'email'=>$userRow['email'],'message'=>'The site user was deactivated.');
	}

    function activate($id, $user_id)
	{
		$userRow = $this->find($id);
		if(!empty($userRow['is_active'])){
			return array('response'=>false,'message'=>'The site user is already active.');
		}
		$this->db->transBegin();
		$this->update($id, array('is_active'=>'1'));
		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return array('response'=>false,'message'=>'The site user was not activated.');
		}
		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_id' => $id,
			'model_name' => 'SiteUser',
			'what' => 'Activate'
		);
		$this->actionLogModel->insert($actionData);
		$this->db->transCommit();
		return array('response'=>true,'email'=>$userRow['email'],'message'=>'The site user was activated.');
	}
    
    function turnSimple($id, $user_id)
	{
		$userRow = $this->find($id);
		if(empty($userRow['is_moderator'])){
			return array('response'=>false,'message'=>'The site user is already a simple user.');
		}
		$this->db->transBegin();
		$this->update($id, array('is_moderator'=>'0'));
		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return array('response'=>false,'message'=>'The site user was not switched to simple user.');
		}
		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_id' => $id,
			'model_name' => 'SiteUser',
			'what' => 'Switch to simple user'
		);
		$this->actionLogModel->insert($actionData);
		$this->db->transCommit();
		return array('response'=>true,'email'=>$userRow['email'],'message'=>'The site user was switched to simple user.');
	}

}
