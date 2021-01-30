<?php
/**
 * @package Word Count
 * @version 1.0
 */
/*
Plugin Name:Word Count
Plugin URI: http://wordpress.org/plugins/word-count/
Description: Count word from any wordpress post.
Author: Faridmia
Version: 1.0
License:GPLv2 or Later
Domain Path: /languages/
Author URI: www.farid.me/
*/


function wordcount_activation_hook(){

}

register_activation_hook(__FILE__,"wordcount_activation_hook");

function wordcount_deactivation_hook(){

}

register_deactivation_hook(__FILE__,"wordcount_deactivation_hook");


function wordcount_load_textdomain(){
    load_plugin_textdomain("word-count",false,dirname(__FILE__)."/languages");// deprecate parameter ta sob sob somoi false hobe
}

add_action("plugins_loaded","wordcount_load_textdomain");


function wordcount_count_words($content){

    $stripped_content = strip_tags($content);

    $wordn = str_word_count($stripped_content);

    $label = __("Total number of words","word-count");

    $label = apply_filters("wordcount_heading<br/>",$label);

    $tag = apply_filters('wordcount_tag','h2');

    $content .= sprintf('<%s>%s: %s</%s>',$tag,$label,$wordn,$tag);

    return $content;


}

add_filter("the_content","wordcount_count_words");

function wordcount_count_reading_time($content){

    $stripped_content = strip_tags($content);

    $wordn = str_word_count($stripped_content);

    $reading_min = floor($wordn / 200);
 
    $reading_sec = floor($wordn % 200 / (200 / 60));

    $is_visiable = apply_filters("wordcount_display_reading_time",1);

    if($is_visiable){

        $label = __("Total Reading Time<br/>","word-count");

        $label = apply_filters("wordcount_reading_heading",$label);

        $tag = apply_filters('wordcount_reading_tag','h2');

        $content .= sprintf('<%s>%s: %s miniute and %s second</%s>',$tag,$label,$reading_min,$reading_sec,$wordn,$tag);
    }

    return $content;


}

add_filter("the_content","wordcount_count_reading_time");