<?php

namespace App\Libraries;

class ContentParser
{
	private $isMobile;
	private $usedJsFiles = array();
	private $usedCssFiles = array();
	private $cacheHandler;

	public function __construct($isMobile = 0, $cacheHandler)
	{
		$this->isMobile = $isMobile;
		$this->cacheHandler = $cacheHandler;
	}

	public function setUsedShortcode($shortcode){
		$shortcode_tags = $this->getShortcodes();
		if(!isset($shortcode_tags[$shortcode])) return;
		foreach($shortcode_tags[$shortcode]['jsFiles'] as $newFile){
			$this->usedJsFiles[$newFile] = $newFile;
		}
		foreach($shortcode_tags[$shortcode]['cssFiles'] as $newFile){
			$this->usedCssFiles[$newFile] = $newFile;
		}
	}

	public function addJsFile($newFile){
		$this->usedJsFiles[$newFile] = $newFile;
	}

	public function addCssFile($newFile){
		$this->usedCssFiles[$newFile] = $newFile;
	}

	public function getJsFiles(){
		return $this->usedJsFiles;
	}

	public function getCssFiles(){
		return $this->usedCssFiles;
	}

	public function getShortcodes(){
		$shortcodes = array();
		$shortcodes['heading'] = array('view'=>'shortcodes/_heading','jsFiles'=>array(),'cssFiles'=>array());
		$shortcodes['search_articles'] = array('view'=>'shortcodes/_search_articles','jsFiles'=>array('searchArticles'),'cssFiles'=>array('searchArticles'));
		$shortcodes['multi_use_shortcode'] = array('view'=>'shortcodes/_multi_use_shortcode','jsFiles'=>array(),'cssFiles'=>array());
		$shortcodes['tabbed_content'] = array('view'=>'shortcodes/_tabbed_content','jsFiles'=>array(),'cssFiles'=>array());
		$shortcodes['article_slider'] = array('view'=>'shortcodes/_article_slider','jsFiles'=>array('customSwiper'),'cssFiles'=>array('customSwiper','articleSlider'));
		$shortcodes['article_category_slider'] = array('view'=>'shortcodes/_article_category_slider','jsFiles'=>array('customSwiper'),'cssFiles'=>array('customSwiper','articleCategorySlider'));
		$shortcodes['vertical_space'] = array('view'=>'shortcodes/_vertical_space','jsFiles'=>array(),'cssFiles'=>array(''));
		$shortcodes['contact_form'] = array('view'=>'shortcodes/_contact_form','jsFiles'=>array('contactForm'),'cssFiles'=>array('contactForm'));
		$shortcodes['articles_row'] = array('view'=>'shortcodes/_articles_row','jsFiles'=>array(),'cssFiles'=>array(''));


		return $shortcodes;
	}

	public function token($length = 32) {
		// Create random token
		$string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$max = strlen($string) - 1;
		$token = '';
		for ($i = 0; $i < $length; $i++) {
			$token .= $string[mt_rand(0, $max)];
		}
		return $token;
	}

	function contentImageUrl($folder, $file_name, $width = 0, $height = 0, $image_extension = 'jpg'){

		if(empty($file_name) || empty($width) || empty($height)) return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAIAAAABCAQAAABeK7cBAAAAC0lEQVR42mNkAAIAAAoAAv/lxKUAAAAASUVORK5CYII=';
		return base_url('images/' . $folder . '/' . $file_name.'.'.$image_extension);
	}


