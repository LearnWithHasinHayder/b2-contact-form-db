<?php
/**
 * Plugin Name: Basic Contact Form
 * Plugin URI: https://example.com/basic-contact-form
 * Description: A dedicated plugin for contact forms with AJAX submission and custom post type storage.
 * Version: 1.0.0
 * Author: Your Name
 * License: GPL v2 or later
 * Text Domain: basic-contact-form
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

define('BCF_PLUGIN_URL', plugin_dir_url(__FILE__));
define('BCF_PLUGIN_PATH', plugin_dir_path(__FILE__));

class Basic_Contact_Form_Plugin {

    public function __construct() {
        register_activation_hook(__FILE__, [$this, 'activate']);
        $this->include_resources();
        $this->init();
        add_action('wp_enqueue_scripts', [$this, 'load_assets']);
        add_action('admin_menu', [$this, 'add_admin_menu']);
    }

    function add_admin_menu(){
        add_menu_page(
			'Contact Submissions',
			'Contact Forms',
			'manage_options',
			'basic-contact-submissions',
			[$this, 'display_submissions_page'],
			'dashicons-feedback',
			30
		);
    }

    function display_submissions_page(){
        $admin = new Basic_Contact_Form_Admin();
        $admin->display_submissions_page();
    }

    public function include_resources() {
        // require_once(BCF_PLUGIN_PATH . 'includes/class-cpt.php');
        require_once(BCF_PLUGIN_PATH . 'includes/class-contact-form.php');
        require_once(BCF_PLUGIN_PATH . 'includes/class-database.php');
        require_once(BCF_PLUGIN_PATH . 'includes/class-admin.php');
    }

    public function init() {
        // new Basic_CPT();
        new Basic_Contact_Form();
    }

    public function load_assets() {
        wp_enqueue_script('bcf-main', BCF_PLUGIN_URL . 'assets/js/contact-form.js', [], time(), true);
        wp_localize_script('bcf-main', 'bcf', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'contactNonce' => wp_create_nonce('contact')
        ]);
    }

    function activate(){
        $database = new Basic_Contact_Form_Database();
        $database->create_table();
    }
}

new Basic_Contact_Form_Plugin();
