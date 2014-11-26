<?php

/*
  Plugin Name: YouTube quick shortcode
  Plugin URI: http://www.prometod.eu/en/fastest-way-embed-youtube-clips-wordpress-site/
  Description: Quicker embedding of YouTube videos
  Version: 1.0.0
 * Author: Deyan Totev
 * Author URI: http://www.prometod.eu/en/
 * License: GPL2
 */
//-------------------------Setting the plugin's settings page
            add_action('admin_menu', 'youtube_quick_shortcode_plugin_menu');

            function youtube_quick_shortcode_plugin_menu() {
                    add_options_page('YouTube Quick Shortcode Plugin Options', 'YouTube Quick Shortcode Plugin', 'manage_options', 'youtube_quick_shortcode', 'youtube_quick_shortcode_plugin');
            }
            function youtube_quick_shortcode_plugin(){
                global $options;
				//------------------------- script for working with plugin settings on the base of Treehouse Wordpress Tutorial  
                    if( isset( $_POST['youtube_quick_shortcode_plugin_options_submitted'] ) ) {
                            $hidden_field = esc_html( $_POST['youtube_quick_shortcode_plugin_options_submitted'] );
                            if( $hidden_field == 'Y' ) {
                                    $youtube_quick_shortcode_plugin_options_control = esc_html( $_POST['youtube_quick_shortcode_plugin_options_control'] );
                                    $youtube_quick_shortcode_plugin_options_related = esc_html( $_POST['youtube_quick_shortcode_plugin_options_related'] );
                                    $youtube_quick_shortcode_plugin_options_info = esc_html( $_POST['youtube_quick_shortcode_plugin_options_info'] );
                                    $youtube_quick_shortcode_plugin_options_size = esc_html( $_POST['youtube_quick_shortcode_plugin_options_size'] );
                                    $options['youtube_quick_shortcode_plugin_options_control']	= $youtube_quick_shortcode_plugin_options_control;
                                    $options['youtube_quick_shortcode_plugin_options_related']	= $youtube_quick_shortcode_plugin_options_related;
                                    $options['youtube_quick_shortcode_plugin_options_info']	= $youtube_quick_shortcode_plugin_options_info;
                                    $options['youtube_quick_shortcode_plugin_options_size']	= $youtube_quick_shortcode_plugin_options_size;
                                    update_option( 'youtube_quick_shortcode_plugin_options', $options );
                                                        }
                                                                    }
            //-------------------------
                    $options = get_option( 'youtube_quick_shortcode_plugin_options' );
                    if( $options != '' ) {
                            $youtube_quick_shortcode_plugin_options_control = $options['youtube_quick_shortcode_plugin_options_control'];
                            $youtube_quick_shortcode_plugin_options_related = $options['youtube_quick_shortcode_plugin_options_related'];
                            $youtube_quick_shortcode_plugin_options_info = $options['youtube_quick_shortcode_plugin_options_info'];
                            $youtube_quick_shortcode_plugin_options_size = $options['youtube_quick_shortcode_plugin_options_size'];
                                        }
             include('ytube-admin.php');	//include the admin page
            }
            //-------------------------
            add_action('admin_head', 'ytube_add_my_tc_button');
			//------------------------- Standart code for adding text button in the visual editor
function ytube_add_my_tc_button() {
    global $typenow;
    // check user permissions
    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
   	return;
    }
    // verify the post type
    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
        return;
	// check if WYSIWYG is enabled
	if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "ytube_add_tinymce_plugin");
		add_filter('mce_buttons', 'ytube_register_my_tc_button');
	}
}
function ytube_add_tinymce_plugin($plugin_array) {
   	$plugin_array['ytube_tc_button'] = plugins_url( '/shortcode.js', __FILE__ ); // CHANGE THE BUTTON SCRIPT HERE
        
   	return $plugin_array;
}
function ytube_register_my_tc_button($buttons) {
   array_push($buttons, "ytube_tc_button");
   return $buttons;
}
//    end of code for implementing text button
//-------------------------
//    begin of actual code creating the logic of the plugin
function ytube_shortcode_function( $atts ) {
//    common code for taking the atributes of the shortcode
            extract( shortcode_atts(
                            array(
                                    'url' => '',
                                    
                            ), $atts )
                    );
	//------------------------- trimming the included | characters 				
          $url = preg_replace('/\|/', '', $url);
	//------------------------- get the unique id that YouTube sets for each clip		
        $ytube_url_code = $url[32].$url[33].$url[34].$url[35].$url[36].$url[37].$url[38].$url[39].$url[40].$url[41].$url[42];
        //------------------------- read the options set by the user
		global $options;
           $options = get_option( 'youtube_quick_shortcode_plugin_options' );
                    
                            $youtube_quick_shortcode_plugin_options_control = $options['youtube_quick_shortcode_plugin_options_control'];
                            $youtube_quick_shortcode_plugin_options_related = $options['youtube_quick_shortcode_plugin_options_related'];
                            $youtube_quick_shortcode_plugin_options_info = $options['youtube_quick_shortcode_plugin_options_info'];
                            $youtube_quick_shortcode_plugin_options_size = $options['youtube_quick_shortcode_plugin_options_size'];
                            //-------------------------
                            if($youtube_quick_shortcode_plugin_options_control === 'On'){
                            $control = 1;}else { $control = 0; }
                            //-------------------------
                             if($youtube_quick_shortcode_plugin_options_related === 'On'){
                            $related = 1;}else { $related = 0; }
                            //-------------------------
                             if($youtube_quick_shortcode_plugin_options_info === 'On'){
                            $info = 1;}else { $info = 0; }
                            //-------------------------
                             if($youtube_quick_shortcode_plugin_options_control === '960'){
                            $size1 = 960; $size2 = 720; }else { $size1 = 640; $size2 = 480; }
        //------------------------- end_data is based on the original code that YouTube delivers for embeding clips
		$end_data = '<iframe width="'.$size1.'" height="'.$size2.'" src="//www.youtube.com/embed/'. $ytube_url_code .'?rel='.$related.'&amp;controls='.$control.'&amp;showinfo='.$info.'" frameborder="0" allowfullscreen></iframe>';
        return $end_data;
}
add_shortcode( 'ytube_shortcode', 'ytube_shortcode_function' );