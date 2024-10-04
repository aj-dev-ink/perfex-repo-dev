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

                    $isConditionMet = false;                    
                    if( true == $objworkflow['is_condition_based'] ){
                        //Based on conditions trigger
                        $isConditionMet =  _evaluateConditions( $objworkflow, $intEntityId );
                    } 

                    if( $isConditionMet || false == $objworkflow['is_condition_based'] ) {
                        //Condition met OR No condition - trigger without condition 

                        //Find Trigger & process
                        switch( $objworkflow['trigger_type_id'] ) {
                            case WF_TRIGGER_TYPE['Edit Field']:
                                _executeEditField( $objworkflow, $intEntityId );
                                break;
                            case WF_TRIGGER_TYPE['Send Email']:
                                $isSuccess = _executeSendEmail( $objworkflow, $intEntityId );
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

                $isCustomField = WF_FIELD_OPTION_MAP[$entityType][$fieldId]['is_custom'] ?? false;
                if( 'customfieldsvalues' == $tableName  ) {
                    $isSucess = _updateCustomTableFieldValueByEntityId( $tableName, $fieldName, $newValue, $intEntityId );
                }else{
                    $isSucess = _updateTableFieldValueByEntityId( $tableName, $fieldName, $newValue, $intEntityId );
                }

                if( $isSucess ) {
                    log_message('info', 'WorkflowProcess: Processed Successfully: ' . $tableName . ', ID: ' . $intEntityId);
                    return true;
                }else{
                    log_message('error', 'WorkflowProcess: Failed to Process: ' . $tableName . ', ID: ' . $intEntityId);
                    return false;
                }
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

function _executeSendEmail( $objWorkflow, $intEntityId ) {
    $objApp = &get_instance();
    $objApp->load->model('workflow_send_email_model');
    $objApp->load->model('email_template_manage_model');

    $arrEntityTypes = array_flip( WF_ENTITY_TYPE );
    $entityType = $objWorkflow['entity_type_id'];

    $objSendEmail = $objApp->workflow_send_email_model->getSendEmailByWorkFlowId( $objWorkflow['id'] );

    //set rel_type based on type to populate send email function vars default to compose
    $rel_type = strtolower( isset( $arrEntityTypes[$entityType] ) ? isset( $arrEntityTypes[$entityType] ) : 'compose' );
    $rel_id = $intEntityId;
    $templateId = $objSendEmail->template_id;

    $arrMailTo = _getEmailsByFieldIds( $objWorkflow, $objSendEmail->email_to_fields, $intEntityId ); //these are email ids based on entity type get thses details
    $arrMailCC = ( '' != $objSendEmail->email_cc_fields ) ? _getEmailsByFieldIds( $objWorkflow, $objSendEmail->email_cc_fields, $intEntityId) : '';
    
    $success = $objApp->email_template_manage_model->send_workflow_mail( $templateId, $arrMailTo, $arrMailCC, $rel_type, $rel_id );

    return $success;
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

function _updateCustomTableFieldValueByEntityId( $tableName, $fieldSlug, $newValue, $intEntityId ){

    $tableName = $tableName;
    $fieldTo = explode( '_', $fieldSlug )[0];
    $relid = $intEntityId;

    $customFieldId = getCustomFieldIdBySlug( $fieldSlug );

    if( !$customFieldId ){
        log_message('info', 'WorkflowProcess: Unable to find Custom Field: ' . $tableName . ', Cusotm Field: ' . $fieldSlug);
        return false;
    } 
    
    $tableName = db_prefix() . $tableName;

    $CI = &get_instance();

    // Prepare the data for updating
    $data = array(
        'value' => $newValue
    );

    // Check if the record exists
    $CI->db->where('relid', $intEntityId);
    $CI->db->where('fieldid', $customFieldId);
    $CI->db->where('fieldto', $fieldTo);
    $query = $CI->db->get($tableName);
    if ($query->num_rows() > 0) {
        // Record exists, perform the update
        $CI->db->where('relid', $intEntityId);
        $CI->db->where('fieldid', $customFieldId);
        $CI->db->where('fieldto', $fieldTo);
        $updated = $CI->db->update($tableName, $data);
        if( $updated ) {
            log_message('info', 'WorkflowProcess: Field updated successfully in table: ' . $tableName . ', ID: ' . $intEntityId);
            return true;
        }else{
            log_message('error', 'WorkflowProcess: Failed to update field in table: ' . $tableName . ', ID: ' . $intEntityId);
            return false;
        }
    } else {
        // Record doesn't exist, perform the insert
        $data['relid'] = $intEntityId;
        $data['fieldid'] = $customFieldId;
        $data['fieldto'] = $fieldTo;
        $inserted = $CI->db->insert($tableName, $data);

        if( $inserted ) {
            log_message('info', 'WorkflowProcess: Field inserted successfully in table: ' . $tableName . ', ID: ' . $intEntityId);
            return true;
        }else{
            log_message('error', 'WorkflowProcess: Failed to insert field in table: ' . $tableName . ', ID: ' . $intEntityId);
            return false;
        }
    }

}

function _evaluateConditions( $arrWorkflow, $intEntityId ){
    $CI = &get_instance();
    $CI->db->where( 'workflow_id', $arrWorkflow['id'] );
    $arrAllConditions = $CI->db->get( db_prefix() . 'workflow_condition' )->result_array();
    $condCount = 0;
    $finalResult = null;

    //...............................
    foreach ($arrAllConditions as $condition) {
        $condCount++;

/*dig( $condition );*/

        // Example: Checking each condition
        
        //get value here
        // Fetch value from data
        $fieldId = $condition['condition_type_id'];
        $field_value = _getFieldValue( $arrWorkflow, $fieldId, $intEntityId );

        $entityType = $arrWorkflow['entity_type_id'];

        $isTextBox = WF_FIELD_OPTION_MAP[$entityType][$fieldId]['is_textbox'];
        $compareVale = $isTextBox ? $condition['actual_compare_value'] : $condition['compare_value_type_id'];

        $field_value = strtolower( trim( $field_value) );
        $compareVale = strtolower( trim( $compareVale) );
/*if( 5 == $condCount ){
    dig( $field_value ) ;
    dig( $compareVale ) ; 
}*/
        $result = false;
        switch ($condition['operator_type_id']) {

            case WFC_OPERATOR_TYPE['Equal To']:
                $result = ($field_value == $compareVale);
                break;
            case WFC_OPERATOR_TYPE['Not Equal To']:
                $result = ($field_value != $compareVale);
                break;
            case WFC_OPERATOR_TYPE['Contains']:
            case WFC_OPERATOR_TYPE['In']:
                //Convert the comma-separated string into an array
                $compare_value_array = explode(", ", $compareVale);
                //Check if $compareVale exists in the array
                $result = in_array($field_value, $compare_value_array);
                break;
            case WFC_OPERATOR_TYPE['Not contains']:
            case WFC_OPERATOR_TYPE['Not in']:
                //Convert the comma-separated string into an array
                $compare_value_array = explode(", ", $compareVale);
                //Check if $compareVale exists in the array
                $result = !in_array($field_value, $compare_value_array);
                break;
            case WFC_OPERATOR_TYPE['Is empty']:
            case WFC_OPERATOR_TYPE['Is not set']:
                $result = ( is_null($field_value) || '' == $field_value );
                break;
            case WFC_OPERATOR_TYPE['Is not empty']:
            case WFC_OPERATOR_TYPE['Is set']:
                $result = !( is_null($field_value) || '' == $field_value );
                break;
            case WFC_OPERATOR_TYPE['Begins with']:
                $result = (strpos($field_value, $compareValue ) === 0);
                break;
            // Add more cases for different operators
        }
    
        //dig('result=' . $result);
        if( 1 == $condCount ){
            $finalResult = $result;
        } else {
            $finalResult = ( true == $condition['is_and'] ) ? $finalResult && $result : $finalResult || $result;
        }
        //dig('Final result=' . $finalResult);
    }
        //out( 'last ' . $finalResult );
    return $finalResult;    

}

function _getFieldValue( $arrWorkflow, $fieldId, $intEntityId ){
    $CI = &get_instance();
    //..............................
    $entityType = $arrWorkflow['entity_type_id'];
    $tableName = WF_FIELD_OPTION_MAP[$entityType][$fieldId]['table_name'];
    $fieldName = WF_FIELD_OPTION_MAP[$entityType][$fieldId]['field_name'];
    if( 'customfieldsvalues' == $tableName ){
        $fieldTo = explode( '_', $fieldName )[0];
        $customFieldId = getCustomFieldIdBySlug( $fieldName );


        $conditions = array(
            'fieldto' => $fieldTo,
            'relid' => $intEntityId,
            'fieldid' => $customFieldId
        );
        //out( $conditions );
        $query = $CI->db->select('value')
                ->from( db_prefix() . $tableName )
                ->where($conditions)
                ->get();

        // Check if a result is returned
        if ($query->num_rows() > 0) {
            // Fetch the value of field_value
            $result = $query->row();
            $field_value = $result->value;
            return $field_value;
        } else {
            //@todo log
            return false;
        }

    } else {
        //@todo need to add 'value_function' to map if its not exist then consider as main table 
        $conditions = array(
            'id' => $intEntityId,
        );

        $query = $CI->db->select($fieldName)
                ->from( db_prefix() . $tableName)
                ->where($conditions)
                ->get();

        // Check if a result is returned
        if ($query->num_rows() > 0) {
            // Fetch the value of field_value
            $result = $query->row();
            $field_value = $result->$fieldName;
            return $field_value;
        } else {
            //@todo log
            return false;
        }
    }

}

function _getEmailsByFieldIds( $objWorkflow, $strFieldIds, $intEntityId ){
    $CI = &get_instance();
    $entityType = $objWorkflow['entity_type_id'];
    $arrFields = explode( ',', $strFieldIds );
    $userIdsOnFields = [];
    $strEmails = '';

    foreach( $arrFields as $fieldId ){
        $arrfieldDetails = WF_FIELD_OPTION_MAP[$entityType][$fieldId];
        $userIdsOnFields[] = _getFieldValue( $objWorkflow, $fieldId, $intEntityId );
    }

    if( !empty($userIdsOnFields) ){
        $CI->db->select('GROUP_CONCAT(email) as emails');  // Use GROUP_CONCAT to get comma-separated emails
        $CI->db->from( db_prefix() . 'staff'); // Specify the table
        $CI->db->where_in('staffid', $userIdsOnFields);
        $query = $CI->db->get();
    
        $result = $query->row();  // Get the first row (as it will only return one row)
        $strEmails = $result->emails;   // Return the comma-separated list of email
    }

    return $strEmails;
}