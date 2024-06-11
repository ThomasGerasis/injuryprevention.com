<?php

namespace App\Models;

use CodeIgniter\Model;

class UserGroup extends Model {
	protected $table = 'user_group';
	protected $primaryKey = 'id';

	protected $allowedFields = [
		'name',
		'plural_name',
        'order_num',
        'relations'
	];
}