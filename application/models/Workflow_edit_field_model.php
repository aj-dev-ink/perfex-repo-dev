<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Workflow_edit_field_model extends App_Model
{

    /* WorkFlow edit_field Performed */

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add new workflow Edit field
     * @param mixed $data Edit field $_POST data
     */
    public function add($data) {
        $this->db->insert(db_prefix() . 'workflow_edit_field', $data);

        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Workflow Edit field trigger Added [ID: ' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    public function get($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'workflow_edit_field')->row();
        }

        return $this->db->get(db_prefix() . 'workflow_edit_field')->result_array();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'workflow_edit_field', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Delete Workflow_edit_field
     * @param  mixed $id Workflow_edit_field 
     * @return boolean
     */
    public function delete_Workflow_edit_field($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'workflow_edit_field');
        return $this->db->affected_rows() > 0;
    }

    /************ */
    public function getEditFieldByWorkFlowId( $intWorkflowId ) {
        if( is_numeric( $intWorkflowId ) ) {
            $this->db->where( 'workflow_id', $intWorkflowId );
            return $this->db->get( db_prefix() . 'workflow_edit_field' )->row();
        }
    }
}
