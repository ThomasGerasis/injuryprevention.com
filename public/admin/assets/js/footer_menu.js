/**
 * Author: SilkTech SA
 */

// import {dataset} from "./tinymce/modules/alloy/src/main/ts/ephox/alloy/behaviour/representing/RepresentState";

var EditingFunctions = function()
{
    var exports = {};


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

    //add-footer-menu-item
    function add_footer_menu_block(){
        var target = $('#'+$(this).attr('data-target'));
        var template = $(this).attr('data-template');
        var origin_counter = $(this).attr('data-origin-counter');
        var counter = parseInt($('input#'+$(this).attr('data-counter')).val());

        var part_attrs = {};
        part_attrs["origin_counter"] = origin_counter;
        part_attrs["counter"] = counter;
        part_attrs["order"] = counter;
        
        var new_part = $.tmpl($('#'+template).html(), part_attrs);
        $('input#'+$(this).attr('data-counter')).val(counter + 1);
        target.append(new_part);
        var container = target.find('.card').last();
        container.find('select.link-selector').val('link');
        container.find('.single-image-upload').fileupload(singleImageUploadOptions);
        
        return false;
    }

    function init(){

        $(".add-menu-item").click(function(){

            var target = $('#'+$(this).attr('data-target'));
		    var template = $(this).attr('data-template');
		    var counter = parseInt($('input#'+$(this).attr('data-counter')).val());

            var part_attrs = {};
			part_attrs["counter"] = counter;
			part_attrs["order"] = counter;
			
			var new_part = $.tmpl($('#'+template).html(), part_attrs);
			$('input#'+$(this).attr('data-counter')).val(counter + 1);
			target.append(new_part);
			var container = target.find('.card').last();
			container.find('select.link-selector').val('link');
			container.find('.single-image-upload').fileupload(singleImageUploadOptions);
            container.find('.add-footer-menu-item').click(add_footer_menu_block);
            container.find(".menu-link-container").sortable( {
                dropOnEmpty: false,
                cursor: "move",
                handle: ".btn-movetoc-part",
                start: function( event, ui ) {
    
                },
                helper: function (event, item) {
                    if(item.hasClass('main_link')){
                        $.each(item.nextAll('.link-card'),function(){
                            if($(this).hasClass('main_link')) return false;
                            $(this).addClass('div_to_clone');
                        })
                    }
                    return item.clone();
                },
                stop: function (event, ui) {
                    if($('.div_to_clone').length){
                        let $selected = $(this).children(".div_to_clone");
                        $selected.removeClass('div_to_clone');
                        ui.item.after($selected);
                    }
                    $('.div_to_clone').remove();
                    $(this).children('.link-card').each(function(index) {
                        $(this).find('input.sort_order').val(index + 1);
                    });
                }
            });
            
			$('html,body').animate({scrollTop: container.offset().top - $('.navbar:first').outerHeight() - 10},'slow');
			
			return false;
		});
        

        $(".add-footer-menu-item").click(add_footer_menu_block);


        $('.single-image-upload').fileupload(singleImageUploadOptions);
        $(document).on("click", ".single-image-upload-btn", function () {
            $(this).parent().find('.single-image-upload').trigger('click');
            return false;
        })


        $(".menu-link-container").sortable({
            dropOnEmpty: false,
            cursor: "move",
            handle: ".btn-movetoc-part",
            start: function( event, ui ) {

            },
            helper: function (event, item) {
                if(item.hasClass('main_link')){
                    $.each(item.nextAll('.link-card'),function(){
                        if($(this).hasClass('main_link')) return false;
                        $(this).addClass('div_to_clone');
                    })
                }
                return item.clone();
            },
            stop: function (event, ui) {
                if($('.div_to_clone').length){
                    let $selected = $(this).children(".div_to_clone");
                    $selected.removeClass('div_to_clone');
                    ui.item.after($selected);
                }
                $('.div_to_clone').remove();
                $(this).children('.link-card').each(function(index) {
                    $(this).find('input.sort_order').val(index + 1);
                });
            }
        });


        $(document).on("change", "select.link-selector", function () {
            $(this).parents('.link-card').removeClass('ml-3').removeClass('ml-5').removeClass('main_link').addClass($(this).find('option:selected').attr('data-class'));
            return false;
        })



        $("#menus-container").sortable({
            dropOnEmpty: false,
            cursor: "move",
            handle: ".btn-movetoc-part",
            start: function( event, ui ) {

            },
            helper: function (event, item) {
                if(item.hasClass('main_link')){
                    $.each(item.nextAll('.menu-card'),function(){
                        $(this).addClass('div_to_clone');
                    })
                }
                return item.clone();
            },
            stop: function (event, ui) {
                if($('.div_to_clone').length){
                    var $selected = $('#menus-container').children(".div_to_clone");

                    $selected.removeClass('div_to_clone');
                    ui.item.after($selected);
                }
                $('.div_to_clone').remove();
                $('#menus-container').children('.menu-card').each(function(index) {
                    $(this).find('input.sort_order').val(index + 1);
                });
                console.log('stop');
            }
        });
    }

    exports.init = init;

    return exports;
}

let editingFunctions = new EditingFunctions();

$(document).ready(function() {
    editingFunctions.init();
});
