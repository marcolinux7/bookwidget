// Script for add button on TinyMCE editor
 
(function() {
	
  tinymce.PluginManager.add('MY_mce_button',function(editor,url) {
    editor.addButton( 'MY_mce_button', {
     title: 'BookPicker',
     type: 'button',
     image : url+'/tiny_bookpicker.png',
     value: '',
     	
        onclick: function(e) {
           
        	e.stopPropagation();
        	 
            tinyMCE.activeEditor.windowManager.open({
            	file: '/', id: 'my-picker', title: 'BookPicker', width : 700, height : 560, inline : true, close_previous: true
            });
            
            jQuery("#my-picker-body").load('/bookpicker-repository/');
            
            jQuery("#my-picker-body div").live('click', function(e) {
            	var pid = jQuery(this).children('div p').attr('data-pid');
            	
            	if (pid) {
                	tinymce.activeEditor.execCommand('mceInsertContent', false, '<br />\n\r[book-gallery pID="'+pid+'"]<br />\n\r');
            	}
            	
            	jQuery(document).on( 'click', '#mce-modal-block', function() {
                    tinyMCE.activeEditor.windowManager.close();
                });
            	
            	tinyMCE.activeEditor.windowManager.close();	// chiudo popup
            	tinyMCE.activeEditor.windowManager.destroy();	// distruggo popup, per liberare memoria
            });
         }
     });
  });
})();
