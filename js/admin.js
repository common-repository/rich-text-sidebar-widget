(function($){
	richTextSidebarWidget = {	
		/**
		* Initialises the plugin:
		* - starts the event listener
		*/
		init: function() {
			richTextSidebarWidget.listen();	
		},

		/**
		* Listener, to handle:
		* 
		* - Sending data to the wp_editor when 'Edit Content' button is clicked,
		* - Sending data to the widget instance hidden field when the 'Save Changes' modal button is clicked
		*/
		listen: function() {
			// Edit Content
			$('a[data-target=#modal-rich-text-sidebar-widget-editor]').live('click', function() {
				// Get existing content from hidden field and insert into TinyMCE
				hiddenField = $('input[type=hidden]', $(this).parent());
				content = $(hiddenField).val();
				console.log(content);
				tinyMCE.get('rich_text_sidebar_widget_editor').execCommand('mceSetContent', false, content);
			});
			
			// Save Changes
			$('#modal-rich-text-sidebar-widget-editor a.btn-primary').on('click', function() {
				// Get existing HTML encoded content from TinyMCE, and insert into hidden field
				content = tinyMCE.get('rich_text_sidebar_widget_editor').getContent();
				console.log(content);
				$(hiddenField).val(content);
				
				// Close modal
				$('#modal-rich-text-sidebar-widget-editor').modal('hide');
			});
		}
	}	
	
	// Init
	$(richTextSidebarWidget.init);	
})(jQuery);