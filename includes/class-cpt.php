<?php

class Basic_CPT {

    public function __construct() {
        add_action('init', [$this, 'register_support_cpt']);
    }

    public function register_support_cpt() {
        $labels = [
            'name' => esc_html__('Supports', 'basic-contact-form'),
            'singular_name' => esc_html__('Support', 'basic-contact-form'),
        ];

        $args = [
            'label' => esc_html__('Supports', 'basic-contact-form'),
            'labels' => $labels,
            'description' => '',
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_rest' => true,
            'has_archive' => false,
            'show_in_menu' => true,
            'delete_with_user' => false,
            'exclude_from_search' => false,
            'capability_type' => 'post',
            'map_meta_cap' => true,
            'hierarchical' => false,
            'rewrite' => ['slug' => 'support', 'with_front' => true],
            'query_var' => true,
            'supports' => ['title', 'editor'],
        ];

        register_post_type('support', $args);
    }
}