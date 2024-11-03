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
			$('#'+target+' .loading').html('<img src="'+config.base_url+'admin/assets/img/sp-loading.gif"> Please wait...');
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
			$('#'+target+' .loading').html('<img src="'+config.base_url+'admin/assets/img/sp-loading.gif"> Please wait...');
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
