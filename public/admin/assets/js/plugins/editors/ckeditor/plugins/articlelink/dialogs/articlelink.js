CKEDITOR.dialog.add( 'articlelinkDialog', function( editor ) {
	return {
		title: 'Link Properties',
		minWidth: 400,
		minHeight: 200,
		contents: [
			{
				id: 'tab-basic',
				label: 'Basic Settings',
				elements: [
					
					{
						type: 'select',
						id: 'thistylink',
						label: 'Article link',
						items: [ ],
						onLoad: function(api) {
							widget = this;
							$.ajax({
								type: 'GET',
								//url: 'http://localhost/admin.foxbet.gr/admin/ajaxData/getArticleLinks',
								url: 'https://adminfx.foxbet.gr/admin/ajaxData/getArticleLinks',
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
						setup: function( element ) {
							this.setValue( element.getAttribute( "href" ) );
						},
						commit: function( element ) {
							element.setAttribute( "href", this.getValue() );
							element.setText( this.getInputElement().$.options[this.getInputElement().$.selectedIndex].innerHTML );
						}
					},
					{
						type: 'select',
						id: 'target',
						label: 'Target',
						items: [
							['Normal',''],
							['New','_blank']
						],
						setup: function( element ) {
							this.setValue( element.getAttribute( "target" ) );
						},
						commit: function( element ) {
							element.setAttribute( "target", this.getValue());
						}
					},
					{
						type: 'text',
						id: 'text',
						label: 'Text',
						//validate: CKEDITOR.dialog.validate.notEmpty( "Text field cannot be empty." ),
						setup: function( element ) {
							this.setValue( element.getText() );
						},
						commit: function( element ) {
							if(this.getValue()) element.setText( this.getValue() );
						}
					},
					/*{
						type: 'text',
						id: 'title',
						label: 'Title',
						setup: function( element ) {
							this.setValue( element.getAttribute( "title" ) );
						},
						commit: function( element ) {
							element.setAttribute( "title", this.getValue() );
						}
					},*/
					{
						type: 'select',
						id: 'font_color',
						label: 'Font color',
						items: [
							['White','font-fff'],
							['Orange','font-orange']
						],
						setup: function( element ) {
							if(element.hasClass('font-orange')){
								this.setValue('font-orange');
							}else if(element.hasClass('font-fff')){
								this.setValue('font-fff');
							}
						},
						commit: function( element ) {
							element.removeClass('font-orange');
							element.removeClass('font-fff');
							if(this.getValue()) element.addClass( this.getValue() );
						}
					},
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
						//validate: CKEDITOR.dialog.validate.notEmpty( "Class cannot be empty." ),
						setup: function( element ) {
							if(element.hasClass('btn-primary')){
								this.setValue('btn-primary');
							}else if(element.hasClass('btn-success')){
								this.setValue('btn-success');
							}else if(element.hasClass('btn-info')){
								this.setValue('btn-info');
							}else if(element.hasClass('btn-warning')){
								this.setValue('btn-warning');
							}else if(element.hasClass('btn-danger')){
								this.setValue('btn-danger');
							}
						},
						commit: function( element ) {
							element.removeClass('btn');
							element.removeClass('btn-primary');
							element.removeClass('btn-success');
							element.removeClass('btn-info');
							element.removeClass('btn-warning');
							element.removeClass('btn-danger');
							if(this.getValue()){
								element.addClass( 'btn' );
								element.addClass( this.getValue() );
							}
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
						//validate: CKEDITOR.dialog.validate.notEmpty( "Size cannot be empty." ),
						setup: function( element ) {
							if(element.hasClass('btn-xs')){
								this.setValue('btn-xs');
							}else if(element.hasClass('btn-md')){
								this.setValue('btn-md');
							}else if(element.hasClass('btn-lg')){
								this.setValue('btn-lg');
							}
						},
						commit: function( element ) {
							element.removeClass('btn-xs');
							element.removeClass('btn-md');
							element.removeClass('btn-lg');
							if(this.getValue()) element.addClass( this.getValue() );
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
						setup: function( element ) {
							if(element.hasClass('d-block')){
								this.setValue('d-block');
							}else if(element.hasClass('d-inline-block')){
								this.setValue('d-inline-block');
							}
						},
						commit: function( element ) {
							element.removeClass('d-block');
							element.removeClass('d-inline-block');
							if(this.getValue()) element.addClass( this.getValue() );
						}
					},
					{
						id: 'align',
						type: 'select',
						label: 'Align',
						items: [
							[ 'Left', 'text-left' ],
							[ 'Right', 'text-right' ],
							[ 'Center', 'text-center' ]
						],
						setup: function( element ) {
							if(element.hasClass('text-center')){
								this.setValue('text-center');
							}else if(element.hasClass('text-left')){
								this.setValue('text-left');
							}else if(element.hasClass('text-right')){
								this.setValue('text-right');
							}
						},
						commit: function( element ) {
							element.removeClass('text-left');
							element.removeClass('text-right');
							element.removeClass('text-center');
							if(this.getValue()) element.addClass( this.getValue() );
						}
					},
				]
			},
		],
		onShow: function() {
			var selection = editor.getSelection();
			var element = selection.getStartElement();
			if ( element )
				element = element.getAscendant( 'a', true );			
			if ( !element || element.getName() != 'a' ) {
				element = editor.document.createElement( 'a' );
				//element.setHtml( '<li>&nbsp;</li>' );
				this.insertMode = true;
			}
			else
				this.insertMode = false;

			this.element = element;

			if ( !this.insertMode )
				this.setupContent( this.element );
		},
		onOk: function() {
			var a = this.element;
			this.commitContent( a );
			if ( this.insertMode )
				editor.insertElement( a );
		}
	};
});
