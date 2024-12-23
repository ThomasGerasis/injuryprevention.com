/**
 * Copyright (c) 2014-2020, CKSource - Frederico Knabben. All rights reserved.
 * Licensed under the terms of the MIT License (see LICENSE.md).
 *
 * The abbr plugin dialog window definition.
 *
 * Created out of the CKEditor Plugin SDK:
 * https://ckeditor.com/docs/ckeditor4/latest/guide/plugin_sdk_sample_1.html
 */

// Our dialog definition.
CKEDITOR.dialog.add( 'checklistDialog', function( editor ) {
	return {

		// Basic properties of the dialog window: title, minimum size.
		title: 'Custom List Dialog Properties',
		minWidth: 400,
		minHeight: 200,

		// Dialog window content definition.
		contents: [
			{
				// Definition of the Basic Settings dialog tab (page).
				id: 'tab-basic',
				label: 'Basic Settings',

				// The tab content.
				elements: [
					{
						// Text input field for the abbreviation text.
						type: 'select',
						id: 'listtype',
						label: 'Type',
						items: [['Check list','checklist'],['Star list','starlist'],['Cross list','xlist']],

						// Validation checking whether the field is not empty.
						validate: CKEDITOR.dialog.validate.notEmpty( "Type field cannot be empty." ),

						// Called by the main setupContent method call on dialog initialization.
						setup: function( element ) {
							this.setValue( element.getAttribute('data-class') );
						},

						// Called by the main commitContent method call on dialog confirmation.
						commit: function( element ) {
							element.removeAttribute('class');
							element.setAttribute( 'data-class', this.getValue() );
							element.addClass( this.getValue() );
							//element.setHtml('<ul><li>sadas</li></ul>'); 
//							element.html("<ul><li></li></ul>"); 
						}
					}
				]
			},
		],

		// Invoked when the dialog is loaded.
		onShow: function() {

			// Get the selection from the editor.
			var selection = editor.getSelection();

			// Get the element at the start of the selection.
			var element = selection.getStartElement();

			// Get the <abbr> element closest to the selection, if it exists.
			if ( element )
				element = element.getAscendant( 'ul', true );
			
			// Create a new <abbr> element if it does not exist.
			if ( !element || element.getName() != 'ul' ) {
				element = editor.document.createElement( 'ul' );
				element.setHtml( '<li>text...</li>' );
				// Flag the insertion mode for later use.
				this.insertMode = true;
			}
			else
				this.insertMode = false;

			// Store the reference to the <abbr> element in an internal property, for later use.
			this.element = element;

			// Invoke the setup methods of all dialog window elements, so they can load the element attributes.
			if ( !this.insertMode )
				this.setupContent( this.element );
		},

		// This method is invoked once a user clicks the OK button, confirming the dialog.
		onOk: function() {

			// Create a new <abbr> element.
			var ul = this.element;

			// Invoke the commit methods of all dialog window elements, so the <abbr> element gets modified.
			this.commitContent( ul );

			// Finally, if in insert mode, insert the element into the editor at the caret position.
			if ( this.insertMode )
				editor.insertElement( ul );
		}
	};
});
