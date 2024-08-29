<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Workflow_model extends App_Model
{
    
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

                $conIndex++;
            }
        }

        //insert Triggers based on type
        if( isset( $arrWorkflowData['trigger_type_id'] ) ){
            $triggerType = $arrWorkflowData['trigger_type_id'];
            $enumTriggerType = WF_TRIGGER_TYPE;
            switch( $triggerType ) {
                case $enumTriggerType['Edit Field']:
                    //Insert to Edit fields
                    $arrEditFieldData = setTableFields( ['edit_type_id','edit_field_id','field_value'], $data );
                    $arrEditFieldData['workflow_id'] = $insert_id;
                    $triggerInserId = $this->Workflow_edit_field_model->add( $arrEditFieldData );
                    break;
                case $enumTriggerType['Send Email']:
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

        $isDeleted = $this->db->affected_rows() > 0;
        if( $isDeleted ){
            $this->db->where('workflow_id', $id);
            $this->db->delete(db_prefix() . 'workflow_condition');
            $this->db->where('workflow_id', $id);
            $this->db->delete(db_prefix() . 'workflow_edit_field');

            log_activity('Workflow & related data Deleted [ID: ' . $id . ']');
        }

        return $isDeleted;
        
    }

    public function toggle_status($id,$newStatus) {
        $this->db->where('id', $id);
        $this->db->update( db_prefix() . 'workflow', ['is_active' => $newStatus]);
        $affected = $this->db->affected_rows();
        if( $affected ){
            log_activity('Workflow & related data Deleted [ID: ' . $id . ']');
        }
        return $affected;
    }
}
