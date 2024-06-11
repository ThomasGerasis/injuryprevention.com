<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = array();
        $pageData = $this->cacheHandler->getHomePage(false);
        $data['isFrontpage'] = true;
        $metaData = array(
            'isPage'=>true,
            'title'=>$pageData['title'],
            'seoTitle'=>(empty($pageData['seo_title']) ? $pageData['title'] : $pageData['seo_title']),
            'socialTitle'=>(empty($pageData['social_title'])?'':$pageData['social_title']),
            'seoDescription'=>(empty($pageData['seo_description']) ? '' : $pageData['seo_description']),
            'metaImage'=>(empty($pageData['metaImage'])?false:$pageData['metaImage']),
            'metaImageMimetype'=>(empty($pageData['metaImageMimetype'])?false:$pageData['metaImageMimetype']),
            'dateCreated'=>$pageData['date_published'],
            'dateEdited'=>$pageData['modified_date'],
            'url'=>base_url(),
            'seoType'=>'article',
            'faqs'=>false,
        );

        $data['headerMeta'] = constructPagedata($metaData);
        $data['pageData'] = $pageData;
        $data['matches'] = [];
        $data['players'] = [];
        $parsedConted = $this->contentParser->parseContent($pageData['content']);
        $data['pageContent'] = $parsedConted['content'] ?? '';
        $data['loadCss'] = $parsedConted['cssFiles'] ?? array();
        if(!empty($pageData['faqs'])){
            $data['loadCss']['faqs'] = 'faqs';
        }
        $data['loadJs'] = $parsedConted['jsFiles'] ?? array();
        $data['loadJs']['timeline'] = 'timeline';
        $data['pageId'] = 1;
        $data['cacheHandler'] = $this->cacheHandler;

        return view('header', $data) . view('home', $data) . view('footer', $data);
    }
}