/**
* Author: BOUBOU
*/
var TinymceDialogFunctions = function()
{
	var exports = {};
	var base_url = config.base_url;
	var width_ = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
	
	function init_multiple_tokeninput()
	{
		$(this).tokenInput($(this).attr('data-ajax-function'), {
			prePopulate: $.parseJSON('['+$(this).attr('data-init-token')+']'),
			preventDuplicates: true,
			tokenDelimiter: '__token|token__'
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
	
	
	function init(){
		
		if($('#shcontent').length){
			window.parent.postMessage({
				mceAction: 'GetContent'
			}, '*');
			window.addEventListener('message', function (event) {
				var data = event.data;
				//console.log(data.message);
				$.post(config.base_url+'admin/ajaxData/getTinymceShortcodeAttrs/'+$('#shcontent').attr('data-shortcode')+'/1',
					{json_data:data.message},//$.parseJSON(data.message), 
					function (data) {
						$('#shcontent').html(data);
						initData();
					},
					"html"
				);
			});
		}else{
			initData();
		}
	}
	
	function image_clicked(){
		var tt = $('#image_modal #image_Bank_form');
		var target = tt.attr('data-target');
		var part = $.tmpl($('#'+tt.attr('data-template')).html(), {
			input_name: tt.attr('data-input-name')
		});
		$(target).html(part);
		$(target).find('img.img-filename').attr('src',$(this).attr('src'));
		$(target).find('input.image_id-input').val($(this).attr('data-image-id'));
		$('#image_modal').modal('hide');
		$('#image_modal .modal-body').html('');
		$(target).find('[data-action=remove]').on('click', function (e) {
			e.preventDefault();
			var $target = $(this),
				slidingSpeed = 150;
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
	
	function initData(){
		
		$('form#shortcode_form').submit(function(e){
			e.preventDefault();
			if($("#shortcode_form .required_draggable_items").length){
				var container = $("#shortcode_form .required_draggable_items").parents('.multiple_draggable_container');
				if(!container.find('table.multiple_draggable_items tbody tr').length){
					swal('Error','You must select at least one casino!','warning');
					return false;
				}
			}
			$.post(config.base_url+'admin/ajaxData/getJsonShortcodeAttrs',
				$('form#shortcode_form').serializeArray(), 
				function (data) {
					window.parent.postMessage({
						mceAction: 'insertContent',
						content: '<div class="shortcode" contenteditable="false" data-shortcode="'+$('form#shortcode_form input#current_shortcode').val()+'">['+$('form#shortcode_form input#current_shortcode').val()+']'+data+'[/'+$('form#shortcode_form input#current_shortcode').val()+']</div>'+($('#shcontent').length?'':'<p></p>')
					}, '*');
					
					window.parent.postMessage({
						mceAction: 'close'
					}, '*');
				},
				"html"
			);
			return false;
		});
		

		$(document).on("click", "#shortcode_form .add-tab-btn", function (e) {
			var target = $('#'+$(this).attr('data-target'));
			var template = $(this).attr('data-template');
			var counter = parseInt($('input#'+$(this).attr('data-counter')).val());
			var part_attrs = {};
			part_attrs["counter"] = counter;
			part_attrs["order"] = counter;

			var new_part = $.tmpl($('#'+template).html(), part_attrs);
			$('input#'+$(this).attr('data-counter')).val(counter + 1);
			target.append(new_part);
			//target.append(new_part_end);
			var container = target.find('.card').last();

			container.find('.select2cont').select2({
				minimumResultsForSearch: Infinity
			});
			
			container.find('.input-tokeninput').each(init_simple_tokeninput);
			container.find('.input-multiple-tokeninput').each(init_multiple_tokeninput);

			$('html,body').animate({scrollTop: container.offset().top},'slow');
			return false;
		});
		
		$('#shortcode_form .container-tabs').each(function(){
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

		$(document).on("change", "#shortcode_form select.update_edit_link", function (e) {
			var container = $(this).parents('.update_edit_link_container');
			container.find('a.update_edit_link_target').attr('href',($(this).val() ? container.find('a.update_edit_link_target').attr('data-href-prefix')+$(this).val() : '#'));
		});

		$(document).on("click", "#shortcode_form .add_new_multiple_draggable_item", function (e) {
			var container = $(this).parents('.multiple_draggable_container');
			var select_field = container.find('select.new_multiple_draggable_item');
			if (!select_field.val()) return false;
			var new_item_id = select_field.val();
			var selected_option = select_field.find('option[value="' + new_item_id + '"]');
			var item_table = container.find('table.multiple_draggable_items tbody');
			var part_attrs = {};
			part_attrs["input_name"] = $(this).attr('data-input-name');
			part_attrs["item_id"] = new_item_id;
			part_attrs["item_name"] = selected_option.html();
			part_attrs["order_num"] = container.find('table.multiple_draggable_items tbody tr').length + 1;
			select_field.val("");
			selected_option.hide();
			var new_part = $.tmpl($("#new_item_row").html(), part_attrs);
			item_table.append(new_part);
			return false;
		});

		$(document).on("click", "#shortcode_form .btn-remove-item", function (e) {
			var container = $(this).parents('.multiple_draggable_container');
			container.find('select.new_multiple_draggable_item option[value="' + $(this).attr('data-item-id') + '"]').show();
			$(this).parents('tr').remove();
			return false;
		});

		$('#shortcode_form table.multiple_draggable_items tbody').each(function(){
			$(this).sortable( {
				dropOnEmpty: false,
				cursor: "move",
				handle: ".btn-move-item",
				update: function( event, ui ) {
					$(this).children().each(function(index) {
						$(this).find('input.sort_order').val(index + 1);
					});
				}
			});
		});

		if($('#shortcode_form select.select2cont').length){
			$('#shortcode_form .select2cont').select2({
				minimumResultsForSearch: Infinity
			});
		}
		if($('#shortcode_form .pickadate-format').length){
			$('#shortcode_form .pickadate-format').pickadate({
				format: 'yyyy-mm-dd',
				formatSubmit: 'yyyy-mm-dd',
				hiddenName: true,
				hiddenSuffix: ''
				//hiddenPrefix: 'schedule_date',
				//hiddenSuffix: ''
			});
		}
		if($('#shortcode_form .single-image-upload').length){
			$('.single-image-upload-btn').click(function () {
				$(this).parent().find('.single-image-upload').trigger('click');
				return false;
			});
			$('#shortcode_form .single-image-upload').fileupload(singleImageUploadOptions);
			
			$('.open-image-bank').click(function () {
				var p = $(this).parent();
				var tt = p.find('.single-image-upload');
				$.post(base_url+'admin/ajaxData/imageBank',
					[], 
					function (data) {
						$('#image_modal .modal-body').html(data);
						$('#image_modal').modal('show');
						$('#image_modal #image_Bank_form').attr('data-target',tt.attr('data-target'));
						$('#image_modal #image_Bank_form').attr('data-template',tt.attr('data-template'));
						$('#image_modal #image_Bank_form').attr('data-input-name',tt.attr('data-input-name'));
						$('#image_modal .img-fluid').click(image_clicked);
		
						$('#image_modal #next-page').click(function(e){
							var np = parseInt($(this).attr('data-page')) + 1;
							var pp = np - 1;
							if(pp < 1) pp = 1;
							$('#image_modal #prev-page').attr('data-page',pp);
							$(this).attr('data-page',np);
							if(np > 1) $('#image_modal #prev-page').show(); else $('#image_modal #prev-page').hide();
							$.post(base_url+'admin/ajaxData/imageBank',
								{term:$('#image_modal #image_Bank_form input#term').val(),page:np}, 
								function (data) {
									$('#image_modal #image_container').html(data);
									$('#image_modal .img-fluid').click(image_clicked);
								},
								"html"
							);
							return false;
						});
						$('#image_modal #prev-page').click(function(e){
							var np = parseInt($(this).attr('data-page'))-1;
							if(np < 1) np = 1;
							var pp = np + 1;
							$('#image_modal #next-page').attr('data-page',pp);
							$(this).attr('data-page',np);
							if(np == 1) $(this).hide(); else $(this).show();
							$.post(base_url+'admin/ajaxData/imageBank',
								{term:$('#image_modal #image_Bank_form input#term').val(),page:np}, 
								function (data) {
									$('#image_modal #image_container').html(data);
									$('#image_modal .img-fluid').click(image_clicked);
								},
								"html"
							);
							return false;
						});
						$('#image_modal #clear_term').click(function(e){
							$('#image_modal #image_Bank_form input#term').val('');
							$.post(base_url+'admin/ajaxData/imageBank',
								{page:1}, 
								function (data) {
									$('#image_modal #image_container').html(data);
									$('#image_modal .img-fluid').click(image_clicked);
								},
								"html"
							);
							return false;
						});
						$('#image_modal #image_Bank_form').submit(function(e){
							e.preventDefault();
							if(!$('#image_modal #image_Bank_form input#term').val()) return false;
							$.post(base_url+'admin/ajaxData/imageBank',
								{term:$('#image_modal #image_Bank_form input#term').val()}, 
								function (data) {
									$('#image_modal #image_container').html(data);
									$('#image_modal .img-fluid').click(image_clicked);
								},
								"html"
							);
							return false;
						});
					},
					"html"
				);
			});
		}
		
		if($('#shortcode_form .input-tokeninput').length){
			$('#shortcode_form .input-tokeninput').each(init_simple_tokeninput);
		}
		if($('#shortcode_form .input-multiple-tokeninput').length){
			$('#shortcode_form .input-multiple-tokeninput').each(init_multiple_tokeninput);
		}
		
	}

    exports.init = init;
	
	return exports;
}
var tinymceDialogFunctions = new TinymceDialogFunctions();

$(document).ready(function() {
	tinymceDialogFunctions.init();
});
