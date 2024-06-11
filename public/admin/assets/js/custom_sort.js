/**
* Author: SilkTech SA
*/
var CustomSortFunctions = function()
{
	var exports = {};
	var base_url = config.base_url;
	
	
	function init(){
		$(".items-sortable").each(function(){
			$(this).sortable( {
				dropOnEmpty: false,
				cursor: "move",
				//handle: ".btn-move-image",
				items: '.card',
				update: function( event, ui ) {
					$(this).children().each(function(index) {
						$(this).find('input.sort_order').val(index + 1);
						$(this).find('span.order_num').html(index + 1);
					});
				}
			});
		});
	}

    exports.init = init;
	
	return exports;
}
var customSortFunctions = new CustomSortFunctions();

$(document).ready(function() {
	customSortFunctions.init();
});
