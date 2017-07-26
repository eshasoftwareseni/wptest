<?php
/*
Plugin Name: Testimonial Form Plugin
Description: Simple non-bloated WordPress Testimonial Form
Version: 1.0
Author: Esha Almaarif
License: none
*/

if (!function_exists( 'esha_options_install' )) {
	// function to create the DB / Options / Defaults					
	function esha_options_install() {

	    global $wpdb;

	    $table_name = $wpdb->base_prefix . "testimonial";
	    $charset_collate = $wpdb->get_charset_collate();
	    $sql = "CREATE TABLE $table_name (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(100) NOT NULL,
			  `email` varchar(50) DEFAULT NULL,
			  `phone` varchar(20) NOT NULL,
			  `testimonial` text NOT NULL,
			  `blog_id` bigint(20) NOT NULL,
			  PRIMARY KEY (`id`)
			) $charset_collate; ";

	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	    dbDelta($sql);
	}

	// run the install scripts upon plugin activation
	register_activation_hook(__FILE__, 'esha_options_install');
}


if (!function_exists( 'esha_admin_menu' )) {
	function esha_admin_menu() {
		//this is the main item for the menu
		add_menu_page('Testimonial', //page title
		'Testimonial', //menu title
		'manage_options', //capabilities
		'esha_testimonial_list', //menu slug
		'esha_testimonial_list' //function
		);
		
		//this is a submenu
		add_submenu_page('esha_testimonial_list', //parent slug
		'Add New Testimonial', //page title
		'Add New', //menu title
		'manage_options', //capability
		'esha_testimonial_add', //menu slug
		'esha_testimonial_add'); //function
	}

	add_action( 'admin_menu', 'esha_admin_menu' );
}

if (!function_exists( 'esha_testimonial_add' )) {
	function esha_testimonial_add() {
	    if(!empty($_POST["cf-name"])) $name = $_POST["cf-name"];
	    if(!empty($_POST["cf-email"])) $email = $_POST["cf-email"];
	    if(!empty($_POST["cf-phone"])) $phone = $_POST["cf-phone"];
	    if(!empty($_POST["cf-testimonial"])) $testimonial = $_POST["cf-testimonial"];
	    $message = '';
	    //insert
	    if (isset($_POST['cf-submitted'])) {
		    if(empty($_POST["cf-name"])) { $message = 'Name is empty';}
		    elseif(empty($_POST["cf-email"])) {$message = 'Email is empty';}
		    elseif(empty($_POST["cf-testimonial"])) {$message = 'Testimonial is empty';}
		    else{  	
		        global $wpdb,$blog_id;
		        $table_name = $wpdb->base_prefix . "testimonial";
		        $msql = $wpdb->insert(
		                $table_name, //table
		                array('name' => $name, 'email' => $email, 'phone' => $phone, 'testimonial' => $testimonial, 'blog_id' => $blog_id), //data
		                array('%s', '%s', '%s', '%s', '%s') //data format			
		        );
		        	        
		        $message = ($msql ==  true ? "Testimonial inserted" : "Error insert data");
	    	}
	    }
	    echo '<div class="wrap">';
	    echo '<h2>Testimonial</h2>';
	   if (isset($message)) echo '<p>' . $message . '</p>';
		echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
		echo '<p>';
		echo 'Your Name (required) <br/>';
		echo '<input type="text" name="cf-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="40" />';
		echo '</p>';
		echo '<p>';
		echo 'Your Email (required) <br/>';
		echo '<input type="email" name="cf-email" value="' . ( isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ) . '" size="40" />';
		echo '</p>';
		echo '<p>';
		echo 'Your Phone Number <br/>';
		echo '<input type="text" name="cf-phone" value="' . ( isset( $_POST["cf-phone"] ) ? esc_attr( $_POST["cf-phone"] ) : '' ) . '" size="40" />';
		echo '</p>';
		echo '<p>';
		echo 'Your Testimonial (required) <br/>';
		echo '<textarea rows="10" cols="35" name="cf-testimonial">' . ( isset( $_POST["cf-testimonial"] ) ? esc_attr( $_POST["cf-testimonial"] ) : '' ) . '</textarea>';
		echo '</p>';
		echo '<p><input type="submit" name="cf-submitted" value="Send"></p>';
		echo '</form>';
		echo '</div>';
	}
}

