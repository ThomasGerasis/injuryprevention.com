<?php

namespace App\Controllers;

class Preview extends BaseController
{
    
    public function previewPage($id, $hash)
    {
        if(!empty($_POST['title'])){
			$pageData = $_POST;
            $pageData['faqs'] = array();
            foreach ($pageData['faq'] as $faq) {
                $pageData['faqs'][] = $faq;
            }
		}else{
            $dbModel = model(Page::class);
            $pageData = $dbModel->find($id);
            if(!empty($pageData['published'])){
				return redirect()->to($pageData['permalink']);
			}
            $pageFaqModel = model(PageFaq::class);
            $pageData['faqs'] = $pageFaqModel->where('page_id', $id)->orderBy('order_num ASC')->findAll();
		}

        if($hash != md5($id.'_SOMALAB_'.$pageData['permalink'])){
			log_message('error','_SOMALAB_ preview');
			return view('errors/html/error_404', ['message', "Preview page not found"]);
		}

        if(!empty($pageData['opener_image_id'])){
            $pageData['openerImage'] = $this->cacheHandler->getImage($pageData['opener_image_id']);
        }

        $data['headerMeta'] = array();
        $data['headerMeta'][] = '<meta content="noindex, nofollow" name="robots">';
        
        $data['pageData'] = $pageData;
        $parsedConted = $this->contentParser->parseContent($pageData['content']);
        $data['pageContent'] = $parsedConted['content'] ?? '';
        $data['loadCss'] = $parsedConted['cssFiles'] ?? [];
        $data['loadJs'] = $parsedConted['jsFiles'] ?? [];

        if(!empty($pageData['faqCategories'])){
            $data['loadCss']['faqs'] = 'faqs';
        }

        $data['cacheHandler'] = $this->cacheHandler;
        $data['contentParser'] = $this->contentParser;

        return view('header', $data) . view('page', $data) . view('footer', $data);
    }

}
