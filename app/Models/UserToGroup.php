<?php

namespace App\Models;

use CodeIgniter\Model;

class UserToGroup extends Model {
	protected $table = 'user_to_group';
	protected $primaryKey = 'id';

	protected $allowedFields = [
		'user_id',
		'user_group_id',
        'relation_ids'
	];
}