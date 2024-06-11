<?php

namespace App\Models;

use CodeIgniter\Model;

class PageFaq extends Model
{
	protected $table = 'page_faqs';
	protected $primaryKey = 'id';
	protected $allowedFields = ['page_id', 'question', 'answer', 'order_num'];
	private ActionLog $actionLogModel;
	
	public function __construct()
    {
        parent::__construct();
        $this->actionLogModel = model(ActionLog::class);
    }
}
