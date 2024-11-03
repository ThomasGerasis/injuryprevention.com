<?php
use App\Models\FaqCategory;
function rebuildCache($type, $type_id, $action = 'update')
{
	$hash = bin2hex(random_bytes(22));
	$model = new \App\Models\RebuildCache();
	$data = array(
		'date' => date('Y-m-d H:i:s'),
		'hash' => $hash,
		'type' => $type,
		'type_id' => $type_id,
		'action' => $action
	);
	$model->insert($data);
	$client = \Config\Services::curlrequest();
	try {
        $client->request('GET', FRONT_SITE_URL . "cache/rebuild/$hash", ['timeout' => 10]);
	}
	catch(\Exception $e) {
		log_message('error','Curl error: ' .$e->getMessage());
	}
	return true;
}

function getShortcodes(){
	$shortcodesLib = new \App\Libraries\Shortcodes();
	return $shortcodesLib->getShortcodes();
}

function getTimeLength($dateTo, $dateFrom){
	$timeDiff = strtotime($dateTo) - strtotime($dateFrom);
	$hours = intval($timeDiff / 3600);
	$timeDiff = $timeDiff - $hours * 3600;
	$minutes = intval($timeDiff / 60);
	$seconds = $timeDiff - $minutes * 60;
	return ($hours < 10 ? '0' : '').$hours.':'.($minutes < 10 ? '0' : '').$minutes.':'.($seconds < 10 ? '0' : '').$seconds;
}

function get_current_week($date)
{
	$week_number  = date("W", strtotime('now')); // ISO-8601 week number
	$year_number  = date("o", strtotime('now')); // ISO-8601 year number
	$week_monday = date('Y-m-d', strtotime("$year_number-W$week_number"));
	$week_sunday = date('Y-m-d', strtotime("$week_monday +7 days"));//for monday's midnight shows
	return array('start_date' => $week_monday, 'end_date' => $week_sunday);
}

function in_current_week($date)
{
	$dates = get_current_week($date);
	return ($date >= $dates['start_date'] && $date <= $dates['end_date']);
}

function custom_date($date)
{
	if ($date == '0000-00-00') return null;
	$d = explode('-', $date);

	$full_month_names_gen = array('Ιανουαρίου', 'Φεβρουαρίου', 'Μαρτίου', 'Απριλίου', 'Μαΐου', 'Ιουνίου', 'Ιουλίου', 'Αυγούστου', 'Σεπτεμβρίου', 'Οκτωβρίου', 'Νοεμβρίου', 'Δεκεμβρίου');
	$short_month_names = array('Ιαν', 'Φεβ', 'Μαρ', 'Απρ', 'Μαϊ', 'Ιουν', 'Ιουλ', 'Αυγ', 'Σεπ', 'Οκτ', 'Νόε', 'Δεκ');
	//$full_day_names = array('Κυριακή', 'Δευτέρα', 'Τρίτη', 'Τετάρτη', 'Πέμπτη', 'Παρασκευή','Σάββατο');
	//$weekday = $full_day_names[date('w', strtotime($date))];
	//return $weekday.', '.$d[2].' '.$full_month_names_gen[$d[1]-1].' '.$d[0];
	return $d[2] . ' ' . $full_month_names_gen[$d[1] - 1] . ' ' . $d[0];
}

function get_user_agent()
{
	$CI = &get_instance();
	$CI->load->library('user_agent');

	if ($CI->agent->is_browser()) {
		$agent = $CI->agent->browser() . ' ' . $CI->agent->version();
	} else if ($CI->agent->is_robot()) {
		$agent = $CI->agent->robot();
	} else if ($CI->agent->is_mobile()) {
		$agent = $CI->agent->mobile();
	} else {
		$agent = 'Unidentified User Agent';
	}

	$platform = $CI->agent->platform();

	return $platform
		? $agent . ' on ' . $platform
		: $agent;
}

function date_from_db($date, $full = true)
{
	$dt = explode(' ', $date);
	if (count($dt) > 1) {
		$date = $dt[0];
	}
	if ($date == '0000-00-00') return null;
	$d = explode('-', $date);
	if (!count($d)) return $date;

	if ($full && count($dt) > 1) {
		$time = explode(':', $dt[1]);
		if (count($time) > 1) return implode('/', array_reverse($d)) . ' ' . $time[0] . ':' . $time[1];
		return implode('/', array_reverse($d));
	}
	return implode('/', array_reverse($d));
}


