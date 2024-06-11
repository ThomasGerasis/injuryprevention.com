/*( function() {
	CKEDITOR.plugins.add( 'golink', {
		icons: 'golink',
		init: function( editor ) {
			editor.addCommand( 'golink', new CKEDITOR.dialogCommand( 'golinkDialog' ) );
			editor.ui.addButton( 'Golink', {
				label: 'Add a go button',
				command: 'golink',
				toolbar: 'shortcodes,20'
			});
			CKEDITOR.dialog.add( 'golinkDialog', this.path + 'dialogs/golink.js?v=5' );
		}
	});
} )();
*/
CKEDITOR.plugins.add( 'golink', {
	requires: 'widget',
	icons: 'golink',
	init: function( editor ) {
		CKEDITOR.dialog.add( 'golinkDialog', this.path + 'dialogs/golink.js' );
		editor.widgets.add( 'golink', {
			allowedContent: 'div(!go)',
			allowedContent:
				'div(!go,align-left,align-right,align-center);' +
				'a(!btn)',
			requiredContent: 'div(go)',
			editables: {
				content: {
					selector: '.btn',
					allowedContent: 'br strong em'
				}
			},
			template:
				'<div class="go"><a class="btn">dafdsfsad</a></div>',
			button: 'Create a go link button',
			dialog: 'golinkDialog',
			upcast: function( element ) {
				return element.name == 'div' && element.hasClass( 'go' );
			},
			init: function() {
				this.element.setHtml('<a class="btn">dafdsAAAAAAAAAAA fsad</a>');
				if ( this.element.hasClass( 'btn-primary' ) )
					this.setData( 'bclass', 'btn-primary' );
				if ( this.element.hasClass( 'btn-success' ) )
					this.setData( 'bclass', 'btn-success' );
				if ( this.element.hasClass( 'btn-info' ) )
					this.setData( 'bclass', 'btn-info' );
				if ( this.element.hasClass( 'btn-warning' ) )
					this.setData( 'bclass', 'btn-warning' );
				if ( this.element.hasClass( 'btn-danger' ) )
					this.setData( 'bclass', 'btn-danger' );
				
				if ( this.element.hasClass( 'btn-xs' ) )
					this.setData( 'size', 'btn-xs' );
				if ( this.element.hasClass( 'btn-md' ) )
					this.setData( 'size', 'btn-md' );
				if ( this.element.hasClass( 'btn-lg' ) )
					this.setData( 'size', 'btn-lg' );
				
				if ( this.element.hasClass( 'd-block' ) )
					this.setData( 'display', 'd-block' );
				if ( this.element.hasClass( 'd-inline-block' ) )
					this.setData( 'display', 'd-inline-block' );
				
				if ( this.element.getAttribute( 'href' ) )
					this.setData( 'href', this.element.getAttribute( 'href' ) );
			},
			data: function() {
				this.element.removeClass( 'btn-primary' );
				this.element.removeClass( 'btn-success' );
				this.element.removeClass( 'btn-info' );
				this.element.removeClass( 'btn-warning' );
				this.element.removeClass( 'btn-danger' );
				if ( this.data.bclass )
					this.element.addClass( this.data.bclass );
				
				this.element.removeClass( 'btn-xs' );
				this.element.removeClass( 'btn-md' );
				this.element.removeClass( 'btn-lg' );
				if ( this.data.size )
					this.element.addClass( this.data.size );
				
				this.element.removeClass( 'd-block' );
				this.element.removeClass( 'd-inline-block' );
				if ( this.data.display )
					this.element.addClass( this.data.display );
				
				if ( this.data.href )
					this.element.setAttribute( 'href', this.data.href );
			}
		} );
	}
} );
