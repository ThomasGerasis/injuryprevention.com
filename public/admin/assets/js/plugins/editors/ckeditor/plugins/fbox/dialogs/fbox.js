CKEDITOR.dialog.add( 'fbox', function( editor ) {
	return {
		title: 'Edit Custom Box',
		minWidth: 300,
		minHeight: 200,
		contents: [
			{
				id: 'info',
				elements: [
					{
						id: 'tclass',
						type: 'select',
						label: 'Class',
						items: [
							[ 'Shadow', 'shadow' ],
							[ 'Shadow small', 'shadow2' ],
							[ 'Info', 'info' ],
							[ 'Success', 'success' ],
							[ 'Warning', 'warning' ],
							[ 'Error', 'error' ],
							[ 'Download', 'download' ],
							[ 'Note', 'note' ]
						],
						setup: function( widget ) {
							this.setValue( widget.data.tclass );
						},
						commit: function( widget ) {
							widget.setData( 'tclass', this.getValue() );
						}
					},
					/*{
						id: 'align',
						type: 'select',
						label: 'Align',
						items: [
							[ editor.lang.common.notSet, '' ],
							[ editor.lang.common.alignLeft, 'left' ],
							[ editor.lang.common.alignRight, 'right' ],
							[ editor.lang.common.alignCenter, 'center' ]
						],
						setup: function( widget ) {
							this.setValue( widget.data.align );
						},
						commit: function( widget ) {
							widget.setData( 'align', this.getValue() );
						}
					},
					{
						id: 'width',
						type: 'text',
						label: 'Width',
						width: '50px',
						setup: function( widget ) {
							this.setValue( widget.data.width );
						},
						commit: function( widget ) {
							widget.setData( 'width', this.getValue() );
						}
					}*/
				]
			}
		]
	};
} );