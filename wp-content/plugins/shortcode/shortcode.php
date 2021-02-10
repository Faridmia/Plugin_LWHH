<?php
function philosophy_button( $attributes ) {

    $default = array(
        'type'=>'primary',
        'title'=>__("Button",'philosophy'),
        'url'=>'',
    );

    $button_attributes = shortcode_atts($default,$attributes);


    return sprintf( '<a target="_blank" class="btn btn--%s full-width" href="%s">%s</a>',
        $button_attributes['type'],
        $button_attributes['url'],
        $button_attributes['title']
    );
}

add_shortcode( 'button', 'philosophy_button' );


function philosophy_button2( $attributes, $content='' ) {
    $default = array(
        'type'=>'primary',
        'title'=>__("Button",'philosophy'),
        'url'=>'',
    );

    $button_attributes = shortcode_atts($default,$attributes);


    return sprintf( '<a target="_blank" class="btn btn--%s full-width" href="%s">%s</a>',
        $button_attributes['type'],
        $button_attributes['url'],
        do_shortcode($content)
    );
}

add_shortcode( 'button2', 'philosophy_button2' );

function philosophy_uppercase($attributes, $content=''){
    return strtoupper(do_shortcode($content));
}
add_shortcode('uc','philosophy_uppercase');

function philosophy_google_map($attributes){
    $default = array(
        'place'=>'Dhaka Museum',
        'width'=>'800',
        'height'=>'500',
        'zoom'=>'14'
    );

    $params = shortcode_atts($default,$attributes);

    $map = <<<EOD
<div>
    <div>
        <iframe width="{$params['width']}" height="{$params['height']}"
                src="https://maps.google.com/maps?q={$params['place']}&t=&z={$params['zoom']}&ie=UTF8&iwloc=&output=embed"
                frameborder="0" scrolling="no" marginheight="0" marginwidth="0">
        </iframe>
    </div>
</div>
EOD;

    return $map;

}
add_shortcode('gmap','philosophy_google_map');