function date_to_db($date, $full = false)
{
	$dt = explode(' ', $date);
	if (count($dt) > 1) {
		$date = $dt[0];
	}
	$d = explode('/', $date);
	if (!count($d)) return $date;

	if ($full && count($dt) > 1) {
		$time = explode(':', $dt[1]);
		if (count($time) > 1) return implode('-', array_reverse($d)) . ' ' . $time[0] . ':' . $time[1] . ':00';
		return implode('/', array_reverse($d));
	}

	return implode('-', array_reverse($d));
}

function findChanges($oldValues, $postValues)
{
	$changes = array();
	foreach ($postValues as $name => $value) {
		if ($value != $oldValues[$name]) {
			$changes[$name] = array(
				'oldValue' => (empty($oldValues[$name]) ? 'null' : $oldValues[$name]),
				'newValue' => (empty($value) ? 'null' : $value),
			);
		}
	}
	return $changes;
}


function ajax_pagination($total, $current, $base, $ajax_base = null, $show_count = true, $page_length = NULL)
{

	if ($total == 0) return;
	$params = array();

	if (empty($page_length)) $page_length = PAGE_LENGTH;
	$totalPages = ceil($total / $page_length);
	//if ( $totalPages == 1 ) return;

	$max = 9; //how many page nums to show
	$start = 0;
	$end = 0;
	if ($totalPages > $max) {
		$before = floor($max / 2);
		$start = $current - $before;
		if ($start < 1) $start = 1;
		$end = $start + $max - 1;
		if ($end > $totalPages) {
			$start -= ($end - $totalPages);
			$end = $totalPages;
		}
	} else {
		$start = 1;
		$end = $totalPages;
	}

	$div = '<div class="text-center mt-3">';
	if ($show_count) {
		$div .= '<p class="text-center"><em>Εμφάνιση ' . (($current - 1) * $page_length + 1) . ' από ' . ($current * $page_length <= $total ? $current * $page_length : $total) . ' of <b>' . $total . '</b> αποτελέσματα</em></p>';
	}
	$div .= '<ul class="pagination pagination-rounded align-self-center">
				<li class="page-item' . ($current == 1 ? ' disabled' : '') . '"><a class="page-link pagination_link ' . ($current == 1 ? 'disabled' : '') . '" data-page-number="1" href="' . $base . '/1" ' . (!empty($ajax_base) ? 'data-ajax-url="' . $ajax_base . '/1"' : '') . '>&laquo;</a></li>';

	// page numbers
	for ($i = $start; $i <= $end; $i++) {
		$div .= '<li class="page-item' . ($current == $i ? ' active' : '') . '"><a class="page-link pagination_link ' . ($current == $i ? 'disabled' : '') . '" data-page-number="' . $i . '" href="' . $base . '/' . $i . '" ' . (!empty($ajax_base) ? 'data-ajax-url="' . $ajax_base . '/' . $i . '"' : '') . '>' . $i . '</a></li>';
	}

	// arrows for forward
	$div .= '<li class="page-item' . ($current == $totalPages ? ' disabled' : '') . '"><a class="page-link pagination_link ' . ($current == $totalPages ? 'disabled' : '') . '" data-page-number="' . $totalPages . '" href="' . $base . '/' . $totalPages . '" ' . (!empty($ajax_base) ? 'data-ajax-url="' . $ajax_base . '/' . $totalPages . '"' : '') . '>&raquo;</a></li>';
	$div .= '</ul>';
	$div .= '</div>';
	return $div;
}

function get_image($image_id, $size = 'sqr200')
{
	if (!$image_id) return '';
	$model = new \App\Models\Image();
	$image = $model->find($image_id);
	if (empty($image['id'])) return '';
	return get_image_url($image['file_name'], $size);
}

function get_image_url($file_name, $size = 'sqr200')
{
	if (empty($file_name)) return '';
	//return config("ImagesConfig")->ci_images_url . $size . '/' . $file_name;
	return '/images/' . $size . '/' . $file_name;
}

