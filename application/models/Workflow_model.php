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

        $arrWorkflowFields = ['name','description','entity_type_id','action_type_id','is_trigger_now','is_condition_based_schedule','is_condition_based','trigger_type_id'];
        $arrWorkflowData = setTableFields( $arrWorkflowFields, $data );

        $this->db->insert(db_prefix() . 'workflow', $arrWorkflowData);
        $insert_id = $this->db->insert_id();

        if( $insert_id ) {
            log_activity('New Workflow Added [ID: ' . $insert_id . ']');
        }

        //insert Delay Actions
        if( isset( $arrWorkflowData['is_trigger_now'] ) && !$arrWorkflowData['is_trigger_now'] ){

            if( isset( $arrWorkflowData['is_condition_based_schedule'] ) && $arrWorkflowData['is_condition_based_schedule'] ){
                $arrScheduleConInsertIds=[];
                $countSchedConditions = count( $data['sched_condition_type_id'] );
                $conIndex = 0;
                while( $countSchedConditions > $conIndex ){
    
                    $arrSchedConditionFields = ['sched_condition_type_id','sched_stage_type_id','sched_value_type_id','sched_operator_type_id','sched_compare_value_type_id', 'sched_actual_compare_value', 'sched_is_and'];
                    $arrSchedConditionData = [];
                    foreach( $arrSchedConditionFields as $field ){
                        if( isset( $data[$field], $data[$field][$conIndex] ) ){
                            $arrSchedConditionData[$field] = $data[$field][$conIndex];
                        }
                    }
                    $arrSchedConditionData['workflow_id'] = $insert_id;
    
                    $arrScheduleConInsertIds[] = $this->workflow_schedule_condition_model->add( $arrSchedConditionData );
    
                    $conIndex++;
                }    
            }
            

            $arrFields = ['pref_count', 'pref_duration', 'is_before', 'delay_date_type', 'repeat_type', 'is_recurance', 'frequency', 'until_date'];

            $arrDelayData = setTableFields( $arrFields, $data );
            $arrDelayData['workflow_id'] = $insert_id;
            $delayInsertId = $this->workflow_delay_model->add( $arrDelayData );
        }

        //insert conditions
        if( isset( $arrWorkflowData['is_condition_based'] ) && $arrWorkflowData['is_condition_based'] ){
            $arrConInsertIds=[];
            $countConditions = count( $data['condition_type_id'] );
            $conIndex = 0;
            while( $countConditions > $conIndex ){

                $arrConditionFields = ['condition_type_id','stage_type_id','value_type_id','operator_type_id','compare_value_type_id', 'actual_compare_value', 'is_and'];
                $arrConditionData = [];
                foreach( $arrConditionFields as $field ){
                    if( isset( $data[$field], $data[$field][$conIndex] ) ){
                        $arrConditionData[$field] = $data[$field][$conIndex];
                    }
                }
                $arrConditionData['workflow_id'] = $insert_id;

                $arrConInsertIds[] = $this->workflow_condition_model->add( $arrConditionData );

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
                    $arrEditFieldData = setTableFields( ['edit_type_id','edit_field_id','edit_field_value','edit_custom_value'], $data );
                    $arrEditFieldData['workflow_id'] = $insert_id;
                    $triggerInsertId = $this->workflow_edit_field_model->add( $arrEditFieldData );
                    break;

                case $enumTriggerType['Send Email']:
                    //Insert to Send Email
                    $sendEmailDataPost = isset( $data['sendEmail'] ) ? $data['sendEmail'] : [];
                    $sendEmailFields = ['template_id', 'email_to_fields', 'email_cc_fields'];
                    $arrSendEmailData = setTableFields( $sendEmailFields, $sendEmailDataPost );
                    $arrSendEmailData['workflow_id'] = $insert_id;
                    $triggerInsertId = $this->workflow_send_email_model->add( $arrSendEmailData );
                    break;
                case $enumTriggerType['Webhook']:
                    //Insert to Webhook
                    $webhookDataPost = isset( $data['webhook'] ) ? $data['webhook'] : [];
                    $webhookFields = ['name', 'description', 'request_type', 'request_url', 'authorization_type', 'api_key', 'bearer_token', 'auth_username', 'auth_password', 'is_url_param', 'url_params' ];
                    $arrWebHookData = setTableFields( $webhookFields, $webhookDataPost );
                    $arrWebHookData['workflow_id'] = $insert_id;
                    $triggerInsertId = $this->workflow_webhook_model->add( $arrWebHookData );
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

    public function getOptionsByFieldEntity( $fieldId, $entityType ) {

        $arrOptions = [ ['id' => NULL, 'name'=> 'No Options Set'] ];

        $functionName = WF_FIELD_OPTION_MAP[$entityType][$fieldId]['function_name'] ?? '';
        
        if( function_exists( $functionName ) ) {
            $arrOptions = $functionName(); // Call the method if it exists
        } else {
            // Handle the case where the method does not exist
            throw new Exception( $functionName . " function for option does not exist!");
        }
        return $arrOptions;
    }

    
    public function getWorkflowsByEntityType( $intEntityType, $intActionType ) {
        if( is_numeric( $intEntityType ) && is_numeric( $intActionType ) ) {
            $this->db->where( 'entity_type_id', $intEntityType );
            $this->db->where( 'action_type_id', $intActionType );
            $this->db->where( 'is_active', 1 );

        }
        return $this->db->get(db_prefix() . 'workflow')->result_array();
    }
 
}
