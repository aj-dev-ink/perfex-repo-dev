<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Workflow_model extends App_Model
{
    public static $enumEntityType = array('Lead'=>1, 'Contact'=>2);
    public static $enumActionType = array('When a deal is updated'=>1, 'When deal is closed.'=>2);
    public static $enumTriggerType = array('Edit Field'=>1, 'Send Email'=>2);
    
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add new workflow
     * @param mixed $data workflow $_POST data
     */
    public function add($data) {
        $this->db->insert(db_prefix() . 'workflow', $data);

        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Workflow Added [ID: ' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    public function get($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'workflow')->row();
        }

        return $this->db->get(db_prefix() . 'workflow')->result_array();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'workflow', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Delete workflow
     * @param  mixed $id workflow id
     * @return boolean
     */
    public function delete_workflow($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'workflow');
        return $this->db->affected_rows() > 0;
    }

    public function get_workflows_by_conditions( $entity_type_id = null, $action_type_id = null )
    {
        // Check if action_type_id is provided
        if (!is_null($action_type_id)) {
            $this->db->where('action_type_id', $action_type_id);
        }

        // Check if entity_type_id is provided
        if (!is_null($entity_type_id)) {
            $this->db->where('entity_type_id', $entity_type_id);
        }

        // Fetch the records from the workflow table based on the conditions
        return $this->db->get(db_prefix() . 'workflow')->result_array();
    }

}
