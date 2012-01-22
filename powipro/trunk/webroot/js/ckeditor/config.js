/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	config.language = 'de';
	// Remove unused plugins.
	config.removePlugins = 'bidi,button,dialogadvtab,div,filebrowser,flash,format,forms,horizontalrule,iframe,indent,justify,liststyle,pagebreak,showborders,stylescombo,table,tabletools,templates';
	// Width and height are not supported in the BBCode format, so object resizing is disabled.
	config.disableObjectResizing = true;
	
	config.toolbar =
	[
		['Save','-','Undo','Redo'],
		['Find','Replace','-','SelectAll','RemoveFormat'],
		['Link', 'Unlink', 'Image','SpecialChar'],
		'/',
		['Bold', 'Italic','Underline'],
		['TextColor', 'BulletedList'],
		['Maximize']
	];
	// config.uiColor = '#AADC6E';
};
