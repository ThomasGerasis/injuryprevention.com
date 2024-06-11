<?php

namespace App\Models;

use CodeIgniter\Model;

class ActionLog extends Model
{
	protected $table = 'action_log';
	protected $primaryKey = 'id';
	protected $allowedFields = ['user_id', 'when', 'what', 'json_changes', 'model_id', 'model_name'];

}
