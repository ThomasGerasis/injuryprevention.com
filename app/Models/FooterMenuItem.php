<?php

namespace App\Models;

use CodeIgniter\Model;

class FooterMenuItem extends Model
{
	protected $table = 'footer_menu_items';
	protected $primaryKey = 'id';
	protected $allowedFields = ['type', 'order_num', 'title', 'image_id', 'relative_url', 'external_url','footer_menu_id'];

	public function __construct()
    {
        parent::__construct();
    }

    public function getMenuLinks($menuID)
    {
        return $this->where('footer_menu_id', $menuID)->orderBy('order_num', 'asc')->findAll();
    }
	
}
