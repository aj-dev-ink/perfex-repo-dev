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
        $arrWorkflowFields = ['name','description','entity_type_id','action_type_id','is_trigger_now','is_condition_based','trigger_type_id'];
        $arrWorkflowData = setTableFields( $arrWorkflowFields, $data );

        $this->db->insert(db_prefix() . 'workflow', $arrWorkflowData);
        $insert_id = $this->db->insert_id();

        if( $insert_id ) {
            log_activity('New Workflow Added [ID: ' . $insert_id . ']');
        }

        //insert conditions
        if( isset( $arrWorkflowData['is_condition_based'] ) && $arrWorkflowData['is_condition_based'] ){
            $arrConInsertIds=[];
            $countConditions = count( $data['condition_type_id'] );
            $conIndex = 0;
            while( $countConditions > $conIndex ){

                $arrConditionFields = ['condition_type_id','stage_type_id','value_type_id','operator_type_id','compare_value_type_id'];
                $arrConditionData = [];
                foreach( $arrConditionFields as $field ){
                    if( isset( $data[$field], $data[$field][$conIndex] ) ){
                        $arrConditionData[$field] = $data[$field][$conIndex];
                    }
                }
                $arrConditionData['workflow_id'] = $insert_id;

                $arrConInsertIds[] = $this->Workflow_condition_model->add( $arrConditionData );
                    //$this->db->insert(db_prefix() . 'workflow_condition', $arrConditionData);
                    //$arrConInsertIds[] = $this->db->insert_id();
                $conIndex++;
            }
        }

        //insert Triggers based on type
        if( isset( $arrWorkflowData['trigger_type_id'] ) ){
            $triggerType = $arrWorkflowData['trigger_type_id'];

            switch( $triggerType ) {
                case Workflow_model::$enumTriggerType['Edit Field']:
                    //Insert to Edit fields
                    $arrEditFieldData = setTableFields( ['edit_type_id','edit_field_id','field_value'], $data );
                    $arrEditFieldData['workflow_id'] = $insert_id;
                    $triggerInserId = $this->Workflow_edit_field_model->add( $arrEditFieldData );
                    break;
                case Workflow_model::$enumTriggerType['Send Email']:
                    # code...
                    break;
            }
        }

        if ($insert_id) {
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
