<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Workflow_processing_schedule_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add new workflow_processing_schedule
     * @param mixed $data workflow_processing_schedule $_POST data
     */
    public function add($data) {
        $this->db->insert(db_prefix() . 'workflow_processing_schedule', $data);

        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New workflow processing schedule Added [ID: ' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    public function get($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'workflow_processing_schedule')->row();
        }

        return $this->db->get(db_prefix() . 'workflow_processing_schedule')->result_array();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'workflow_processing_schedule', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Delete workflow_processing_schedule
     * @param  mixed $id workflow_processing_schedule id
     * @return boolean
     */
    public function delete_workflow_processing_schedule($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'workflow_processing_schedule');
        return $this->db->affected_rows() > 0;
    }
}