function get_image_mime($target_file_abs)
{
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	if ($finfo == FALSE) {
		log_message('error', 'cannot get finfo_open with FILEINFO_MIME_TYPE');
		return FALSE;
	}
	$mime_type = finfo_file($finfo, $target_file_abs);
	if ($mime_type == FALSE) {
		finfo_close($finfo);
		log_message('error', 'cannot get finfo_file for ' . $target_file_abs);
		return FALSE;
	}
	finfo_close($finfo);
	return ($mime_type == 'image/svg' ? 'image/svg+xml' : $mime_type);
}

function serve_image($target_file_abs)
{

	$fp = fopen($target_file_abs, 'r');
	if ($fp == FALSE) {
		log_message('error', 'cannot get fopen for: ' . $target_file_abs);
		return FALSE;
	}
	$fpt = fpassthru($fp);
	if ($fpt == FALSE) {
		fclose($fp);
		log_message('error', 'cannot get fpassthru');
		return FALSE;
	}

	fclose($fp);
}

function getFaqCategories()
{
	$categoryModel = model(FaqCategory::class);
	$list = $categoryModel->orderBy('order_num ASC')->findAll();
	$response = array();
	foreach($list as $item){
		$response[$item['id']] = $item['title'];
	}
	return $response;
}

function check_permalink($permalink, $id = null)
{
	$model = new \App\Models\Permalink();
	$permalink_row = $model->where('permalink', $permalink);
	if (!empty($id)) {
		$permalink_row = $permalink_row->where('type_id != ', $id);
	}
	$permalink_row = $permalink_row->first();
	if (!empty($permalink_row['id'])) return array('resp' => false, 'msg' => 'The permalink ' . $permalink . ' is used on another url.');
	return array('resp' => true, 'permalink' => $permalink);
}

function greeklish($string)
{
	$greek   = array('«', '»', '…', '’', 'α', 'ά', 'Ά', 'Α', 'β', 'Β', 'γ', 'Γ', 'δ', 'Δ', 'ε', 'έ', 'Ε', 'Έ', 'ζ', 'Ζ', 'η', 'ή', 'Η', 'Ή', 'θ', 'Θ', 'ι', 'ί', 'ϊ', 'ΐ', 'Ι', 'Ί', 'κ', 'Κ', 'λ', 'Λ', 'μ', 'Μ', 'ν', 'Ν', 'ξ', 'Ξ', 'ο', 'ό', 'Ο', 'Ό', 'π', 'Π', 'ρ', 'Ρ', 'σ', 'ς', 'Σ', 'τ', 'Τ', 'υ', 'ύ', 'Υ', 'Ύ', 'φ', 'Φ', 'χ', 'Χ', 'ψ', 'Ψ', 'ω', 'ώ', 'Ω', 'Ώ', ' ', '|', '\'');
	$english = array('', '', '', '', 'a', 'a', 'a', 'a', 'b', 'b', 'g', 'g', 'd', 'd', 'e', 'e', 'e', 'e', 'z', 'z', 'i', 'i', 'i', 'i', 'th', 'th', 'i', 'i', 'i', 'i', 'i', 'i', 'k', 'k', 'l', 'l', 'm', 'm', 'n', 'n', 'x', 'x', 'o', 'o', 'o', 'o', 'p', 'p', 'r', 'r', 's', 's', 's', 't', 't', 'u', 'u', 'y', 'y', 'f', 'f', 'ch', 'ch', 'ps', 'ps', 'o', 'o', 'o', 'o', '-', '', '');
	$rr = str_replace($greek, $english, $string);
	return strtolower($rr);
}

