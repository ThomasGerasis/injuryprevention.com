<?php

/**
 * Displays error templates
 */
function displayErrors(): void
{
    $session = \Config\Services::session();
    if (null !== $session->getFlashdata('error')) {
        echo view('templates/error.php', ['error' => $session->getFlashdata('error')]);
    }
    if (null !== $session->getFlashdata('success')) {
        echo view('templates/success.php', ['success' => $session->getFlashdata('success')]);
    }
}

function getToken($length = 32)
{
    // Create random token
    $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_-';
    $max = strlen($string) - 1;
    $token = '';
    for ($i = 0; $i < $length; $i++) {
        $token .= $string[mt_rand(0, $max)];
    }
    return $token;
}

function constructPagedata($pagedata = array())
{

    if (ENVIRONMENT === 'production') {
        $robotsContent = empty($pagedata['robots']) ? "index, follow" : $pagedata['robots'];
        if (!empty($pagedata['isPreview'])) {
            $robotsContent = "noindex, nofollow";
        }
    } else {
        $robotsContent = "noindex, nofollow";
    }

    $headerData = array();

    if (empty($pagedata['seoType'])) $pagedata['seoType'] = 'article';
    if (empty($pagedata['seoTitle'])) $pagedata['seoTitle'] = 'Somelab';//todo
    if (empty($pagedata['seoDescription'])) $pagedata['seoDescription'] = 'Somelab default descr';//todo
    $pagedata['seoTitle'] = str_replace('\"', '', $pagedata['seoTitle']);
    $pagedata['seoTitle'] = str_replace('"', '', $pagedata['seoTitle']);
    $pagedata['seoDescription'] = str_replace('\"', '', $pagedata['seoDescription']);
    $pagedata['seoDescription'] = str_replace('"', '', $pagedata['seoDescription']);

    $pagedata['seoDescription'] = str_replace(array("<br/>", "\n", "\r\n", "\r"), " ", $pagedata['seoDescription']);
    if (mb_strlen($pagedata['seoDescription']) > '160') {
        $lastdot = mb_strrpos($pagedata['seoDescription'], '.');
        if ($lastdot !== false) {
            if ($lastdot > '160') {
                $lastdot = mb_strpos($pagedata['seoDescription'], '.', 140);
                if ($lastdot > '160') $lastdot = mb_strpos($pagedata['seoDescription'], ' ', 150) - 1;
            }
            $pagedata['seoDescription'] = mb_substr($pagedata['seoDescription'], 0, ($lastdot + 1));
        }
    }


    $headerData[] = '<title>' . $pagedata['seoTitle'] . '</title>';
    $headerData[] = '<meta content="' . $pagedata['seoDescription'] . '" name="description">';

    $headerData[] = '<meta content="Somelab.com" property="og:site_name">';//todo
    $headerData[] = '<meta content="' . (empty($pagedata['socialTitle']) ? $pagedata['seoTitle'] : $pagedata['socialTitle']) . '" property="og:title">';
    $headerData[] = '<meta content="' . $pagedata['seoDescription'] . '" property="og:description">';
    $headerData[] = '<meta content="' . $pagedata['url'] . '" property="og:url">';
    $headerData[] = '<meta content="el_GR" property="og:locale">';

    $headerData[] = '<meta content="' . $pagedata['url'] . '" name="twitter:url">';
    $headerData[] = '<meta content="' . $pagedata['seoTitle'] . '" name="twitter:title">';
    $headerData[] = '<meta content="' . $pagedata['seoDescription'] . '" name="twitter:description">';


    $headerData[] = '<link rel="canonical" href="' . $pagedata['url'] . '">';
    $headerData[] = '<meta content="1200" property="og:image:width">';
    $headerData[] = '<meta content="628" property="og:image:height">';
    $headerData[] = '<meta content="@Somelab" name="twitter:site">';
    $headerData[] = '<meta content="@Somelab" name="twitter:creator">';

    $imageSrc = base_url('assets/img/Somelab-social.jpg');
    //todo default social image

    $imageMimetype = 'image/jpg';
    if (!empty($pagedata['metaImage'])) {
        $imageSrc = $pagedata['metaImage'];
        $imageMimetype = $pagedata['metaImageMimetype'];
    }
    $headerData[] = '<meta property="og:image" content="' . $imageSrc . '">';
    $headerData[] = '<meta property="og:image:type" content="' . $imageMimetype . '">';
    $headerData[] = '<meta name="twitter:image" content="' . $imageSrc . '">';

    if (!empty($pagedata['has_pagination'])) {
        if (!empty($pagedata['prev_page'])) {
            $headerData[] = '<link rel="prev" href="' . $pagedata['prev_page'] . '">';
        }
        if (!empty($pagedata['next_page'])) {
            $headerData[] = '<link rel="next" href="' . $pagedata['next_page'] . '">';
        }
        if (ENVIRONMENT === 'production') {
            if (empty($pagedata['prev_page'])) {//first page
                $robotsContent = "index, follow";
            } else {
                $robotsContent = "noindex, follow";
            }
        }
    }

    /*if(!empty($pagedata['isFrontpage'])){
        $extra = '';
        if(!empty($pagedata['date_created'])) $extra .= ',"dateCreated":"'.date(DATE_ISO8601, strtotime($pagedata['date_created'])).'"';
        if(!empty($pagedata['date_edited'])) $extra .= ',"dateModified":"'.date(DATE_ISO8601, strtotime($pagedata['date_edited'])).'"';
        if(!empty($pagedata['date_published'])) $extra .= ',"datePublished":"'.date('Y-m-d',strtotime($pagedata['date_published'])).'"';
        $header_data[] = '<script data-schema="WebSite" type="application/ld+json">{"@context":"https://schema.org","@type":"WebSite","@id":"#website","url":"https://www.foxbet.gr","name":"Foxbet.gr"'.$extra.'}</script>';
        $header_data[] = '<script data-schema="WebPage" type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Foxbet.gr Στοίχημα, Αναλύσεις, Προγνωστικά","description":"Προγνωστικά και αναλύσεις για στοίχημα. Live betting, πλήρη στατιστικά - καθημερινή ενημέρωση για τα νέα της αγοράς και τις νόμιμες στοιχηματικές εταιρείες"}</script>';
    }

    if (!empty($pagedata['is_category'])){
        $extra = '';
        if(!empty($pagedata['date_edited'])) $extra .= ',"datePublished":"'.date('Y-m-d',strtotime($pagedata['date_edited'])).'"';
        $header_data[] = '<script data-schema="WebPage" type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"'.$pagedata['seo_title'].'","description":"'.$pagedata['seo_description'].'"'.$extra.'}</script>';
    }
    if (!empty($pagedata['is_page']) || !empty($pagedata['is_tag'])){
        $header_data[] = '<script data-schema="WebPage" type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"'.$pagedata['seo_title'].'","description":"'.$pagedata['seo_description'].'"}</script>';
    }
    if (!empty($pagedata['is_category']) || !empty($pagedata['is_tag'])){
        if(!empty($pagedata['has_pagination'])){
            if(!empty($pagedata['prev_page'])){
                $header_data[] = '<link rel="prev" href="'.$pagedata['prev_page'].'">';
            }
            if(!empty($pagedata['next_page'])){
                $header_data[] = '<link rel="next" href="'.$pagedata['next_page'].'">';
            }
            if (ENVIRONMENT == 'production'){
                if(empty($pagedata['prev_page'])){//first page
                    $robots_content = "index, follow";
                }else{
                    $robots_content = "noindex, follow";
                }
            }
        }
    }

    if(!empty($pagedata['is_article']) || !empty($pagedata['is_analysi'])){
        $header_data[] = '<!-- Additional OG:meta -->';
        $header_data[] = '<meta content="article" property="og:type">';
        $header_data[] = '<meta property="article:publisher" content="https://www.facebook.com/Foxbet.gr"/>';
        $header_data[] = '<meta property="article:section" content="'.$pagedata['article_category_title'].'"/>';
        $header_data[] = '<meta property="og:updated_time" content="'.date(DATE_ISO8601, strtotime($pagedata['date_created'])).'"/>';
        $header_data[] = '<meta property="article:published_time" content="'.date(DATE_ISO8601, strtotime($pagedata['date_created'])).'"/>';
        $header_data[] = '<meta property="article:modified_time" content="'.date(DATE_ISO8601, strtotime($pagedata['date_edited'])).'"/>';
        $header_data[] = '<!-- Additional Twitter:meta -->';
        $header_data[] = '<meta content="summary_large_image" name="twitter:card">';

        $news_keywords = array();
        $news_keywords[] = $pagedata['article_category_title'];
        if (!empty($pagedata['keys']) && count($pagedata['keys'])){
            foreach($pagedata['keys'] as $key){
                $news_keywords[] = $key;
            }
        }
        if (!empty($pagedata['tags']) && count($pagedata['tags'])){
            foreach($pagedata['tags'] as $tag){
                $header_data[] = '<meta property="article:tag" content="'.$tag['title'].'">';
                $news_keywords[] = $tag['title'];
            }
        }

        if (!empty($news_keywords)){
            $header_data[] = '<meta name="news_keywords" content="'.implode(', ',$news_keywords).'"/>';
        }

        $headline = $pagedata['seo_title'];
        if (mb_strlen($headline)>'110'){
            $lastspace = mb_strpos($headline, ' ', 100);
            if ($lastspace!==false){
                if ($lastspace>'110'){
                    $lastspace = mb_strpos($headline, ' ', 90);
                }
                $headline = mb_substr($headline, 0, $lastspace);
            }
        }
        $header_data_articleBody = json_encode($pagedata['body'], JSON_UNESCAPED_UNICODE);
        if (empty($header_data_articleBody)) { $header_data_articleBody = '""';}

        $header_data[] = '<script data-schema="NewsArticle" type="application/ld+json">{"@context":"https://schema.org","@type":"NewsArticle","description":'.json_encode($pagedata['seo_description'], JSON_UNESCAPED_UNICODE).',"image":"'.$image_src.'","mainEntityOfPage":{"@type":"WebPage","url":"'.str_replace('amp/', '', $pagedata['url']).'"},"url":"'.$pagedata['url'].'","alternativeHeadline":'.json_encode($pagedata['seo_title'], JSON_UNESCAPED_UNICODE).',"author":{"@type":"Organization","name":"Foxbet.gr","url":"https://www.foxbet.gr","logo":{"@type":"ImageObject","url":"'.assets_url('img/logo_lg.png').'","height":70,"width":170}, "sameAs":["http://www.facebook.com/Foxbet.gr","http://twitter.com/foxbet_gr","https://www.instagram.com/foxbet.gr/"]},"dateCreated":"'.date(DATE_ISO8601, strtotime($pagedata['date_created'])).'","dateModified":"'.date(DATE_ISO8601, strtotime($pagedata['date_edited'])).'","datePublished":"'.date('Y-m-d',strtotime($pagedata['date_created'])).'","genre":"'.$pagedata['article_category_title'].'","headline":'.json_encode($headline, JSON_UNESCAPED_UNICODE).',"keywords":'.json_encode(implode(', ',$news_keywords), JSON_UNESCAPED_UNICODE).',"publisher":{"@type":"Organization","name":"Foxbet.gr","url":"https://www.foxbet.gr","logo":{"@type":"ImageObject","url":"'.assets_url('img/logo_lg.png').'","height":70,"width":170}, "sameAs":["http://www.facebook.com/Foxbet.gr","http://twitter.com/foxbet_gr","https://www.instagram.com/foxbet.gr/"]},"articleBody":'.$header_data_articleBody.'}</script>';

    }

    if(!empty($pagedata['is_review'])){
        $logo_src = $image_src;
        if(!empty($pagedata['logo'])){
            if(!empty($pagedata['igaming_logo'])){
                $logo_src = igaming_image_url($pagedata['logo'],'rct300');
            }else{
                $logo_src = image_url($pagedata['logo'],'rct300');
            }
        }
        $review_body = '""';
        if(!empty($pagedata['review_body'])) $review_body = json_encode($pagedata['review_body'], JSON_UNESCAPED_UNICODE);
        $header_data[] = '<script data-schema="Review" type="application/ld+json">{"@context":"https://schema.org","@type":"Review","image":"'.$image_src.'","mainEntityOfPage":{"@type":"WebPage","url":"'.$pagedata['url'].'"},"url":"'.$pagedata['url'].'",
        "itemReviewed":{"@type":"EntertainmentBusiness","name":"'.$pagedata['title'].'","image":"'.$image_src.'","logo":"'.$logo_src.'"},"reviewRating":{"@type":"Rating","bestRating":"100","ratingValue":"'.round($pagedata['ratingValue'],2).'","worstRating":"0"},"author":{"@type":"Organization","name":"foxbet.gr","url":"https://www.foxbet.gr","logo":{"@type":"ImageObject","url":"'.assets_url('img/logo_lg.png').'","height":70,"width":170}},"dateCreated":"'.date(DATE_ISO8601, strtotime($pagedata['date_created'])).'","dateModified":"'.date(DATE_ISO8601, strtotime($pagedata['date_edited'])).'","keywords":"","publisher":{"@type":"Organization","name":"foxbet.gr","url":"https://www.foxbet.gr","logo":{"@type":"ImageObject","url":"'.assets_url('img/logo_lg.png').'","height":70,"width":170}},"reviewBody":'.$review_body.'}</script>';
    }
    */

    if (!empty($pagedata['faqs'])) {
        $faqs = array();
        foreach ($pagedata['faqs'] as $faq) {
            if (empty($faq['question']) || empty($faq['answer'])) {
                continue;
            }
            $faqs[] = '{"@type": "Question","name": ' . json_encode($faq['question'], JSON_UNESCAPED_UNICODE) . ',
                    "acceptedAnswer": {"@type": "Answer","text": ' . json_encode($faq['answer'], JSON_UNESCAPED_UNICODE) . '}}';
        }
        if (count($faqs)) {
            $faq_schema = '<script type="application/ld+json">{"@context": "https://schema.org","@type": "FAQPage","mainEntity": [';
            $faq_schema .= implode(',', $faqs);
            $faq_schema .= ']}</script>';
            $headerData[] = $faq_schema;
        }
    }
    $headerData[] = '<meta content="' . $robotsContent . '" name="robots">';

    return $headerData;
}

function getSubString($string = NULL, $length = 15) {
	if (empty($string)) return '';
	$newString = strip_tags($string);
	if(mb_strlen($newString) < $length ) return $string;
	$finalString = mb_substr($newString, 0, $length, 'UTF-8').(mb_strlen($newString) <= $length?'':'.');
	return $finalString;
}