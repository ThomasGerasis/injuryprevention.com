<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Dashboard');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// admin group

$routes->group('admin',['namespace' => 'App\Controllers\Admin'], static function ($routes) {

    // route since we don't have to scan directories.
    $routes->get('/', 'Dashboard::index',['filter' => 'loggedFilter']);

    $routes->get('migrate', 'Migrate::index',['filter' => 'adminFilter']);

    $routes->get('images/(:any)/(:any)', 'MediaServer::index/$1/$2');

    //view logs
    $routes->get('viewLogs', 'ViewLogs::view',['filter' => 'adminFilter']);
    $routes->get('viewLogs/(:any)', 'ViewLogs::view/$1',['filter' => 'adminFilter']);
    $routes->get('viewLogs/(:any)/(:num)', 'ViewLogs::view/$1/$2',['filter' => 'adminFilter']);

    //images
    $routes->add('images/(:any)/(:any)', 'MediaServer::index/$1/$2');
    $routes->post('fileUpload/do_upload_image/(:any)', 'FileUpload::do_upload_image/$1',['filter' => 'loggedFilter']);

    //user login/logout
    $routes->get('login', 'AccessControl::login', ['filter' => 'notLoggedFilter']);
    $routes->post('login/googleauth', 'AccessControl::attemptGoogleAuth');
    $routes->get('logout', 'AccessControl::logout',['filter' => 'loggedFilter']);

    //dashboard
    $routes->get('dashboard', 'Dashboard::index',['filter' => 'loggedFilter']);
    $routes->get('dashboard/(:any)', 'Dashboard::$1',['filter' => 'loggedFilter']);

    //homepage
    $routes->get('homepage', 'Homepage::index',['filter' => 'adminFilter']);
    $routes->post('homepage', 'Homepage::index',['filter' => 'adminFilter']);
    $routes->get('homepage/getLock', 'Homepage::getLock',['filter' => 'adminFilter']);

    $routes->get('menu', 'Menu::index',['filter' => 'adminFilter']);
    $routes->post('menu', 'Menu::index',['filter' => 'adminFilter']);
    $routes->get('menu/getLock', 'Menu::getLock',['filter' => 'adminFilter']);


    $routes->get('footerMenu', 'FooterMenu::index',['filter' => 'adminFilter']);
    $routes->post('footerMenu', 'FooterMenu::index',['filter' => 'adminFilter']);
    $routes->get('footerMenu/getLock', 'FooterMenu::getLock',['filter' => 'adminFilter']);


    //siteOptions
    $routes->get('siteOptions/(:any)', 'SiteOptions::edit/$1',['filter' => 'adminFilter']);
    $routes->post('siteOptions/(:any)', 'SiteOptions::edit/$1',['filter' => 'adminFilter']);
    $routes->get('siteOptions/getLock/(:any)', 'SiteOptions::getLock/$1',['filter' => 'adminFilter']);

    $routes->get('crons/cleanLocks', 'Crons::cleanLocks');
    $routes->cli('crons/cleanLocks', 'Crons::cleanLocks');


    $routes->group('mediaLibrary',['namespace' => 'App\Controllers\Admin','filter' => 'loggedFilter'], static function ($routes) {
        $routes->get('/', 'MediaLibrary::index');
        $routes->post('getPaginatedList', 'MediaLibrary::getPaginatedList');
        $routes->post('update/(:num)', 'MediaLibrary::update/$1');
        $routes->get('getLock/(:num)', 'MediaLibrary::getLock/$1');
        $routes->get('delete/(:num)', 'MediaLibrary::delete/$1');
    });

    $routes->group('faqCategories',['namespace' => 'App\Controllers\Admin','filter' => 'loggedFilter'], static function ($routes) {
            $routes->get('/', 'FaqCategories::index');
            $routes->get('index', 'FaqCategories::index');
            $routes->get('edit/', 'FaqCategories::edit');
            $routes->post('edit/', 'FaqCategories::edit');
            $routes->get('edit/(:num)', 'FaqCategories::edit/$1');
            $routes->post('edit/(:num)', 'FaqCategories::edit/$1');
            $routes->get('edit', 'FaqCategories::edit');
            $routes->post('edit', 'FaqCategories::edit');
            $routes->get('getLock/(:num)', 'FaqCategories::getLock/$1');
            $routes->get('delete/(:num)', 'FaqCategories::delete/$1');
            $routes->get('sortOrder', 'FaqCategories::sortOrder');
            $routes->post('sortOrder', 'FaqCategories::sortOrder');
     });

    // Users
    $routes->group('users',['namespace' => 'App\Controllers\Admin','filter' => 'loggedFilter',], static function ($routes) {
        $routes->get('/', 'Users::index');
        $routes->get('index', 'Users::index');
        $routes->post('getPaginatedList', 'Users::getPaginatedList');
        $routes->get('add', 'Users::add');
        $routes->post('add', 'Users::attemptAdd');
        $routes->get('edit/(:num)', 'Users::edit/$1');
        $routes->post('edit/(:num)', 'Users::attemptEdit/$1');
        $routes->get('activate/(:num)', 'Users::activate/$1');
        $routes->get('deactivate/(:num)', 'Users::deactivate/$1');
    });

    // Site Users
    $routes->group('siteUsers',['namespace' => 'App\Controllers\Admin','filter' => 'loggedFilter',], static function ($routes) {
        $routes->get('/','SiteUsers::index');
        $routes->post('getPaginatedList', 'SiteUsers::getPaginatedList');
        $routes->get('activate/(:num)', 'SiteUsers::activate/$1');
        $routes->get('deactivate/(:num)', 'SiteUsers::deactivate/$1');
        $routes->get('makeSimple/(:num)', 'SiteUsers::makeSimple/$1');
        $routes->get('exportResults','SiteUsers::exportResults');
    });


    $routes->group('articles',['namespace' => 'App\Controllers\Admin','filter' => 'loggedFilter',], static function ($routes) {
        $routes->get('/', 'Articles::index');
        $routes->get('index', 'Articles::index');
        $routes->post('getPaginatedList', 'Articles::getPaginatedList');
        $routes->get('edit/', 'Articles::edit');
        $routes->post('edit/', 'Articles::edit');
        $routes->get('edit/(:num)', 'Articles::edit/$1');
        $routes->post('edit/(:num)', 'Articles::edit/$1');
        $routes->get('edit', 'Articles::edit');
        $routes->post('edit', 'Articles::edit');
        $routes->get('publish/(:num)', 'Articles::publish/$1');
        $routes->get('unpublish/(:num)', 'Articles::unpublish/$1');
        $routes->get('getLock/(:num)', 'Articles::getLock/$1');
        $routes->get('delete/(:num)', 'Articles::delete/$1');
        $routes->get('unschedule/(:num)', 'Articles::unschedule/$1');
        $routes->post('schedule/(:num)', 'Articles::schedule/$1');
    });

    $routes->group('articleCategories',['namespace' => 'App\Controllers\Admin','filter' => 'loggedFilter',], static function ($routes) {
        $routes->get('/', 'ArticleCategories::index');
        $routes->get('index', 'ArticleCategories::index');
        $routes->post('getPaginatedList', 'ArticleCategories::getPaginatedList');
        $routes->get('edit/', 'ArticleCategories::edit');
        $routes->post('edit/', 'ArticleCategories::edit');
        $routes->get('edit/(:num)', 'ArticleCategories::edit/$1');
        $routes->post('edit/(:num)', 'ArticleCategories::edit/$1');
        $routes->get('publish/(:num)', 'ArticleCategories::publish/$1');
        $routes->get('unpublish/(:num)', 'ArticleCategories::unpublish/$1');
        $routes->get('edit', 'ArticleCategories::edit');
        $routes->post('edit', 'ArticleCategories::edit');
        $routes->get('getLock/(:num)', 'ArticleCategories::getLock/$1');
    });


    //ajaxData
    $routes->group('ajaxData',['namespace' => 'App\Controllers\Admin','filter' => 'loggedFilter',], static function ($routes) {
        $routes->post('editLock', 'AjaxData::editLock');
        $routes->get('(:any)', 'AjaxData::$1');
        $routes->post('(:any)', 'AjaxData::$1');
        $routes->get('(:any)/(:any)', 'AjaxData::$1/$2');
        $routes->post('(:any)/(:any)', 'AjaxData::$1/$2');
        $routes->get('(:any)/(:any)/(:any)', 'AjaxData::$1/$2/$3');
        $routes->post('(:any)/(:any)/(:any)', 'AjaxData::$1/$2/$3');
    });

    $routes->group('multiUseContents',['namespace' => 'App\Controllers\Admin','filter' => 'loggedFilter',], static function ($routes) {
        $routes->get('/', '::index');
        $routes->get('index', '::index');
        $routes->post('getPaginatedList', '::getPaginatedList');
        $routes->get('edit/', '::edit');
        $routes->post('edit/', '::edit');
        $routes->get('edit/(:num)', '::edit/$1');
        $routes->post('edit/(:num)', '::edit/$1');
        $routes->get('edit', '::edit');
        $routes->post('edit', '::edit');
        $routes->get('duplicate/(:num)', '::duplicate/$1');
        $routes->get('delete/(:num)', '::delete/$1');
        $routes->get('getLock/(:num)', '::getLock/$1');
    });

    //pages
    $routes->group('pages',['namespace' => 'App\Controllers\Admin','filter' => 'loggedFilter'], static function ($routes) {
        $routes->get('/', 'Pages::index');
        $routes->get('index', 'Pages::index');
        $routes->post('getPaginatedList', 'Pages::getPaginatedList');
        $routes->get('publish/(:num)', 'Pages::publish/$1');
        $routes->get('unpublish/(:num)', 'Pages::unpublish/$1');
        $routes->get('edit/', 'Pages::edit');
        $routes->post('edit/', 'Pages::edit');
        $routes->get('edit/(:num)', 'Pages::edit/$1');
        $routes->post('edit/(:num)', 'Pages::edit/$1');
        $routes->get('edit', 'Pages::edit');
        $routes->post('edit', 'Pages::edit');
        $routes->get('getLock/(:num)', 'Pages::getLock/$1');
        $routes->get('delete/(:num)', 'Pages::delete/$1');
    });


    $routes->group('tokenInputSearch',['namespace' => 'App\Controllers\Admin','filter' => 'loggedFilter'], static function ($routes) {
        $routes->get('searchArticles', 'TokenInputSearch::searchArticles');
        $routes->get('searchArticles/(:any)', 'TokenInputSearch::searchArticles/$1');
        $routes->get('searchArticleCategories', 'TokenInputSearch::searchArticleCategories');
        $routes->get('searchPolls', 'TokenInputSearch::searchPolls');
    });

});