function get_permalink($string)
{
	$string = greeklish($string);

	$expressions = array(
		'/[αΑ][ιίΙΊ]/u' => 'e',
		'/[οΟΕε][ιίΙΊ]/u' => 'i',
		'/[αΑ][υύΥΎ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u' => 'af$1',
		'/[αΑ][υύΥΎ]/u' => 'av',
		'/[εΕ][υύΥΎ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u' => 'ef$1',
		'/[εΕ][υύΥΎ]/u' => 'ev',
		'/[οΟ][υύΥΎ]/u' => 'ou',
		'/(^|\s)[μΜ][πΠ]/u' => '$1b',
		'/[μΜ][πΠ](\s|$)/u' => 'b$1',
		'/[μΜ][πΠ]/u' => 'mp',
		'/[νΝ][τΤ]/u' => 'nt',
		'/[τΤ][σΣ]/u' => 'ts',
		'/[τΤ][ζΖ]/u' => 'tz',
		'/[γΓ][γΓ]/u' => 'ng',
		'/[γΓ][κΚ]/u' => 'gk',
		'/[ηΗ][υΥ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u' => 'if$1',
		'/[ηΗ][υΥ]/u' => 'iu',
		'/[θΘ]/u' => 'th',
		'/[χΧ]/u' => 'ch',
		'/[ψΨ]/u' => 'ps',
		'/[αάΑΆ]/u' => 'a',
		'/[βΒ]/u' => 'v',
		'/[γΓ]/u' => 'g',
		'/[δΔ]/u' => 'd',
		'/[εέΕΈ]/u' => 'e',
		'/[ζΖ]/u' => 'z',
		'/[ηήΗΉ]/u' => 'i',
		'/[ιίϊΙΊΪ]/u' => 'i',
		'/[κΚ]/u' => 'k',
		'/[λΛ]/u' => 'l',
		'/[μΜ]/u' => 'm',
		'/[νΝ]/u' => 'n',
		'/[ξΞ]/u' => 'x',
		'/[οόΟΌ]/u' => 'o',
		'/[πΠ]/u' => 'p',
		'/[ρΡ]/u' => 'r',
		'/[σςΣ]/u' => 's',
		'/[τΤ]/u' => 't',
		'/[υύϋΥΎΫ]/u' => 'i',
		'/[φΦ]/iu' => 'f',
		'/[ωώ]/iu' => 'o'
	);
	$string = preg_replace(array_keys($expressions), array_values($expressions), $string);
	//log_message('error','setp 3 '.$string);
	$special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", "%", "+", chr(0));
	$string = str_replace($special_chars, '', $string);
	//log_message('error',$string);
	$p1 = array('"', ',', '.', ':', '\'');
	$rr = str_replace($p1, '', $string);
	$rr = str_replace('----', '-', $rr);
	$rr = str_replace('---', '-', $rr);
	$rr = str_replace('--', '-', $rr);
	return strtolower($rr);
}


function getElementAttributes(string $element): array {
	if (false !== preg_match_all('/([a-z0-9]+)=[\"\']{1}(.*?)[\"\']{1}/sui', $element, $found_attrs, PREG_PATTERN_ORDER)) {
		$i = 0;
		$attributes = [];
		foreach ($found_attrs[1] as $name) {
			$attributes["$name"] = $found_attrs[2]["$i"];
			++$i;
		}
		return $attributes;
	}
	return [];
}

