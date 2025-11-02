<?php
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Basic_Contact_Form_Admin extends WP_List_Table {

    private $database;

    function __construct() {
        parent::__construct([
            'singular' => 'submission',
            'plural' => 'submissions',
            'ajax' => false
        ]);

        $this->database = new Basic_Contact_Form_Database();
    }

    public function get_columns() {
        return [
            'cb' => '<input type="checkbox" />',
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'severity' => 'Severity',
            'submitted_at' => 'Submitted At'
        ];
    }

    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'id':
                return esc_html($item[$column_name]);
            case 'name':
                $url = add_query_arg([
                    'page' => 'basic-contact-submissions',
                    'action' => 'view',
                    'id' => $item['id']
                ], admin_url('admin.php'));
                return '<a href="' . esc_url($url) . '">' . esc_html($item[$column_name]) . '</a>';
            // return esc_html($item[$column_name]);
            case 'email':
            case 'severity':
                return esc_html($item[$column_name]);
            case 'submitted_at':
                return esc_html(date('Y-m-d H:i:s', strtotime($item[$column_name])));
            default:
                return print_r($item, true);
        }
    }

    public function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="submission[]" value="%s" />',
            $item['id']
        );
    }

    function prepare_items() {
        $columns = $this->get_columns();
        $hidden = [];
        $sortable = [];
        $this->_column_headers = [$columns, $hidden, $sortable];
        // $this->items = $this->database->get_submissions(null,null,"id", "ASC");
        $this->items = $this->database->get_submissions(orderby: 'id', order: 'ASC');
    }

    function display_submission_detail($id) {
        $submission = $this->database->get_submission($id);
        if (!$submission) {
            echo '<div class="wrap"><h1>Submission not found</h1><p><a href="' . esc_url(admin_url('admin.php?page=basic-contact-submissions')) . '">← Back to submissions</a></p></div>';
            return;
        }

        echo '<div class="wrap">';
        echo '<h1>Contact Form Submission Details</h1>';
        echo '<p><a href="' . esc_url(admin_url('admin.php?page=basic-contact-submissions')) . '">← Back to submissions</a></p>';

        echo '<div style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 5px; margin-top: 20px;">';
        echo '<table class="form-table">';
        echo '<tr><th style="width: 150px;">ID:</th><td>' . esc_html($submission['id']) . '</td></tr>';
        echo '<tr><th>Name:</th><td>' . esc_html($submission['name']) . '</td></tr>';
        echo '<tr><th>Email:</th><td>' . esc_html($submission['email']) . '</td></tr>';
        echo '<tr><th>Severity:</th><td>' . esc_html($submission['severity']) . '</td></tr>';
        echo '<tr><th>IP Address:</th><td>' . esc_html($submission['ip_address']) . '</td></tr>';
        echo '<tr><th>Submitted At:</th><td>' . esc_html(date('Y-m-d H:i:s', strtotime($submission['submitted_at']))) . '</td></tr>';
        echo '<tr><th>Message:</th><td><div style="white-space: pre-wrap; background: #f9f9f9; padding: 10px; border: 1px solid #ddd; border-radius: 3px;">' . esc_html($submission['message']) . '</div></td></tr>';

        $extra_fields = json_decode($submission['extra_fields'], true);
        if (!empty($extra_fields)) {
            echo '<tr><th>Extra Fields:</th><td>';
            echo '<ul style="margin: 0; padding-left: 20px;">';
            foreach ($extra_fields as $field => $value) {
                echo '<li><strong>' . esc_html(ucfirst($field)) . ':</strong> ' . esc_html($value) . '</li>';
            }
            echo '</ul>';
            echo '</td></tr>';
        }
        echo '</table>';
        echo '</div>';
        echo '</div>';
    }

    public function get_bulk_actions() {
        return [
            'delete' => 'Delete',
        ];
    }

    function process_bulk_action() {
        if ('delete' === $this->current_action()) {
            if (isset($_POST['submission']) && is_array($_POST['submission'])) {
                check_admin_referer('bulk-submissions');
                $submission_ids = array_map('intval', $_POST['submission']); //[3,18,16]
                foreach ($submission_ids as $id) {
                    $this->database->delete_submission($id);
                }
            }
        }
    }

    public function display_submissions_page() {
        $this->process_bulk_action();
        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($action === 'view' && $id > 0) {
            $this->display_submission_detail($id);
        } else {
            echo '<div class="wrap">';
            echo '<h1 class="wp-heading-inline">Contact Form Submissions</h1>';
            echo '<form method="post">';
            $this->prepare_items();
            $this->display();
            echo '</form>';
            echo '</div>';
        }
    }
}