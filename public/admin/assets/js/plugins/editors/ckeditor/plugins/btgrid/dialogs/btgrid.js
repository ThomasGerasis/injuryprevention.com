CKEDITOR.dialog.add( 'btgrid', function( editor ) {
  var lang = editor.lang.btgrid;
  var commonLang = editor.lang.common;

  // Whole-positive-integer validator.
  function validatorNum(msg) {
    return function() {
      var value = this.getValue(),
        pass = !!(CKEDITOR.dialog.validate.integer()(value) && value > 0);

      if (!pass) {
        alert(msg); // jshint ignore:line
      }

      return pass;
    };
  }
  return {
    title: lang.editBtGrid,
    minWidth: 600,
    minHeight: 300,
    onShow: function() {
      // Detect if there's a selected table.
      var selection = editor.getSelection(),
        ranges = selection.getRanges();
      var command = this.getName();

      var rowsInput = this.getContentElement('info', 'rowCount'),
        colsInput = this.getContentElement('info', 'colCount');
      if (command == 'btgrid') {
        var grid = selection.getSelectedElement();
        // Enable or disable row and cols.
        if (grid) {
          this.setupContent(grid);
          rowsInput && rowsInput.disable();
          colsInput && colsInput.disable();
        }
      }
    },
    contents: [
      {
        id: 'info',
        label: lang.infoTab,
        accessKey: 'I',
        elements: [
          {
            id: 'colCount',
            type: 'select',
            required: true,
            label: lang.selNumCols,
            items: [
				[ '2 on all', '2all'],
              [ '2 from 576px', '2sm'],
              [ '2 from 768px', '2md'],
              [ '2 from 992px', '2lg'],
              [ '3 on all', '3all'],
              [ '3 from 768px', '3md'],
              [ '3 from 992px', '3lg'],
              /*[ '2', 2],
              [ '3', 3],
              [ '4', 4],
              [ '6', 6],
              [ '12', 12],*/
            ],
            validate: CKEDITOR.dialog.validate.notEmpty( "Columns number cannot be empty." ),//validatorNum(lang.numColsError),
            setup: function( widget ) {
              this.setValue(widget.data.colCount);
            },
            // When committing (saving) this field, set its value to the widget data.
            commit: function( widget ) {
              widget.setData( 'colCount', this.getValue());
            }
          },
          {
            id: 'rowCount',
            type: 'text',
            width: '50px',
            required: true,
            label: lang.genNrRows,
            validate: validatorNum(lang.numRowsError),
            setup: function( widget ) {
              if(widget.data.rowCount) {
				  this.setValue( widget.data.rowCount );
			  }else{
				  this.setValue( 1 );
			  }
            },
            commit: function( widget ) {
              widget.setData( 'rowCount', this.getValue());
            }
          }
        ]
      }
    ],
  };
});
