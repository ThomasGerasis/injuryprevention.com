CKEDITOR.dialog.add( 'golinkDialog', function( editor ) {
	return {
		title: 'Go Button Properties',
		minWidth: 400,
		minHeight: 200,
		contents: [
			{
				id: 'tab-basic',
				label: 'Basic Settings',
				elements: [
					{
						type: 'select',
						id: 'bclass',
						label: 'Class',
						items: [
							['Blue','btn-primary'],
							['Green','btn-success'],
							['Light Blue','btn-info'],
							['Orange','btn-warning'],
							['Red','btn-danger'],
						],
						validate: CKEDITOR.dialog.validate.notEmpty( "Class cannot be empty." ),
						setup: function( widget ) {
							this.setValue( widget.data.bclass );
						},
						commit: function( widget ) {
							widget.setData( 'bclass', this.getValue() );
						}
					},
					{
						type: 'select',
						id: 'size',
						label: 'Size',
						items: [
							['Small','btn-xs'],
							['Medium','btn-md'],
							['Big','btn-lg']
						],
						validate: CKEDITOR.dialog.validate.notEmpty( "Size cannot be empty." ),
						setup: function( widget ) {
							this.setValue( widget.data.size );
						},
						commit: function( widget ) {
							widget.setData( 'size', this.getValue() );
						}
					},
					{
						type: 'select',
						id: 'display',
						label: 'Display',
						items: [
							['Block','d-block'],
							['Inline','d-inline-block']
						],
						setup: function( widget ) {
							this.setValue( widget.data.display );
						},
						commit: function( widget ) {
							widget.setData( 'display', this.getValue() );
						}
					},
					/*{
						type: 'text',
						id: 'text',
						label: 'Text',
						//validate: CKEDITOR.dialog.validate.notEmpty( "Text field cannot be empty." ),
						setup: function( element ) {
							this.setValue( element.getText() );
						},
						commit: function( element ) {
							element.setText( this.getValue() );
						}
					},
					{
						type: 'text',
						id: 'title',
						label: 'Title',
						setup: function( widget ) {
							this.setValue( widget.data.title );
						},
						commit: function( widget ) {
							widget.setData( 'title', this.getValue() );
						}
					},*/
					{
						type: 'select',
						id: 'thistylink',
						label: 'Link',
						items: [ ],
						onLoad: function(api) {
							widget = this;
							$.ajax({
								type: 'GET',
								url: 'http://localhost/admin.foxbet.gr/admin/ajaxData/getGoLinks',
								dataType: 'json',
								success: function(data, textStatus, jqXHR) {
									for (var i = 0; i < data.length; i++) {
										widget.add(data[i]['title'], data[i]['url']);
									}
								},
								error: function(jqXHR, textStatus, errorThrown) {
									console.log('ajax error ' + textStatus + ' ' + errorThrown);
								},
							});
						},
						setup: function( widget ) {
							this.setValue( widget.data.href );
						},
						commit: function( widget ) {
							widget.setData( 'href', this.getValue() );
						}
					},
				]
			},
		],
	};
});
