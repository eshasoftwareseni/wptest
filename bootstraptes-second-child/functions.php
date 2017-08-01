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
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-autocomplete');
		wp_enqueue_script('dataTables', get_stylesheet_directory_uri().'/js/jquery.dataTables.min.js', array('jquery'));
		wp_enqueue_script('dataTables.bootstrap', get_stylesheet_directory_uri().'/js/dataTables.bootstrap.min.js');
				
		// Register the script
		wp_register_script('mysite-js', get_stylesheet_directory_uri().'/js/mysite.js', array('jquery', 'jquery-ui-autocomplete'));

		// Localize the script with new data
		$translation_array = array(
			'url' => __( admin_url() . 'admin-ajax.php' ),
			'data' => __( 'action=get_listing_names&name=' )
		);
		wp_localize_script( 'mysite-js', 'object_name', $translation_array );

		// Enqueued script with localized data.
		wp_enqueue_script( 'mysite-js' );
		
		wp_enqueue_style('dataTables.css', get_stylesheet_directory_uri().'/css/dataTables.bootstrap.min.css');
	}

	add_action('wp_enqueue_scripts', 'mysite_js');
}

if ( !function_exists( 'ajax_listings' ) ) {
	//get listings for 'works at' on submit listing page
	add_action('wp_ajax_nopriv_get_listing_names', 'ajax_listings');
	add_action('wp_ajax_get_listing_names', 'ajax_listings');

	function ajax_listings() {
		$keyword = sanitize_text_field($_POST['name']);		    	
		$query = new WP_Query(array('s' => $keyword, 'post_status' => 'publish'));
		$titles = array();
		
		while ( $query->have_posts() ) : $query->the_post();
			$titles[] = addslashes(get_the_title());
		endwhile;

		// Restore original Post Data
		wp_reset_postdata();

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
		    $keyword = sanitize_text_field($_POST['sulname']);		    	
			$query = new WP_Query(array('s' => $keyword, 'post_status' => 'publish'));
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
		        <?php if ( $query->have_posts() ) {
        		while ( $query->have_posts() ) : $query->the_post();?>
		            <tr>
		                <td class="manage-column ss-list-width"><?php echo get_the_date('l F j, Y'); ?></td>
		                <td class="manage-column ss-list-width"><?php echo get_the_title(); ?></td>
		            </tr>
		        <?php endwhile; } ?>
		        </tbody>
		     </table>
	    <?php } 

		// Restore original Post Data
		wp_reset_postdata();

		return ob_get_clean();
	}

add_shortcode( 'search_post_form', 'qwerk_search_form' );
}
?>