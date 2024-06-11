/**
 * Copyright (c) 2014-2020, CKSource - Frederico Knabben. All rights reserved.
 * Licensed under the terms of the MIT License (see LICENSE.md).
 *
 * Basic sample plugin inserting abbreviation elements into the CKEditor editing area.
 *
 * Created out of the CKEditor Plugin SDK:
 * https://ckeditor.com/docs/ckeditor4/latest/guide/plugin_sdk_sample_1.html
 */

// Register the plugin within the editor.
( function() {

	CKEDITOR.plugins.add( 'customlists', {

		// Register the icons.
		icons: 'customlists',

		// The plugin initialization logic goes inside this method.
		init: function( editor ) {

			// Define an editor command that opens our dialog window.
			editor.addCommand( 'customlists', new CKEDITOR.dialogCommand( 'checklistDialog' ) );

			// Create a toolbar button that executes the above command.
			editor.ui.addButton( 'Customlists', {

				// The text part of the button (if available) and the tooltip.
				label: 'Add custom list',

				// The command to execute on click.
				command: 'customlists',

				// The button placement in the toolbar (toolbar group name).
				toolbar: 'shortcodes,10'
			});

			/*if ( editor.contextMenu ) {

				// Add a context menu group with the Edit Abbreviation item.
				editor.addMenuGroup( 'checklistGroup' );
				editor.addMenuItem( 'checklistItem', {
					label: 'Edit custom list',
					icon: this.path + 'icons/checklist.png',
					command: 'checklist',
					group: 'checklistGroup'
				});

				editor.contextMenu.addListener( function( element ) {
					if ( element.getAscendant( 'ul', true ) ) {
						return { checklistItem: CKEDITOR.TRISTATE_OFF };
					}
				});
			}*/

			// Register our dialog file -- this.path is the plugin folder path.
			CKEDITOR.dialog.add( 'checklistDialog', this.path + 'dialogs/customlists.js?v=5' );
		}
	});
} )();