//ajaxData
$routes->group('ajaxFunctions',['namespace' => 'App\Controllers'], static function ($routes) {
    $routes->post('(:any)/(:any)', 'AjaxContent::$1/$2');
    $routes->post('(:any)/(:any)/(:any)', 'AjaxContent::$1/$2/$3');
    $routes->post('(:any)', 'AjaxContent::$1');

    $routes->get('(:any)/(:any)/(:any)', 'AjaxContent::$1/$2/$3');
    $routes->get('(:any)', 'AjaxContent::$1');
    $routes->get('(:any)/(:any)', 'AjaxContent::$1/$2');
});


$routes->get('/', 'Home::index');

$routes->get('images/(:any)/(:any)', 'MediaServer::index/$1/$2');

$routes->get('cache/rebuild/(:any)', 'Cache::rebuild/$1');

$routes->get('articleCategoryFeed/(:num)/(:num)', 'ContentFeed::articleCategoryFeed/$1/$2');

$routes->post('sendEmail', 'Contact::sendEmail');

$routes->get('preview/(:any)/(:num)/(:any)', 'Preview::$1/$2/$3');
$routes->post('preview/(:any)/(:num)/(:any)', 'Preview::$1/$2/$3');

$routes->get('(:any)/page/(:num)', 'Page::index/$1/$2');

$routes->get('(:any)/', 'Page::index/$1');
$routes->get('(:any)', 'Page::index/$1');


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
