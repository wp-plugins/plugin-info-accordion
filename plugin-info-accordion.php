<?php
/*
Plugin Name:  Plugin Info Accordion
Description:  Adds an accordion to the FAQ and change log info from the Plugin Info plugin.
Plugin URI:   http://toolstack.com/plugin-info-accordion
Version:      1.0
Author:       Greg Ross
Author URI:   http://toolstack.com
License:      GPL v2

Compatible with WordPress 3+.

Read the accompanying readme.txt file for instructions and documentation.

Copyright (c) 2015 by Greg Ross

This software is released under the GPL v2.0, see license.txt for details

*/

function plugin_info_accordion( $output, $attribute, $slug ) {
	if ( 'faq' == $attribute || 'changelog' == $attribute ) {
		// Add div tags before/after each of the h4's.
		$output = str_replace( array( '<h4>', '</h4>' ), array( '</div><h4>', '</h4><div>' ), $output );
		
		// Get rid of the first closing div as we haven't opened a div yet.
		$output = preg_replace('/\<\/div\>\<h4\>/', '<h4>', $output, 1); 
		
		// Close the last div we opened.
		$output = $output . '</div>';

		// Enqueue jquery ui components.
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-accordion');
		
		// Load the jQuery UI CSS.
		wp_register_style("pia_jquery-ui-css", plugin_dir_url(__FILE__) . "jquery-ui/jquery-ui.css");
		wp_enqueue_style("pia_jquery-ui-css");

		// Add a div around the entire output and give it an id so we can use it to activate the accordion.
		$output = '<div id=' . $slug . '-' . $attribute . '>' . $output . '</div>';
		
		// Setup the default options for the accordion.
		$options = 'animate: false, heightStyle : "content", collapsible: true'; 
		
		// By default the first item is visible, for FAQ's that probably doesn't make sense so close it.
		if( 'faq' == $attribute ) { $options .= ', active: false'; }
		
		// Add the javascript to create the accordion.
		$output = $output . '<script>jQuery(document).ready(function(){ jQuery("#' . $slug . '-' . $attribute . '").accordion( { ' . $options . ' } ); } );</script>';
	}
	
	return $output;
}
add_filter( 'plugin_info_shortcode', 'plugin_info_accordion', 10, 3 );
