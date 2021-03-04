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
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e( 'Hey Here is some information for you!', 'notice-minja' ); ?></p>
    </div>
    <?php
}
add_action( 'admin_notices', 'notice_minja_admin_notice__success' );