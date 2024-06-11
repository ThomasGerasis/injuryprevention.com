( function() {
	CKEDITOR.plugins.add( 'analysilink', {
		icons: 'analysilink',
		init: function( editor ) {
			editor.addCommand( 'analysilink', new CKEDITOR.dialogCommand( 'analysilinkDialog' ) );
			editor.ui.addButton( 'Analysilink', {
				label: 'Εισαγωγή ανάλυσης',
				command: 'analysilink',
				toolbar: 'shortcodes,20'
			});
			CKEDITOR.dialog.add( 'analysilinkDialog', this.path + 'dialogs/analysilink.js?v=3' );
		}
	});
} )();