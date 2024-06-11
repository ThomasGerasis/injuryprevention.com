<?php

namespace App\Models;

use CodeIgniter\Model;

class EditingLock extends Model
{
	protected $table = 'editing_lock';
	protected $primaryKey = 'id';

	function getLock($type, $type_id)
	{
		$builder = $this->db->table('editing_lock');
		$builder->select('editing_lock.*, users.username');
		$builder->where('type', $type);
		$builder->where('type_id', $type_id);
		$builder->join('users', 'users.id = editing_lock.user_id');
		$query = $builder->get();
		$row = $query->getRowArray();
		return (empty($row['id']) ? false : $row);
	}

	function saveLock($type, $type_id, $user_id, $time)
	{
		$this->db->query("INSERT INTO editing_lock (type, type_id, user_id, updated) VALUES ('" . $type . "', '" . $type_id . "', '" . $user_id . "', '" . date('Y-m-d H:i:s', $time) . "') ON DUPLICATE KEY update user_id = VALUES(user_id), updated = VALUES(updated)");
	}

	function cleanLocks()
	{
		$builder = $this->db->table('editing_lock');
		$builder->where('updated <', date('Y-m-d H:i:s', (time() - 40)));
		$builder->delete();
	}
}
