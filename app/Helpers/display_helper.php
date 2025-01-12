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
    if (empty($pagedata['seoTitle'])) $pagedata['seoTitle'] = 'Injury Prevention Lab';//todo
    if (empty($pagedata['seoDescription'])) $pagedata['seoDescription'] = 'Injury Prevention Lab';//todo
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

    $headerData[] = '<meta content="injurypreventionlab.com" property="og:site_name">';//todo
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
    $headerData[] = '<meta content="@Injury Prevention Lab" name="twitter:site">';
    $headerData[] = '<meta content="@Injury Prevention Lab" name="twitter:creator">';

    $imageSrc = base_url('assets/img/sample-social.jpg');
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