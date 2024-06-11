CKEDITOR.plugins.add( 'fbox', {
	requires: 'widget',
	icons: 'fbox',
	init: function( editor ) {
		CKEDITOR.dialog.add( 'fbox', this.path + 'dialogs/fbox.js?v=2' );
		editor.widgets.add( 'fbox', {
			allowedContent:
				'div(!fbox,align-left,align-right,align-center,shadow,shadow2,info,success,warning,error,download,note){width};' +
				'div(!fbox-content)',
			requiredContent: 'div(fbox)',
			editables: {
				content: {
					selector: '.fbox-content',
					allowedContent: 'p br ul ol li strong h3 h4 em span a[!href,alt,title,class,target,rel]'
				}
			},
			template:
				'<div class="fbox">' +
					'<div class="fbox-content"><p>Content...</p></div>' +
				'</div>',
			button: 'Create a custom box',
			//toolbar: 'shortcodes,30',
			dialog: 'fbox',
			upcast: function( element ) {
				return element.name == 'div' && element.hasClass( 'fbox' );
			},
			init: function() {
				var width = this.element.getStyle( 'width' );
				if ( width )
					this.setData( 'width', width );

				if ( this.element.hasClass( 'align-left' ) )
					this.setData( 'align', 'left' );
				if ( this.element.hasClass( 'align-right' ) )
					this.setData( 'align', 'right' );
				if ( this.element.hasClass( 'align-center' ) )
					this.setData( 'align', 'center' );
						
				if ( this.element.hasClass( 'shadow' ) )
					this.setData( 'tclass', 'shadow' );
				if ( this.element.hasClass( 'shadow2' ) )
					this.setData( 'tclass', 'shadow2' );
				if ( this.element.hasClass( 'info' ) )
					this.setData( 'tclass', 'info' );
				if ( this.element.hasClass( 'success' ) )
					this.setData( 'tclass', 'success' );
				if ( this.element.hasClass( 'warning' ) )
					this.setData( 'tclass', 'warning' );
				if ( this.element.hasClass( 'error' ) )
					this.setData( 'tclass', 'error' );
				if ( this.element.hasClass( 'download' ) )
					this.setData( 'tclass', 'download' );
				if ( this.element.hasClass( 'note' ) )
					this.setData( 'tclass', 'note' );
			},
			data: function() {
				if ( this.data.width == '' )
					this.element.removeStyle( 'width' );
				else
					this.element.setStyle( 'width', this.data.width );
				this.element.removeClass( 'align-left' );
				this.element.removeClass( 'align-right' );
				this.element.removeClass( 'align-center' );
				if ( this.data.align )
					this.element.addClass( 'align-' + this.data.align );
				
				this.element.removeClass( 'shadow' );
				this.element.removeClass( 'shadow2' );
				this.element.removeClass( 'info' );
				this.element.removeClass( 'success' );
				this.element.removeClass( 'warning' );
				this.element.removeClass( 'error' );
				this.element.removeClass( 'download' );
				this.element.removeClass( 'note' );
				if ( this.data.tclass )
					this.element.addClass( this.data.tclass );
			}
		} );
	}
} );