if (!function_exists( 'esha_testimonial_list' )) {
	function esha_testimonial_list(){
		?>
		 <div class="wrap">
	        <h2>Testimonial Admin Page</h2>
	        <div class="tablenav top">
	            <div class="alignleft actions">
	                <a href="<?php echo admin_url('admin.php?page=esha_testimonial_add'); ?>">Add New</a>
	            </div>
	            <br class="clear">
	        </div>
	        <?php
	        global $wpdb,$blog_id;
	        $table_name = $wpdb->base_prefix . "testimonial";

		//delete
		   if (isset($_GET['delete']) && isset($_GET['id'])) {
		   		$id = $_GET['id'];
		        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %s AND blog_id = %s", $id, $blog_id));
		    } 

	        $rows = $wpdb->get_results($wpdb->prepare("SELECT id,name,email,phone,testimonial from $table_name WHERE blog_id = %s", $blog_id));

	        ?>
	        <table class='wp-list-table widefat fixed striped posts'>
	            <tr>
	                <th class="manage-column ss-list-width">Name</th>
	                <th class="manage-column ss-list-width">Email</th>
	                <th class="manage-column ss-list-width">Phone</th>
	                <th class="manage-column ss-list-width">Testimonial</th>
	                <th>&nbsp;</th>
	            </tr>
	            <?php foreach ($rows as $row) { ?>
	                <tr>
	                    <td class="manage-column ss-list-width"><?php echo $row->name; ?></td>
	                    <td class="manage-column ss-list-width"><?php echo $row->email; ?></td>
	                    <td class="manage-column ss-list-width"><?php echo $row->phone; ?></td>
	                    <td class="manage-column ss-list-width"><?php echo $row->testimonial; ?></td>
	                    <td><a href="<?php echo admin_url('admin.php?page=esha_testimonial_list&delete=1&id=' . $row->id); ?>">Delete</a></td>
	                </tr>
	            <?php } ?>
	        </table>
	    </div>
		<?php
	}
}

if (!class_exists( 'eshaTestimonialWidget' )) {
	class eshaTestimonialWidget extends WP_Widget {
		/**
		 * Register widget with WordPress.
		 */
		function __construct() {
			parent::__construct(
				'eshaTestimonialWidget', // Base ID
				esc_html__( 'Testimonial', 'text_domain' ), // Name
				array( 'description' => esc_html__( 'A Testimonial Widget', 'text_domain' ), ) // Args
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}
			global $wpdb,$blog_id;
	        $table_name = $wpdb->base_prefix . "testimonial";
	        $testi = $wpdb->get_results($wpdb->prepare("SELECT name,testimonial from $table_name WHERE blog_id = %s ORDER BY RAND() LIMIT 1", $blog_id));

	        foreach ($testi as $s) {
	            $testimonial = $s->testimonial;
	            $name = $s->name;
	        }

			echo $testimonial . '<br><i>- '.$name.' -</i>';
			echo $args['after_widget'];
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
			?>
			<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<?php 
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

			return $instance;
		}

	}
}

if (!function_exists( 'esha_register_testimonial_widget' )) {
	// register Foo_Widget widget
	function esha_register_testimonial_widget() {
	    register_widget( 'eshaTestimonialWidget' );
	}

	add_action( 'widgets_init', 'esha_register_testimonial_widget' );
}

if (!function_exists( 'esha_testimonial_shortcode' )) {
	function esha_testimonial_shortcode() {
	    ob_start();
	    esha_testimonial_add();

	    return ob_get_clean();
	}

	add_shortcode( 'esha_testimonial_add_form', 'esha_testimonial_shortcode' );
}
?>