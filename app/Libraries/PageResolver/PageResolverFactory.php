<?php

namespace App\Libraries\PageResolver;

use App\Controllers\Page;
use App\Libraries\Exceptions\PageResolverNotFoundException;

class PageResolverFactory
{
    public static function make(string $id, Page $pageController, string $pageId, int $pageNumber): AbstractPageResolver
    {
        switch ($id) {
            case 'page';
                return new GenericPageResolver($pageController, $pageId, $pageNumber);
            case 'articleCategory':
                return new ArticleCategoryPageResolver($pageController, $pageId, $pageNumber);
            case 'article':
                return new ArticlePageResolver($pageController, $pageId, $pageNumber);
            default:
                throw new PageResolverNotFoundException("unsupported page type");
        }
    }
}
