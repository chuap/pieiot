/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
    config.allowedContent = true; 
    config.height = '150px';
    config.toolbar = [
    { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-'] },
	{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [  'FontSize','TextColor', 'BGColor','Bold', 'Italic', 'Underline', 'Strike' ] },
	{ name: 'insert', items: [ 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe','Link' ] },
        { name: 'paragraph' , items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ]},
       { name: 'tools', items: [ 'Maximize'] }
]; 
};
