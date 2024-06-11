( function() {
	CKEDITOR.plugins.add( 'div', {
		icons: 'div', // %REMOVE_LINE_CORE%
		init: function( editor ) {
			var allowed = 'div(*)';
			editor.addCommand( 'div', new CKEDITOR.dialogCommand( 'creatediv',{
				allowedContent: allowed,
				requiredContent: 'div',
				contextSensitive: true,
				contentTransformations: [
					[ 'div: alignmentToStyle' ]
				],
				refresh: function( editor, path ) {
					var context = editor.config.div_wrapTable ? path.root : path.blockLimit;
					this.setState( 'div' in context.getDtd() ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED );
				}
			}) );
			editor.addCommand( 'editdiv', new CKEDITOR.dialogCommand( 'editdiv', { requiredContent: 'div' } ) );
			editor.addCommand( 'removediv', {
				requiredContent: 'div',
				exec: function( editor ) {
					var selection = editor.getSelection(),
						ranges = selection && selection.getRanges(),
						range,
						bookmarks = selection.createBookmarks(),
						walker,
						toRemove = [];

					function findDiv( node ) {
						var div = CKEDITOR.plugins.div.getSurroundDiv( editor, node );
						if ( div && !div.data( 'cke-div-added' ) ) {
							toRemove.push( div );
							div.data( 'cke-div-added' );
						}
					}

					for ( var i = 0; i < ranges.length; i++ ) {
						range = ranges[ i ];
						if ( range.collapsed )
							findDiv( selection.getStartElement() );
						else {
							walker = new CKEDITOR.dom.walker( range );
							walker.evaluator = findDiv;
							walker.lastForward();
						}
					}

					for ( i = 0; i < toRemove.length; i++ )
						toRemove[ i ].remove( true );

					selection.selectBookmarks( bookmarks );
				}
			} );
			editor.ui.addButton( 'Div', {
				label: 'Add a custom box',
				command: 'div',
				//toolbar: 'shortcodes,50'
			} );

			if ( editor.addMenuItems ) {
				editor.addMenuItems( {
					editdiv: {
						label: 'edit',
						command: 'editdiv',
						group: 'div',
						order: 1
					},

					removediv: {
						label: 'remove',
						command: 'removediv',
						group: 'div',
						order: 5
					}
				} );

				if ( editor.contextMenu ) {
					editor.contextMenu.addListener( function( element ) {
						if ( !element || element.isReadOnly() )
							return null;


						if ( CKEDITOR.plugins.div.getSurroundDiv( editor ) ) {
							return {
								editdiv: CKEDITOR.TRISTATE_OFF,
								removediv: CKEDITOR.TRISTATE_OFF
							};
						}

						return null;
					} );
				}
			}
			
			CKEDITOR.dialog.add( 'creatediv', this.path + 'dialogs/div.js?v=4' );
			CKEDITOR.dialog.add( 'editdiv', this.path + 'dialogs/div.js?v=4' );
		}
	} );

	CKEDITOR.plugins.div = {
		getSurroundDiv: function( editor, start ) {
			var path = editor.elementPath( start );
			return editor.elementPath( path.blockLimit ).contains( function( node ) {
				// Avoid read-only (i.e. contenteditable="false") divs (https://dev.ckeditor.com/ticket/11083).
				return node.is( 'div' ) && !node.isReadOnly();
			}, 1 );
		}
	};
} )();
