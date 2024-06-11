<?php

namespace App\Libraries;


class CacheHandler
{
    private \CodeIgniter\Cache\CacheInterface $cache;
    private int $cache_ttl = 90000; //25 hours;

    private int $cacheStatsTTl = 604800; //7 days;



    public function __construct()
    {
        $this->cache = \Config\Services::cache();
    }

    public function imagePortalUrl($imageData, $folder)
    {
        return base_url('images/p/' . $folder . '/' . $imageData['file_name']);
    }

    function getPermalink($slug)
    {
        $permalinks = $this->getPermalinks();
        return (isset($permalinks[$slug]) ? $permalinks[$slug] : false);
    }

    public function getPermalinks($refreshCache = false)
    {
        $cache_item_name = "permalinks";
        $cached_response = false;
        if (!$refreshCache) {
            $cached_response = $this->cache->get($cache_item_name);
        }
        if (!$cached_response) {
            $dbModel = model(Permalink::class);
            $permalinks = $dbModel->findAll();
            $cached_response = array();
            foreach ($permalinks as $permalink) {
                $cached_response[$permalink['permalink']] = $permalink;
            }
            $this->cache->save($cache_item_name, $cached_response, $this->cache_ttl);
        }
        return $cached_response;
    }


    private function my_email_crypt($string, $action = 'e')
    {
        // you may change these values to your own
        $secret_key = 'my_email_crypt_UkGZXahjBA3JfsaoLP5ZbyEk0XcYKzj3';
        $secret_iv = 'my_email_secret_lku9VFKziq';
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $string = strtolower($string);
        if ($action === 'e') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } elseif ($action === 'd') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }


    public function getFixtures($refreshCache = false)
    {
        $cache_item_name = "all_fixtures";
        $cached_response = false;
        if (!$refreshCache) {
            $cached_response = $this->cache->get($cache_item_name);
        }
        if (!$cached_response) {
           $allFixtures = file_get_contents('assets/stats.json');
           $cached_response =  !empty($allFixtures) ? json_decode($allFixtures,true) : [];
           $this->cache->save($cache_item_name, $cached_response, $this->cacheStatsTTl);
        }
        return $cached_response;
    }

    public function getFixtureByDate($date, $refreshCache = false)
    {
        $cache_item_name = "fixture_".$date;
        $cached_response = false;
        if (!$refreshCache) {
            $cached_response = $this->cache->get($cache_item_name);
        }
        if (!$cached_response) {
            $fixtures = $this->getFixtures();
            $cached_response =  $fixtures[$date][0] ?? [];
            $this->cache->save($cache_item_name, $cached_response, $this->cacheStatsTTl);
        }
        return $cached_response;
    }


    public function getFixturePlayers($date, $refreshCache = false)
    {
        $cache_item_name = "fixture_".$date."_players";
        $cached_response = false;
        if (!$refreshCache) {
            $cached_response = $this->cache->get($cache_item_name);
        }
        if (!$cached_response) {
            $fixture = $this->getFixtureByDate($date);
            $cached_response =  $fixture['Players'] ?? [];
            $this->cache->save($cache_item_name, $cached_response, $this->cacheStatsTTl);
        }
        return $cached_response;
    }


    public function getAllPlayers($refreshCache = false)
    {
        $cache_item_name = "players";
        $cached_response = false;
        if (!$refreshCache) {
            $cached_response = $this->cache->get($cache_item_name);
        }
        if (!$cached_response) {
            $players = file_get_contents('assets/players.json');
            $cached_response = !empty($players) ? json_decode($players,true) : [];
            $this->cache->save($cache_item_name, $cached_response, $this->cacheStatsTTl);
        }
        return $cached_response;
    }

    public function getFixturePlayerDetails($player, $refreshCache = false)
    {
        $cache_item_name = "player_".$player."_details";
        $cached_response = false;
        if (!$refreshCache) {
            $cached_response = $this->cache->get($cache_item_name);
        }
        if (!$cached_response) {
            $players = $this->getAllPlayers();
            $cached_response =  $players[$player][0] ?? [];
            $this->cache->save($cache_item_name, $cached_response, $this->cacheStatsTTl);
        }
        return $cached_response;
    }


    public function rebuild(array $cacheRow)
    {
        switch ($cacheRow['type']) {
            case 'permalinks':
                $this->getPermalinks(true);
                break;
            case 'page':
                if ($cacheRow['action'] == 'unpublish') {
                    $this->deletePage($cacheRow['type_id']);
                } else {
                    $this->getPage($cacheRow['type_id'], true);
                }
                break;
            case 'faqCategories':
                $this->getFaqCategories(true);
                break;
            case 'option':
                $this->getOption($cacheRow['type_id'], true);
                break;
            case 'image':
                if ($cacheRow['action'] === 'delete') {
                    $this->deleteImage($cacheRow['type_id']);
                } else {
                    $this->getImage($cacheRow['type_id'], true);
                }
                break;
            case 'menuItems':
                $this->getMenuItems(true);
                break;
                /*case 'footerMenus':
                $this->getFooterMenus(true);
                break;*/
            case 'footerMenuItems':
                $this->getFooterMenuItems(true);
                break;
            case 'homepage':
                $this->getHomePage(true);
                break;
            default:
                break;
        }
    }

    public function deleteImage($id)
    {
        $cache_item_name = "image_$id";
        $this->cache->save($cache_item_name, 'deleted', $this->cache_ttl);
        return true;
    }

    public function getImage($id, $refreshCache = false)
    {
        $cache_item_name = "image_$id";
        $cached_response = false;
        if (!$refreshCache) {
            $cached_response = $this->cache->get($cache_item_name);
        }
        if (!$cached_response) {
            $dbModel = model(Image::class);
            $cached_response = $dbModel->find($id);
            if (empty($cached_response)) $cached_response = 'deleted';
            $this->cache->save($cache_item_name, $cached_response, $this->cache_ttl);
        }
        return ($cached_response == 'deleted' ? false : $cached_response);
    }

    public function getImageFallbackUrl(): string
    {
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAIAAAABCAQAAABeK7cBAAAAC0lEQVR42mNkAAIAAAoAAv/lxKUAAAAASUVORK5CYII=';
    }

    public function imageUrl($image_id, $folder)
    {
        $imageData = $this->getImage($image_id);
        if (empty($imageData['id']) || empty($imageData['file_name'])) return $this->getImageFallbackUrl();
        return base_url('images/' . $folder . '/' . $imageData['file_name']);
    }

    public function deletePage($id)
    {
        $cache_item_name = "page_$id";
        $cached_response = $this->cache->get($cache_item_name);
        if ($cached_response) {
            $this->cache->delete($cache_item_name);
        }
        return true;
    }

    public function getPage($id, $refreshCache = false)
    {
        $cache_item_name = "page_$id";
        $cached_response = false;
        if (!$refreshCache) {
            $cached_response = $this->cache->get($cache_item_name);
        }

        if (!$cached_response) {
            $dbModel = model(Page::class);
            $cached_response = $dbModel->find($id);

            if ($cached_response === null) {
                return false;
            }

            $pageFaqModel = model(PageFaq::class);
            $cached_response['faqs'] = $pageFaqModel->where('page_id', $id)->orderBy('order_num ASC')->findAll();
            $this->cache->save($cache_item_name, $cached_response, $this->cache_ttl);
        }

        if (empty($cached_response['published'])) return false;

        if (!empty($cached_response['social_image_id'])) {
            $cached_response['socialImage'] = $this->getImage($cached_response['social_image_id']);
            if (!empty($cached_response['socialImage'])) {
                $cached_response['metaImage'] = $this->imageUrl($cached_response['social_image_id'], 'social');
                $cached_response['metaImageMimetype'] = $cached_response['socialImage']['mimetype'];
            }
        }
        if (!empty($cached_response['opener_image_id'])) {
            $cached_response['openerImage'] = $this->getImage($cached_response['opener_image_id']);
            if (empty($cached_response['metaImage']) && !empty($cached_response['openerImage'])) {
                $cached_response['metaImage'] = $this->imageUrl($cached_response['opener_image_id'], 'social');
                $cached_response['metaImageMimetype'] = $cached_response['openerImage']['mimetype'];
            }
        }

        return $cached_response;
    }

    public function getFaqCategories($refreshCache = false)
    {
        $cache_item_name = "faqCategories";
        $cached_response = false;
        if (!$refreshCache) {
            $cached_response = $this->cache->get($cache_item_name);
        }
        if (!$cached_response) {
            $cached_response = array();
            $dbModel = model(FaqCategory::class);
            $faqs = $dbModel->orderBy('order_num ASC')->findAll();
            foreach ($faqs as $faq) {
                $cached_response[$faq['id']] = $faq['title'];
            }
            $this->cache->save($cache_item_name, $cached_response, $this->cache_ttl);
        }
        return $cached_response;
    }


    public function unpublishArticle($id)
    {
        $cache_item_name = "article_$id";
        $cached_response = $this->cache->get($cache_item_name);
        if ($cached_response) {
            $this->cache->delete($cache_item_name);
        }
        return true;
    }

    public function publishArticle($id)
    {
        $articleData = $this->getArticle($id, true);
        if (!empty($articleData['article_category_id'])) {
            $this->getCategoryFeedLength($articleData['article_category_id'], true);
            $this->getCategoryFeed(1, $articleData['article_category_id'], true);
            $this->getGenenalCategoryFeedLength(true);
            $this->getGenenalCategoryFeed(1, true);
        }
    }

    public function getArticle($id, $refreshCache = false)
    {
        $cache_item_name = "article_$id";
        $cached_response = false;
        if (!$refreshCache) {
            $cached_response = $this->cache->get($cache_item_name);
        }
        if (!$cached_response) {
            $dbModel = model(Article::class);
            $cached_response = $dbModel->find($id);
            if ($cached_response === null) {
                return false;
            }
            $this->cache->save($cache_item_name, $cached_response, $this->cache_ttl);
        }

        if (empty($cached_response['published'])) return false;

        if (!empty($cached_response['social_image_id'])) {
            $cached_response['socialImage'] = $this->getImage($cached_response['social_image_id']);
            if (!empty($cached_response['socialImage'])) {
                $cached_response['metaImage'] = $this->imageUrl($cached_response['social_image_id'], 'social');
                $cached_response['metaImageMimetype'] = $cached_response['socialImage']['mimetype'];
            }
        }
        if (!empty($cached_response['opener_image_id'])) {
            $cached_response['openerImage'] = $this->getImage($cached_response['opener_image_id']);
            if (empty($cached_response['metaImage']) && !empty($cached_response['openerImage'])) {
                $cached_response['metaImage'] = $this->imageUrl($cached_response['opener_image_id'], 'social');
                $cached_response['metaImageMimetype'] = $cached_response['openerImage']['mimetype'];
            }
        }
        return $cached_response;
    }

    function getCategoryFeedLength($id, $refreshCache = false)
    {
        $cache_item_name = "category_feed_length_$id";
        $cached_response = false;
        if (!$refreshCache) {
            $cached_response = $this->cache->get($cache_item_name);
        }
        if (!$cached_response) {
            $dbModel = model(ArticleCategory::class);
            $cached_response = $dbModel->getFeedLength($id);
            $this->cache->save($cache_item_name, $cached_response, $this->cache_ttl);
        }
        return $cached_response;
    }

    function getCategoryFeed($page = 1, $id, $refreshCache = false)
    {
        $cache_item_name = "category_feed_" . $id . "_p_" . $page;
        $cached_response = false;
        if (!$refreshCache) {
            $cached_response = $this->cache->get($cache_item_name);
        }
        if (!$cached_response) {
            $dbModel = model(ArticleCategory::class);
            $cached_response = $dbModel->getFeedPage($page, $id);
            $this->cache->save($cache_item_name, $cached_response, $this->cache_ttl);
            if ($refreshCache && $page == 1) {
                $totalPages = ceil($this->getCategoryFeedLength($id) / PAGE_LENGTH);
                switch ($totalPages) {
                    case 1:
                        break;
                    case 2:
                        $this->getCategoryFeed(2, $id, true);
                        break;
                    case 3:
                        $this->getCategoryFeed(2, $id, true);
                        $this->getCategoryFeed(3, $id, true);
                        break;
                    default:
                        $this->getCategoryFeed(2, $id, true);
                        $this->getCategoryFeed(3, $id, true);
                        for ($i = 4; $i <= $totalPages; $i++) {
                            $this->cache->delete("category_feed_" . $id . "_p_" . $i);
                        }
                        break;
                }
            }
        }
        return $cached_response;
    }

    function getGenenalCategoryFeedLength($refreshCache = false)
    {
        $cache_item_name = "general_category_feed_length";
        $cached_response = false;
        if (!$refreshCache) {
            $cached_response = $this->cache->get($cache_item_name);
        }
        if (!$cached_response) {
            $dbModel = model(ArticleCategory::class);
            $cached_response = $dbModel->getFeedLength();
            $this->cache->save($cache_item_name, $cached_response, $this->cache_ttl);
        }
        return $cached_response;
    }

    function getGenenalCategoryFeed($page = 1, $refreshCache = false)
    {
        $cache_item_name = "general_category_feed_p_" . $page;
        $cached_response = false;
        if (!$refreshCache) {
            $cached_response = $this->cache->get($cache_item_name);
        }
        if (!$cached_response) {
            $dbModel = model(ArticleCategory::class);
            $cached_response = $dbModel->getFeedPage($page);
            $this->cache->save($cache_item_name, $cached_response, $this->cache_ttl);
            if ($refreshCache && $page == 1) {
                $totalPages = ceil($this->getGenenalCategoryFeedLength() / PAGE_LENGTH);
                switch ($totalPages) {
                    case 1:
                        break;
                    case 2:
                        $this->getGenenalCategoryFeed(2, true);
                        break;
                    case 3:
                        $this->getGenenalCategoryFeed(2, true);
                        $this->getGenenalCategoryFeed(3, true);
                        break;
                    default:
                        $this->getGenenalCategoryFeed(2, true);
                        $this->getGenenalCategoryFeed(3, true);
                        for ($i = 4; $i <= $totalPages; $i++) {
                            $this->cache->delete("general_category_feed_p_" . $i);
                        }
                        break;
                }
            }
        }
        return $cached_response;
    }

    public function unpublishArticleCategory($id)
    {
        $cache_item_name = "articleCategory_$id";
        $cached_response = $this->cache->get($cache_item_name);
        if ($cached_response) {
            $this->cache->delete($cache_item_name);
        }
        return true;
    }

    public function getArticleCategory($id, $refreshCache = false)
    {
        $cache_item_name = "articlesCategory__$id";
        $cached_response = false;
        if (!$refreshCache) {
            $cached_response = $this->cache->get($cache_item_name);
        }
        if (!$cached_response) {
            $dbModel = model(ArticleCategory::class);
            $cached_response = $dbModel->find($id);

            if ($cached_response === null) {
                return false;
            }
            $this->cache->save($cache_item_name, $cached_response, $this->cache_ttl);
        }

        if (empty($cached_response['published'])) return false;

        if (!empty($cached_response['social_image_id'])) {
            $cached_response['socialImage'] = $this->getImage($cached_response['social_image_id']);
            if (!empty($cached_response['socialImage'])) {
                $cached_response['metaImage'] = $this->imageUrl($cached_response['social_image_id'], 'social');
                $cached_response['metaImageMimetype'] = $cached_response['socialImage']['mimetype'];
            }
        }
        if (!empty($cached_response['opener_image_id'])) {
            $cached_response['openerImage'] = $this->getImage($cached_response['opener_image_id']);
            if (empty($cached_response['metaImage']) && !empty($cached_response['openerImage'])) {
                $cached_response['metaImage'] = $this->imageUrl($cached_response['opener_image_id'], 'social');
                $cached_response['metaImageMimetype'] = $cached_response['openerImage']['mimetype'];
            }
        }
        return $cached_response;
    }

    function getArticleCategories($refreshCache = false)
    {
        $cache_item_name = "categories";
        $cached_response = false;
        if (!$refreshCache) {
            $cached_response = $this->cache->get($cache_item_name);
        }
        if (!$cached_response) {
            $dbModel = model(ArticleCategory::class);
            //todo add order to category
            $categories = $dbModel->where('published', '1')->orderBy('id ASC')->findAll();
            $cached_response = array();
            foreach ($categories as $category) {
                $cached_response[$category['id']] = $category['title'];
            }
            $this->cache->save($cache_item_name, $cached_response, $this->cache_ttl);
        }
        return $cached_response;
    }


    public function getOption($name, $refreshCache = false)
    {
        $cache_item_name = "option_$name";
        $cached_response = false;
        if (!$refreshCache) {
            $cached_response = $this->cache->get($cache_item_name);
        }
        if (!$cached_response) {
            $dbModel = model(Option::class);
            $option = $dbModel->where('name', $name)->first();
            if ($option === null) {
                return false;
            }
            $cached_response = (empty($option['value']) ? array() : json_decode($option['value'], true));
            $this->cache->save($cache_item_name, $cached_response, $this->cache_ttl);
        }
        return $cached_response;
    }

    public function getMenuItems($refreshCache = false)
    {
        $cache_item_name = "menuItems";
        $cached_response = false;
        if (!$refreshCache) {
            $cached_response = $this->cache->get($cache_item_name);
        }
        if (!$cached_response) {
            $dbModel = model(MenuItem::class);
            $cached_response = $dbModel->orderBy('order_num', 'asc')->findAll();
            $this->cache->save($cache_item_name, $cached_response, $this->cache_ttl);
        }
        return $cached_response;
    }

    public function getFooterMenuItems($refreshCache = false)
    {
        $cache_item_name = "footerMenuItemsS";
        $cached_response = false;
        if (!$refreshCache) {
            $cached_response = $this->cache->get($cache_item_name);
        }

        if (!$cached_response) {
            $menuModel = model(FooterMenu::class);
            $itemModel = model(FooterMenuItem::class);
            $menus = $menuModel->orderBy('order_num', 'asc')->findAll();
            $cached_response = array();
            foreach ($menus as $menu) {
                $menu['itemList'] = $itemModel->where('footer_menu_id', $menu['id'])->orderBy('order_num', 'asc')->findAll();
                $cached_response[] = $menu;
            }
            $this->cache->save($cache_item_name, $cached_response, $this->cache_ttl);
        }
        return $cached_response;
    }

    public function getHomePage($refreshCache = false)
    {
        $cache_item_name = "homepage";
        $cached_response = false;
        if (!$refreshCache) {
            $cached_response = $this->cache->get($cache_item_name);
        }
        if (!$cached_response) {
            $dbModel = model(HomepageData::class);
            $cached_response = $dbModel->find(1);
            $pageFaqModel = model(PageFaq::class);
            $cached_response['faqs'] = $pageFaqModel->where('page_id', 1)->orderBy('order_num ASC')->findAll();

            $this->cache->save($cache_item_name, $cached_response, $this->cache_ttl);
        }
        if (!empty($cached_response['social_image_id'])) {
            $cached_response['socialImage'] = $this->getImage($cached_response['social_image_id']);
            if (!empty($cached_response['socialImage'])) {
                $cached_response['metaImage'] = $this->imageUrl($cached_response['social_image_id'], 'social');
                $cached_response['metaImageMimetype'] = $cached_response['socialImage']['mimetype'];
            }
        }
        return $cached_response;
    }

}
