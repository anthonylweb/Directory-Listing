<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/*
Plugin Name: Directory Listings
Plugin URI:  http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Creates a simple and responsive directory of businesses, clients, organizations, etc, to be used with Wordpress.  
Version:     1.0
Author:      Anthony Laurence 
Author URI:  http://www.anthonylaurence.net
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

//config
$dir = plugin_dir_path( __FILE__ );

//Add Directory Listing Styles to the Admin Panel

function directory_list_funct(){
	wp_enqueue_style( 'dir-styles', plugins_url('dl_styles.css', __FILE__));
	wp_enqueue_script( 'dir-scripts', plugins_url('dl_scripts.js', __FILE__), array('jquery'));
}

add_action('wp_enqueue_scripts', 'directory_list_funct');



//Creates the Directory Listing Custom Post Type
function dirList(){

	array(
					$labels = array(
									'name' => 'Directory List',
									'singular_name' => 'Listing',
									'add_new' => 'Add Listing',
                                    'add_new_item' => 'Add New Listing',
									'edit_item' => "Edit Listing",
									'view_item'  => "View Listing" 
					),
					$args = array(
							'labels' => $labels,
								'public' => true,
								'has_archive' => true,
								'supports' => array(
													"title",
                                                    "editor",
                                                    "thumbnail"
	
											)
					)
			
	
				);
	
	register_post_type( 'Directory List', $args );
}

add_action('init', 'dirList', 1);

//Include the Meta Box Classes
require_once($dir.'dl_listingDetails.php');

//Create the Shortcode
require_once($dir.'directoryTemplate.php');




?>