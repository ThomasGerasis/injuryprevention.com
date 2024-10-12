/**
* Author: SilkTech SA
*/
var EditingFunctions = function()
{
	var exports = {};
	var base_url = config.base_url;
	var width_ = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
	var analysi_date_picker;
	
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
			//if(data.context.find('input.image_id-input_fl') data.context.find('input.image_id-input_fl').val(data.result.image_id_fl);
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
	
	
	var multipleImageUploadOptions = {
		dataType: 'json',
		pasteZone: null,
		paramName: 'file_to_upload',
		formData: function(){
			return [];
		},
		add: function (e, data) {
			target = $(this).attr('data-target');
			panel = $(this).parents('.panel-body');
			image_counter = parseInt($('input#'+$(this).attr('data-counter')).val());
			template = $(this).attr('data-template');
			
			var part = $.tmpl($('#'+template).html(), {
				image_counter: image_counter,
				counter: parseInt($(this).attr('data-body-counter'))
			});
			$(target).append(part);
			$('input#'+$(this).attr('data-counter')).val(image_counter + 1);
			$(target).find('.imgcard:last .progress').removeClass('d-none');
			data.context = $(target).find('.imgcard:last');
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
				data.context.remove();
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
	
	function show_live_matches(){
		var sid = $(this).val();
		$('#live_match_selector').hide();
		$('#live_match_selector select.select_match option').remove();
		if(sid != ''){
			$.post($(this).attr('data-matches-url')+sid,
				[], 
				function (data) {
					if(data.length){
						$('#live_match_selector select.select_match').append('<option value=""> -- </option>');
						$.each(data,function(i,v){
							$('#live_match_selector select.select_match').append('<option value="'+i+'" data-home="'+v.home_name+'" data-away="'+v.away_name+'">'+v.home_name+' - '+v.away_name+' @ '+v.match_date+'</option>');
						});
						$('#live_match_selector').show();
					}
				},
				"json"
			);
		}
	}
	
	function show_result_change(){
		var counter = $(this).attr('data-counter');
		var t = $('.predictions_'+counter+'_result');
		if($(this).val() == 'open'){
			t.hide();
			t.find('input.result_required').removeAttr('required');
		}else{
			if($('input[name="predictions['+counter+'][stake]').length){
				var stake = parseFloat($('input[name="predictions['+counter+'][stake]"]').val());
				var odd = parseFloat($('input[name="predictions['+counter+'][odd]"]').val());
				var resinput = $('input[name="predictions['+counter+'][return]"]');
			}else{
				var stake = parseFloat($('input[name="stake"]').val());
				var odd = parseFloat($('input[name="odd"]').val());
				var resinput = $('input[name="return"]');
			}
			
			if($(this).val() == 'win'){
				var ret = stake * (odd - 1);
				resinput.val(ret.toFixed(2));
			}else if($(this).val() == 'lose'){
				var ret = -1 * stake;
				resinput.val(ret.toFixed(2));
			}else if($(this).val() == 'void'){
				resinput.val('');
			}else if($(this).val() == 'win_half'){
				var ret = (stake * (odd - 1))/2;
				resinput.val(ret.toFixed(2));
			}else if($(this).val() == 'lose_half'){
				var ret = -1/2 * stake;
				resinput.val(ret.toFixed(2));
			}
			t.show();
			t.find('input.result_required').attr('required','required');
		}
	}
	
	function init_multiple_tokeninput()
	{
		$(this).tokenInput($(this).attr('data-ajax-function'), {
			prePopulate: $.parseJSON('['+$(this).attr('data-init-token')+']'),
			preventDuplicates: true,
		});
	}
	
	function init_simple_tokeninput(){
		var prepopulated = [];
		if($(this).attr('data-init-token-id') && $(this).attr('data-init-token-name')){
			var prepopulated = [{id: $(this).attr('data-init-token-id'),name: $(this).attr('data-init-token-name')}];
		}
		var update_input = $(this).attr('data-hidden-input');
		var update_type = false;
		if($(this).hasClass('update_type')){
			update_type = $(this).parents('.input-container').find('input.input_type');
		}
		var slide = 0;
		var ptype = 0;
		if($(this).hasClass('add_slide')){
			slide = $(this).attr('data-counter');
			ptype = $(this).attr('data-type');
		}
		var extraf = 0;
		if($(this).hasClass('extra-field')){
			var extraf = $(this).attr('data-extra');
		}
		$(this).tokenInput($(this).attr('data-ajax-function'), {
			prePopulate: prepopulated,
			tokenLimit: 1,
			onCachedResult: function (results) {
				var new_results = new Array();
				if(extraf){
					var ef = $('select[name="'+extraf+'"]').val();
					$.each(results, function (index, value) {
						if(value[extraf] == ef) new_results.push(value);
					});
				}else{
					new_results = results;
				}
				return new_results;
			},
			onResult: function (results) {
				var new_results = new Array();
				if(extraf){
					var ef = $('select[name="'+extraf+'"]').val();
					$.each(results, function (index, value) {
						if(value[extraf] == ef) new_results.push(value);
					});
				}else{
					new_results = results;
				}
				return new_results;
			},
			onAdd: function(item){
				if(update_input && $('input[name="'+update_input+'"]').length){
					$('input[name="'+update_input+'"]').val(item.name);
				}
				if(update_type){
					update_type.val(item.type);
				}
				if(slide){
					$('input[name="slides['+slide+'][post_type]"]').val(ptype);
					$('input[name="slides['+slide+'][post_id]"]').val(item.id);
				}
			},
			onDelete: function(item){
				if(update_input && $('input[name="'+update_input+'"]').length){
					$('input[name="'+update_input+'"]').val('');
				}
				if(update_type){
					update_type.val('');
				}
				if(slide){
					$('input[name="slides['+slide+'][post_type]"]').val('');
					$('input[name="slides['+slide+'][post_id]"]').val('');
				}
			}
		});
	}
		
	function add_template_block(){
		
		var target = $('#'+$(this).attr('data-target'));
		var template = $(this).attr('data-template');
		var counter = parseInt($('input#'+$(this).attr('data-counter')).val());
		var part_attrs = {};
		part_attrs["counter"] = counter;
		part_attrs["order"] = counter;
		if($(this).attr('data-second-counter')){
			var origcounter = parseInt($(this).attr('data-orig-counter'));
			var secondcounter = parseInt($(this).attr('data-second-counter'));
			part_attrs["counter"] = origcounter;
			part_attrs["tab_counter"] = secondcounter;
			part_attrs["pair_counter"] = counter;
			part_attrs["order"] = counter;
		}else if($(this).attr('data-orig-counter')){
			var origcounter = parseInt($(this).attr('data-orig-counter'));
			part_attrs["counter"] = origcounter;
			part_attrs["tab_counter"] = counter;
			part_attrs["order"] = counter;
			//console.log(part_attrs);
			//$('input#'+$(this).attr('data-tab-counter')).val(tab_counter + 1);
		}
		if($(this).attr('data-block-counter')){
			var blockcounter = parseInt($(this).attr('data-block-counter'));
			part_attrs["counter"] = blockcounter;
			part_attrs["bp_counter"] = counter;
			part_attrs["order"] = counter;
			//console.log(part_attrs);
			//$('input#'+$(this).attr('data-tab-counter')).val(tab_counter + 1);
		}
		if($(this).attr('data-other-attrs')){
			var other_attrs = $.parseJSON('{'+$(this).attr('data-other-attrs')+'}');
			$.each(other_attrs,function(oa_name,oa_val){
				part_attrs[oa_name] = oa_val;
			});
		}
		
		var new_part = $.tmpl($('#'+template).html(), part_attrs);
		$('input#'+$(this).attr('data-counter')).val(counter + 1);
		if($(this).hasClass('prepend-template')){
			target.prepend(new_part);
			var container = target.find('.card').first();
		}else{
			target.append(new_part);
			var container = target.find('.card').last();
		}
		
		container.find('.input-tokeninput').each(init_simple_tokeninput);
		container.find('.pickadate-format').pickadate({
			format: 'yyyy-mm-dd',
			formatSubmit: 'yyyy-mm-dd',
			hiddenName: true,
			hiddenSuffix: ''
		});
		container.find("input.pickatime-input").each(function(){
			$(this).AnyTime_picker({
				input: $(this),
				format: '%H:%i'
			});
		});

		container.find('.single-image-upload').fileupload(singleImageUploadOptions);
		container.find('.multiple-image-upload').fileupload(multipleImageUploadOptions);
		container.find('.add-template-btn').click(add_template_block);
		container.find('select.show_result').change(show_result_change);
		
		if(container.find('select.select2cont').length){
			container.find('.select2cont').select2({
				minimumResultsForSearch: Infinity
			});
		}
		
		container.find('input.pickatime-format').each(function(){
			$(this).AnyTime_picker({
				input: $(this),
				format: '%H:%i'
			});
		});

		container.find(".bodypart-images").sortable( {
			dropOnEmpty: false,
			cursor: "move",
			handle: ".btn-move-image",
			update: function( event, ui ) {
				$(this).children().each(function(index) {
					$(this).find('input.sort_order').val(index + 1);
				});
			}
		});
		
		$.each(container.find(".bodypart-pairs"),function(){
			$(this).sortable( {
				dropOnEmpty: false,
				cursor: "move",
				handle: ".btn-move-tab",
				update: function( event, ui ) {
					$(this).children().each(function(index) {
						$(this).find('input.sort_order').val(index + 1);
					});
				}
			});
		});
		
		$.each(container.find(".bodypart-tabs"),function(){
			$(this).sortable( {
				dropOnEmpty: false,
				cursor: "move",
				handle: ".btn-move-tab",
				update: function( event, ui ) {
					$(this).children().each(function(index) {
						$(this).find('input.sort_order').val(index + 1);
					});
				}
			});
		});
		
		if(container.find(".block_bodyparts_container").length){
			container.find(".block_bodyparts_container").sortable( {
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
		
		if(container.find("textarea.inline_editor").length){
			var inlineConfig = {
				selector: '#'+container.find("textarea.inline_editor").attr('id'),
				menubar: false,
				//inline: true,
				plugins: 'paste searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap hr nonbreaking anchor insertdatetime advlist lists wordcount textpattern noneditable help charmap emoticons flist autoresize',
				toolbar: 'undo redo | bold italic underline strikethrough | styleselect | link | alignleft aligncenter alignright alignjustify | numlist bullist | flist | forecolor backcolor removeformat | hr | charmap emoticons',
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
				//link_list: $.parseJSON($('#qlinks').html()),//config.base_url+'ajaxData/getTinymceLinks',
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
		
		if($(this).hasClass('scrollto')){
			$('html,body').animate({scrollTop: container.offset().top - $('.navbar:first').outerHeight() - 10},'slow');
		}
		
		return false;
	}
	
	function init(){
		
		$('form#tinymce_form select.show_result').change(show_result_change);
		if($('form#tinymce_form select.live_select_sport').length){
			$('form#tinymce_form select.live_select_sport').change(show_live_matches);
			$('#live_match_selector select.select_match').change(function(){
				if($(this).val()){
					var vv = $(this).find('option:selected');
					//$('input[name="home_team"]').val(vv.attr('data-home'));
					$('input[name="home_team"]').tokenInput("clear");
					$('input[name="home_team"]').tokenInput("add", {id: vv.attr('data-home'), name: vv.attr('data-home')});
					$('input[name="away_team"]').tokenInput("clear");
					$('input[name="away_team"]').tokenInput("add", {id: vv.attr('data-away'), name: vv.attr('data-away')});
				}
			});
		}
		
		$('form#tinymce_form .pickadate-format').pickadate({
			format: 'yyyy-mm-dd',
			formatSubmit: 'yyyy-mm-dd',
			hiddenName: true,
			hiddenSuffix: ''
		});
		$('form#tinymce_form input.pickatime-format').each(function(){
			$(this).AnyTime_picker({
				input: $(this),
				format: '%H:%i'
			});
		});

		if($('input.input-simple-tokeninput').length){
			$('input.input-simple-tokeninput').each(init_simple_tokeninput);
		}
		
		if($('form#tinymce_form input.input-tokeninput').length){
			$('form#tinymce_form input.input-tokeninput').each(init_simple_tokeninput);
		}
			
		if($('form#tinymce_form input.check-permalink-input').length || $('form#tinymce_form input.fill-permalink').length){
			$('form#tinymce_form input.check-permalink-input').blur(function(){
				if($(this).attr('data-original-value')){
					if($(this).attr('data-original-value') == $(this).val()) return false;
				}
				//var url_suf = '/1/'+$('form#tinymce_form input.fill-permalink').attr('data-type-id');
				var url_suf = '/'+$('form#tinymce_form input.fill-permalink').attr('data-type-id');
				$.post(base_url+'admin/ajaxData/getPermalink'+url_suf,
					{value: $(this).val()}, 
					function (data) {
						if(data.resp){
							
						}else{
							swal({
								title: 'Error',
								text: data.msg,
								icon: 'warning'
							});
						}
					},
					"json"
				);
			});
			$('form#tinymce_form input.fill-permalink').blur(function(){
				var pp = $(this).attr('data-permalink-name');
				if(!$(this).val()){
					$('form#tinymce_form input[name="'+pp+'"]').val('');
				}else{
					var url_suf = '';
					if($(this).hasClass('check-permalink')){
						//var url_suf = '/1/'+$(this).attr('data-permalink-type')+'/'+$(this).attr('data-type-id');
						var url_suf = '/'+$(this).attr('data-type-id');
					}
					$.post(base_url+'admin/ajaxData/getPermalink'+url_suf,
						{value: $(this).val()}, 
						function (data) {
							if(data.resp){
								$('form#tinymce_form input[name="'+pp+'"]').val(data.permalink);
							}else{
								$('form#tinymce_form input[name="'+pp+'"]').val('');
								swal({
									title: 'Error',
									text: data.msg,
									icon: 'warning'
								});
							}
						},
						"json"
					);
				}
			});
		}
		
		$(document).on("change", ".select_show_hide_content", function () {
			var selected_option = $(this).find('option[value="' + $(this).val() + '"]');
			var p = $(this).parents('.card');
			p.find('.show_hide_elements').addClass('d-none');
			p.find('.show_hide_elements.'+selected_option.attr('data-class-to-show')).removeClass('d-none');
		});


		$('input.numeric').keydown(function(e){
			var key = e.charCode || e.keyCode || 0;
			return (
				key == 8 || 
				key == 110 || 
				key == 190 || 
				key == 9 ||
				key == 13 ||
				key == 46 ||
				(key >= 35 && key <= 40) ||
				(key >= 48 && key <= 57) ||
				(key >= 96 && key <= 105));
		});

		if($(".btn-move-part-parent").length){
			$(".btn-move-part-parent").each(function(){
				$(this).sortable( {
					dropOnEmpty: false,
					cursor: "move",
					handle: ".btn-move-part",
					update: function( event, ui ) {
						$(this).children().each(function(index) {
							$(this).find('input.sort_order').val(index + 1);
						});
					}
				});
			})
		}


		
		if($("#faq-container").length){
			$("#faq-container").sortable( {
				dropOnEmpty: false,
				cursor: "move",
				handle: ".btn-movefaq-part",
				update: function( event, ui ) {
					$(this).children().each(function(index) {
						$(this).find('input.sort_order').val(index + 1);
					});
				}
			});
		}
		
		$('.add-template-btn').click(add_template_block);
		
		$('.single-image-upload').fileupload(singleImageUploadOptions);
		$('.multiple-image-upload').fileupload(multipleImageUploadOptions);
		
		$(document).on("click", ".single-image-upload-btn", function () {
			$(this).parent().find('.single-image-upload').trigger('click');
			return false;
		})
		
		$(document).on("click", ".multiple-image-upload-btn", function () {
			$(this).parent().find('.multiple-image-upload').trigger('click');
			return false;
		})
		
		if($('#mypickatime_unpublish_t').length){
			$('#mypickatime_unpublish_t').AnyTime_picker({
				input: $('.pickatime-format'),
				format: '%H:%i:%s'
			});
		}
		
		if($('select.select2cont').length){
			$('.select2cont').select2({
				minimumResultsForSearch: Infinity
			});
		}
		$('select.fill_select').change(function(){
			var id = $(this).val();
			$('select[name="'+$(this).attr('data-target')+'"]').val('0');
			$('select[name="'+$(this).attr('data-target')+'"] option').hide();
			$('select[name="'+$(this).attr('data-target')+'"] option[data-fill-id="'+id+'"]').show();
			$('select[name="'+$(this).attr('data-target')+'"] option[data-fill-id="0"]').show();
		});
		
		$('.modal').on("hidden.bs.modal", function (e) { 
			if ($('.modal:visible').length) { 
				$('body').addClass('modal-open');
			}
		});

		$('form.minmax_form').submit(function(e){
			var error = false;
			$('input.minmax').removeClass('border-danger');
			$('input.minmax').each(function(i,v){
				var vv = parseFloat($(this).val());
				var mi = 0;
				var ma = 1000;
				if($(this).attr('data-min')) mi = parseFloat($(this).attr('data-min'));
				if($(this).attr('data-max')) ma = parseFloat($(this).attr('data-max'));
				if(vv < mi || vv > ma){
					error = true;
					$(this).addClass('border-danger');
				}
			});
			
			if(error){
				e.preventDefault();
				swal({
					title: 'Πρόβλημα',
					text: 'Υπάρχει λάθος στις απόδοσεις ή τα πονταρίσματα!',
					icon: 'warning',
					timer: 6000
				});
				return false;
			}
			return true;
		});
		
		if($('.form-check-input-switch').length){
			jQuery.ajax({
				url: base_url+"assets/js/switch.min.js",
				dataType: "script",
				cache: true
			}).done(function() {
				$('.form-check-input-switch').bootstrapSwitch();
			});
		}

		$('form#tinymce_form').submit(function(e){
			
			var error = false;
			var error_msg = '';
			
			if($(this).hasClass('slide_form')){
				$('#body-parts-container .slide_item').each(function(){
					if(!($(this).find('input.post_type').val() != '' && $(this).find('input.post_id').val() != '')){
						//console.log($(this).find('input.post_id').attr('name') + '-' + $(this).find('input.post_type').attr('name'));
						if(!$(this).find('input.custom_title').val() || !$(this).find('input.custom_url').val()){
							error = true;
							error_msg = 'Επιλέξτε ανάλυση, άρθρο ή βάλτε custom κείμενο και url για όλα τα slides! ';error_msg += "\n";
						}
					}
					if(!$(this).find('input.date1').val() || !$(this).find('input.time1').val()){
						error = true;
						error_msg += 'Επιλέξτε ημ/νίες δημοσίευσης για όλα τα slides! '+$(this).find('input.date1').attr('id');error_msg += "\n";
					}
					if(!$(this).find('input.date2').val() || !$(this).find('input.time2').val()){
						error = true;
						error_msg += 'Επιλέξτε ημ/νίες αποδημοσίευσης για όλα τα slides! ';error_msg += "\n";
					}
					if(!$(this).find('input.image_id-input').val()){
						error = true;
						error_msg += 'Επιλέξτε εικόνα για όλα τα slides! ';error_msg += "\n";
					}
				});
			}
			if($(this).hasClass('mission_form')){
				if(!$(this).find('input[name="starts_at_date"]').val() || !$(this).find('input[name="starts_at_time"]').val()){
					error = true;
					error_msg += 'You have to select the "Starts at" date and time.';error_msg += "\n";
				}
				if(!$(this).find('input[name="expires_at_date"]').val() || !$(this).find('input[name="expires_at_time"]').val()){
					error = true;
					error_msg += 'You have to select the "Expires at" date and time.';error_msg += "\n";
				}
			}
			if(error){
				swal({
					title: 'Error',
					text: error_msg,
					icon: 'warning',
					timer: 6000
				});
				e.preventDefault();
				return false;
			}
			return true;
		});
		
	
	}

    exports.init = init;
	
	return exports;
}
var editingFunctions = new EditingFunctions();

$(document).ready(function() {
	editingFunctions.init();
});
