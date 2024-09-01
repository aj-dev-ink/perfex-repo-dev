<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Workflow_delay_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add new workflow  delay data
     * @param mixed $data workflow delay data condition $_POST data
     */
    public function add($data) {
        $this->db->insert(db_prefix() . 'workflow_delay', $data);

        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Workflow delay data Added [ID: ' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    public function get($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'workflow_delay')->row();
        }

        return $this->db->get(db_prefix() . 'workflow_delay')->result_array();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'workflow_delay', $data);
        return $this->db->affected_rows() > 0;
    }
}