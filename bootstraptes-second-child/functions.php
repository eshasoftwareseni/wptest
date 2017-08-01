<?php

if ( !function_exists( 'my_theme_enqueue_styles' ) ) {
	
	add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

	function my_theme_enqueue_styles() {
	    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

	}
}

/* 
 * Helper function to return the theme option value. If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 * This code allows the theme to work without errors if the Options Framework plugin has been disabled.
 */

if ( !function_exists( 'of_get_option' ) ) {
	function of_get_option($name, $default = false) {
		
		$optionsframework_settings = get_option('optionsframework');
		
		// Gets the unique option id
		$option_name = $optionsframework_settings['id'];
		
		if ( get_option($option_name) ) {
			$options = get_option($option_name);
		}
			
		if ( isset($options[$name]) ) {
			return $options[$name];
		} else {
			return $default;
		}
	}
}

if ( !function_exists( 'hwl_home_pagesize' ) ) {
	function hwl_home_pagesize( $query ) {
		$perpage = of_get_option( 'perpage_select');
		$default_perpage =  get_option( 'posts_per_page' );
		if(!$perpage) $perpage = $default_perpage;

	    if ( is_home() ) {
	        // Display only 1 post for the original blog archive
	        $query->set( 'posts_per_page', $perpage );
	        return;
	    }

	}
	add_action( 'pre_get_posts', 'hwl_home_pagesize', 1 );
}

if ( !function_exists( 'mysite_js' ) ) {
	function mysite_js() {
		wp_enqueue_script('dataTables', get_stylesheet_directory_uri().'/js/jquery.dataTables.min.js', array('jquery'));
		wp_enqueue_script('dataTables.bootstrap', get_stylesheet_directory_uri().'/js/dataTables.bootstrap.min.js');
		wp_enqueue_script('autocomplete', get_stylesheet_directory_uri().'/js/jquery.auto-complete.js', array('jquery'));
		
		// Register the script
		wp_register_script('mysite-js', get_stylesheet_directory_uri().'/js/mysite.js', array('jquery', 'autocomplete'));

		// Localize the script with new data
		$translation_array = array(
			'url' => __( admin_url() . 'admin-ajax.php' ),
			'data' => __( 'action=get_listing_names&name=' )
		);
		wp_localize_script( 'mysite-js', 'object_name', $translation_array );

		// Enqueued script with localized data.
		wp_enqueue_script( 'mysite-js' );
		wp_enqueue_style('autocomplete.css', get_stylesheet_directory_uri().'/css/jquery.auto-complete.css');
		wp_enqueue_style('dataTables.css', get_stylesheet_directory_uri().'/css/dataTables.bootstrap.min.css');
	}

	add_action('wp_enqueue_scripts', 'mysite_js');
}

if ( !function_exists( 'ajax_listings' ) ) {
	//get listings for 'works at' on submit listing page
	add_action('wp_ajax_nopriv_get_listing_names', 'ajax_listings');
	add_action('wp_ajax_get_listing_names', 'ajax_listings');

	function ajax_listings() {
		global $wpdb; //get access to the WordPress database object variable

		//get names of all businesses
		$name = $wpdb->esc_like(stripslashes($_POST['name'])).'%'; //escape for use in LIKE statement
		$sql = "select post_title 
			from $wpdb->posts 
			where post_title like %s 
			and post_status='publish'";

		$sql = $wpdb->prepare($sql, $name);
		
		$results = $wpdb->get_results($sql);

		//copy the business titles to a simple array
		$titles = array();
		foreach( $results as $r )
			$titles[] = addslashes($r->post_title);
			
		echo json_encode($titles); //encode into JSON format and output

		die(); //stop "0" from being output
	}
}

if ( !function_exists( 'qwerk_search_form' ) ) {
	function qwerk_search_form(){
	    ob_start();?>
	    <form method="post" id="sul-searchform" name="sul-searchform" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
	      <label for="as" class="assistive-text">Search</label>
	      <input type="text" class="field" name="sulname" id="sul-name" value="<?php echo (!empty($_POST['sulname'])) ? $_POST['sulname'] : '' ;?>" placeholder="Search" />
	      <input type="submit" class="submit" name="sul-submit" id="sul-searchsubmit" value="Sumbit" />
	    </form>
	    <?php
		    if (isset($_POST['sul-submit'])) {
		    	global $wpdb; //get access to the WordPress database object variable

				//get names of all businesses
				$name = $wpdb->esc_like(stripslashes($_POST['sulname'])).'%'; //escape for use in LIKE statement
				$sql = "select post_author, post_date, post_title 
					from $wpdb->posts 
					where post_title like %s 
					and post_status='publish'";

				$sql = $wpdb->prepare($sql, $name);
				
				$results = $wpdb->get_results($sql);
		  ?>
		  <br/>
		  <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
		  		<thead>
		            <tr>
		                <th class="manage-column ss-list-width">Date</th>
		                <th class="manage-column ss-list-width">Title</th>
		            </tr>
		        </thead>
		        <tbody>
		            <?php foreach ($results as $row) { ?>
		                <tr>
		                    <td class="manage-column ss-list-width"><?php echo $row->post_date; ?></td>
		                    <td class="manage-column ss-list-width"><?php echo $row->post_title; ?></td>
		                </tr>
		            <?php } ?>
		        </tbody>
		     </table>
	    <?php } return ob_get_clean();
	}

add_shortcode( 'search_post_form', 'qwerk_search_form' );
}
?>