	public function parseContent($content, $mobileImage = false){
		$ignore_html = false;
		preg_match_all( '/<img[\s\r\n]+.*?>/is', $content, $imgmatches);
		$search = array();
		$replace = array();
		foreach ( $imgmatches[0] as $imgHTML ) {
			$replaceHTML = $imgHTML;
			$replaceHTML = '<img';
			$attributes = $this->get_element_attributes($imgHTML);
			$w = false; $h = false; $src = false; $image_render = 'NORMAL'; $inline_style = '';
			foreach($attributes as $at=>$va){
				if($at == 'width'){
					$w = $va;
				}else if($at == 'height'){
					$h = $va;
				}else if($at == 'style'){
					$inline_style = $va;
				}else if($at == 'src'){
					$src = $va;
				}else if($at == 'class'){
					/*if(strpos( $va, 'wpicon_fox') !== false){
						$image_render = 'wpicon_fox';
					}else if(strpos( $va, 'wpicon_tip') !== false){
						$image_render = 'wpicon_tip';
					}else*/ if(strpos( $va, 'float-md-right') !== false){
						$image_render = 'FLOAT_RIGHT_MD';
					}else if(strpos( $va, 'float-right') !== false){
						$image_render = 'FLOAT_RIGHT';
					}else if(strpos( $va, 'float-md-left') !== false){
						$image_render = 'FLOAT_LEFT_MD';
					}else if(strpos( $va, 'float-left') !== false){
						$image_render = 'FLOAT_LEFT';
					}
				}else{
					$replaceHTML .= ' '.$at.'="'.$va.'"';
				}
			}

			if(strpos( $src, "https:") !== false){ //external src
				array_push( $search, $imgHTML );
				array_push( $replace, $imgHTML );
				continue;
			}
			$image_width = 0;
			$image_height = 0;
			$image_folder = 0;
			$image_alternative_src = '';
			$src_url_parts = explode('/',$src);
			$fname = explode('.',$src_url_parts[count($src_url_parts)-1]);
			$image_extension = $fname[count($fname)-1];
			unset($fname[count($fname)-1]);
			$image_name = implode('.',$fname);
			unset($src_url_parts[count($src_url_parts)-1]);
			$remaing_uri = implode('/',$src_url_parts);
			switch($image_render){
				case 'NORMAL':
					if((empty($w) || empty($h)) || (!empty($w) && $w > 1000)){
						if($this->isMobile){
							$image_width = 440;
							$image_height = 220;
						}else{
							$image_width = 1000;
							$image_height = 500;
						}
						$image_folder = 'customSize';
						$image_name = $image_name.'_'.$w.'_'.$h;
					}else if(!empty($w) && !empty($h) && $w < 200){
						$image_width = $w;
						$image_height = $h;
						$image_folder = 'customSize';
						$image_name = $image_name.'_'.$w.'_'.$h;
					}else if(!empty($w) && !empty($h) && $h > ($w/2)){
						if($this->isMobile){
							$image_width = 440;
							$image_height = ceil(440*$h/$w);
							$image_folder = 'customSize';
							$image_name = $image_name.'_440_'.(ceil(440*$h/$w));
						}else{
							$image_width = $w;
							$image_height = $h;
							$image_folder = 'customSize';
							$image_name = $image_name.'_'.$w.'_'.$h;
						}
					}else{
						if($this->isMobile){
							$image_width = 300;
							$image_height = 150;
							$image_folder = 'rct300';
							$image_alternative_src = str_replace('original','rct300',$src);
						}else{
							$image_width = $w;
							$image_height = $h;
							$image_folder = 'customSize';
							$image_name = $image_name.'_'.$w.'_'.$h;
						}
					}
					$replaceHTML .= ' src="'.$this->contentImageUrl($image_folder, $image_name, $image_width, $image_height, $image_extension).'" width="'.$image_width.'" height="'.$image_height.'"';
					if(!empty($inline_style)) $replaceHTML .= ' style="'.$inline_style.'"';
					$replaceHTML .= ' loading="lazy" class="mx-auto mb-2 imgw100">';
					if($this->isMobile){
						$replaceHTMLNew = '<div class="text-center">'.$replaceHTML.'</div>';
						$replaceHTML = $replaceHTMLNew;
					}
					break;
				case 'FLOAT_RIGHT_MD':
					$replaceHTML .= ' class="float-md-end me-auto ms-auto me-md-0 ms-md-2 mb-2 d-block imgw100"';
					$replaceHTML .= ' loading="lazy" ';
					if(empty($w) || empty($h)){
						$replaceHTML .= ' style="width:300px;height:150px"';
						$replaceHTML .= ' src="'.$this->contentImageUrl('rct300', $image_name, 300, 150, $image_extension).'" width="300" height="150">';
					}else{
						$replaceHTML .= ' style="width:'.$w.'px;height:'.$h.'px;'.$inline_style.'"';
						$replaceHTML .= ' src="'.$this->contentImageUrl('customSize', $image_name.'_'.$w.'_'.$h, $w, $h, $image_extension).'" width="'.$w.'" height="'.$h.'">';
					}
					break;
				case 'FLOAT_RIGHT':
					$replaceHTML .= ' class="float-end mb-2 ms-2 d-block imgw100"';
					$replaceHTML .= ' loading="lazy" ';
					if(empty($w) || empty($h)){
						$replaceHTML .= ' style="width:300px;height:150px"';
						$replaceHTML .= ' src="'.$this->contentImageUrl('rct300', $image_name, 300, 150, $image_extension).'" width="300" height="150">';
					}else{
						$replaceHTML .= ' style="width:'.$w.'px;height:'.$h.'px;'.$inline_style.'"';
						$replaceHTML .= ' src="'.$this->contentImageUrl('customSize', $image_name.'_'.$w.'_'.$h, $w, $h, $image_extension).'" width="'.$w.'" height="'.$h.'">';
					}
					break;
				case 'FLOAT_LEFT_MD':
					$replaceHTML .= ' class="float-md-start me-auto ms-auto me-md-2 ms-md-0 mb-2 d-block imgw100"';
					$replaceHTML .= ' loading="lazy" ';
					if(empty($w) || empty($h)){
						$replaceHTML .= ' style="width:300px;height:150px;'.$inline_style.'"';
						$replaceHTML .= ' src="'.$this->contentImageUrl('rct300', $image_name, 300, 150, $image_extension).'" width="300" height="150">';
					}else{
						$replaceHTML .= ' style="width:'.$w.'px;height:'.$h.'px;'.$inline_style.'"';
						$replaceHTML .= ' src="'.$this->contentImageUrl('customSize', $image_name.'_'.$w.'_'.$h, $w, $h, $image_extension).'" width="'.$w.'" height="'.$h.'">';
					}
					break;
				case 'FLOAT_LEFT':
					$replaceHTML .= ' class="float-start mb-2 me-2 d-block imgw100"';
					$replaceHTML .= ' loading="lazy" ';
					if(empty($w) || empty($h)){
						$replaceHTML .= ' style="width:300px;height:150px;'.$inline_style.'"';
						$replaceHTML .= ' src="'.$this->contentImageUrl('rct300', $image_name, 300, 150, $image_extension).'" width="300" height="150">';
					}else{
						$replaceHTML .= ' style="width:'.$w.'px;height:'.$h.'px;'.$inline_style.'"';
						$replaceHTML .= ' src="'.$this->contentImageUrl('customSize', $image_name.'_'.$w.'_'.$h, $w, $h, $image_extension).'" width="'.$w.'" height="'.$h.'">';
					}
					break;
			}
			array_push( $search, $imgHTML );
			array_push( $replace, $replaceHTML );
		}
		$search = array_unique( $search );
		$replace = array_unique( $replace );
		$content = str_replace( $search, $replace, $content );
		$content = str_replace('<p>&nbsp;</p>','<p></p>',$content);
		$content = str_replace('<p> </p>','<p></p>',$content);

		$content = str_replace('<pre class="language-markup"><code>','<div class="bodypart-raw"><div class="viewportLoadTotalRawCode" id="raw_code_'.rand(100,2400).$this->token(6).'"><textarea class="d-none">',$content);
		$content = str_replace('</code></pre>','</textarea></div></div>',$content);

		if(false === strpos( $content, '[')){
			if(!empty($mobileImage)){
				$pos = mb_strpos($content, '</p>', 0, 'UTF-8');
				if ( $pos !== false ) {
					$first_text = mb_substr($content, 0, $pos+mb_strlen('</p>','UTF-8'), 'UTF-8');
					$second_text = mb_substr($content, $pos+mb_strlen('</p>','UTF-8'), NULL, 'UTF-8');
					$content = $first_text.'<div class="text-center">'.$mobileImage.'</div>'.$second_text;
				}
			}
			return array('content'=>$content,'cssFiles'=>$this->getCssFiles(),'jsFiles'=>$this->getJsFiles());
		}

		$shortcode_tags = $this->getShortcodes();
		$allsh = array();
		foreach($shortcode_tags as $shortcode_tag=>$shortcode_tags_info){
			$allsh[] = 'data-shortcode="'.$shortcode_tag.'"';
		}
		$allsh[] = 'contenteditable="false"';
		$content = str_replace($allsh,'',$content);
		$allsh = array();
		$allsh[] = 'class="bracket"';
		$allsh[] = 'class="shortcodetabs"';
		$allsh[] = 'class="texttabs"';
		$allsh[] = 'class="shortcode"';
		$content = str_replace($allsh,'class="shortcode-content"',$content);
		$content = str_replace(array('<div  >','<div >','<div   >'),'<div>',$content);

		preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );
		$tagnames = array_intersect( array_keys( $shortcode_tags ), $matches[1] );
		if(empty($tagnames)){
			if(!empty($mobileImage)){
				$pos = mb_strpos($content, '</p>', 0, 'UTF-8');
				if ( $pos !== false ) {
					$first_text = mb_substr($content, 0, $pos+mb_strlen('</p>','UTF-8'), 'UTF-8');
					$second_text = mb_substr($content, $pos+mb_strlen('</p>','UTF-8'), NULL, 'UTF-8');
					$content = $first_text.'<div class="text-center">'.$mobileImage.'</div>'.$second_text;
				}
			}
			return array('content'=>$content,'cssFiles'=>$this->getCssFiles(),'jsFiles'=>$this->getJsFiles());
		}
		$content = $this->do_shortcodes_in_html_tags($content, $ignore_html, $tagnames);
		$pattern = $this->get_shortcode_regex( $tagnames );
		$content = preg_replace_callback( "/$pattern/", function($match) { return $this->do_shortcode_tag($match); }, $content );
		$content = $this->unescape_invalid_shortcodes( $content );

