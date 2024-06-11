<?php

namespace App\Controllers;

use App\Libraries\CacheHandler;
use CodeIgniter\Controller;

class Crons extends Controller
{
    private CacheHandler $cacheHandler;

    public function __construct()
    {
        parent::initController(service('request'), service('response'), service('logger'));
        $this->cacheHandler = new CacheHandler();
    }

}
		