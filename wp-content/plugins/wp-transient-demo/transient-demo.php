<?php
/*
Plugin Name: Transient Demo
Plugin URI:
Description: Demonstration of transient API
Version: 1.0.0
Author: LWHH
Author URI:
License: GPLv2 or later
Text Domain: transient-demo
 */

add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( 'toplevel_page_transient-demo' == $hook ) {
        wp_enqueue_style( 'pure-grid-css', '//unpkg.com/purecss@1.0.1/build/grids-min.css' );
        wp_enqueue_style( 'transient-demo-css', plugin_dir_url( __FILE__ ) . "assets/css/style.css", null, time() );
        wp_enqueue_script( 'transient-demo-js', plugin_dir_url( __FILE__ ) . "assets/js/main.js", array( 'jquery' ), time(), true );
        $nonce = wp_create_nonce( 'transient_display_result' );
        wp_localize_script(
            'transient-demo-js',
            'plugindata',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'nonce' => $nonce )
        );
    }
} );

add_action( 'wp_ajax_transient_display_result', function () {
    global $transient;
    $table_name = $transient->prefix . 'persons';
    if ( wp_verify_nonce( $_POST['nonce'], 'transient_display_result' ) ) {
        $task = $_POST['task'];
        if ( 'add-transient' == $task ) {
            $key = 'tr-country';
            $value = 'Bangladesh';
            echo "Result = " . set_transient( $key, $value );
        } elseif ( 'set-expiry' == $task ) { 
            $key = 'tr-capital';
            $value = 'Dhaka';
            $expiry = '60x1';
            echo "Result = " . set_transient( $key, $value,$expiry);
        }
        elseif ( 'get-transient' == $task ) { 
            $key1 = 'tr-country';
            $key2 = 'tr-capital';
            echo "Result1 = " . get_transient( $key1)."<br/>";
            echo "Result2 = " . get_transient( $key2)."<br/>";
        }
        elseif ( 'importance' == $task ) { 
            $key1 = 'tr-country';
            $result = get_transient( $key1);

            if($result == false){
                echo "Country Not Found";
            }else{
                echo $result;
            }
            echo "<br/>";
            $key2 = 'tr-temparature-faridpur';
            $value = '0';
            set_transient($key2,$value);
            $result = get_transient( $key2);

            if($result === false){
                echo "Faridpur Temparature Not Found";
            }else{
                echo "Today Temp Faridpur is ". $result ."Degree";
            }

            
        }

        elseif ( 'add-complex-transient' == $task ) { 
            global $wpdb;
            $result = $wpdb->get_results("select post_title from wp_posts order by id desc limit 10",ARRAY_A);
            $key = 'tr-latest-post';
            set_transient($key,$result);
            $later = get_transient($key);
            print_r($later);

        }

        elseif ( 'transient-filter-hook' == $task ) { 
            $key1 = 'tr-country';
            echo "Result1 = " . get_transient( $key1)."<br/>";
        }
        elseif ( 'delete-transient' == $task ) { 
            $key1 = 'tr-country';
            echo "Before Delete = " . get_transient( $key1)."<br/>";

            delete_transient($key1);
            echo "After Delete = " . get_transient( $key1)."<br/>";
        }
    }
    die( 0 );
} );

add_filter( 'pre_transient_tr-country', function ( $result ) {
    return false;
    return "BANGLDAESH MY LOVE";
} );

add_action( 'admin_menu', function () {
    add_menu_page( 'Transient Demo', 'Transient Demo', 'manage_options', 'transient-demo', 'transientdemo_admin_page' );
} );

function transientdemo_admin_page() {
    ?>
        <div class="container" style="padding-top:20px;">
            <h1>Transient Demo</h1>
            <div class="pure-g">
                <div class="pure-u-1-4" style='height:100vh;'>
                    <div class="plugin-side-options">
                        <button class="action-button" data-task='add-transient'>Add New transient</button>
                        <button class="action-button" data-task='set-expiry'>Set Expiry</button>
                        <button class="action-button" data-task='get-transient'>Display Transient</button>
                        <button class="action-button" data-task='importance'>Importance of ===</button>
                        <button class="action-button" data-task='add-complex-transient'>Add Complex Transient</button>
                        <button class="action-button" data-task='transient-filter-hook'>Transient Filter Hook</button>
                        <button class="action-button" data-task='delete-transient'>Delete Transient</button>
                    </div>
                </div>
                <div class="pure-u-3-4">
                    <div class="plugin-demo-content">
                        <h3 class="plugin-result-title">Result</h3>
                        <div id="plugin-demo-result" class="plugin-result"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php
}
