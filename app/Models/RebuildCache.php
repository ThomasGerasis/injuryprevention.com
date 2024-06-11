<?php
namespace App\Models;
use CodeIgniter\Model;
class RebuildCache extends Model {
	protected $table = 'rebuild_cache';
	protected $primaryKey = 'id';
	protected $allowedFields = ['hash','date','type','type_id','action'];
}