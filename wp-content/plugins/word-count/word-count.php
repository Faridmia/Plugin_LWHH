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

    $label = apply_filters("wordcount_heading",$label);

    $tag = apply_filters('wordcount_tag','h2');

    $content .= sprintf('<%s>%s: %s</%s>',$tag,$label,$wordn,$tag);

    return $content;


}

add_filter("the_content","wordcount_count_words");