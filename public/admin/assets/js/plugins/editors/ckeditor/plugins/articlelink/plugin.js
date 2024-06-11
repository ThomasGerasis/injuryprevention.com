( function() {
	CKEDITOR.plugins.add( 'articlelink', {
		icons: 'articlelink',
		init: function( editor ) {
			editor.addCommand( 'articlelink', new CKEDITOR.dialogCommand( 'articlelinkDialog' ) );
			editor.ui.addButton( 'Articlelink', {
				label: 'Εισαγωγή άρθρου',
				command: 'articlelink',
				toolbar: 'shortcodes,20'
			});
			CKEDITOR.dialog.add( 'articlelinkDialog', this.path + 'dialogs/articlelink.js?v=3' );
		}
	});
} )();