		if(!empty($mobileImage)){
			$pos = mb_strpos($content, '</p>', 0, 'UTF-8');
			if ( $pos !== false ) {
				$first_text = mb_substr($content, 0, $pos+mb_strlen('</p>','UTF-8'), 'UTF-8');
				$second_text = mb_substr($content, $pos+mb_strlen('</p>','UTF-8'), NULL, 'UTF-8');
				$content = $first_text.'<div class="text-center">'.$mobileImage.'</div>'.$second_text;
			}
		}
		return array('content'=>$content,'cssFiles'=>$this->getCssFiles(),'jsFiles'=>$this->getJsFiles());
	}

	function unescape_invalid_shortcodes( $content ) {
		// Clean up entire string, avoids re-parsing HTML.
		$trans = array(
			'&#91;' => '[',
			'&#93;' => ']',
		);

		$content = strtr( $content, $trans );

		return $content;
	}

	function get_element_attributes(string $element): array {
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

	function wp_html_split($input){
		return preg_split( $this->get_html_split_regex(), $input, -1, PREG_SPLIT_DELIM_CAPTURE );
	}

	function get_html_split_regex() {
		static $regex;
		if ( ! isset( $regex ) ) {
			// phpcs:disable Squiz.Strings.ConcatenationSpacing.PaddingFound -- don't remove regex indentation
			$comments =
				'!'             // Start of comment, after the <.
				. '(?:'         // Unroll the loop: Consume everything until --> is found.
				.     '-(?!->)' // Dash not followed by end of comment.
				.     '[^\-]*+' // Consume non-dashes.
				. ')*+'         // Loop possessively.
				. '(?:-->)?';   // End of comment. If not found, match all input.

			$cdata =
				'!\[CDATA\['    // Start of comment, after the <.
				. '[^\]]*+'     // Consume non-].
				. '(?:'         // Unroll the loop: Consume everything until ]]> is found.
				.     '](?!]>)' // One ] not followed by end of comment.
				.     '[^\]]*+' // Consume non-].
				. ')*+'         // Loop possessively.
				. '(?:]]>)?';   // End of comment. If not found, match all input.

			$escaped =
				'(?='             // Is the element escaped?
				.    '!--'
				. '|'
				.    '!\[CDATA\['
				. ')'
				. '(?(?=!-)'      // If yes, which type?
				.     $comments
				. '|'
				.     $cdata
				. ')';

			$regex =
				'/('                // Capture the entire match.
				.     '<'           // Find start of element.
				.     '(?'          // Conditional expression follows.
				.         $escaped  // Find end of escaped element.
				.     '|'           // ... else ...
				.         '[^>]*>?' // Find end of normal element.
				.     ')'
				. ')/';
		}
		return $regex;
	}

	function get_shortcode_regex($tagnames = null){
		$shortcode_tags = $this->getShortcodes();
		if(empty($tagnames)){
			$tagnames = array_keys($shortcode_tags);
		}
		$tagregexp = join( '|', array_map('preg_quote',$tagnames));

		return
			'\\['                                // Opening bracket
			. '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
			. "($tagregexp)"                     // 2: Shortcode name
			. '(?![\\w-])'                       // Not followed by word character or hyphen
			. '('                                // 3: Unroll the loop: Inside the opening shortcode tag
			.     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
			.     '(?:'
			.         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
			.         '[^\\]\\/]*'               // Not a closing bracket or forward slash
			.     ')*?'
			. ')'
			. '(?:'
			.     '(\\/)'                        // 4: Self closing tag ...
			.     '\\]'                          // ... and closing bracket
			. '|'
			.     '\\]'                          // Closing bracket
			.     '(?:'
			.         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
			.             '[^\\[]*+'             // Not an opening bracket
			.             '(?:'
			.                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
			.                 '[^\\[]*+'         // Not an opening bracket
			.             ')*+'
			.         ')'
			.         '\\[\\/\\2\\]'             // Closing shortcode tag
			.     ')?'
			. ')'
			. '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
	}

	function wp_kses_attr_parse($element){
		$valid = preg_match( '%^(<\s*)(/\s*)?([a-zA-Z0-9]+\s*)([^>]*)(>?)$%', $element, $matches );
		if ( 1 !== $valid ) {
			return false;
		}
		$begin  = $matches[1];
		$slash  = $matches[2];
		$elname = $matches[3];
		$attr   = $matches[4];
		$end    = $matches[5];
		if ( '' !== $slash ) {
			// Closing elements do not get parsed.
			return false;
		}
		// Is there a closing XHTML slash at the end of the attributes?
		if ( 1 === preg_match( '%\s*/\s*$%', $attr, $matches ) ) {
			$xhtml_slash = $matches[0];
			$attr = substr( $attr, 0, -strlen( $xhtml_slash ) );
		} else {
			$xhtml_slash = '';
		}
		// Split it
		$attrarr = wp_kses_hair_parse( $attr );
		if ( false === $attrarr ) {
			return false;
		}

		// Make sure all input is returned by adding front and back matter.
		array_unshift( $attrarr, $begin . $slash . $elname );
		array_push( $attrarr, $xhtml_slash . $end );

		return $attrarr;
	}

	function do_shortcode_tag( $m) {
		$shortcode_tags = $this->getShortcodes();
		if ( $m[1] == '[' && $m[6] == ']' ) {
			return substr( $m[0], 1, -1 );
		}
		if(empty($m[2]) || !isset($shortcode_tags[$m[2]])) return '';
		$this->setUsedShortcode($m[2]);
		$shortcode_attrs = (empty($m[5])?array():json_decode($m[5],true));

		$multiuse_ids = array();
		switch($m[2]){
			case 'tabbed_content': foreach($shortcode_attrs['tabs'] as $tab){
					if(empty($tab['multi_use_content_id'])) continue;
					$multiuse_ids[$tab['multi_use_content_id']] = $tab['multi_use_content_id'];
				}
				break;
			case 'multi_use_shortcode':
				if(!empty($shortcode_attrs['multi_use_shortcode_id'])){
					$multiuse_ids[$shortcode_attrs['multi_use_shortcode_id']] = $shortcode_attrs['multi_use_shortcode_id'];
				}
				break;
			default: break;
		}
		if(count($multiuse_ids)){
			foreach($multiuse_ids as $multiuse_id){
				$multiData = $this->cacheHandler->getMultiUseContent($multiuse_id);
                if(!empty($multiData["loadJs"]) && is_array($multiData["loadJs"])){
                    foreach($multiData['loadJs'] as $newFile){
                        $this->addJsFile($newFile);
                    }
                }
                if(!empty($multiData["loadCss"]) && is_array($multiData["loadCss"])){
                    foreach($multiData['loadCss'] as $newFile){
                        $this->addJsFile($newFile);
                    }
                }
			}
		}
		return view($shortcode_tags[$m[2]]['view'], array('attrs'=>$shortcode_attrs,'cacheHandler'=>$this->cacheHandler, 'contentParser'=>$this, 'isMobile'=>$this->isMobile));
	}

	function do_shortcodes_in_html_tags($content, $ignore_html, $tagnames){
		$trans   = array('&#91;' => '&#091;', '&#93;' => '&#093;');
		$content = strtr($content, $trans);
		$trans   = array('[' => '&#91;', ']' => '&#93;');
		$pattern = $this->get_shortcode_regex($tagnames);
		$textarr = $this->wp_html_split($content);
		foreach($textarr as &$element){
			if('' == $element || '<' !== $element[0]){
				continue;
			}
			$noopen  = false === strpos($element, '[');
			$noclose = false === strpos($element, ']');
			if($noopen || $noclose){
				// This element does not contain shortcodes.
				if($noopen xor $noclose){
					// Need to encode stray [ or ] chars.
					$element = strtr($element, $trans);
				}
				continue;
			}
			if ( $ignore_html || '<!--' === substr( $element, 0, 4 ) || '<![CDATA[' === substr( $element, 0, 9 ) ) {
				// Encode all [ and ] chars.
				$element = strtr( $element, $trans );
				continue;
			}

			$attributes = $this->wp_kses_attr_parse($element);
			if ( false === $attributes ) {
				// Some plugins are doing things like [name] <[email]>.
				if ( 1 === preg_match( '%^<\s*\[\[?[^\[\]]+\]%', $element ) ) {
					$element = preg_replace_callback( "/$pattern/", function($match) { return $this->do_shortcode_tag($match); }, $element );
				}

				// Looks like we found some crazy unfiltered HTML.  Skipping it for sanity.
				$element = strtr( $element, $trans );
				continue;
			}

			// Get element name
			$front   = array_shift( $attributes );
			$back    = array_pop( $attributes );
			$matches = array();
			preg_match( '%[a-zA-Z0-9]+%', $front, $matches );
			$elname = $matches[0];

			// Look for shortcodes in each attribute separately.
			foreach ( $attributes as &$attr ) {
				$open  = strpos( $attr, '[' );
				$close = strpos( $attr, ']' );
				if ( false === $open || false === $close ) {
					continue; // Go to next attribute.  Square braces will be escaped at end of loop.
				}
				$double = strpos( $attr, '"' );
				$single = strpos( $attr, "'" );

				if ( ( false === $single || $open < $single ) && ( false === $double || $open < $double ) ) {
					// $attr like '[shortcode]' or 'name = [shortcode]' implies unfiltered_html.
					// In this specific situation we assume KSES did not run because the input
					// was written by an administrator, so we should avoid changing the output
					// and we do not need to run KSES here.
					$attr = preg_replace_callback( "/$pattern/", function($match) { return $this->do_shortcode_tag($match); }, $attr );
				} else {
					// $attr like 'name = "[shortcode]"' or "name = '[shortcode]'"
					// We do not know if $content was unfiltered. Assume KSES ran before shortcodes.
					$count    = 0;
					$attr = preg_replace_callback( "/$pattern/", function($match) { return $this->do_shortcode_tag($match); }, $attr, -1, $count );
					//if ( $count > 0 ) {
						// Sanitize the shortcode output using KSES.
						//$new_attr = wp_kses_one_attr( $new_attr, $elname );
						//if ( '' !== trim( $new_attr ) ) {
							// The shortcode is safe to use now.
							//$attr = $new_attr;
						//}
					//}
				}
			}
			$element = $front . implode( '', $attributes ) . $back;

			// Now encode any remaining [ or ] chars.
			$element = strtr( $element, $trans );
		}

		$content = implode( '', $textarr );

		return $content;
	}


}
