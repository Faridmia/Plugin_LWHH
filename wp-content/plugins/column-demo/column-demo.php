<?php
/**
 * @package Column Demo
 * @version 1.0
 */
/*
Plugin Name:Column Demo Farid
Plugin URI: http://wordpress.org/plugins/word-count/
Description: Count word from any wordpress post.
Author: Faridmia
Version: 1.0
License:GPLv2 or Later
Domain Path: /languages/
Author URI: www.farid.me/
Textdomain:columndemo
*/

// function wpdocs_widget_enqueue_script()
// {   
//     wp_enqueue_script( 'my_custom_script', plugin_dir_url( __FILE__ ) . 'assets/js/script.js' );
// }
// add_action('admin_enqueue_scripts', 'wpdocs_widget_enqueue_script');


function columndemo_activation_hook(){

}

register_activation_hook(__FILE__,"columndemo_activation_hook");

function columndemo_deactivation_hook(){

}

register_deactivation_hook(__FILE__,"columndemo_deactivation_hook");


function columndemo_load_textdomain(){
    load_plugin_textdomain("columndemo",false,dirname(__FILE__)."/languages");// deprecate parameter ta sob sob somoi false hobe
}

add_action("plugins_loaded","columndemo_load_textdomain");

function columndemo_manage_posts_column($columns){

   // print_r($columns);

    unset($columns['tags']);
    unset($columns['comments']);

    $columns['id'] = __('Post Id','column-demo');
    $columns['thumbnail'] = __('Thumbnail','column-demo');
    $columns['wordcount'] = __('Word Count','column-demo');

    return $columns;
}

add_filter( 'manage_posts_columns', 'columndemo_manage_posts_column' );
add_filter( 'manage_pages_columns', 'columndemo_manage_posts_column' );

function coldemo_post_column_data($column,$post_id){

    if('id' == $column){
        echo $post_id;
    }elseif('thumbnail' == $column){
        $img = get_the_post_thumbnail($post_id,array(100,100));
        echo $img;
    }
    elseif('wordcount' == $column){
        // $_post = get_post($post_id);
        // $content = $_post->post_content;
        // $wordn = str_word_count(strip_tags($content));

        $wordn = get_post_meta($post_id,'wordn',true);
        echo $wordn;
    }
   
}

add_action("manage_posts_custom_column","coldemo_post_column_data",10,2);
add_action("manage_pages_custom_column","coldemo_post_column_data",10,2);

function coldemo_sortable_column($columns){
    $columns['wordcount'] = 'wordn';

    return $columns;
}

add_filter("manage_edit-post_sortable_columns","coldemo_sortable_column");

// function coldemo_set_word_count(){
//     $post_arg = get_posts(array(
//         'posts_per_page' => -1,
//         'post_type' => array('post','book'),

//     ));

//     foreach($post_arg as $p){
//         $content = $p->post_content;
//         $wordn = str_word_count(strip_tags($content)); 

//         update_post_meta($p->ID,'wordn',$wordn);
//     }
// }

// add_action("init","coldemo_set_word_count");

function coldemo_sort_column_data($wpquery){

    if(!is_admin()){
        return;
    }

    $orderby = $wpquery->get('orderby');
    if('wordn' == $orderby){
        $wpquery->set('meta_key','wordn');
        $wpquery->set('orderby','meta_value_num');
    }

}

add_action('pre_get_posts','coldemo_sort_column_data');

function coldemo_update_post_save($post_id){
        $p = get_post($post_id);
        $content = $p->post_content;
        $wordn = str_word_count(strip_tags($content));
        update_post_meta($p->ID,'wordn',$wordn);
}

add_action("save_post","coldemo_update_post_save");

