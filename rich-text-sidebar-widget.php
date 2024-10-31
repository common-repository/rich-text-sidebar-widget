<?php
/**
* Plugin Name: Rich Text Sidebar Widget
* Plugin URI: http://www.wpcube.co.uk/plugins/rich-text-sidebar-widget/ 
* Version: 1.0
* Author: <a href="http://www.n7studios.co.uk">n7 Studios</a>, <a href="http://www.wpcube.co.uk">WP Cube</a>
* Description: Adds a rich text (WordPress Editor / TinyMCE) widget to your WordPress web site.
* License: GPL2
*/

/*  Copyright 2013 n7 Studios, WP Cube (email : tim@n7studios.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
* Widget: Rich Text Sidebar Widget
* 
* @package WordPress
* @subpackage Rich Text Sidebar Widget
* @author Tim Carr
* @version 1.0
* @copyright WP Cube 
*/ 
class RichTextSidebarWidget extends WP_Widget {
    /**
    * Constructor.  Sets up the widget, and adds it to the WP_Widget class
    */
    function __construct() {
    	// Setup Widget
    	parent::__construct(
	 		'rich_text_sidebar_widget',
			__('Rich Text'),
			array(
				'classname' => 'rich_text_sidebar_widget',
				'description' => __('Adds a rich text (WordPress Editor / TinyMCE) widget.')
			)
		);
		
		// Plugin Data
		$this->plugin->name = 'rich-text-sidebar-widget';
        $this->plugin->displayName = 'Rich Text Widget';
        $this->plugin->version = '1.0';
        $this->plugin->url = WP_PLUGIN_URL.'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__));

		// Hooks and Filters
		if (is_admin()) {
			add_action('init', array(&$this, 'AdminScriptsAndCSS'));
			add_action('admin_footer', array(&$this, 'AdminFooter'));
		}
    }
    
    /**
    * Register and enqueue any JS and CSS for the WordPress Administration Interface
    */
    function AdminScriptsAndCSS() { 
    	// JS
    	wp_enqueue_script($this->plugin->name.'-bootstrap', $this->plugin->url.'js/bootstrap.min.js', array('jquery'), $this->plugin->version);
    	wp_enqueue_script($this->plugin->name.'-admin', $this->plugin->url.'js/admin.js', array('jquery'), $this->plugin->version, true);
    	
    	// CSS
    	wp_enqueue_style($this->plugin->name.'-bootstrap', $this->plugin->url.'css/bootstrap.min.css');
    }
    
    /**
    * Outputs a wp_editor instance, hidden in a bootstrap modal for use by this widget
    */
    function AdminFooter() {
    	?>
    	<!-- Modal for Widget -->
		<div id="modal-rich-text-sidebar-widget-editor" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-header">
		    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    	<h3><?php _e('Rich Text Editor'); ?></h3>
		  	</div>
		  	<div class="modal-body">
			 	<?php
			 	wp_editor('', 'rich_text_sidebar_widget_editor', array(
					'media_buttons' => false, 
					'dfw' => false, 
					'tinymce' => array(
						'height' => 300
					)
				));
			 	?>  
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true"><?php _e('Discard Changes'); ?></button>
				<a href="#" class="btn btn-primary"><?php _e('Save Changes'); ?></a>
			</div>
		</div>
		
		<!-- Modal for Donate Box -->
		<div id="modal-rich-text-sidebar-widget-donate" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-header">
		    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    	<h3><?php _e('Donate'); ?></h3>
		  	</div>
		  	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
			  	<div class="modal-body">
				 	<p>
				 		<?php _e('I try my best to maintain this plugin outside of my full time job, therefore any donations for ongoing development and support are greatly appreciated.'); ?>
				 	</p>
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="RBAHE4QWKJX3N">
						<input type="hidden" name="on0" value="Donation">
						<select name="os0">
							<option value="2">&pound;2 GBP (~ $3 USD)</option>
							<option value="5">&pound;5 GBP (~ $8 USD)</option>
							<option value="7">&pound;7.50 GBP (~ $12 USD)</option>
							<option value="10">&pound;10 GBP (~ $15 USD)</option>
						</select> 
						<input type="hidden" name="currency_code" value="GBP">
						<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
						 
				</div>
				<div class="modal-footer">
					<button class="btn" data-dismiss="modal" aria-hidden="true"><?php _e('Close Window'); ?></button>
					<input type="submit" name="donate" value="Donate Now" class="btn btn-primary">
				</div>
			</form>
		</div>
    	<?php
    }

    /**
    * Displays the front end widget
    * 
    * @param string $args Native Wordpress Vars
    * @param string $instance Configurable jobs
    */
    function widget($args, $instance) {
    	extract($args);
    	?>
 		<div class="widget widget_rich_text_sidebar_widget">
	 		<?php 
	 		echo $before_widget;
	 		if ($instance['title'] != '') echo $before_title.$instance['title'].$after_title;
	 		echo wpautop(str_replace("&quot;", '"', $instance['content']));
	 		echo $after_widget;
	 		?>
		</div>
		<?php
    }

    /**
    * Process the new settings before they're sent off to be saved
    * 
    * @param array $new_instance Array of settings we're about to process, before saving
    * @param array $old_instance Old Settings
    * @return array New Settings to be saved
    */
    function update($new_instance, $old_instance) {  
    	$new_instance['content'] = str_replace('"', "&quot;", $new_instance['content']);
    	return $new_instance;
    }
    
    /**
    * Creates the edit form for the widget
    *
    * We don't display wp_editor here, as it would break on the Appearance > Widgets screen (as it'll try to reload it every time
    * the user saves).  Instead, the wp_editor instance is already loaded into a Bootstrap Modal, which is then triggered for display.
    *
    * See js/admin.js for listeners which send/receive data to and from the wp_editor
    * 
    * @param array $instance Current Settings
    */
    function form($instance) {
    	echo (' <p>
                    <label for="'.$this->get_field_id('title').'">
                        '.__('Title').':
                        <input type="text" name="'.$this->get_field_name('title').'" id="'.$this->get_field_id('title').'" value="'.$instance['title'].'" class="widefat" />
                    </label>
                </p>
                <p>
                    <label for="'.$this->get_field_id('content').'">
                        <a class="button button-primary" data-toggle="modal" data-target="#modal-rich-text-sidebar-widget-editor" data-remote="false">
                        	'.__('Edit Content').'
                        </a>
                        <input type="hidden" name="'.$this->get_field_name('content').'" id="'.$this->get_field_id('content').'" value="'.$instance['content'].'" />
					</label>
                </p>
                <p>
                	'.__('Found this widget useful?').' <a href="#" data-toggle="modal" data-target="#modal-rich-text-sidebar-widget-donate" data-remote="false">'.__('Donate').'</a>
                </p>');
    }
}

// Register Widget
add_action('widgets_init', 'RegisterRichTextSidebarWidget');
function RegisterRichTextSidebarWidget() { register_widget('RichTextSidebarWidget'); }
?>