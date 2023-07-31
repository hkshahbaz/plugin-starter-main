<?php
/**
 * Plugin Name: Starter
 * Plugin URI: https://github.com/arunbasillal/WordPress-Starter-Plugin
 * Description: A starter plugin for WordPress complete with inline documentation and working admin options page.
 * Author: Arun Basil Lal
 * Author URI: https://millionclues.com
 * Version: 1.0
 * Text Domain: starter-plugin
 * Domain Path: /languages
 * License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * This plugin was developed using the WordPress starter plugin template by Arun Basil Lal <arunbasillal@gmail.com>
 * Please leave this credit and the directory structure intact for future developers who might read the code.
 * @GitHub https://github.com/arunbasillal/WordPress-Starter-Plugin
 */
 
/**
 * ~ Directory Structure ~
 *
 * /admin/ 					- Plugin backend stuff.
 * /functions/					- Functions and plugin operations.
 * /includes/					- External third party classes and libraries.
 * /languages/					- Translation files go here. 
 * /public/					- Front end files and functions that matter on the front end go here.
 * index.php					- Dummy file.
 * license.txt					- GPL v2
 * starter-plugin.php				- Main plugin file containing plugin name and other version info for WordPress.
 * readme.txt					- Readme for WordPress plugin repository. https://wordpress.org/plugins/files/2018/01/readme.txt
 * uninstall.php				- Fired when the plugin is uninstalled. 
 */
 
/**
 * ~ TODO ~
 *
 * - Note: (S&R) = Search and Replace by matching case.
 *
 * - Plugin name: Starter Plugin (S&R)
 * - Plugin folder slug: starter-plugin (S&R)
 * - Decide on a prefix for the plugin (S&R)
 * - Plugin description
 * - Text domain. Text domain for plugins has to be the folder name of the plugin. For eg. if your plugin is in /wp-content/plugins/abc-def/ folder text domain should be abc-def (S&R)
 * - Update prefix_settings_link() 		in \admin\basic-setup.php
 * - Update prefix_footer_text()		in \admin\basic-setup.php
 * - Update prefix_add_menu_links() 		in \admin\admin-ui-setup.php
 * - Update prefix_register_settings() 		in \admin\admin-ui-setup.php
 * - Update UI format and settings		in \admin\admin-ui-render.php
 * - Update uninstall.php
 * - Update readme.txt
 * - Update PREFIX_VERSION_NUM 			in starter-plugin.php (keep this line for future updates)
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Define constants
 *
 * @since 1.0
 */
if ( ! defined( 'PREFIX_VERSION_NUM' ) ) 		define( 'PREFIX_VERSION_NUM'		, '1.0' ); // Plugin version constant
if ( ! defined( 'PREFIX_STARTER_PLUGIN' ) )		define( 'PREFIX_STARTER_PLUGIN'		, trim( dirname( plugin_basename( __FILE__ ) ), '/' ) ); // Name of the plugin folder eg - 'starter-plugin'
if ( ! defined( 'PREFIX_STARTER_PLUGIN_DIR' ) )	define( 'PREFIX_STARTER_PLUGIN_DIR'	, plugin_dir_path( __FILE__ ) ); // Plugin directory absolute path with the trailing slash. Useful for using with includes eg - /var/www/html/wp-content/plugins/starter-plugin/
if ( ! defined( 'PREFIX_STARTER_PLUGIN_URL' ) )	define( 'PREFIX_STARTER_PLUGIN_URL'	, plugin_dir_url( __FILE__ ) ); // URL to the plugin folder with the trailing slash. Useful for referencing src eg - http://localhost/wp/wp-content/plugins/starter-plugin/

/**
 * Database upgrade todo
 *
 * @since 1.0
 */
function prefix_upgrader() {
	
	// Get the current version of the plugin stored in the database.
	$current_ver = get_option( 'abl_prefix_version', '0.0' );
	
	// Return if we are already on updated version. 
	if ( version_compare( $current_ver, PREFIX_VERSION_NUM, '==' ) ) {
		return;
	}
	
	// This part will only be excuted once when a user upgrades from an older version to a newer version.
	
	// Finally add the current version to the database. Upgrade todo complete. 
	update_option( 'abl_prefix_version', PREFIX_VERSION_NUM );
}
add_action( 'admin_init', 'prefix_upgrader' );

// Load everything
require_once( PREFIX_STARTER_PLUGIN_DIR . 'loader.php' );

