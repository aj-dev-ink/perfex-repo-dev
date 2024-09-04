<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Workflow_webhook_model extends App_Model
{

    /* WorkFlow webhook Performed */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add new workflow webhook
     * @param mixed $data webhook $_POST data
     */
    public function add($data) {
        $this->db->insert(db_prefix() . 'workflow_webhook', $data);

        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Workflow webhook trigger Added [ID: ' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    public function get($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'workflow_webhook')->row();
        }

        return $this->db->get(db_prefix() . 'workflow_webhook')->result_array();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'workflow_webhook', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Delete Workflow_webhook
     * @param  mixed $id Workflow_webhook 
     * @return boolean
     */
    public function delete_Workflow_webhook($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'workflow_webhook');
        return $this->db->affected_rows() > 0;
    }
}
