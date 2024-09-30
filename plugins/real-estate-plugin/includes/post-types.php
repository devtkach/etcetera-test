<?php
function real_estate_register_post_type()
{
    $labels = array(
        'name' => __('Real Estate Objects', 'real-estate-plugin'),
        'singular_name' => __('Real Estate Object', 'real-estate-plugin'),
        'menu_name' => __('Real Estate', 'real-estate-plugin'),
        'add_new' => __('Add New', 'real-estate-plugin'),
        'add_new_item' => __('Add New Real Estate Object', 'real-estate-plugin'),
        'edit_item' => __('Edit Real Estate Object', 'real-estate-plugin'),
        'new_item' => __('New Real Estate Object', 'real-estate-plugin'),
        'view_item' => __('View Real Estate Object', 'real-estate-plugin'),
        'search_items' => __('Search Real Estate Objects', 'real-estate-plugin'),
        'not_found' => __('No Real Estate Objects found', 'real-estate-plugin'),
        'not_found_in_trash' => __('No Real Estate Objects found in trash', 'real-estate-plugin'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'real-estate'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'thumbnail'),
        'menu_icon' => 'dashicons-building',
    );

    register_post_type('real_estate', $args);
}

add_action('init', 'real_estate_register_post_type');
