<?php
/**
 * @package Custom Post Farid
 * @version 1.0
 */
/*
Plugin Name:Custom Post Farid
Plugin URI: http://wordpress.org/plugins/word-count/
Description: Count word from any wordpress post.
Author: Faridmia
Version: 1.0
License:GPLv2 or Later
Domain Path: /languages/
Author URI: www.farid.me/
Textdomain:custom-post-farid
*/

function wpdocs_widget_enqueue_script()
{   
    wp_enqueue_script( 'my_custom_script', plugin_dir_url( __FILE__ ) . 'assets/js/script.js' );
}
add_action('admin_enqueue_scripts', 'wpdocs_widget_enqueue_script');


function custompostfarid_activation_hook(){

}

register_activation_hook(__FILE__,"custompostfarid_activation_hook");

function custompostfaridt_deactivation_hook(){

}

register_deactivation_hook(__FILE__,"custompostfaridt_deactivation_hook");


function wordcount_load_textdomain(){
    load_plugin_textdomain("custom-post-farid",false,dirname(__FILE__)."/languages");// deprecate parameter ta sob sob somoi false hobe
}

add_action("plugins_loaded","wordcount_load_textdomain");


/**
 * Register a custom post type called "book".
 *
 * @see get_post_type_labels() for label keys.
 */
