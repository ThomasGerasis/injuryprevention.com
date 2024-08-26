/**
* Author: SilkTech SA
*/
var BasicFormFunctions = function()
{
	var exports = {};
	var base_url = config.base_url;
	
	var singleImageUploadOptions = {
		dataType: 'json',
		pasteZone: null,
		paramName: 'file_to_upload',
		formData: function(){
			return [];
		},
		add: function (e, data) {
			target = $(this).attr('data-target');
			inputname = $(this).attr('data-input-name');
			template = $(this).attr('data-template');
			
			var part = $.tmpl($('#'+template).html(), {
				input_name: inputname
			});
			$(target).html(part);
			$(target).find('.progress').removeClass('d-none');
			data.context = $(target);
			data.submit();
		},
		progress: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			data.context.find('.progress .progress-bar').css(
				'width',
				progress + '%'
			).html(progress + '%');
		},
		fail: function (e, data) {
			swal('Missing data',data.error,'warning');
		},
		done: function (e, data) {
			if(data.result.resp != 'ok'){
				data.context.html('');
				swal('Πρόβλημα!',data.result.error,'warning');
				return;
			}
			data.context.find('.progress').remove();
			data.context.find('img.img-filename').attr('src',data.result.file_name);
			data.context.find('input.image_id-input').val(data.result.image_id);
			data.context.find('[data-action=remove]').on('click', function (e) {
				e.preventDefault();
				var $target = $(this),
					slidingSpeed = 150;

				// If not disabled
				if(!$target.hasClass('disabled')) {
					$target.closest('.card').slideUp({
						duration: slidingSpeed,
						start: function() {
							$target.addClass('d-block');
						},
						complete: function() {
							$target.closest('.imgcard').remove();
						}
					});
				}
			});
		}
	};
	
	var singleImgNameUploadOptions = {
		dataType: 'json',
		pasteZone: null,
		paramName: 'file_to_upload',
		formData: {filename: $('input.image_name_input').val()},
		send: function (e, data) {
			var target = $(this).attr('data-target');
			$('#'+target+' .loading').html('<img src="'+config.base_url+'assets/img/sp-loading.gif"> Please wait...');
			$('#'+target+' .img-container').hide;
		},
		done: function (e, data) {
			var target = $(this).attr('data-target');
			if(data.result.resp != 'ok'){
				$('#'+target+' .loading').html('<div class="alert alert-danger">'+data.result.error+'</div>');
				$('#'+target+' .img-container').hide;
				return;
			}
			$('#'+target+' .img-container input.input-filename').val(data.result.upload_data.file_name);
			$('#'+target+' .img-container img').attr("src",$('#'+target+' .img-container img').attr('data-original-src')+data.result.upload_data.file_name).show();
			$('#'+target+' .img-container .remove-single-img-btn').show();
			$('#'+target+' .loading').html('');
			$('#'+target+' .img-container').show;
		}
	};
	
	var singleImgUploadOptions = {
		dataType: 'json',
		pasteZone: null,
		paramName: 'file_to_upload',
		formData: function(){return [];},
		send: function (e, data) {
			var target = $(this).attr('data-target');
			$('#'+target+' .loading').html('<img src="'+config.base_url+'assets/img/sp-loading.gif"> Please wait...');
			$('#'+target+' .img-container').hide;
		},
		done: function (e, data) {
			var target = $(this).attr('data-target');
			if(data.result.resp != 'ok'){
				$('#'+target+' .loading').html('<div class="alert alert-danger">'+data.result.error+'</div>');
				$('#'+target+' .img-container').hide;
				return;
			}
			$('#'+target+' .img-container input.input-filename').val(data.result.upload_data.file_name);
			$('#'+target+' .img-container img').attr("src",$('#'+target+' .img-container img').attr('data-original-src')+data.result.upload_data.file_name).show();
			$('#'+target+' .img-container .remove-single-img-btn').show();
			$('#'+target+' .loading').html('');
			$('#'+target+' .img-container').show;
		}
	};
	
	function add_template(){
		
		var target = $('#'+$(this).attr('data-target'));
		var template = $(this).attr('data-template');
		var counter = parseInt($('input#'+$(this).attr('data-counter')).val());
		var part_attrs = {};
		part_attrs["counter"] = counter;
		var new_part = $.tmpl($('#'+template).html(), part_attrs);
		$('input#'+$(this).attr('data-counter')).val(counter + 1);
		target.append(new_part);
		
		//var container = target.find('.player_part').last();
		//container.find('.btn-remove-player').click(remove_player_block);
		return false;
	}
	
	function init(){
		
		
		if($('#bookmaker_review_restriction_text').length){
			var introduction = document.getElementById('bookmaker_review_restriction_text');
			introduction.setAttribute('contenteditable', true);
			CKEDITOR.inline('bookmaker_review_restriction_text', {
				extraAllowedContent: 'a(documentation);abbr[title];code',
				removePlugins: 'stylescombo',
				extraPlugins: 'justify,wordcount',
				entities: false,
				removeButtons: 'Save,Templates,Cut,Copy,Paste,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Outdent,Indent,CreateDiv,BidiLtr,BidiRtl,Language,Anchor,Image,Flash,PageBreak,Iframe,Font,ShowBlocks,About',
				disableNativeSpellChecker: false
			});
		}
		
		if($('#sport-positions-sortable').length){
			$('.add-template-btn').click(add_template);
			$('#sport-positions-sortable').sortable( {
				dropOnEmpty: false,
				cursor: "move",
				handle: ".btn-move-part",
				update: function( event, ui ) {
					$(this).children().each(function(index) {
						$(this).find('input.sort_order').val(index + 1);
					});
				}
			});
		}
		
		
		if($('.form-check-input-switch').length){
			jQuery.ajax({
				url: base_url+"assets/js/switch.min.js",
				dataType: "script",
				cache: true
			}).done(function() {
				$('.form-check-input-switch').bootstrapSwitch();
			});
		}
		
		$('.remove-single-img-btn').click(function(){
			var p = $(this).parents('.single-img-container');
			p.find('input.input-filename').val('');
			p.find('.img-container img').hide().attr('src','');
			p.find('.img-container').hide();
			$(this).hide();
			return false;
		});
		
		$('.select_file').click(function(){
			var p = $(this).parents('.single-img-container');
			p.find('.do-single-img-upload').trigger('click');
			return false;
		});
		if($('.do-single-img-upload').length){
			if($('input.image_name_input').length){
				$('.do-single-img-upload').fileupload(singleImgNameUploadOptions);
			}else{
				$('.do-single-img-upload').fileupload(singleImgUploadOptions);
			}
		}
		
		$('.select_image_file').click(function(){
			var p = $(this).parents('.single-img-container');
			p.find('.single-image-upload').trigger('click');
			return false;
		});
		if($('.single-image-upload').length){
			$('.single-image-upload').fileupload(singleImageUploadOptions);
		}
		
		if($('select.select2cont').length){
			$('.select2cont').select2({
				minimumResultsForSearch: Infinity
			});
		}
		if($('textarea#user_content').length){
			CKEDITOR.stylesSet.add( 'default', [
				{ name: '60% font size', element: 'span', attributes: { 'class': 'font-xs' } },
				{ name: 'Small', element: 'small' },
				{ name: '0.8rem font size', element: 'span', attributes: { 'class': 'font-0-8' } },
				{ name: '0.9rem font size', element: 'span', attributes: { 'class': 'font-0-9' } },
				{ name: '1rem font size', element: 'span', attributes: { 'class': 'font-1' } },
				{ name: '1.1rem font size', element: 'span', attributes: { 'class': 'font-1-1' } },
				{ name: '1.2rem font size', element: 'span', attributes: { 'class': 'font-1-2' } },
				{ name: '1.3rem font size', element: 'span', attributes: { 'class': 'font-1-3' } },
				{ name: '1.4rem font size', element: 'span', attributes: { 'class': 'font-1-4' } },
				{ name: '1.5rem font size', element: 'span', attributes: { 'class': 'font-1-5' } },
				{ name: '2rem font size', element: 'span', attributes: { 'class': 'font-2' } },
				{ name: '3rem font size', element: 'span', attributes: { 'class': 'font-3' } },
				{ name: '4rem font size', element: 'span', attributes: { 'class': 'font-4' } },
				{ name: 'Highlight', element: 'span', attributes: { 'class': 'highlight' } },
				{ name: 'Dropcap', element: 'span', attributes: { 'class': 'dropcap' } },
			]);
			CKEDITOR.timestamp='v6';
			CKEDITOR.replace('user_content', {
				customConfig: '../../../editor_ckeditor_full_config.js?v=58',
				embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',
			});
		}
		
		if($('textarea#fox_review').length){
			CKEDITOR.stylesSet.add( 'default', [
				{ name: '60% font size', element: 'span', attributes: { 'class': 'font-xs' } },
				{ name: 'Small', element: 'small' },
				{ name: '0.8rem font size', element: 'span', attributes: { 'class': 'font-0-8' } },
				{ name: '0.9rem font size', element: 'span', attributes: { 'class': 'font-0-9' } },
				{ name: '1rem font size', element: 'span', attributes: { 'class': 'font-1' } },
				{ name: '1.1rem font size', element: 'span', attributes: { 'class': 'font-1-1' } },
				{ name: '1.2rem font size', element: 'span', attributes: { 'class': 'font-1-2' } },
				{ name: '1.3rem font size', element: 'span', attributes: { 'class': 'font-1-3' } },
				{ name: '1.4rem font size', element: 'span', attributes: { 'class': 'font-1-4' } },
				{ name: '1.5rem font size', element: 'span', attributes: { 'class': 'font-1-5' } },
				{ name: '2rem font size', element: 'span', attributes: { 'class': 'font-2' } },
				{ name: '3rem font size', element: 'span', attributes: { 'class': 'font-3' } },
				{ name: '4rem font size', element: 'span', attributes: { 'class': 'font-4' } },
				{ name: 'Highlight', element: 'span', attributes: { 'class': 'highlight' } },
				{ name: 'Dropcap', element: 'span', attributes: { 'class': 'dropcap' } },
			]);
			CKEDITOR.timestamp='v6';
			CKEDITOR.replace('fox_review', {
				customConfig: '../../../editor_ckeditor_full_config.js?v=58',
				embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',
			});
			CKEDITOR.replace('shortcode_minireview', {
				customConfig: '../../../editor_ckeditor_full_config.js?v=58',
				embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',
			});
			CKEDITOR.replace('shortcode_promos', {
				customConfig: '../../../editor_ckeditor_full_config.js?v=58',
				embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',
			});
			if($('textarea#shortcode_livecasino').length){
				CKEDITOR.replace('shortcode_livecasino', {
					customConfig: '../../../editor_ckeditor_full_config.js?v=58',
					embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',
				});
				CKEDITOR.replace('shortcode_slots', {
					customConfig: '../../../editor_ckeditor_full_config.js?v=58',
					embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',
				});
				CKEDITOR.replace('shortcode_cs', {
					customConfig: '../../../editor_ckeditor_full_config.js?v=58',
					embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',
				});
			}
		}
		
		if($('.rating_slider').length){
			$('.rating_slider').each(function(){
				var sval = $('input#'+$(this).attr('id')+'_val').val();
				var this_ = document.getElementById($(this).attr('id'));
				var this_id = $(this).attr('id');
				noUiSlider.create(this_, {
					start: sval,
					behaviour: 'snap',
					connect: 'lower',
					step: 1,
					range: {
						'min':  0,
						'max':  100
					},
					direction: 'ltr'
				});

				this_.noUiSlider.on('update', function( values, handle ) {
					$('input#'+this_id+'_val').val(parseInt(values[handle]));
				});
			});
		}
		
		if($('.add-sgroup').length){
			$('.add-sgroup').click(function(){
				$('.'+$(this).attr('data-target')).append('<div class="input-group mb-1"><input type="text" class="form-control" name="'+$(this).attr('data-input-name')+'" value=""><span class="input-group-append"><button class="btn btn-light remove-sgroup" type="button" title="Αφαίρεση"><i class="icon-cross3"></i></button></span></div>');
				return false;
			});
			
			$(document).on("click", ".remove-sgroup", function () {
				$(this).closest('.input-group').remove();
				return false;
			});
		}
		if($('#user_groups').length){
			$('select.is_admin').change(function(){
				if($(this).val() == '1'){
					$('#user_groups').hide();
				}else{
					$('#user_groups').show();
				}
			});
			$('input.has-relations').change(function(){
				if($(this).prop('checked')){
					$(this).parents('.user_group_row').find('.relation-row').show();
				}else{
					$(this).parents('.user_group_row').find('.relation-row').hide();
				}
			});
		}
		
		/*if($('#add_new_user_group').length){
			$('#add_new_user_group').click(function(){
				if(!$('select#new_user_group').val()) return false;
				var new_pid = $('select#new_user_group').val();
				var opt = $('select#new_user_group option[value="'+new_pid+'"]');
				var part_attrs = {};
				part_attrs["pid"] = new_pid;
				part_attrs["pname"] = opt.html();
				$('select#new_user_group').val('');
				opt.hide();
				var new_part = $.tmpl($('#new_user_group_row').html(), part_attrs);
				$('table.all_user_groups').append(new_part);
				if(opt.attr('league_ids')){
					$('table tr.has-league_ids:last').show();
				}
				return false;
			});
			$(document).on("click", ".btn-remove-row", function () {
				var pid = $(this).parents('tr').attr('data-user_group-id');
				$('select#new_user_group option[value="'+pid+'"]').show();
				$(this).parents('tr').remove();
				return false;
			});
		}*/
		
		if($('#add_new_payment').length){
			$('#add_new_payment').click(function(){
				if(!$('select#new_payment').val()) return false;
				var new_pid = $('select#new_payment').val();
				var opt = $('select#new_payment option[value="'+new_pid+'"]');
				var part_attrs = {};
				part_attrs["pid"] = new_pid;
				part_attrs["pname"] = opt.html();
				$('select#new_payment').val('');
				opt.hide();
				var new_part = $.tmpl($('#new_payment_row').html(), part_attrs);
				$('table.all_payments').append(new_part);
				return false;
			});
			$(document).on("click", ".btn-remove-row", function () {
				var pid = $(this).parents('tr').attr('data-payment-id');
				$('select#new_payment option[value="'+pid+'"]').show();
				$(this).parents('tr').remove();
				return false;
			});
			$('table.all_payments tbody').sortable( {
				dropOnEmpty: false,
				cursor: "move",
				handle: ".btn-move-part",
				update: function( event, ui ) {
					$(this).children().each(function(index) {
						$(this).find('input.sort_order').val(index + 1);
					});
				}
			});
		}
		
		$(document).on("click", ".change-video-url", function () {
			$(this).parents('.video-block-container').find('.video-url-container').show();
			$(this).hide();
			return false;
		})
		
		$(document).on("click", ".video-url-submit", function () {
			var p = $(this).parents('.video-block-container');
			var input_target = $(this).attr('data-target');
			var vp = p.find('input.video_url');
			var video = vp.val();
			if(video){
				var found = false;
				//var videoid = video.match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/);
				var myRegexp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
				var match = myRegexp.exec(video);
				if(match !== null && typeof(match) !== "undefined"){
					if(match[2] !== null){
						var videoid = match[2];
						found = true;
						found = true;
						p.find('.video_source').css('margin-top','10px');
						$('input[name="'+input_target+'"]').val(videoid);
						p.find('.video_source').html('<iframe src="https://www.youtube.com/embed/'+videoid+'" allowfullscreen="" width="400" height="300" frameborder="0"></iframe>');
					}
				}
				if(!found){
					//check fb
					var myRegexp = /^http(?:s?):\/\/(?:www\.|web\.|m\.)?facebook\.com\/([A-z0-9\.]+)\/videos(?:\/[0-9A-z].+)?\/(\d+)(?:.+)?$/gm;
					var match = myRegexp.exec(video);
					if(match !== null && typeof(match) !== "undefined"){
						if(match[2] !== null){
							videoid = match[2];
							found = true;
							p.find('.video_source').css('margin-top','10px');
							$('input[name="'+input_target+'"]').val(videoid);
							p.find('.video_source').html('<iframe src="https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2Ffacebook%2Fvideos%2F'+videoid+'%2F&width=400&show_text=false&height=300" width="400" height="300" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media" allowFullScreen="true"></iframe>');
						}
					}
				}
				if(!found){ 
					swal({
						title: 'Πρόβλημα',
						text: 'To url του video δεν είναι σωστό.',
						icon: 'warning',
						timer: 3000
					});
				}
			}else{
				swal({
					title: 'Πρόβλημα',
					text: 'Παρακαλώ εισάγετε το url του video.',
					icon: 'warning',
					timer: 3000
				});
			}
			return false;
		})
		
		
		$(document).on("keyup", "input.required", function(e){
			if($(this).val() != ''){
				$(this).parents('.form-group').find('.col-form-label').removeClass('text-danger');
				$(this).removeClass('border-danger');
			}else{
				$(this).parents('.form-group').find('.col-form-label').addClass('text-danger');
				$(this).addClass('border-danger');
			}
		});
		
		$(document).on("change", "select.required", function(e){
			if($(this).val() != ''){
				$(this).parents('.form-group').find('.col-form-label').removeClass('text-danger');
				$(this).removeClass('border-danger');
			}else{
				$(this).parents('.form-group').find('.col-form-label').addClass('text-danger');
				$(this).addClass('border-danger');
			}
		});
		
		$('form.myvalidation').submit(function(e){
			var error = false;
			$(this).find('.col-form-label.text-danger').removeClass('text-danger');
			$(this).find('.border-danger').removeClass('border-danger');
			$.each($(this).find('.required'),function(){
				if(!$(this).val()){
					error = true;
					$(this).parents('.form-group').find('.col-form-label').addClass('text-danger');
					$(this).addClass('border-danger');
				}
			});
			if(error){
				e.preventDefault();
				$('html,body').animate({scrollTop: $('.col-form-label.text-danger:first').offset().top - $('.navbar:first').outerHeight() - 10},'slow');
				return false;
			}
			return true;
		});
		
		
	}

    exports.init = init;
	
	return exports;
}
var basicFormFunctions = new BasicFormFunctions();

$(document).ready(function() {
	basicFormFunctions.init();
});