// Register activation hook (this has to be in the main plugin file or refer bit.ly/2qMbn2O)
register_activation_hook( __FILE__, 'prefix_activate_plugin');
// do_action( 'save_post', int $post_ID, WP_Post $post, bool $update)





//shortcode
function shortcode_function() {
     

	$form='<form method="post" action="#">
	<div class="form-group" >
		
		<input type="text" class="form-control" id="name" name="user_name" placeholder="Enter Name"  style="width:100%;">
	  </div>
	  
	  <div class="form-group" style="margin-top:6px;">
		
		<input type="text" class="form-control" id="website" name="website_url" aria- placeholder="Enter Website Url"  style="width:100%;">
		
	  </div>
      
      <div class="form-group" style="margin-top:6px;">
		
      <input type="hidden" name="action" value="submit_form" />
	  </div>
      
	  
	  
	  <button type="button" class="btn btn-primary" id="submit_form" value="submit_form" name="submit" style="margin:6px;; ">Submit</button>
	   
	</form>';

    ?>


    <script>
        //adding jquery on click event
        jQuery(document).on('click',"#submit_form",function(){


            let name=jQuery("#name").val()
            let website=jQuery("#website").val()
            var ajaxurl = window.location.origin+"/wp-admin/admin-ajax.php";
            jQuery.ajax({
                        //url:window.location.origin+"/wp-admin/admin-ajax.php", // Since WP 2.8 ajaxurl is always defined and points to admin-ajax.php
                        url:"<?php echo admin_url('admin-ajax.php'); ?>",  
                        data: {
                            'action':'form_data', // This is our PHP function below
                            'name' : name, // This is the variable we are sending via AJAX
                            'website':website
                        },
                        method:'post',
                        success:function(response) {
                        
                             location.reload();
                          
                            },
                        error:function(){
                        
                            }
                    
            })

        })
        
        </script>
        <?php
	wp_nonce_field( 'new_form_nonce' ); 

    
	return $form;
}
add_shortcode('form22', 'shortcode_function');




//for storing data
add_action( 'wp_ajax_form_data', 'form_data' );
    function form_data()
    {
        
			$form = wp_insert_post(
             array(
                'post_title' => $_POST['name'],
                'post_content' => $_POST['website'],
                'post_type' => 'Websites',
				'post_status'=> 'publish',
            ),);

          wp_insert_post($post);
			update_post_meta($post->ID, 'user_name', $_POST['website']);
             update_post_meta($post->ID, '_song_artist_name', $_POST['song_artist']);
        }
    




//createing website custom post type

// Our custom post type function
function create_posttype() {
  
    register_post_type( 'websites',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Website' ),
                'singular_name' => __( 'Websites' )
            ),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   =>false,
            'show_in_admin_bar'   =>false,
            'can_export'          => false,
            'has_archive'         => false,
            'exclude_from_search' => false,
            'publicly_queryable'  => false,
        
            'has_archive' => false,
            'rewrite' => array('slug' => 'websites'),
            'show_in_rest' =>false,
            'capability_type' => 'post',
            'capabilities' => array(
              'create_posts' => false, // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
            ),
            'map_meta_cap' => true, // Set to `false`, if users are not allowed to edit/delete existing posts
          
  
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );



//hiding option of update
function hide_edit_book_update(){ ?>
    <style type="text/css">
     #publish,.is-primary {display:none!important;}
    </style>
    <?php
  }
  add_action( 'admin_enqueue_scripts', 'hide_edit_book_update' );







//adding custom meta box
add_action( 'add_meta_boxes', 'add_taddressbox_address_meta_box' );

    function add_taddressbox_address_meta_box() {

		
        add_meta_box(
            'taddressbox_address_meta_box', // $id
            'custom_box', // $title
            'show_taddressbox_address_meta_box', // $callback
            get_current_screen(), // $screen
            'normal', // $context
            'high' // $priority
        );
    }


   


    function show_taddressbox_address_meta_box( $post ) {
      
        $custom = file_get_contents($post->post_content);

        wp_nonce_field( 'my_lat_lang_box', 'my_lat_lang_box_nonce' );

         ?>


        <!-- All fields will go here -->

        <div id="map" tabindex="0" style="position: relative;height:400px;margin:0; padding:0; display: block;"></div>
        <div id="custom_metabox">
         <teaxtarea type="text" id="custom-meta" name="custom-meta" ><?php echo $custom; ?></textarea>
          </div>

<?php }

?>