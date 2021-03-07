<?php
/**
 * Plugin Name:       Notice Minja
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            farid mia
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       notice-minja
 * Domain Path:       /languages
 * **/
 

function notice_minja_admin_notice__success() {

    global $pagenow;
    //if(!(isset($_COOKIE['nn-close']) && $_COOKIE['nn-close'] == 1)){
      //  if('index.php' == $pagenow || 'themes.php' == $pagenow){
            if(in_array($pagenow,['index.php','themes.php','plugins.php'])){
    ?>
    <div id="noticeninja" class="notice notice-success is-dismissible notice-dismiss notice-custom">
        <?php 
        $remote_data = wp_remote_get("https://books.hasin.me/notice.php");
        $remote_body = wp_remote_retrieve_body( $remote_data);
        if($remote_body != ''){
        ?>
        <h2><?php echo $remote_body; ?></h2>
        <h1>Here is a Heading</h1>
        <p>Hey Here is some information for you <?php echo $pagenow;?></p>
        <?php } ?>
    </div>
    <?php
        }
   // }
}
add_action( 'admin_notices', 'notice_minja_admin_notice__success' );


add_action("admin_enqueue_scripts",function(){


    wp_enqueue_script( 'notice-ninja-js', plugin_dir_url( __FILE__ ) . 'assets/js/script.js', array('jquery'), '1.0' );
    wp_enqueue_style( 'notice-css', plugin_dir_url( __FILE__ ) . 'assets/css/style.css',null,time() );

});