<?php

namespace App\Controllers;

use App\Libraries\Exceptions\PageResolverNotFoundException;
use App\Libraries\PageResolver\PageResolver;
use App\Libraries\PageResolver\PageResolverFactory;

class Page extends BaseController
{
    
    /**
     * @throws PageResolverNotFoundException
     */

    public function index($slug, $page = null)
    {
        if(empty($slug) || $slug == 'dist'){
            return redirect()->to('/');
        }
	    $permalink = $this->cacheHandler->getPermalink(trim(urldecode($slug)));

        if (!($permalink)) {
            log_message('error', 'Page not found for slug ' . $slug);
            return view('errors/html/error_404', ['message' => "$slug not found"]);
        }

        if ($page && $permalink['type'] !== 'articleCategory') {
            return view('errors/html/error_404', ['message' => "$slug not found"]);
        }

        $type = PageResolverFactory::make(
            $permalink['type'],
            $this,
            $permalink['type_id'],
            $page ?? 1
        );

        return (new PageResolver($type))->resolve();
    }
}
