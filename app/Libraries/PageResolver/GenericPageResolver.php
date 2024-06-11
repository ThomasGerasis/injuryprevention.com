<?php

namespace App\Libraries\PageResolver;

final class GenericPageResolver extends AbstractPageResolver
{
    public function resolve(): string
    {
        $data = array();

        $pageData = $this->pageController->cacheHandler->getPage($this->pageId);

        if (!$pageData) {
            return view('errors/html/error_404', ['message', "Page not found"]);
        }

        $data['isFrontpage'] = false;
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
			'url'=>base_url($pageData['permalink']),
			'seoType'=>'article',
			'faqs'=>(!empty($pageData['faqs']) ? $pageData['faqs'] : false),
		);

        
		$data['headerMeta'] = constructPagedata($metaData);
        $data['pageData'] = $pageData;
        $parsedConted = $this->pageController->contentParser->parseContent($pageData['content']);
        $data['pageContent'] = $parsedConted['content'] ?? '';
        $data['loadCss'] = $parsedConted['cssFiles'] ?? [];
        $data['loadJs'] = $parsedConted['jsFiles'] ?? [];
        if(!empty($pageData['faqCategories'])){
            $data['loadCss']['faqs'] = 'faqs';
        }
        $data['pageId'] = $this->pageId;
        $data['cacheHandler'] = $this->pageController->cacheHandler;
        $data['contentParser'] = $this->pageController->contentParser;

        return view('header', $data) . view('page', $data) . view('footer', $data);
    }
}
