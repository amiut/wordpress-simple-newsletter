<?php
/**
 * Subscriber Class
 *
 * @package DW_NLITE
 * @since   1.0
 */

namespace DW_NLITE;

class Subscriber
{
    /**
     * Subscriber id
     */
    public $id = 0;

    /**
     * Person name
     * 
     * @var string
     */
    protected $name;

    /**
     * Person Email
     * 
     * @var string
     */
    protected $email;

    /**
     * Person phone number
     * 
     * @var string
     */
    protected $phone_number;    

    /**
     * Instance of $wpdb
     */
    protected $db;

    /**
     * Constructor
     */
    public function __construct($id = 0) {
        global $wpdb;    
        $this->db = $wpdb;

        if ($id) {
            $this->id = $id;
            $this->setup();
        }

        return $this;
    }

    public function exists() {
        return $this->id && $this->id > 0;
    }

    protected function setup() {
        $data = $this->db->get_results(
            $this->db->prepare("
                SELECT * FROM {$this->db->prefix}dw_nlite WHERE ID = %d
            ",
            $this->id)
        );

        if ($data) {
            $data = $data[0];
            $this->id = $data->ID;
            $this->name = $data->name;
            $this->email = $data->email;
            $this->phone_number = $data->phone_number;
        }
    }

    public function get_field($key) {
        return ! empty($this->$key) ? $this->$key : '';
    }

    protected function set_field($key, $value) {
        $this->$key = $value;
    }

    public function set_name($value) {
        $this->set_field('name', $value);
        return $this;
    }

    public function set_email($value) {
        $this->set_field('email', $value);
        return $this;
    }

    public function set_phone_number($value) {
        $this->set_field('phone_number', $value);
        return $this;
    }

    public function get_name() {
        return $this->get_field('name');
    }

    public function get_email() {
        return $this->get_field('email');
    }

    public function get_phone_number() {
        return $this->get_field('phone_number');
    }

    public function save() {
        // Update Existing
        if ($this->id) {
            $check = $this->db->update(
                $this->db->prefix . 'dw_nlite',
                [
                    'name'          => $this->get_name(),
                    'email'         => $this->get_email(),
                    'phone_number'  => $this->get_phone_number(),
                ],
                ['ID' => $this->id],
                [
                    '%s',
                    '%s',
                    '%s',
                ],
                ['%d']
            );

        } else {
            // Create new
            $check = $this->db->insert(
                $this->db->prefix . 'dw_nlite',
                [
                    'name'          => $this->get_name(),
                    'email'         => $this->get_email(),
                    'phone_number'  => $this->get_phone_number(),
                ],
                [
                    '%s',
                    '%s',
                    '%s',
                ]
            );
        }

        $check = (bool) $check;

        if ($check) {
            $this->id = $this->db->insert_id;
            $this->setup();
        }

        return (bool) $check;
    }

    public function find_by_email($email) {
        $data = $this->db->get_results(
            $this->db->prepare("
                SELECT * FROM {$this->db->prefix}dw_nlite WHERE email = %s
            ",
            $email)
        );
        
        if ($data) {
            $data = $data[0];
            $this->id = $data->ID;
            $this->name = $data->name;
            $this->email = $data->email;
            $this->phone_number = $data->phone_number;
        }

        return $this;
    }

    public function find_by_phone_number($phone_number) {
        $data = $this->db->get_results(
            $this->db->prepare("
                SELECT * FROM {$this->db->prefix}dw_nlite WHERE phone_number = %s
            ",
            $phone_number)
        );

        if ($data) {
            $data = $data[0];
            $this->id = $data->ID;
            $this->name = $data->name;
            $this->email = $data->email;
            $this->phone_number = $data->phone_number;
        }        

        return $this;
    }

    public function find_by_name($name) {
        $data = $this->db->get_results(
            $this->db->prepare("
                SELECT * FROM {$this->db->prefix}dw_nlite WHERE name = %s
            ",
            $name)
        );

        if ($data) {
            $data = $data[0];
            $this->id = $data->ID;
            $this->name = $data->name;
            $this->email = $data->email;
            $this->phone_number = $data->phone_number;
        }        

        return $this;
    }

    public function remove() {
        if (! $this->id) return false;
        
        return $this->db->delete([
            $this->db->prefix . 'dw_nlite',
            ['ID' => $this->id],
            ['%d']
        ]);
    }
}
