<?php

namespace App\Libraries\PageResolver;

use App\Models\Article;

final class ArticleCategoryPageResolver extends AbstractPageResolver
{
    public function resolve(): string
    {
        $data = [];

        $pageData = $this->pageController->cacheHandler->getArticleCategory($this->pageId);

        if (!$pageData) {
            return view('errors/html/error_404', ['message' => 'Article Category not found']);
        }

        $totalArticles = $this->pageController->cacheHandler->getCategoryFeedLength($this->pageId);
        $totalPages = ceil($totalArticles / PAGE_LENGTH);
        if ($totalPages && $totalPages < $this->pageNumber) {
            return redirect()->to($pageData['permalink']);
        }

        if ($this->pageNumber > 2) {
            $previousPage = base_url($pageData['permalink']) . '/page/' . ($this->pageNumber - 1);
        } elseif ($this->pageNumber === 2) {
            $previousPage = base_url($pageData['permalink']);
        }

        $data['isFrontpage'] = false;

        $metaData = array(
            'isPage' => true,
            'title' => $pageData['title'],
            'seoTitle' => (empty($pageData['seo_title']) ? $pageData['title'] : $pageData['seo_title']),
            'socialTitle' => (empty($pageData['social_title']) ? '' : $pageData['social_title']),
            'seoDescription' => (empty($pageData['seo_description']) ? '' : $pageData['seo_description']),
            'metaImage' => (empty($pageData['metaImage']) ? false : $pageData['metaImage']),
            'metaImageMimetype' => (empty($pageData['metaImageMimetype']) ? false : $pageData['metaImageMimetype']),
            'dateCreated' => $pageData['date_published'],
            'dateEdited' => $pageData['modified_date'],
            'url' => base_url($pageData['permalink']),
            'seoType' => 'article',
            'faqs' => (!empty($pageData['faqs']) ? $pageData['faqs'] : false),
            'has_pagination' => $totalPages > 1,
            'page' => $this->pageNumber,
            'next_page' => $this->pageNumber < $totalPages ? base_url($pageData['permalink']) . '/page/' . ($this->pageNumber + 1) : false,
            'prev_page' => !empty($previousPage)
        );

        $data['headerMeta'] = constructPagedata($metaData);
        $data['pageData'] = $pageData;
        $data['pageNumber'] = $this->pageNumber;
        $data['totalPages'] = $totalPages;
        $parsedContent = $this->pageController->contentParser->parseContent($pageData['content']);
        $data['pageContent'] = $parsedContent['content'] ?? '';
        $data['loadCss'] = $parsedContent['cssFiles'] ?? [];
        $data['loadCss']['articleRow'] = 'articleRow';
        $data['loadCss']['pagination'] = 'pagination';
        $data['loadJs'] = $parsedContent['jsFiles'] ?? [];
        $data['loadJs']['pagination'] = 'pagination';
        $data['pageId'] = $this->pageId;
        $data['isMobile'] = $this->pageController->isMobile;
        $data['cacheHandler'] = $this->pageController->cacheHandler;
        $data['contentParser'] = $this->pageController->contentParser;
        return view('header', $data) . view('articleCategory', $data) . view('footer', $data);
    }
}
