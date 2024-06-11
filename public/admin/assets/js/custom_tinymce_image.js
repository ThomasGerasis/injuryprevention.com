/**
* Author: BOUBOU
*/
var TinymceImageFunctions = function()
{
	var exports = {};
	var base_url = config.base_url;
	var width_ = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
	
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
			
			var img = document.createElement('img');
			img.src = data.result.file_name.replace("rct200n", "original");
			img.onload = function () {
				var width = Math.max(parseInt(img.width, 10), parseInt(img.clientWidth, 10));
				var height = Math.max(parseInt(img.height, 10), parseInt(img.clientHeight, 10));
				$('#image_form input[name="width"]').val(width);
				$('#image_form input[name="width"]').attr('data-ratio',height/width);
				$('#image_form input[name="height"]').val(height);
				$('#image_form input[name="height"]').attr('data-ratio',width/height);
				$('#image_form .actions').removeClass('d-none');
			};
			
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
	
	function image_clicked() {
		var tt = $('#image_modal #image_Bank_form');
		var target = tt.attr('data-target');
		var part = $.tmpl($('#'+tt.attr('data-template')).html(), {
			input_name: tt.attr('data-input-name')
		});
		$(target).html(part);
		$(target).find('img.img-filename').attr('src',$(this).attr('src').replace("rct200n", "original"));
		$(target).find('input.image_id-input').val($(this).attr('data-image-id'));
		$('input[name="title"]').val($(this).attr('data-title'));
		$('input[name="alt"]').val($(this).attr('data-alt'));
		var img = document.createElement('img');
		img.src = $(this).attr('src').replace("rct200n", "original");
		img.onload = function () {
			var width = Math.max(parseInt(img.width, 10), parseInt(img.clientWidth, 10));
			var height = Math.max(parseInt(img.height, 10), parseInt(img.clientHeight, 10));
			$('input[name="width"]').val(width);
			$('input[name="width"]').attr('data-ratio',height/width);
			$('input[name="height"]').val(height);
			$('input[name="height"]').attr('data-ratio',width/height);
			$('#image_form .actions').removeClass('d-none');
		};
		$('#image_modal').modal('hide');
		$('#image_modal .modal-body').html('');
	}
	
	function init(){
		$('.single-image-upload-btn').click(function () {
			$(this).parent().find('.single-image-upload').trigger('click');
			return false;
		});
		$('#image_form .single-image-upload').fileupload(singleImageUploadOptions);
		
		$('input[name="width"]').on("keyup", function(e){
			if ($(this).attr('data-ratio')) {
			  var nh = Math.ceil($(this).val() * $(this).attr('data-ratio'));
			  $('input[name="height"]').val(nh);
			}
		});
		$('input[name="height"]').on("keyup", function(e){
			if ($(this).attr('data-ratio')) {
			  var nh = Math.ceil($(this).val() * $(this).attr('data-ratio'));
			  $('input[name="width"]').val(nh);
			}
		});
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
		
		$('form#image_form').submit(function(e){
			e.preventDefault();
			var ifloat = $('form#image_form select.image_render').val();
			var src = $('form#image_form img.img-fluid').attr('src').replace("rct200n", "original");
			
			var iclass = 'd-block';
			if(ifloat == 'FLOAT_RIGHT'){
				iclass = 'float-right ml-2 mb-2';
			}else if(ifloat == 'FLOAT_RIGHT_MD'){
				iclass = 'float-md-right mr-auto ml-auto mr-md-0 ml-md-3 mb-2';
			}else if(ifloat == 'FLOAT_LEFT'){
				iclass = 'float-left mr-2 mb-2';
			}else if(ifloat == 'FLOAT_LEFT_MD'){
				iclass = 'float-md-left mr-auto ml-auto ml-md-0 mr-md-3 mb-2';
			}
			
			var content = '<img class="img-fluid '+iclass+'" src="'+src+'" title="'+$('form#image_form input[name="title"]').val()+'" alt="'+$('form#image_form input[name="alt"]').val()+'">';
			if($('input[name="width"]').val() && $('input[name="height"]').val()){
				content = '<img class="img-fluid '+iclass+'" src="'+src+'" title="'+$('form#image_form input[name="title"]').val()+'" alt="'+$('form#image_form input[name="alt"]').val()+'" width="'+$('form#image_form input[name="width"]').val()+'" height="'+$('form#image_form input[name="height"]').val()+'">';
			}
			
			window.parent.postMessage({
				mceAction: 'insertContent',
				content: content
			}, '*');
			
			window.parent.postMessage({
				mceAction: 'close'
			}, '*');
			
			return false;
		});
	}

    exports.init = init;
	
	return exports;
}
var tinymceImageFunctions = new TinymceImageFunctions();

$(document).ready(function() {
	tinymceImageFunctions.init();
});
