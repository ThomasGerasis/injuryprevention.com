( function() {
	CKEDITOR.plugins.add( 'golink', {
		icons: 'golink',
		init: function( editor ) {
			editor.addCommand( 'golink', new CKEDITOR.dialogCommand( 'golinkDialog' ) );
			editor.ui.addButton( 'Golink', {
				label: 'Add a go button',
				command: 'golink',
				toolbar: 'shortcodes,20'
			});
			CKEDITOR.dialog.add( 'golinkDialog', this.path + 'dialogs/golink.js?v=13' );
		}
	});
} )();