function fixPostContent($content, $user_id){
	//parse external images and save and serve them from our media
	preg_match_all( '/<img[\s\r\n]+.*?>/is', $content, $imgmatches);
	$search = array();
	$replace = array();
	foreach ( $imgmatches[0] as $imgHTML ) {
		$replaceHTML = '<img';
		$attributes = getElementAttributes($imgHTML);
		$w = false; $h = false; $src = false; $image_render = 'NORMAL'; $emoji = false;
		foreach($attributes as $at=>$va){
			if($at == 'width'){
				$w = $va;
			}else if($at == 'height'){
				$h = $va;
			}else if($at == 'src'){
				$src = $va;
			}else if($at == 'class'){
				if(strpos( $va, 'float-md-right') !== false){
					$image_render = 'FLOAT_RIGHT_MD';
				}else if(strpos( $va, 'float-right') !== false){
					$image_render = 'FLOAT_RIGHT';
				}else if(strpos( $va, 'float-md-left') !== false){
					$image_render = 'FLOAT_LEFT_MD';
				}else if(strpos( $va, 'float-left') !== false){
					$image_render = 'FLOAT_LEFT';
				}
				if(strpos( $va, 'emoji') !== false){
					 $emoji = true;
				}
			}else{
				$replaceHTML .= ' '.$at.'="'.$va.'"';
			}
		}
		if(substr( $src, 0, 17 ) === "/images/original/"){
			continue;
		}else if(substr( $src, 0, 8 ) === "/images/"){
			$src_parts = explode('/',$src);
			$src = '/images/original/'.$src_parts[count($src_parts)-1];
		}else{
			$temp_name2 = explode('/',$src);
			$temp_name = explode('.',$temp_name2[count($temp_name2)-1]);
			$ext = $temp_name[count($temp_name)-1];
			$fn = '';
			for($i=0;$i<count($temp_name)-1;$i++){
				$temp_name[$i] = str_replace(' ','_',$temp_name[$i]);
				$temp_name[$i] = str_replace('\'','',$temp_name[$i]);
				$fn .= $temp_name[$i];
			}
			$target_folder = config("ImagesConfig")->site_upload_path.'/original_images';
			if (!is_dir($target_folder))
			{
				$create_folder = mkdir($target_folder);
				if ($create_folder == FALSE)
				{
					continue;
				}
			}

			$imgModel = new \App\Models\Image();
			$filename = false; $cc = 0; $oname = get_permalink($fn);
			while($filename == false){
				$filename = $imgModel->check_random_name($ext,$oname.($cc>0?'_'.$cc:''));
				$cc++;
			}
			$source_file = config("ImagesConfig")->site_upload_path.'/original_images/'.$filename.'.'.$ext;
			//log_message('error','src '.$src.' | source_file: '.$source_file);
			$file_copied = copy($src, $source_file);
			if(!$file_copied){
				log_message('error','img '.$src.' not found for article');
				continue;
			}
			$width = NULL;
			$height = NULL;
			if($ext == 'svg'){
				$xmlget = simplexml_load_string(file_get_contents($source_file));
				$xmlattributes = $xmlget->attributes();
				$width = (string) $xmlattributes->width; 
				$height = (string) $xmlattributes->height;
			}else{
				$info = \Config\Services::image()
					->withFile(realpath($source_file))
					->getFile()
					->getProperties(true);
				$width = @$info['width']; 
				$height = @$info['height'];
			}
			$new_image_data = array(
				'title' => $temp_name2[count($temp_name2)-1],
				'file_name' => $filename.'.'.$ext,
				'mimetype' => mime_content_type($source_file),
				'extension' => $ext,
				'width' => $width,
				'height' => $height,
				'added_by' => $user_id,
			);
			$image_id = $imgModel->insert($new_image_data);
			if(empty($image_id)){
				log_message('error','img '.$src.' not saved on db');
				continue;
			}
			$src = '/images/original/'.$filename.'.'.$ext;
		}
		$replaceHTML .= ' src="'.$src.'"';
		if(empty($w) || empty($h)){	
			if(empty($width)){
				array_push( $search, $imgHTML );
				array_push( $replace, '' );
				continue;
			}
			$replaceHTML .= ' width="'.$width.'"';
			$replaceHTML .= ' height="'.$height.'"';
		}else{
			$replaceHTML .= ' width="'.$w.'"';
			$replaceHTML .= ' height="'.$h.'"';
		}
		
		switch($image_render){
			case 'NORMAL':
				$replaceHTML .= ' class="img-fluid d-block'.($emoji?' emoji':'').'"';
				break;
			case 'FLOAT_RIGHT_MD': 
				$replaceHTML .= ' class="img-fluid float-md-right mr-auto ml-auto mr-md-0 ml-md-3 mb-2'.($emoji?' emoji':'').'"';
				break;
			case 'FLOAT_RIGHT':
				$replaceHTML .= ' class="img-fluid float-right ml-2 mb-2'.($emoji?' emoji':'').'"';
				break;
			case 'FLOAT_LEFT_MD':
				$replaceHTML .= ' class="img-fluid float-md-left mr-auto ml-auto ml-md-0 mr-md-3 mb-2'.($emoji?' emoji':'').'"';
				break;
			case 'FLOAT_LEFT':
				$replaceHTML .= ' class="img-fluid float-left mr-2 mb-2'.($emoji?' emoji':'').'"';
				break;
		}
		$replaceHTML .= '>';
		array_push( $search, $imgHTML );
		array_push( $replace, $replaceHTML );
	}
	$search = array_unique( $search );
	$replace = array_unique( $replace );
	if(count($search)) $content = str_replace( $search, $replace, $content );
	return $content;
}

function curlGetContent($url)
{
    $headers = [
        'Content-Type:application/json',
        'Accept:application/json',
    ];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_REFERER, base_url());
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        log_message('error', 'Custom Curl:error '. curl_error($curl));
        curl_close($curl);
        return false;
    }

    curl_close($curl);

    if (empty($response)) {
        return false;
    }
    return json_decode($response, true);
}