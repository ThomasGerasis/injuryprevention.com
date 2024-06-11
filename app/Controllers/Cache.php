<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\RebuildCache;
use App\Libraries\CacheHandler;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\IncomingRequest;

class Cache extends Controller
{
    use ResponseTrait;

    public function rebuild($hash)
    {
        if (empty($hash)) {
            return ''; die();
        }

        helper(['display','misc']);

        $cacheModel = model(RebuildCache::class);

        $data = $cacheModel->where('hash', $hash)->first();


        if (!$data) {
            log_message('error','rebuild no data');
            return ''; die();
        }

        $date = strtotime($data['date']);
        $time = time();
        $twoMinutesAgo = $time - (MINUTE * 2);
        $notValid = $date > $time || $date < $twoMinutesAgo;

        if ($notValid) {
            $cacheModel->delete($data['id']);
            return ''; die();
        }
        $cacheHandler = new CacheHandler();
        $cacheHandler->rebuild($data);
        $cacheModel->delete($data['id']);
        
        return true;
    }

}
