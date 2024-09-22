<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('_initWorkflowCheck')) {
    function _initWorkflowCheck( $arrProcessData ){
        $intEntityType = $arrProcessData['intEntityType'];
        $intActionType = $arrProcessData['intActionType'];
        $intEntityId = $arrProcessData['intEntityId'];
        $objExistingEntity = $arrProcessData['objExistingEntity'];

        //find all wotkflows for given entity type id 
        //if found then

        $objApp = &get_instance();
        $objApp->load->model('workflow_model');
        $arrobjWorkflow = $objApp->workflow_model->getWorkflowsByEntityType( $intEntityType, $intActionType );

        if( !empty( $arrobjWorkflow ) ) {
            foreach( $arrobjWorkflow as $objworkflow ){
                if( true == $objworkflow['is_trigger_now'] ){
                    //trigger now - ( Immediate )
                    if( false == $objworkflow['is_condition_based'] ){
                        //No condition - trigger for All Entities

                        //Find Trigger & process
                        switch( $objworkflow['trigger_type_id'] ) {
                            case WF_TRIGGER_TYPE['Edit Field']:
                                _executeEditField( $objworkflow, $intEntityId );
                                break;
                            case WF_TRIGGER_TYPE['Send Email']:
                                # code...
                                break;

                            case WF_TRIGGER_TYPE['Webhook']:
                                # code...
                                break;

                            case WF_TRIGGER_TYPE['Reassign']:
                                # code...
                                break;

                            case WF_TRIGGER_TYPE['Share']:
                                # code...
                                break;

                            case WF_TRIGGER_TYPE['Convert']:
                                # code...
                                break;

                            case WF_TRIGGER_TYPE['Create task']:
                                    # code...
                                break;
                            case WF_TRIGGER_TYPE['Marketplace actions']:
                                # code...
                            break;
                        
                            default:
                                # code...
                                break;
                        }

                    } else {
                        //Based on conditions trigger
                        /*
                        $arrobjConditions = []; //travel through workflow_conditions
                        foreach( $arrobjConditions as $objCondition ){
                            $isConditionMatched = false;
                            $objCondition;//Assert if condition fulfills
                            if( $isConditionMatched ){
                                //execute appropriate action
                                $arrobjActions = [];
                                foreach( $arrobjActions as $objAction ){
                                    $objAction; //Execute
                                }
                            }
                        }*/
                    }
                } else {
                    //Delayed trigger - ( Delayed Action )
                }
                
            }
        }
    }
}

function _executeEditField( $objWorkflow, $intEntityId ) {
    $objApp = &get_instance();
    $objApp->load->model('workflow_edit_field_model');
    $objEditField = $objApp->workflow_edit_field_model->getEditFieldByWorkFlowId( $objWorkflow['id'] );
    // dig( $objWorkflow );
    // out( $objEditField );

    if( $objEditField ){
        $entityType = $objWorkflow['entity_type_id'];
        $fieldId = $objEditField->edit_type_id;

        switch ($objEditField->edit_field_id) {
            case WFEF_EDIT_TYPE['Enter Custom Value']:
                $tableName = WF_FIELD_OPTION_MAP[$entityType][$fieldId]['table_name'];
                $fieldName = WF_FIELD_OPTION_MAP[$entityType][$fieldId]['field_name'];

                $isTextBox = WF_FIELD_OPTION_MAP[$entityType][$fieldId]['is_textbox'];
                $newValue = $isTextBox ? $objEditField->edit_custom_value : $objEditField->edit_field_value;

                $isSucess = _updateTableFieldValueByEntityId( $tableName, $fieldName, $newValue, $intEntityId );
                //end execution

                break;
            case WFEF_EDIT_TYPE['Copy Field Value']:
                # code...
                break;
            default:
                # code...
                break;
        }
        
    }
}

function _updateTableFieldValueByEntityId( $tableName, $fieldName, $newValue, $intEntityId ){
    $tableName = db_prefix() . $tableName;
    // Get the CI instance
    $CI = &get_instance();
    // Check if table exists
    if (!$CI->db->table_exists($tableName)) {
        log_message('error', 'Table does not exist: ' . $tableName);
        return false;
    }
    // Check if field exists
    if (!$CI->db->field_exists($fieldName, $tableName)) {
        log_message('error', 'Field does not exist in table ' . $tableName . ': ' . $fieldName);
        return false;
    }

    // Prepare the data for updating
    $data = array(
        $fieldName => $newValue
    );

    // Update the field with the new value where `id` matches
    $CI->db->where('id', $intEntityId);  // Assuming `id` is the primary key or identifier
    $updated = $CI->db->update($tableName, $data);

    if ($updated) {
        log_message('info', 'Field updated successfully in table: ' . $tableName . ', ID: ' . $intEntityId);
        return true;
    } else {
        log_message('error', 'Failed to update field in table: ' . $tableName . ', ID: ' . $intEntityId);
        return false;
    }

}

