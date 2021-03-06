<?php

// Set post thumbnail sizes
// -------------------------------------------------------------
add_theme_support( 'post-thumbnails' );
// add_image_size( 'index-thumb', 150, 150, true ); // section index

// Register wp_nav_menu()s
// -------------------------------------------------------------
function my_register_nav_menus() {
    register_nav_menus(
        array(
            'primary' => __( 'Primary' ),
        )
    );
}

add_action( 'init', 'my_register_nav_menus' );

// Remove wp_nav_menu() containers
// -------------------------------------------------------------
function my_wp_nav_menu_args( $args = '' )
{
    $args['container'] = false;
    return $args;
}

add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' );

// Remove all wp_nav_menu() classes (and add .is-current)
// -------------------------------------------------------------
function my_wp_nav_strip_classes( $a ){
    return ( in_array( 'current_page_item', $a ) ) ? array( 'is-current' ) : array();
}

add_filter( 'nav_menu_css_class', 'my_wp_nav_strip_classes', 10, 2 );

// Add special classes to wp_nav_menu
// -------------------------------------------------------------
function my_wp_nav_special_classes( $classes, $item ){
     //if( $item->object_id == page_chalets() ) {
     //        $classes[] = 'section-chalets';
     //}
     if( $item->object_id == get_root_parent_id() ) {
             $classes[] = 'is-root-parent';
     }
     return $classes;
}

add_filter( 'nav_menu_css_class' , 'my_wp_nav_special_classes', 10, 2 );

// Remove wp_nav_menu() IDs
// -------------------------------------------------------------
function my_wp_nav_strip_id() {
    return '';
}

add_filter( 'nav_menu_item_id', 'my_wp_nav_strip_id' );

// Remove <img> dimensions from the_post_thumbnail()
// -------------------------------------------------------------
function remove_thumbnail_dimensions( $html ) {
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}

add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10, 2 );

// Remove inline CSS and change wp-caption images
// -------------------------------------------------------------
class FixWpCaption{

    public function __construct(){
        add_filter( 'img_caption_shortcode', array( $this, 'fixcaption' ), 10, 3 );
    }
    public function fixcaption( $x=null, $attr, $content ){

        extract(shortcode_atts(array(
                'id'    => '',
                'align'    => 'alignnone',
                'width'    => '',
                'caption' => ''
            ), $attr));

        if ( 1 > (int) $width || empty( $caption ) ) {
            return $content;
        }

    return '<aside class="wp-caption ' . $align . '">'
    . $content . '<p class="wp-caption-text">' . $caption . '</p></aside>';
    }
    
}

$FixWpCaption = new FixWpCaption();

// Include jQuery properly to stop conflicts
// -------------------------------------------------------------
if (!is_admin()) add_action("wp_enqueue_scripts", "jquery_dequeue", 11);
function jquery_dequeue() {
    wp_deregister_script('jquery');
    wp_enqueue_script('js-scripts');
}

// Remove rel="next" rel="prev" in <head>
// -------------------------------------------------------------
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );

// Remove rel="wlwmanifest in <head> (Windows Live Writer)
// -------------------------------------------------------------
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );

// Remove RSS feed links in <head>
// -------------------------------------------------------------
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );

/*
function my_add_back_rss() {
    echo '<link rel="alternate" type="application/rss+xml" title="RSS 2.0 Feed" href="' . get_bloginfo('rss2_url') . '" />'; 
}

add_action( 'wp_head', 'my_add_back_rss' );
*/

// Add page excerpts panel
// -------------------------------------------------------------
add_post_type_support( 'page', 'excerpt' );


// Remove all default widgets
// -------------------------------------------------------------
function my_unregister_widgets() {
    unregister_widget( 'WP_Widget_Pages' );
    unregister_widget( 'WP_Widget_Calendar' );
    unregister_widget( 'WP_Widget_Archives' );
    unregister_widget( 'WP_Widget_Links' );
    unregister_widget( 'WP_Widget_Categories' );
    unregister_widget( 'WP_Widget_Recent_Posts' );
    unregister_widget( 'WP_Widget_Search' );
    unregister_widget( 'WP_Widget_Tag_Cloud' );
    unregister_widget( 'WP_Widget_RSS' );
    unregister_widget( 'WP_Widget_Meta' );
    unregister_widget( 'WP_Widget_Recent_Comments' );
    unregister_widget( 'WP_Nav_Menu_Widget' );
    unregister_widget( 'bcn_widget' );
    unregister_widget( 'GFWidget' );
    unregister_widget( 'HSS_WpWidgets' );
    unregister_widget( 'P2P_Widget' );
    unregister_widget( 'WP_Widget_Recent_Posts_No_Title_Attributes' );
}

add_action( 'widgets_init', 'my_unregister_widgets' );
