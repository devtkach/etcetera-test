<?php
function real_estate_register_taxonomy()
{
    $labels = array(
        'name' => __('Regions', 'real-estate-plugin'),
        'singular_name' => __('Region', 'real-estate-plugin'),
        'search_items' => __('Search Regions', 'real-estate-plugin'),
        'all_items' => __('All Regions', 'real-estate-plugin'),
        'parent_item' => __('Parent Region', 'real-estate-plugin'),
        'parent_item_colon' => __('Parent Region:', 'real-estate-plugin'),
        'edit_item' => __('Edit Region', 'real-estate-plugin'),
        'update_item' => __('Update Region', 'real-estate-plugin'),
        'add_new_item' => __('Add New Region', 'real-estate-plugin'),
        'new_item_name' => __('New Region Name', 'real-estate-plugin'),
        'menu_name' => __('Regions', 'real-estate-plugin'),
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'region'),
    );

    register_taxonomy('region', array('real_estate'), $args);
}

add_action('init', 'real_estate_register_taxonomy');
