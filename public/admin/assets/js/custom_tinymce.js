/**
* Author: BOUBOU
*/
var TinymceFunctions = function()
{
	var exports = {};
	var base_url = config.base_url;
	var width_ = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
	//var fox_links = $.parseJSON($('#qlinks').html());
	
	function init(){
		$('a#previewChangesBtn').click(function(){
			$('form#tinymce_form').attr('action',$(this).attr('href'));
			$('form#tinymce_form').attr('target','_new');
			$('form#tinymce_form').submit();
			setTimeout(function(){
				$('form#tinymce_form').removeAttr('action');
				$('form#tinymce_form').removeAttr('target');
			},200);
			return false;
		});
		
		tinymce.PluginManager.add('flist', function(editor, url) {
			
			editor.ui.registry.addButton('flist', {
				icon: 'unordered-list',
				tooltip: 'Insert custom checklist',
				//text: 'Insert checklist',
				onAction: function () {
					editor.insertContent('<ul class="checklist"><li></li></ul><p></p>');
				}
			});
			
			return {
				getMetadata: function () {
					return  {
						name: 'Somelab custom list plugin',
						url: ''
					};
				}
			};
		});
		
		tinymce.PluginManager.add('fiframe', function(editor, url) {
			
			editor.ui.registry.addButton('fiframe', {
				tooltip: 'Insert iframe',
				icon: 'embed-page',
				onAction: function () {
					editor.windowManager.open({
						title: 'Insert iframe',
						body: {
						type: 'panel',
							items: [
								{
									type: 'input',
									name: 'tiframe',
									label: 'iframe html'
								}
							]
						},
						buttons: [
							{
								type: 'cancel',
								text: 'Close'
							},
							{
								type: 'submit',
								text: 'Save',
								primary: true
							}
						],
						onSubmit: function (api) {
							var data = api.getData();
							if(data.tiframe){
								var fsrc = data.tiframe.match(/\<iframe.+src\=(?:\"|\')(.+?)(?:\"|\')(?:.+?)\>/);
								if(fsrc[1]){
									editor.insertContent('<div contenteditable="false">[iframe]{"src":"' + fsrc[1] + '"}[/iframe]</div><p></p>');
								}
							}
							api.close();
						}
					});
				}
			});
		})
		
		tinymce.PluginManager.add('fytvideo', function(editor, url) {
			
			editor.ui.registry.addButton('fytvideo', {
				tooltip: 'Insert yt video',
				icon: 'embed',
				onAction: function () {
					editor.windowManager.open({
						title: 'Insert yt video',
						body: {
						type: 'panel',
							items: [
								{
									type: 'input',
									name: 'tsrc',
									label: 'Video url'
								}
							]
						},
						buttons: [
							{
								type: 'cancel',
								text: 'Close'
							},
							{
								type: 'submit',
								text: 'Save',
								primary: true
							}
						],
						onSubmit: function (api) {
							var data = api.getData();
							if(data.tsrc){
								var myRegexp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
								var match = myRegexp.exec(data.tsrc);
								if(match !== null && typeof(match) !== "undefined"){
									if(match[2] !== null){
										editor.insertContent('<div contenteditable="false">[ytvideo]{"src":"' + match[2] + '"}[/ytvideo]</div><p></p>');
									}
								}
							}
							api.close();
						}
					});
				}
			});
		})
		
		tinymce.PluginManager.add('fximg', function(editor, url) {
			
			editor.ui.registry.addButton('fximg', {
				tooltip: 'Insert image',
				icon: 'edit-image',
				onAction: function () {
					editor.windowManager.openUrl({
						title: 'Insert image',
						url: config.base_url+'admin/ajaxData/getTinymceInsertImage'
					});
				}
			});
			
			return {
				getMetadata: function () {
					return  {
						name: 'Somelab image plugin',
						url: ''
					};
				}
			};
		});
		
		tinymce.PluginManager.add('fgrid', function(editor, url) {
			
			editor.ui.registry.addMenuButton('fgrid', {
				text: 'Insert grid',
				fetch: function (callback) {
					var boxes = [];
					boxes.push({type: 'menuitem',text: '1 column 50% > 768px center',onAction: function () {editor.insertContent('<div class="row justify-content-center"><div class="col-12 col-sm-6"><p></p></div></div><p></p>');}});
					boxes.push({type: 'menuitem',text: '2 columns',onAction: function () {editor.insertContent('<div class="row"><div class="col-6"><p></p></div><div class="col-6"><p></p></div></div><p></p>');}});
					boxes.push({type: 'menuitem',text: '2 columns > 576px',onAction: function () {editor.insertContent('<div class="row"><div class="col-12 col-sm-6"><p></p></div><div class="col-12 col-sm-6"><p></p></div></div><p></p>');}});
					boxes.push({type: 'menuitem',text: '2 columns > 768px',onAction: function () {editor.insertContent('<div class="row"><div class="col-12 col-md-6"><p></p></div><div class="col-12 col-md-6"><p></p></div></div><p></p>');}});
					boxes.push({type: 'menuitem',text: '3 columns',onAction: function () {editor.insertContent('<div class="row"><div class="col-4"><p></p></div><div class="col-4"><p></p></div><div class="col-4"><p></p></div></div><p></p>');}});
					boxes.push({type: 'menuitem',text: '3 columns > 576px',onAction: function () {editor.insertContent('<div class="row"><div class="col-12 col-sm-4"><p></p></div><div class="col-12 col-sm-4"><p></p></div></div><div class="col-12 col-sm-4"><p></p></div></div><p></p>');}});
					boxes.push({type: 'menuitem',text: '3 columns > 768px',onAction: function () {editor.insertContent('<div class="row"><div class="col-12 col-md-4"><p></p></div><div class="col-12 col-md-4"><p></p></div></div><div class="col-12 col-md-4"><p></p></div></div><p></p>');}});
					boxes.push({type: 'menuitem',text: '4 columns',onAction: function () {editor.insertContent('<div class="row"><div class="col-3"><p></p></div><div class="col-3"><p></p></div><div class="col-3"><p></p></div><div class="col-3"><p></p></div></div><p></p>');}});
					boxes.push({type: 'menuitem',text: '4 columns > 768px',onAction: function () {editor.insertContent('<div class="row"><div class="col-12 col-md-3"><p></p></div><div class="col-12 col-md-3"><p></p></div><div class="col-12 col-md-3"><p></p></div><div class="col-12 col-md-3"><p></p></div></div><p></p>');}});
					callback(boxes);
				}
			});
			
			return {
				getMetadata: function () {
					return  {
						name: 'Somelab bootstap grid plugin',
						url: ''
					};
				}
			};
		});
		
		tinymce.PluginManager.add('fbox', function(editor, url) {
			
			var makeBox = function (fclass) {
				var seltext = tinyMCE.activeEditor.selection.getContent();
				var fnode = tinyMCE.activeEditor.dom.getParent(tinyMCE.activeEditor.selection.getNode(), 'div.fbox');
				if(fnode){
					tinymce.activeEditor.dom.removeClass(fnode, 'shadow');
					tinymce.activeEditor.dom.removeClass(fnode, 'shadow2');
					tinymce.activeEditor.dom.removeClass(fnode, 'info');
					tinymce.activeEditor.dom.removeClass(fnode, 'success');
					tinymce.activeEditor.dom.removeClass(fnode, 'warning');
					tinymce.activeEditor.dom.removeClass(fnode, 'error');
					tinymce.activeEditor.dom.removeClass(fnode, 'download');
					tinymce.activeEditor.dom.removeClass(fnode, 'note');
					tinymce.activeEditor.dom.addClass(fnode, fclass);
				}else{
					tinyMCE.execCommand('mceReplaceContent', false, '<div class="fbox '+fclass+'"><p>'+seltext+'</p></div><p></p>');
				}
			};
			
			editor.ui.registry.addMenuButton('fbox', {
				text: 'Insert box',
				fetch: function (callback) {
					var boxes = [];
					boxes.push({type: 'menuitem',text: 'Shadow box',onAction: function () {makeBox('shadow');}});
					boxes.push({type: 'menuitem',text: 'Shadow small box',onAction: function () {makeBox('shadow2');}});
					boxes.push({type: 'menuitem',text: 'Info box',onAction: function () {makeBox('info');}});
					boxes.push({type: 'menuitem',text: 'Success box',onAction: function () {makeBox('success');}});
					boxes.push({type: 'menuitem',text: 'Warning box',onAction: function () {makeBox('warning');}});
					boxes.push({type: 'menuitem',text: 'Error box',onAction: function () {makeBox('error');}});
					boxes.push({type: 'menuitem',text: 'Download box',onAction: function () {makeBox('download');}});
					boxes.push({type: 'menuitem',text: 'Note box',onAction: function () {makeBox('note');}});
					callback(boxes);
				}
			});
			
			return {
				getMetadata: function () {
					return  {
						name: 'Somelab box plugin',
						url: ''
					};
				}
			};
		});
		
		tinymce.PluginManager.add('shortcodes', function(editor, url) {
			var openDialog = function (stitle,sid) {
				return editor.windowManager.openUrl({
					title: stitle,
					url: config.base_url+'admin/ajaxData/getTinymceShortcodeAttrs/'+sid
				});
			};
			editor.ui.registry.addMenuButton('shortcodes', {
				text: 'Add shortcode',
				fetch: function (callback) {
					var shortcodes = [];
					$('select#shortcodes option').each(function(){
						var opt = $(this);
						shortcodes.push({
							type: 'menuitem',
							text: opt.html(),
							onAction: function () {
								if(opt.attr('data-filters') > 0){
									openDialog(opt.html(),opt.attr('value'));
								}else{
									editor.insertContent('<div class="shortcode" contenteditable="false" data-shortcode="'+opt.attr('value')+'">['+opt.attr('value')+'][/'+opt.attr('value')+']</div><p></p>');
								}
							}
						});
					});
					callback(shortcodes);
				}
			});
			return {
				getMetadata: function () {
					return  {
						name: 'Shortcodes plugin',
						url: ''
					};
				}
			};
		});
		
		tinymce.init({
			selector: 'textarea.tinymce_editor',
			min_height: 500,
			plugins: 'paste searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap hr nonbreaking insertdatetime advlist lists wordcount textpattern noneditable help charmap emoticons shortcodes fbox flist fiframe fytvideo fgrid autoresize',
			toolbar1: 'undo redo | bold italic underline strikethrough | styleselect | link | alignleft aligncenter alignright alignjustify | numlist bullist | flist | forecolor backcolor removeformat | hr | charmap emoticons',
			toolbar2: 'table | image fiframe fytvideo codesample | shortcodes | fgrid | fbox | code',
			forced_root_block: 'p',
			toolbar_sticky: true,
			link_context_toolbar: true,
			//link_quicklink: true,
			init_instance_callback: function (editor) {
				editor.on('dblClick', function (e) {
					if(e.target.nodeName == 'DIV'){
						if(e.target.classList.contains('shortcodetabs')){
							var thtml = e.target.textContent.replace("[shortcodetabs]", "").replace("[/shortcodetabs]", "");
							const instanceApi = editor.windowManager.openUrl({
								title: 'Edit shortcode tabs',
								url: config.base_url+'admin/ajaxData/getTinymceInsertShortcodeTabs/1',
								onMessage: function (instance, data) {
									switch(data.mceAction)
									{
										case 'GetContent':
											instanceApi.sendMessage({
												type: 'tinymce',
												message: thtml
											});
											break;
										default:
											// run code for replacing the content
											break;
									}
								}
							});
						}else if(e.target.classList.contains('shortcode')){
							var shortcode = e.target.attributes['data-shortcode'].value;
							var thtml = e.target.textContent.replace("["+shortcode+"]", "").replace("[/"+shortcode+"]", "");
							if(thtml){
								const instanceApi = editor.windowManager.openUrl({
									title: 'Edit shortcode',
									url: config.base_url+'admin/ajaxData/getTinymceShortcode/'+shortcode,
									onMessage: function (instance, data) {
										switch(data.mceAction)
										{
											case 'GetContent':
												instanceApi.sendMessage({
													type: 'tinymce',
													message: thtml
												});
												break;
											default:
												// run code for replacing the content
												break;
										}
									}
								});
							}
						}
					}
					//includes('table-bordered')
				});
				editor.on('ExecCommand', function(e) {
					//console.log('The ' + e.command + ' command was fired.');
				});
			},
			codesample_languages: [{text: 'HTML/XML',value: 'markup'}],
			convert_urls : false,
			contextmenu: false,//'link image fbox',
			browser_spellcheck: true,
			paste_webkit_styles: 'none',
			paste_word_valid_elements: 'b,strong,i,em,h1,h2,h3,p,a',
			entity_encoding: 'raw',
			content_css: [config.base_url+'assets/css/bootstrap.min.css',config.base_url+'assets/js/custom_tinymce.css?v=23'],
			style_formats_merge: true,
			fox_base_url: config.base_url,
			link_list: [],//fox_links,//config.base_url+'admin/ajaxData/getTinymceLinks',
			rel_list: [
				{title: 'None', value: ''},
				{title: 'No follow', value: 'nofollow'}
			],
			link_class_list: [
				{title: 'Normal text link', value: ''},
				{title: 'Button blue display inline', value: 'btn btn-primary m-1 d-inline-block'},
				{title: 'Button blue display block', value: 'btn btn-primary mx-auto my-1 d-block'},
				{title: 'Button light blue display inline', value: 'btn btn-info m-1 d-inline-block'},
				{title: 'Button light blue display block', value: 'btn btn-info mx-auto my-1 d-block'},
				{title: 'Button green display inline', value: 'btn btn-success m-1 d-inline-block'},
				{title: 'Button green display block', value: 'btn btn-success mx-auto my-1 d-block'},
				{title: 'Button orange display inline', value: 'btn btn-warning m-1 d-inline-block'},
				{title: 'Button orange display block', value: 'btn btn-warning mx-auto my-1 d-block'},
				{title: 'Button red display inline', value: 'btn btn-danger m-1 d-inline-block'},
				{title: 'Button red display block', value: 'btn btn-danger mx-auto my-1 d-block'},
			],
			style_formats: [
				{ title: 'Like heading', block: 'p', classes: 'like_heading'},
				{ title: 'Highlight', inline: 'span', classes: 'highlight'},
				{ title: 'Orange color', inline: 'span', classes: 'font-orange'},
				{ title: '60% font size', inline: 'span', classes: 'font-xs'},
				{ title: 'Small', inline: 'small' },
				{ title: '0.8rem font size', inline: 'span', classes: 'font-0-8'},
				{ title: '0.9rem font size', inline: 'span', classes: 'font-0-9'},
				{ title: '1rem font size', inline: 'span', classes: 'font-1'},
				{ title: '1.1rem font size', inline: 'span', classes: 'font-1-1'},
				{ title: 'Dropcap', inline: 'span', classes: 'dropcap'},
			],
			table_advtab: false,
			table_row_advtab: false,
			table_cell_advtab: false,
			table_appearance_options: false,
			table_default_attributes: {class: 'table'},
			table_default_styles: {},
			table_class_list: [],
			table_row_class_list: [
				{title: 'None', value: ''},
				{title: 'Primary', value: 'table-primary'},
				{title: 'Secondary', value: 'table-secondary'},
				{title: 'Success', value: 'table-success'},
				{title: 'Danger', value: 'table-danger'},
				{title: 'Warning', value: 'table-warning'},
				{title: 'Info', value: 'table-info'},
				{title: 'Light', value: 'table-light'},
				{title: 'Dark', value: 'table-dark'},
			],
			table_cell_class_list: [
				{title: 'None', value: ''},
				{title: 'Primary', value: 'table-primary'},
				{title: 'Secondary', value: 'table-secondary'},
				{title: 'Success', value: 'table-success'},
				{title: 'Danger', value: 'table-danger'},
				{title: 'Warning', value: 'table-warning'},
				{title: 'Info', value: 'table-info'},
				{title: 'Light', value: 'table-light'},
				{title: 'Dark', value: 'table-dark'},
			],
			quickbars_insert_toolbar: '',
			quickbars_selection_toolbar: '',
			image_title: true,
			image_class_list: [
				{title: 'Normal', value: 'img-fluid d-block'},
				{title: 'float δεξιά', value: 'img-fluid float-right ml-2 mb-2'},
				{title: 'float δεξιά (>768px)', value: 'img-fluid float-md-right mr-auto ml-auto mr-md-0 ml-md-3 mb-2'},
				{title: 'float αριστερά', value: 'img-fluid float-left mr-2 mb-2'},
				{title: 'float αριστερά (>768px)', value: 'img-fluid float-md-left mr-auto ml-auto ml-md-0 mr-md-3 mb-2'},
			],
			powerpaste_word_import: 'clean',
			powerpaste_html_import: 'clean',
			setup: function (editor) {
			},
			content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
		});
		
		if($('textarea.max_inline_editor').length){
			$.each($("textarea.max_inline_editor"),function(){
				var tid = $(this).attr('id');
				var dm = parseInt($(this).attr('data-maxcount'));
				
				var inlineConfig = {
					selector: 'textarea#'+tid,
					menubar: false,
					//inline: true,
					min_height: 200,
					plugins: 'paste searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap hr nonbreaking insertdatetime advlist lists wordcount textpattern noneditable help charmap emoticons flist autoresize',
					toolbar: 'undo redo | bold italic underline strikethrough | styleselect | link  | alignleft aligncenter alignright alignjustify | numlist bullist | flist | forecolor backcolor removeformat | hr | charmap emoticons',
					forced_root_block: 'p',
					powerpaste_word_import: 'clean',
					powerpaste_html_import: 'clean',
					keep_styles : false,
					convert_urls : false,
					contextmenu: false,
					browser_spellcheck: true,
					paste_webkit_styles: 'none',
					paste_word_valid_elements: 'b,strong,i,em,h1,h2,h3,p,a',
					entity_encoding: 'raw',
					content_css: [config.base_url+'assets/css/bootstrap.min.css',config.base_url+'assets/js/custom_tinymce.css?v=23'],
					style_formats_merge: true,
					fox_base_url: config.base_url,
					//link_list: fox_links,//config.base_url+'admin/ajaxData/getTinymceLinks',
					rel_list: [
						{title: 'None', value: ''},
						{title: 'No follow', value: 'nofollow'}
					],
					link_class_list: [
						{title: 'Normal text link', value: ''},
						{title: 'Button blue display inline', value: 'btn btn-primary m-1 d-inline-block'},
						{title: 'Button blue display block', value: 'btn btn-primary mx-auto my-1 d-block'},
						{title: 'Button light blue display inline', value: 'btn btn-info m-1 d-inline-block'},
						{title: 'Button light blue display block', value: 'btn btn-info mx-auto my-1 d-block'},
						{title: 'Button green display inline', value: 'btn btn-success m-1 d-inline-block'},
						{title: 'Button green display block', value: 'btn btn-success mx-auto my-1 d-block'},
						{title: 'Button orange display inline', value: 'btn btn-warning m-1 d-inline-block'},
						{title: 'Button orange display block', value: 'btn btn-warning mx-auto my-1 d-block'},
						{title: 'Button red display inline', value: 'btn btn-danger m-1 d-inline-block'},
						{title: 'Button red display block', value: 'btn btn-danger mx-auto my-1 d-block'},
					],
					style_formats: [
						{ title: 'Like heading', block: 'p', classes: 'like_heading'},
						{ title: 'Highlight', inline: 'span', classes: 'highlight'},
						{ title: 'Orange color', inline: 'span', classes: 'font-orange'},
						{ title: '60% font size', inline: 'span', classes: 'font-xs'},
						{ title: 'Small', inline: 'small' },
						{ title: '0.8rem font size', inline: 'span', classes: 'font-0-8'},
						{ title: '0.9rem font size', inline: 'span', classes: 'font-0-9'},
						{ title: '1rem font size', inline: 'span', classes: 'font-1'},
						{ title: '1.1rem font size', inline: 'span', classes: 'font-1-1'},
						{ title: 'Dropcap', inline: 'span', classes: 'dropcap'},
					],
					init_instance_callback: function (editor) {
						$(editor.getContainer()).find('button.tox-statusbar__wordcount').click();
					},
					setup: function (editor) {
						var allowedKeys = [8, 37, 38, 39, 40, 46]; // backspace, delete and cursor keys
						editor.on('keydown', function (e) {
							if (allowedKeys.indexOf(e.keyCode) != -1) return true;
							var tx = editor.getContent({ format: 'raw' });
							var txt = document.createElement("textarea");
							txt.innerHTML = tx;	
							var decoded = txt.value;
							var decodedStripped = decoded.replace(/(<([^>]+)>)/ig, "").trim();
							var tc = decodedStripped.length;
							if(tc >= dm){
								e.preventDefault();
								e.stopPropagation();
								return false;
							}
							return true;
						});
					}
				};

				tinymce.init(inlineConfig);
			});
		}
		
		if($('textarea.inline_editor').length){
			var inlineConfig = {
				selector: 'textarea.inline_editor',
				menubar: false,
				//inline: true,
				plugins: 'paste searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap hr nonbreaking insertdatetime advlist lists wordcount textpattern noneditable help charmap emoticons flist autoresize',
				toolbar: 'undo redo | bold italic underline strikethrough | styleselect | link  | alignleft aligncenter alignright alignjustify | numlist bullist | flist | forecolor backcolor removeformat | hr | charmap emoticons',
				forced_root_block: 'p',
				powerpaste_word_import: 'clean',
				powerpaste_html_import: 'clean',
				keep_styles : false,
				convert_urls : false,
				contextmenu: false,
				browser_spellcheck: true,
				paste_webkit_styles: 'none',
				paste_word_valid_elements: 'b,strong,i,em,h1,h2,h3,p,a',
				entity_encoding: 'raw',
				content_css: [config.base_url+'assets/css/bootstrap.min.css',config.base_url+'assets/js/custom_tinymce.css?v=23'],
				style_formats_merge: true,
				fox_base_url: config.base_url,
				//link_list: fox_links,//config.base_url+'admin/ajaxData/getTinymceLinks',
				rel_list: [
					{title: 'None', value: ''},
					{title: 'No follow', value: 'nofollow'}
				],
				link_class_list: [
					{title: 'Normal text link', value: ''},
					{title: 'Button blue display inline', value: 'btn btn-primary m-1 d-inline-block'},
					{title: 'Button blue display block', value: 'btn btn-primary mx-auto my-1 d-block'},
					{title: 'Button light blue display inline', value: 'btn btn-info m-1 d-inline-block'},
					{title: 'Button light blue display block', value: 'btn btn-info mx-auto my-1 d-block'},
					{title: 'Button green display inline', value: 'btn btn-success m-1 d-inline-block'},
					{title: 'Button green display block', value: 'btn btn-success mx-auto my-1 d-block'},
					{title: 'Button orange display inline', value: 'btn btn-warning m-1 d-inline-block'},
					{title: 'Button orange display block', value: 'btn btn-warning mx-auto my-1 d-block'},
					{title: 'Button red display inline', value: 'btn btn-danger m-1 d-inline-block'},
					{title: 'Button red display block', value: 'btn btn-danger mx-auto my-1 d-block'},
				],
				style_formats: [
					{ title: 'Like heading', block: 'p', classes: 'like_heading'},
					{ title: 'Highlight', inline: 'span', classes: 'highlight'},
					{ title: 'Orange color', inline: 'span', classes: 'font-orange'},
					{ title: '60% font size', inline: 'span', classes: 'font-xs'},
					{ title: 'Small', inline: 'small' },
					{ title: '0.8rem font size', inline: 'span', classes: 'font-0-8'},
					{ title: '0.9rem font size', inline: 'span', classes: 'font-0-9'},
					{ title: '1rem font size', inline: 'span', classes: 'font-1'},
					{ title: '1.1rem font size', inline: 'span', classes: 'font-1-1'},
					{ title: 'Dropcap', inline: 'span', classes: 'dropcap'},
				],
			};

			tinymce.init(inlineConfig);
		}

		window.addEventListener('message', function (event) {
		});
	}

    exports.init = init;
	
	return exports;
}
var tinymceFunctions = new TinymceFunctions();

$(document).ready(function() {
	tinymceFunctions.init();
});
