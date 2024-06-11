( function() {
	CKEDITOR.plugins.add( 'goanchor', {
		icons: 'goanchor',
		init: function( editor ) {
			editor.addCommand( 'goanchor', new CKEDITOR.dialogCommand( 'goanchorDialog' ) );
			editor.ui.addButton( 'Goanchor', {
				label: 'Add a go link',
				command: 'goanchor',
				toolbar: 'shortcodes,21'
			});
			CKEDITOR.dialog.add( 'goanchorDialog', this.path + 'dialogs/goanchor.js?v=13' );
		}
	});
} )();