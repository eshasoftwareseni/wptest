<?php
/*
Plugin Name: Team Member
Description: Declares a plugin that will create a custom post type displaying team member.
Version: 1.0
Author: Esha
License: None
*/

if (!function_exists( 'create_team_member' )) {

    add_action( 'init', 'create_team_member' );

    function create_team_member() {
        register_post_type( 'team_members',
            array(
                'labels' => array(
                    'name' => 'Team Members',
                    'singular_name' => 'Team Member',
                    'add_new' => 'Add New',
                    'add_new_item' => 'Add New Team Member',
                    'edit' => 'Edit',
                    'edit_item' => 'Edit Team Member',
                    'new_item' => 'New Team Member',
                    'view' => 'View',
                    'view_item' => 'View Team Member',
                    'search_items' => 'Search Team Members',
                    'not_found' => 'No Team Members found',
                    'not_found_in_trash' => 'No Team Members found in Trash',
                    'parent' => 'Parent Team Member'
                ),
     
                'public' => true,
                'menu_position' => 15,
                'supports' => array( 'title', 'editor', 'comments', 'thumbnail'),
                'taxonomies' => array( ''),
                'menu_icon' => plugins_url( 'images/download.png', __FILE__ ),
                'has_archive' => true
            )
        );
    }
}

if (!function_exists( 'your_prefix_meta_boxes' )) {

    add_filter( 'rwmb_meta_boxes', 'your_prefix_meta_boxes' );

    function your_prefix_meta_boxes( $meta_boxes ) {
        $meta_boxes[] = array(
            'title'      => __( 'Other Information', 'textdomain' ),
            'post_types' => 'team_members',
            'fields'     => array(
                array(
                    'id'   => 'position',
                    'name' => __( 'Position', 'textdomain' ),
                    'type' => 'text',
                ),
                array(
                    'id'   => 'email',
                    'name' => __( 'Email', 'textdomain' ),
                    'type' => 'email',
                ),
                array(
                    'id'   => 'phone',
                    'name' => __( 'Phone', 'textdomain' ),
                    'type' => 'text',
                ),
                array(
                    'id'   => 'website',
                    'name' => __( 'Website', 'textdomain' ),
                    'type' => 'text',
                ),
                // IMAGE UPLOAD
                array(
                    'id'               => 'image_upload',
                    'name'             => esc_html__( 'Image', 'your-prefix' ),
                    'type'             => 'image_upload',
                    // Delete image from Media Library when remove it from post meta?
                    // Note: it might affect other posts if you use same image for multiple posts
                    'force_delete'     => false,
                    // Maximum image uploads
                    'max_file_uploads' => 1,
                    // Display the "Uploaded 1/2 files" status
                    'max_status'       => true,
                ),
            ),
        );
        return $meta_boxes;
    }
}

if (!function_exists( 'esha_team_member_shortcode' )) {

    function esha_team_member_shortcode( $atts ) {
            ob_start();?>
                <?php 
                $atts = shortcode_atts(
                            array(
                                'email' => 'true',
                                'phone' => 'true',
                                'website' => 'true',
                            ), $atts);                      
                $mypost = array( 'post_type' => 'team_members', );
                $loop = new WP_Query( $mypost );
                ?>
                <?php while ( $loop->have_posts() ) : $loop->the_post();?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                        <style type="text/css">
                            .circular-image img {
                               width: 150px;
                               height: 150px;
                               -webkit-border-radius: 50%;
                               -moz-border-radius: 50%;
                               -ms-border-radius: 50%;
                               -o-border-radius: 50%;
                                border-radius: 50%;
                                  }

                            .testimonial{
                                text-align: center;
                                }
                        </style>
             
                        <div class="testimonial">
                            <!-- Display featured image in right-aligned floating div -->

                            <div class="circular-image">
                                <img src="<?php echo wp_get_attachment_url( get_post_meta( get_the_ID(), 'image_upload', true ) ); ?>">
                            </div>
             
                            <!-- Display Title and Author Name -->
                            <strong><?php the_title(); ?> </strong>
                            <br />
                            <strong><?php echo esc_html( get_post_meta( get_the_ID(), 'position', true ) ); ?></strong>
                            <br />
             
                        <!-- Display movie review contents -->
                        <?php the_content(); ?>
                            <br />
                        <?php if($atts['email'] == 'true') echo 'Mail: ' . esc_html( get_post_meta( get_the_ID(), 'email', true ) ); ?>
                            <br /> 
                        <?php if($atts['phone'] == 'true') echo 'Phone: ' . esc_html( get_post_meta( get_the_ID(), 'phone', true ) ); ?>
                            <br />
                        <?php if($atts['website'] == 'true') echo 'Site: ' . esc_html( get_post_meta( get_the_ID(), 'website', true ) ); ?>
                        </div>
                    </article>
             
                <?php endwhile; ?>
            <?php wp_reset_query();

            return ob_get_clean();
        }

        add_shortcode( 'team_member_code', 'esha_team_member_shortcode' );
}
?>