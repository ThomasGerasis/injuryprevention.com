<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use CodeIgniter\Controller;

class Migrate extends Controller{

	public function index()
	{
		$migrate = \Config\Services::migrations();
		try {
			$migrate->latest();
		} catch (\Throwable $e) {
			// Do something with the error here...
		}
	}
}
