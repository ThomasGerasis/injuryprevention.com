/**
* Author: SilkTech SA
*/
var LockFunctions = function()
{
	var exports = {};
	var base_url = config.base_url;
	var edit_interval;
	var type;
	var type_id;
	
	function init(){
		if($('#edit_type').length){
			type = $('#edit_type').val();
			type_id = $('#edit_type_id').val();
			edit_back_url = $('#edit_back_url').val();
			edit_interval = setInterval(function(){
				$.post(base_url+'admin/ajaxData/editLock',
					{type:type,type_id:type_id}, 
					function (data) {
						if(data.resp){
						}else{
							swal({
								title: 'Πρόβλημα',
								text: data.msg,
								showCancelButton: false,
								allowOutsideClick: false,
							}).then(function(text) {
								window.location.href = edit_back_url;
							}, function (dismiss) {
								window.location.href = edit_back_url;
							});
							setTimeout(function(){
								window.location.href = edit_back_url;
							},3000);
						}
					},
					"json"
				)
			},15*1000);
		}
	}

    exports.init = init;
	
	return exports;
}
let lockFunctions = new LockFunctions();

$(document).ready(function() {
	lockFunctions.init();
});
