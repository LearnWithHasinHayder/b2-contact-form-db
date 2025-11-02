<?php

class Basic_Contact_Form_Database {

    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'contact_submissions';
    }

    public function create_table() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $this->table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            message text NOT NULL,
            severity varchar(50) DEFAULT 'low',
            ip_address varchar(100) DEFAULT '',
            extra_fields text DEFAULT '',
            submitted_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function drop_table() {
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS $this->table_name");
    }

    public function insert_submission($data) {
        global $wpdb;

        $result = $wpdb->insert(
            $this->table_name,
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'message' => $data['message'],
                'severity' => $data['severity'],
                'ip_address' => $data['ip_address'],
                'extra_fields' => $data['extra_fields']
            ],
            ['%s', '%s', '%s', '%s', '%s', '%s']
        );

        return $result ? $wpdb->insert_id : false;
    }

    public function get_submissions($per_page = null, $page_number = null, $orderby = null, $order = null) {
        global $wpdb;

        $sql = "SELECT * FROM $this->table_name";

        if (!empty($orderby) && !empty($order)) {
            $sql .= ' ORDER BY ' . esc_sql($orderby) . ' ' . esc_sql($order);
        } else {
            // Default ordering by submitted_at DESC
            $sql .= ' ORDER BY submitted_at DESC';
        }

        if ($per_page !== null && $page_number !== null) {
            $sql .= " LIMIT $per_page";
            $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;
        }

        return $wpdb->get_results($sql, 'ARRAY_A');
    }

    public function get_submission_count() {
        global $wpdb;
        return $wpdb->get_var("SELECT COUNT(*) FROM $this->table_name");
    }

    public function get_submission($id) {
        global $wpdb;
        return $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM $this->table_name WHERE id = %d", $id),
            'ARRAY_A'
        );
    }

    public function delete_submission($id) {
        global $wpdb;
        return $wpdb->delete(
            $this->table_name,
            ['id' => $id],
            ['%d']
        );
    }
}