function coldemo_filter(){
    if(isset($_GET['post_type']) && $_GET['post_type'] == 'book'){
        $filter = isset($_GET['demofilter']) ? $_GET['demofilter'] : '';

        $values = [
            '0' => 'Select Status',
            '1' => 'Some Post',
            '2' => 'Some Posts++'

        ];
    ?>
    <select name="demofilter">
       <?php 
            foreach($values as $key => $value){
                printf("<option value='%s' %s>%s</option>",$key,
                    $key == $filter ? "selected = 'selected'" : '',
                    $value
                );
            }
       ?>
    </select>
    <?php
    }
}

add_action( 'restrict_manage_posts', 'coldemo_filter', 10, 1 );


function coldemo_filter_data($wpquery){
    if(!is_admin()){
        return;
    }

    $filter = isset($_GET['demofilter']) ? $_GET['demofilter'] : '';

    if('1' == $filter){
        $wpquery->set('post__in',array(12,77,79));
    }
}

add_action("pre_get_posts","coldemo_filter_data");


function thumbnail_coldemo_filter(){
    if(isset($_GET['post_type']) && $_GET['post_type'] == 'book'){
        $filter = isset($_GET['thumbnail_filter']) ? $_GET['thumbnail_filter'] : '';

        $values = [
            '0' => 'Thumbnail Status',
            '1' => 'Has Thumbnail',
            '2' => 'No Thumbnail'

        ];
    ?>
    <select name="thumbnail_filter">
       <?php 
            foreach($values as $key => $value){
                printf("<option value='%s' %s>%s</option>",$key,
                    $key == $filter ? "selected = 'selected'" : '',
                    $value
                );
            }
       ?>
    </select>
    <?php
    }
}

add_action( 'restrict_manage_posts', 'thumbnail_coldemo_filter', 10, 1 );


function coldemo_thumbnail_filter_data($wpquery){
    if(!is_admin()){
        return;
    }

    $thumbnail_filter = isset($_GET['thumbnail_filter']) ? $_GET['thumbnail_filter'] : '';

    if('1' == $thumbnail_filter){
        $wpquery->set('meta_query',array(
            array(
                'key' => '_thumbnail_id',
                'compare' => 'EXISTS'
            )
        ));
    }elseif('2' == $thumbnail_filter){ 
        $wpquery->set('meta_query',array(
            array(
                'key' => '_thumbnail_id',
                'compare' => 'NOT EXISTS'
            )
        ));
    }
}

add_action("pre_get_posts","coldemo_thumbnail_filter_data");


function wc_filter_coldemo_filter(){
    if(isset($_GET['post_type']) && $_GET['post_type'] == 'book'){
        $filter = isset($_GET['wc_filter']) ? $_GET['wc_filter'] : '';

        $values = [
            '0' => 'word count',
            '1' => 'Above 50',
            '2' => '30 to 40',
            '3' => 'below 20'

        ];
    ?>
    <select name="wc_filter">
       <?php 
            foreach($values as $key => $value){
                printf("<option value='%s' %s>%s</option>",$key,
                    $key == $filter ? "selected = 'selected'" : '',
                    $value
                );
            }
       ?>
    </select>
    <?php
    }
}

add_action( 'restrict_manage_posts', 'wc_filter_coldemo_filter', 10, 1 );


function wc_filter_data($wpquery){
    if(!is_admin()){
        return;
    }

    $wordcount_filter = isset($_GET['wc_filter']) ? $_GET['wc_filter'] : '';

    if('1' == $wordcount_filter){
        $wpquery->set('meta_query',array(
            array(
                'key' => 'wordn',
                'value' => 50,
                'compare' => '>=',
                'type' => 'NUMERIC'
            )
        ));
    } elseif('2' == $wordcount_filter){
        $wpquery->set('meta_query',array(
            array(
                'key' => 'wordn',
                'value' => array(30,40),
                'compare' => 'BETWEEN',
                'type' => 'NUMERIC'
            )
        ));
    }

    elseif('3' == $wordcount_filter){
        $wpquery->set('meta_query',array(
            array(
                'key' => 'wordn',
                'value' => 20,
                'compare' => '<=',
                'type' => 'NUMERIC'
            )
        ));
    }
}

add_action("pre_get_posts","wc_filter_data");