function wpdocs_codex_book_init() {
    $labels = array(
        'name'                  => _x( 'Books', 'Post type general name', 'textdomain' ),
        'singular_name'         => _x( 'Book', 'Post type singular name', 'textdomain' ),
        'menu_name'             => _x( 'Books', 'Admin Menu text', 'textdomain' ),
        'name_admin_bar'        => _x( 'Book', 'Add New on Toolbar', 'textdomain' ),
        'add_new'               => __( 'Add New', 'textdomain' ),
        'add_new_item'          => __( 'Add New Book', 'textdomain' ),
        'new_item'              => __( 'New Book', 'textdomain' ),
        'edit_item'             => __( 'Edit Book', 'textdomain' ),
        'view_item'             => __( 'View Book', 'textdomain' ),
        'all_items'             => __( 'All Books', 'textdomain' ),
        'search_items'          => __( 'Search Books', 'textdomain' ),
        'parent_item_colon'     => __( 'Parent Books:', 'textdomain' ),
        'not_found'             => __( 'No books found.', 'textdomain' ),
        'not_found_in_trash'    => __( 'No books found in Trash.', 'textdomain' ),
        'featured_image'        => _x( 'Book Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'archives'              => _x( 'Book archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
        'insert_into_item'      => _x( 'Insert into book', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this book', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
        'filter_items_list'     => _x( 'Filter books list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
        'items_list_navigation' => _x( 'Books list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
        'items_list'            => _x( 'Books list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
    );
 
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'book' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
    );
 
    register_post_type( 'book', $args );
}
 
add_action( 'init', 'wpdocs_codex_book_init' );


/**
 * Create two taxonomies, genres and writers for the post type "book".
 *
 * @see register_post_type() for registering custom post types.
 */
function wpdocs_create_book_taxonomies() {
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
        'name'              => _x( 'Genres', 'taxonomy general name', 'textdomain' ),
        'singular_name'     => _x( 'Genre', 'taxonomy singular name', 'textdomain' ),
        'search_items'      => __( 'Search Genres', 'textdomain' ),
        'all_items'         => __( 'All Genres', 'textdomain' ),
        'parent_item'       => __( 'Parent Genre', 'textdomain' ),
        'parent_item_colon' => __( 'Parent Genre:', 'textdomain' ),
        'edit_item'         => __( 'Edit Genre', 'textdomain' ),
        'update_item'       => __( 'Update Genre', 'textdomain' ),
        'add_new_item'      => __( 'Add New Genre', 'textdomain' ),
        'new_item_name'     => __( 'New Genre Name', 'textdomain' ),
        'menu_name'         => __( 'Genre', 'textdomain' ),
    );
 
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'genre' ),
    );
 
    register_taxonomy( 'genre', array( 'book' ), $args );
 
    unset( $args );
    unset( $labels );
 
    // Add new taxonomy, NOT hierarchical (like tags)
    $labels = array(
        'name'                       => _x( 'Writers', 'taxonomy general name', 'textdomain' ),
        'singular_name'              => _x( 'Writer', 'taxonomy singular name', 'textdomain' ),
        'search_items'               => __( 'Search Writers', 'textdomain' ),
        'popular_items'              => __( 'Popular Writers', 'textdomain' ),
        'all_items'                  => __( 'All Writers', 'textdomain' ),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => __( 'Edit Writer', 'textdomain' ),
        'update_item'                => __( 'Update Writer', 'textdomain' ),
        'add_new_item'               => __( 'Add New Writer', 'textdomain' ),
        'new_item_name'              => __( 'New Writer Name', 'textdomain' ),
        'separate_items_with_commas' => __( 'Separate writers with commas', 'textdomain' ),
        'add_or_remove_items'        => __( 'Add or remove writers', 'textdomain' ),
        'choose_from_most_used'      => __( 'Choose from the most used writers', 'textdomain' ),
        'not_found'                  => __( 'No writers found.', 'textdomain' ),
        'menu_name'                  => __( 'Writers', 'textdomain' ),
    );
 
    $args = array(
        'hierarchical'          => false,
        'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'writer' ),
    );
 
    register_taxonomy( 'writer', 'book', $args );
}





//Register Meta Box
function rm_register_meta_box() {
    add_meta_box( 'rm-meta-box-id', esc_html__( 'RM MetaBox Title', 'text-domain' ), 'rm_meta_box_callback', 'post', 'advanced', 'high' );
}
add_action( 'add_meta_boxes', 'rm_register_meta_box');
 
//Add field
function rm_meta_box_callback( $meta_id ) {
 
    $outline = '<label for="title_field" style="width:150px; display:inline-block;">'. esc_html__('Title Field', 'text-domain') .'</label>';
    $title_field = get_post_meta( $meta_id->ID, 'title_field', true );
    $outline .= '<input type="text" name="title_field" id="title_field" class="title_field" value="'. esc_attr($title_field) .'" style="width:300px;"/>';
 
    echo $outline;
}


/**
 * Calls the class on the post edit screen.
 */
function call_someClass() {
    new someClass();
}
 
if ( is_admin() ) {
    add_action( 'load-post.php',     'call_someClass' );
    add_action( 'load-post-new.php', 'call_someClass' );
}
 
/**
 * The Class.
 */
class someClass {
 
    /**
     * Hook into the appropriate actions when the class is constructed.
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post',      array( $this, 'save'         ) );
    }
 
    /**
     * Adds the meta box container.
     */
    public function add_meta_box( $post_type ) {
        // Limit meta box to certain post types.
        $post_types = array( 'post', 'page','book' );
 
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'some_meta_box_name',
                __( 'Some Meta Box Headline', 'textdomain' ),
                array( $this, 'render_meta_box_content' ),
                $post_type,
                'advanced',
                'high'
            );
        }
    }
 
    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id ) {
 
        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */
 
        // Check if our nonce is set.
        if ( ! isset( $_POST['myplugin_inner_custom_box_nonce'] ) ) {
            return $post_id;
        }
 
        $nonce = $_POST['myplugin_inner_custom_box_nonce'];
 
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'myplugin_inner_custom_box' ) ) {
            return $post_id;
        }
 
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
 
        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
 
        /* OK, it's safe for us to save the data now. */
 
        // Sanitize the user input.
        $heading_one = sanitize_text_field( $_POST['heading_one'] );
        $heading_two = sanitize_text_field( $_POST['heading_two'] );
        $heading_three = sanitize_text_field( $_POST['heading_three'] );
        $heading_four = sanitize_text_field( $_POST['heading_four'] );
        $heading_five = sanitize_text_field( $_POST['heading_five'] );
        $heading_six = sanitize_text_field( $_POST['heading_six'] );
 
        // Update the meta field.
        update_post_meta( $post_id, '_my_meta_value_key_one', $heading_one );
        update_post_meta( $post_id, '_my_meta_value_key_two', $heading_two );
        update_post_meta( $post_id, '_my_meta_value_key_three', $heading_three );
        update_post_meta( $post_id, '_my_meta_value_key_four', $heading_four );
        update_post_meta( $post_id, '_my_meta_value_key_five', $heading_five );
        update_post_meta( $post_id, '_my_meta_value_key_six', $heading_six );

    }
 
 
    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content( $post ) {
 
        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'myplugin_inner_custom_box', 'myplugin_inner_custom_box_nonce' );
 
        // Use get_post_meta to retrieve an existing value from the database.
        $heading_one = get_post_meta( $post->ID, '_my_meta_value_key_one', true );
        $heading_two = get_post_meta( $post->ID, '_my_meta_value_key_two', true );
        $heading_three = get_post_meta( $post->ID, '_my_meta_value_key_three', true );
        $heading_four = get_post_meta( $post->ID, '_my_meta_value_key_four', true );
        $heading_five = get_post_meta( $post->ID, '_my_meta_value_key_five', true );
        $heading_six = get_post_meta( $post->ID, '_my_meta_value_key_six', true );
 
        // Display the form, using the current value.
        ?>
        <label for="heading_one">
            <?php _e( 'Heading One', 'textdomain' ); ?>
        </label>
        <input type="text" id="heading_one" name="heading_one" value="<?php echo esc_attr( $heading_one ); ?>" size="25" />
        <label for="heading_two">
            <?php _e( 'Heading Two', 'textdomain' ); ?>
        </label>
        <input type="text" id="heading_two" name="heading_two" value="<?php echo esc_attr( $heading_two ); ?>" size="25" />
        <label for="heading_three">
            <?php _e( 'Heading Three', 'textdomain' ); ?>
        </label>
        <input type="text" id="heading_three" name="heading_three" value="<?php echo esc_attr( $heading_three ); ?>" size="25" /><br/>
        <label for="heading_four">
            <?php _e( 'Heading Four', 'textdomain' ); ?>
        </label>
        <input type="text" id="heading_four" name="heading_four" value="<?php echo esc_attr( $heading_four ); ?>" size="25" />
        <label for="heading_five">
            <?php _e( 'Heading Five', 'textdomain' ); ?>
        </label>
        <input type="text" id="heading_five" name="heading_five" value="<?php echo esc_attr( $heading_five ); ?>" size="25" />
        <label for="heading_six">
            <?php _e( 'Heading Six', 'textdomain' ); ?>
        </label>
        <input type="text" id="heading_six" name="heading_six" value="<?php echo esc_attr( $heading_six ); ?>" size="25" />
        <?php
    }
}
