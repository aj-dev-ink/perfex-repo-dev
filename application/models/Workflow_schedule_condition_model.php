<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Workflow_schedule_condition_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add new workflow schedule condition 
     * @param mixed $data workflow schedule condition $_POST data
     */
    public function add($data) {
        $this->db->insert(db_prefix() . 'workflow_schedule_condition', $data);

        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Workflow schedule condition Added [ID: ' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    public function get($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'workflow_schedule_condition')->row();
        }

        return $this->db->get(db_prefix() . 'workflow_schedule_condition')->result_array();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'workflow_schedule_condition', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Delete workflow schedule condition
     * @param  mixed $id workflow schedule condition id
     * @return boolean
     */
    public function delete_workflow_schedule_condition($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'workflow_schedule_condition');
        return $this->db->affected_rows() > 0;
    }
}
