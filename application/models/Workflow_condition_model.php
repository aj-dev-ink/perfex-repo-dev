<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Workflow_condition_model extends App_Model
{
    public static $enumConditionType = array('Opportunity Pipeline'=>1);
    public static $enumStageType = array('Pipeline stage'=>1);
    public static $enumValueType = array('New Value'=>1);
    public static $enumOperatorType = array(
        'Equal To'=>1,
        'Not Equal To'=>2,
        'Contains'=>3,
        'Not contains'=>4,
        'Is empty'=>5,
        'Is not empty'=>6,
        'Begins with'=>7,
        'Is set'=>8,
        'Is not set'=>9,
        'In'=>10,
        'Not in'=>11,
        'And'=>12,
        'Or'=>13
     );

    public static $enumCompareValueType = array('Negotiation'=>1 );

    


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add new workflow  condition
     * @param mixed $data workflow  condition $_POST data
     */
    public function add($data) {
        $this->db->insert(db_prefix() . 'Workflow_condition', $data);

        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Workflow condition Added [ID: ' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    public function get($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'Workflow_condition')->row();
        }

        return $this->db->get(db_prefix() . 'Workflow_condition')->result_array();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'Workflow_condition', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Delete workflow condition
     * @param  mixed $id workflow condition id
     * @return boolean
     */
    public function delete_workflow_condition($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'Workflow_condition');
        return $this->db->affected_rows() > 0;
    }
}
