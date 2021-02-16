<?php

class OptiondemoTwo{
    public function __construct()
    {
        add_action("admin_menu",array($this,"optionsdemo_create_admin_page"));
    }

    public function optionsdemo_create_admin_page(){
        $page_title = __( 'Options Admin Page', 'optionsdemo' );
		$menu_title = __( 'Options Admin Page', 'optionsdemo' );
		$capability = 'manage_options';
		$slug       = 'optionsdemopage';
		$callback   = array( $this, 'optionsdemo_page_content' );
		add_options_page( $page_title, $menu_title, $capability, $slug, $callback );
		//add_menu_page( $page_title, $menu_title, $capability, $slug, $callback );
    }

    public function optionsdemo_page_content(){
        
    }
}

new OptiondemoTwo();