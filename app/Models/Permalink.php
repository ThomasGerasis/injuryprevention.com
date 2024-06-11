<?php
namespace App\Models;
use CodeIgniter\Model;
class Permalink extends Model {
	protected $table = 'permalinks';
	protected $primaryKey = 'id';
	protected $allowedFields = ['permalink','type','type_id','published'];
}