/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {

	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// Setup
	// ------------------------------

    // RTL version uses different config file
    if (document.dir == "rtl") {
		config.skin = 'default_rtl';
	    config.contentsLangDirection = 'rtl';
	    config.dialog_buttonsOrder = 'rtl';
	    config.language = 'ar';
	    config.defaultLanguage = 'en';
    }
    else {
		config.skin = 'default';
    }


    // Load content styles
	config.contentsCss = [CKEDITOR.basePath+'../../../../css/bootstrap.min.css',CKEDITOR.basePath+'skins/' + config.skin + '/contents.css?v=3'];
	config.entities = false;
	config.extraPlugins = "widget,justify,abbr,customlists,fdivider,div,goanchor,golink,analysilink,articlelink,btgrid";//embed,autoembed,image2,cta,,fbox
	//pastetools,pastefromword,
	// Toolbar
	// ------------------------------
	
	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'paragraph', groups: [ 'list', 'blocks', 'bidi', 'paragraph' ] },
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others', groups: [ 'others' ] },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'align', groups: [ 'align'] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'shortcodes', groups: [ 'shortcodes' ] }
	]

	/*config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },source
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		'/',
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'shortcodes', groups: [ 'shortcodes' ] }
	];*/
	//config.colorButton_backStyle = '';
	
	config.removeButtons = 'Save,Templates,Cut,Copy,Paste,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Outdent,Indent,CreateDiv,BidiLtr,BidiRtl,Language,Anchor,Image,Flash,PageBreak,Iframe,Font,ShowBlocks,About';
	//config.removeButtons = 'Subscript,Superscript,Scayt,Outdent,Indent';

	// Extra config
	// ------------------------------

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;h4;h5;h6;pre';
	config.removeFormatAttributes = 'style';
	config.ignoreEmptyParagraph = true;
	config.disableNativeSpellChecker = false;

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';

	// Allow content rules
	config.allowedContent = true;
	//config.blockedKeystrokes = [[ CTRL + 86 /*V*/, 'Paste' ]];
	/*config.blockedKeystrokes = [
		CKEDITOR.CTRL + 86, // PAste - Ctrl+B
		CKEDITOR.CTRL + CKEDITOR.SHIFT + 86, // PAste - Ctrl+B
		CKEDITOR.CTRL + 66, // Ctrl+B
		CKEDITOR.CTRL + 73, // Ctrl+I
		CKEDITOR.CTRL + 85 // Ctrl+U
	];*/

	config.pasteFromWordRemoveFontStyles = true;
	config.pasteFromWordRemoveStyles = true;
	//config.pasteFromWordCleanupFile = false;
	//config.pasteFromWordCleanupFile = 'plugins/pastetools/filter/common.js';
	//config.pasteFromWordCleanupFile = 'plugins/pastefromword/filter/default.js';
	//config.pasteFilter = true;
	
	// Allow any class and any inline style
	config.extraAllowedContent = '*(*);*{*}';
};
