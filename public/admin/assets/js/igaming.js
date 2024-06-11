/**
* Author: BoUbOu
*/
var iGamingFunctions = function()
{
	var exports = {};
	var base_url = config.base_url;
	var topMenuHeight = $('.navbar.fixed-top').outerHeight();
	var width_ = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
	var ctrlDown = false;
	function resize(){
		width_ = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
		topMenuHeight = $('.navbar.fixed-top').outerHeight();
	}
	exports.resize = resize;
	
	function scrolling(){
		var fromTop = $(this).scrollTop();
		if ($(window).scrollTop() > 500) {
			$("#scroll-toper").fadeIn();
		} else {
			$("#scroll-toper").fadeOut();
		}
	}
	exports.scrolling = scrolling;
	
	
	function image_clicked() {
		var tt = $('#image_modal #image_Bank_form');
		var target = tt.attr('data-target');
		var part = $.tmpl($('#'+tt.attr('data-template')).html(), {
			input_name: tt.attr('data-input-name')
		});
		$(target).html(part);
		$(target).find('img.img-filename').attr('src',$(this).attr('src').replace("rct200n", "original"));
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
	

	function init(){
		$('[data-toggle="tooltip"]').tooltip();
		
		$(document).on("keydown", ".int-input", function(e){
			var key = e.charCode || e.keyCode || 0;
			if(key == 17 || key == 91) ctrlDown = true;
			return (
				(ctrlDown && key == 86) || //paste
				(ctrlDown && key == 67) ||  //copy
				key == 8 || 
				key == 9 ||
				key == 13 ||
				key == 46 ||
				(key >= 35 && key <= 40) ||
				(key >= 48 && key <= 57) ||
				(key >= 96 && key <= 105));
		});
		$(document).on("keyup", ".int-input", function(e){
			ctrlDown = false;
		});
		
		$(document).on("keydown", ".float-input", function(e){
			var key = e.charCode || e.keyCode || 0;
			if(key == 17 || key == 91) ctrlDown = true;
			return (
				(ctrlDown && key == 86) || //paste
				(ctrlDown && key == 67) ||  //copy
				key == 190 || 
				key == 188 || 
				key == 110 || 
				key == 8 || 
				key == 9 ||
				key == 13 ||
				key == 46 ||
				(key >= 35 && key <= 40) ||
				(key >= 48 && key <= 57) ||
				(key >= 96 && key <= 105));
		});
		
		$(document).on("keyup", ".float-input", function(e){
			ctrlDown = false;
			if($(this).val) $(this).val($(this).val().replace(",", "."));
		});
		$('.show-simple-modal-btn').click(function(){
			var modal_id = 'simple_modal';
			if($(this).attr('data-modal-id')){
				modal_id = $(this).attr('data-modal-id');
			}
			$('#'+modal_id+' .modal-title').html($(this).attr('data-modal-title'));
			$('#'+modal_id+' .modal-body').html('<div class="text-center my=3"><img src="'+base_url+'assets/img/ajax-loader.gif"></div>');
			$.post($(this).attr('href'), 
				[], 
				function (data) {
					$('#'+modal_id+' .modal-body').html(data);
				},
				"html"
			);
			$('#'+modal_id).modal('show');
			return false;
		});
		
		$('.confirm-action').click(function(){
			var confirmation_text = $(this).attr('data-confirmation');
			var url = $(this).attr('href');
			swal({
				title: "Be careful!",
				text: confirmation_text,
				showCancelButton: true,
				confirmButtonClass: 'btn-warning',
				confirmButtonText: "Yes, I do!",
				cancelButtonText: "No, don't do it.",
				allowOutsideClick: false,
			}).then(function(text) {
				window.location.href = url;
			}, function (dismiss) {
				
			});
			return false;
		});
		
		$("#scroll-toper").click(function() {
			$('html,body').animate({scrollTop: 0},'slow');
			return false;
		});
		
		$('.gotoanchor').click(function(){
			var aid = $(this).attr("href");
			$('html,body').animate({scrollTop: $(aid).offset().top - topMenuHeight},'slow');
			return false;
		});
		
		$('.submit-url-selector').click(function(e){
			var p = $(this).parents('.input-group');
			var s = p.find('select');
			if(!s.val()){
				swal({
					title: 'Πρόβλημα',
					text: 'Παρακαλώ επιλέξτε '+$(this).attr('data-error')+'.',
					icon: 'warning',
					timer: 3000
				});
				return false;
			}
			window.location = s.attr('data-url')+'/'+s.val();
		});

		$(document).on("click", ".open-image-bank", function () {
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

    exports.init = init;
	
	return exports;
}
var iGamingFunctions = new iGamingFunctions();

$(document).ready(function() {
	iGamingFunctions.init();
	
	$(window).on('resize', function() {
		iGamingFunctions.resize();
	});

	$(window).scroll(function() {
		iGamingFunctions.scrolling();
	});
	
});
