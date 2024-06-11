/*
// Register the plugin within the editor.
( function() {

	CKEDITOR.plugins.add( 'fdivider', {

		// Register the icons.
		icons: 'fdivider',

		// The plugin initialization logic goes inside this method.
		init: function( editor ) {

			editor.addCommand( 'fdivider',  {
				exec: function( editor ) {
					editor.insertHtml( '<div class="divider" contenteditable="false"></div>' );
				}
			} );
			editor.ui.addButton( 'Fdivider', {

				// The text part of the button (if available) and the tooltip.
				label: 'Add divider',

				// The command to execute on click.
				command: 'fdivider',

				// The button placement in the toolbar (toolbar group name).
				toolbar: 'shortcodes,20'
			});
			
		}
	});
} )();*/
CKEDITOR.plugins.add( 'fdivider', {
	requires: 'widget',
	icons: 'fdivider',
	init: function( editor ) {
		editor.widgets.add( 'fdivider', {
			allowedContent: 'div(!divider)',
			requiredContent: 'div(divider)',
			editables: {},
			template:
				'<div class="divider"></div>',
			button: 'Add custom divider',
			//toolbar: 'shortcodes,30',
			upcast: function( element ) {
				return element.name == 'div' && element.hasClass( 'divider' );
			},
			init: function() {
				
			},
		} );
	}
} );
