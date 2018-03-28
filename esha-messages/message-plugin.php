<?php
/*
Plugin Name: Messages Form Plugin
Description: Simple non-bloated WordPress Messages Form
Version: 2.0
Author: Esha Almaarif
License: none
*/

if (!class_exists( 'eshaMessagesClass' )) {
    class eshaMessagesClass {

        protected static $instance = null;

        public static function instance() {
            if (null == self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        function __construct() {
            //add new shortcode
            add_shortcode( 'esha_messages_add_form', array($this, 'esha_messages_shortcode' ));

            // add new admin menu
            add_action( 'admin_menu', array($this, 'esha_admin_menu' ));

            // run the install scripts upon plugin activation
            register_activation_hook(__FILE__, array($this, 'esha_options_install'));
        }

        /**
         * Create the DB / Options / Defaults
         */                                       
        protected function esha_options_install() {
            global $wpdb;
            $table_name = $wpdb->prefix . "messages";
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE $table_name (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `name` varchar(100) NOT NULL,
                          `email` varchar(50) DEFAULT NULL,
                          `messages` text NOT NULL,
                          PRIMARY KEY (`id`)
                        ) $charset_collate; ";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($sql);
        }

        /**
         * Create admin menu on Back End
         */
        public function esha_admin_menu() {
                //this is the main item for the menu
                add_menu_page('Messages', //page title
                'Messages', //menu title
                'manage_options', //capabilities
                'esha_messages_list', //menu slug
                array($this,'esha_messages_list'), //function
                'dashicons-email' //menu icon
                );
                
                //this is a submenu
                add_submenu_page('esha_messages_list', //parent slug
                'Add New Messages', //page title
                'Add New', //menu title
                'manage_options', //capability
                'esha_messages_add', //menu slug
                array($this,'esha_messages_add')); //function
        }

        /**
         * Create form input
         */
        public function esha_messages_add() {
            if(!empty($_POST["input_name"])) $name = $_POST["input_name"];
            if(!empty($_POST["input_email"])) $email = $_POST["input_email"];
            if(!empty($_POST["input_messages"])) $messages = $_POST["input_messages"];

            $message = '';

            // insert data
            if (isset($_POST['input_submitted'])) {
                if(empty($_POST["input_name"])) { $message = 'Please Fill Your Name'; }
                elseif(empty($_POST["input_email"])) { $message = 'Please Fill Your Email'; }
                elseif(empty($_POST["input_messages"])) { $message = 'Please Fill Your Messages'; }
                else{       
                    global $wpdb;
                    $table_name = $wpdb->prefix . "messages";
                    $wpdb->insert(
                            $table_name, //table
                            array('name' => $name, 'email' => $email, 'messages' => $messages), //data
                            array('%s', '%s', '%s', '%s') //data format                     
                    );
                    $message="Messages inserted";

                    $to = '<esha@softwareseni.com>'; //email admin
                    $subject = 'User Messages';
                    $headers = 'From: '.$name.' '.'<'.$email.'>' . "\r\n";
                    $message_mail .= 'Name: '. $name. "\r\n\r\n";
                    $message_mail .= 'Message: '. $messages. "\r\n\r\n";

                    //sent email
                    wp_mail( $to, $subject, $message_mail, $headers );
                }
            } 
            ?>
            <div class="wrap">
            <h2>Messages</h2>
            <?php if (isset($message)) echo '<p style="color: red;">' . $message . '</p>'; ?>
                <form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post">
                    <p> Your Name (required) <br/>
                        <input type="text" name="input_name" pattern="[a-zA-Z0-9 ]+" value="<?php echo ( isset( $_POST["input_name"] ) ? esc_attr( $_POST["input_name"] ) : '' ); ?>" size="40" />
                    </p>
                    <p>Your Email (required) <br/>
                        <input type="email" name="input_email" value="<?php echo ( isset( $_POST["input_email"] ) ? esc_attr( $_POST["input_email"] ) : '' ); ?>" size="40" />
                    </p>
                    <p>Your Messages (required) <br/>
                        <textarea rows="10" cols="35" name="input_messages"><?php echo ( isset( $_POST["input_messages"] ) ? esc_attr( $_POST["input_messages"] ) : '' ); ?></textarea>
                    </p>
                    <p><input type="submit" name="input_submitted" value="Send"></p>
                </form>
            </div>
            <?php
        }

        /**
         * Create Wordpress Shortcode
         */
        public function esha_messages_shortcode() {
            ob_start();
            $this->esha_messages_add();
            return ob_get_clean();
        }

        /**
         * Create table list of messages on Back End
         */
        public function esha_messages_list() {
            ?>
            <div class="wrap">
                <h2>Messages Admin Page</h2>
                <div class="tablenav top">
                    <div class="alignleft actions">
                        <a href="<?php echo admin_url('admin.php?page=esha_messages_add'); ?>">Add New</a>
                    </div>
                    <br class="clear">
                </div>
                <?php
                global $wpdb;
                $table_name = $wpdb->prefix . "messages";

                // delete data
                if (isset($_GET['delete']) && isset($_GET['id'])) {
                            $id = $_GET['id'];
                    $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %s", $id));
                }

                // list of data 
                $rows = $wpdb->get_results("SELECT id,name,email,messages from $table_name");
                ?>
                <table class='wp-list-table widefat fixed striped posts'>
                    <tr>
                        <th class="manage-column ss-list-width">Name</th>
                        <th class="manage-column ss-list-width">Email</th>
                        <th class="manage-column ss-list-width">Messages</th>
                        <th>&nbsp;</th>
                    </tr>
                    <?php foreach ($rows as $row) { ?>
                        <tr>
                            <td class="manage-column ss-list-width"><?php echo $row->name; ?></td>
                            <td class="manage-column ss-list-width"><?php echo $row->email; ?></td>
                            <td class="manage-column ss-list-width"><?php echo $row->messages; ?></td>
                            <td><a href="<?php echo admin_url('admin.php?page=esha_messages_list&delete=1&id=' . $row->id); ?>">Delete</a></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            <?php
        }
    }
}

if (!function_exists('eshaMessagesClass')) {
    /**
     * Call instance function
     *
     * @return instance value
     */
    function esha_messages_class() {
        return eshaMessagesClass::instance();
    }

}

esha_messages_class();


if (!class_exists( 'eshaMessagesWidget' )) {
        class eshaMessagesWidget extends WP_Widget {

            /**
             * Register widget with WordPress.
             */
            function __construct() {
                parent::__construct(
                        'eshaMessagesWidget', // Base ID
                        esc_html__( 'Messages', 'text_domain' ), // Name
                        array( 'description' => esc_html__( 'A Messages Widget', 'text_domain' ), ) // Args
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
                global $wpdb;
                $table_name = $wpdb->prefix . "messages";
                $testi = $wpdb->get_results("SELECT name,email,messages from $table_name"); ?>                
                <table class='wp-list-table widefat fixed striped posts'>
                    <tr>
                        <th class="manage-column ss-list-width">Name</th>
                        <th class="manage-column ss-list-width">Email</th>
                        <th class="manage-column ss-list-width">Messages</th>
                    </tr>
                    <?php foreach ($testi as $s) { ?>
                        <tr>
                            <td class="manage-column ss-list-width"><?php echo $s->name; ?></td>
                            <td class="manage-column ss-list-width"><?php echo $s->email; ?></td>
                            <td class="manage-column ss-list-width"><?php echo $s->messages; ?></td>
                        </tr>
                    <?php } ?>
                </table>
                <?php echo $args['after_widget'];
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

if (!function_exists( 'esha_register_messages_widget' )) {
    /**
    * Register Foo_Widget widget
    */
    function esha_register_messages_widget() {
        register_widget( 'eshaMessagesWidget' );
    }
    add_action( 'widgets_init', 'esha_register_messages_widget' );
}
