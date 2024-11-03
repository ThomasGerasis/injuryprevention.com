<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    private ActionLog $actionLogModel;

    private UserGroup $userGroupModel;
    private UserToGroup $userToGroupModel;

    protected $allowedFields = [
        'is_admin',
        'username',
        'email',
        'is_active',
        'date_last_connected',
        'ip_last_connected'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->userGroupModel = model(UserGroup::class);
        $this->userToGroupModel = model(UserToGroup::class);
        $this->actionLogModel = model(ActionLog::class);
    }

    public function googleLogin($email, $ip)
    {

        $user = $this->getUserByEmail($email);

        if ($user === null) {
            return null;
        }

        if (empty($user['id'])) {
            return null;
        }
        
        if (empty($user['is_active'])) {
            return null;
        }

        $dataToUpdate = [
            'date_last_connected' => date('Y-m-d H:i:s'),
            'ip_last_connected'      => $ip,
        ];

        $this->update($user['id'], $dataToUpdate);

        if ($user['is_admin'] != 1) {
            $userGroups = $this->userToGroupModel->where('user_id', $user['id'])->findAll();
            $user['userGroups'] = [];
            foreach ($userGroups as $userGroup) {
                $groupId = $userGroup['user_group_id'];
                $relationIds = $userGroup['relation_ids'];
                $user['userGroups'][$groupId] = empty($relationIds) ? [] : json_decode($relationIds,true);
            }
        }

        return $user;
    }

    function deactivate($id, $user_id)
	{
		$userRow = $this->find($id);
		if(empty($userRow['is_active'])){
			return array('response'=>false,'message'=>'The user is already inactive.');
		}
		$this->db->transBegin();
		$this->update($id, array('is_active'=>'0'));
		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return array('response'=>false,'message'=>'The user was not deactivated.');
		}
		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_id' => $id,
			'model_name' => 'User',
			'what' => 'Deactivate'
		);
		$this->actionLogModel->insert($actionData);
		$this->db->transCommit();
		return array('response'=>true,'message'=>'The user was deactivated.');
	}

    function activate($id, $user_id)
	{
		$userRow = $this->find($id);
		if(!empty($userRow['is_active'])){
			return array('response'=>false,'message'=>'The user is already active.');
		}
		$this->db->transBegin();
		$this->update($id, array('is_active'=>'1'));
		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return array('response'=>false,'message'=>'The user was not activated.');
		}
		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_id' => $id,
			'model_name' => 'User',
			'what' => 'Activate'
		);
		$this->actionLogModel->insert($actionData);
		$this->db->transCommit();
		return array('response'=>true,'message'=>'The user was activated.');
	}

    public function getUserByEmail(string $email)
    {
        $user = $this->where('email', $email)->first();
        return $user ?? false;
    }

    public function addRelationId($userId, $groupId, $key, $newId)
    {
        $userGroup = $this->userToGroupModel
            ->where('user_id', $userId)
            ->where('user_group_id', $groupId)
            ->findAll();

        $relations = empty($userGroup['relation_ids']) ? [] : json_decode($userGroup['relation_ids']);

        if (!isset($relations[$key])) {
            $relations[$key][] = [];
        }

        $relations[$key][] = $newId;

        $this->userToGroupModel->where('id', $userGroup['id'])->update(['relation_ids' => json_encode($relations)]);

        return $relations;
    }

    public function getGroupsForSelect($full = false) {

		$builder = $this->db->table('user_group')->orderBy('order_num', 'asc')->get();
		$resp = array();
		foreach($builder->getResultArray() as $row){
			$resp[$row['id']] = ($full ? $row : $row['name']);
		}
		return $resp;
	}

    public function updateUserGroups($id, $post_data)
    {
        if(!empty($post_data['is_admin'])){
            $this->db->table('user_to_group')->where('user_id',$id)->delete();
            return;
        }
        //log_message('error',print_r($post_data,true));
        if(empty($post_data['userGroup'])) return;

        $all_groups = $this->getGroupsForSelect(true);
        $user_group_ids = array();
        foreach($all_groups as $uid=>$gro){
            if(empty($post_data['userGroup'][$uid]['on'])) continue;
            $group_rels = (empty($gro['relations']) ? array() : explode('|',trim($gro['relations'],'|')));
            $relation_ids = array();
            foreach($group_rels as $group_rel){
                if(!empty($post_data['userGroup'][$uid]['all_'.$group_rel])){
                    $relation_ids['all_'.$group_rel] = '1';
                }else if(!empty($post_data['userGroup'][$uid][$group_rel])){
                    $relation_ids[$group_rel] = $post_data['userGroup'][$uid][$group_rel];
                }
            }
            if(empty($post_data['userGroup'][$uid]['id'])){
                $this->db->table('user_to_group')->insert(array('user_id'=>$id,'user_group_id'=>$uid,'relation_ids'=>(empty($relation_ids)?NULL:json_encode($relation_ids))));
                $user_group_ids[] = $this->db->insertID();
            }else{
                $this->db->table('user_to_group')->where('id',$post_data['userGroup'][$uid]['id'])->update(array('relation_ids'=>empty($relation_ids)?NULL:json_encode($relation_ids)));
                $user_group_ids[] = $post_data['userGroup'][$uid]['id'];
            }
            
        }
        $p_del = $this->db->table('user_to_group')->where('user_id',$id);
        if(count($user_group_ids)){
            $p_del = $p_del->whereNotIn('id',$user_group_ids);
        }
        $p_del = $p_del->delete();
		return true;
    }

    public function getWithGroups($id)
    {
        $user = $this->find($id);

        $userGroupList = $this->userToGroupModel->where('user_id', $id)->findAll();
        $user['userGroups'] = [];
        foreach ($userGroupList as $userGroup) {
            $user['userGroups'][$userGroup['user_group_id']] = $userGroup;
        }

        return $user;
    }

    // Legacy Methods ( To be refactored )
    function init_params($params = null)
    {
        if (is_array($params) && count($params)) {
            if (isset($params['user_group_id']) && $params['user_group_id'] != 'all' && $params['user_group_id'] != 'admin') {
                $included_ids = array();
                $usrs = $this->db->table('user_to_group')
                    ->where('user_group_id', $params['user_group_id'])
                    ->get();
                foreach ($usrs->getResultArray() as $row) {
                    $included_ids[$row['user_id']] = $row['user_id'];
                }
                $params['included_ids'] = $included_ids;
            } else if (isset($params['user_group_id']) && $params['user_group_id'] == 'admin') {
                $params['is_admin'] = '1';
            }
        }
        return $params;
    }

    function fill_params($builder, $params = null)
    {

        if (is_array($params) && count($params)) {

            if (!empty($params['included_ids']) && is_array($params['included_ids']) && count($params['included_ids'])) {
                $builder->whereIn('users.id', $params['included_ids']);
            } else if (isset($params['included_ids'])) {
                $builder->where('users.id', '0');
            }
            if (isset($params['is_admin'])) {
                $builder->where('users.is_admin', '1');
            }
            if (isset($params['term']) && !empty($params['term'])) {
                $builder->groupStart()
                    ->like('users.username', $params['term'])
                    ->orLike('users.email', $params['term'])
                    ->groupEnd();
            }
        }
        return $builder;
    }

    function get_count($params = null)
    {
        $params = $this->init_params($params);
        $builder = $this->builder('users')->select('count(*) as total_count');
        $builder = $this->fill_params($builder, $params);
        $query = $builder->get();
        $row = $query->getRowArray();
        return $row['total_count'];
    }

    function get_paginated_list($page = null, $params = array())
    {
        $params = $this->init_params($params);
        if (empty($params['sortingColumn'])) $params['sortingColumn'] = 'username';
        if (empty($params['sortingType'])) $params['sortingType'] = 'asc';

        $builder = $this->builder('users');
        $builder = $this->fill_params($builder, $params);
        if (!empty($page)) $builder->limit(PAGE_LENGTH, ($page - 1) * PAGE_LENGTH);
        $builder->orderBy($params['sortingColumn'], $params['sortingType']);
        $query = $builder->get();
        $res = array();

        foreach ($query->getResultArray() as $row) {
            $row['groups'] = array();
        
            $groups = $this->db->table('user_to_group')->where('user_id', $row['id'])->get()->getResultArray();
            foreach ($groups as $group) {
                if (!empty($group['relation_ids'])) {
                    $rs = json_decode($group['relation_ids'], true);
                    foreach ($rs as $gkey => $gval) {
                        switch ($gkey) {
                            default:
                                break;
                        }
                    }
                    $group['relation_ids'] = json_encode($rs);
                }
                $row['groups'][] = $group;
            }
            $res[] = $row;
        }
        return $res;
    }
    // End of Legacy Methods
}
