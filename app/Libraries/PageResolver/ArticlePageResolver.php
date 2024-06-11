<?php

namespace App\Libraries\PageResolver;

use App\Models\Article;

final class ArticlePageResolver extends AbstractPageResolver
{
    public function resolve(): string
    {
        $data = array();
        $pageData = $this->pageController->cacheHandler->getArticle($this->pageId);
        if (!$pageData) {
            return view('errors/html/error_404', ['message', "Article not found"]);
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
        );

        $data['headerMeta'] = constructPagedata($metaData);
        $data['pageData'] = $pageData;
        $parsedConted = $this->pageController->contentParser->parseContent($pageData['content']);
        $data['pageContent'] = $parsedConted['content'] ?? '';
        $data['loadCss'] = $parsedConted['cssFiles'] ?? [];
        $data['loadJs'] = $parsedConted['jsFiles'] ?? [];
        $data['loadCss']['article_slider'] = 'articleSlider';
        $data['loadCss']['customSwiper'] = 'customSwiper';
        $data['loadJs']['customSwiper'] = 'customSwiper';
        $data['pageId'] = $this->pageId;
        $data['isMobile'] = $this->pageController->isMobile;
        $data['cacheHandler'] = $this->pageController->cacheHandler;
        $data['contentParser'] = $this->pageController->contentParser;

        return view('header', $data) . view('article', $data) . view('footer', $data);
    }
}
