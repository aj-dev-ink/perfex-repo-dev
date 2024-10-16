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
                    $isConditionMet = false;                    
                    if( true == $objworkflow['is_condition_based_schedule'] ){
                        //Based on conditions trigger
                        $isConditionMet =  _evaluateScheduleConditions( $objworkflow, $intEntityId );
                    } 

                    if( $isConditionMet || false == $objworkflow['is_condition_based_schedule'] ) {
                        _createProcessSchedule( $objworkflow, $intEntityId );
                    }
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
    $rel_type = strtolower( isset( $arrEntityTypes[$entityType] ) ? $arrEntityTypes[$entityType] : 'compose' );
    $rel_id = $intEntityId;
    $templateId = $objSendEmail->template_id;

    //these are email ids based on entity type get thses details
    $strToFields = $objSendEmail->email_to_fields;
    $strCcFields = $objSendEmail->email_cc_fields;

    $strMailTo = ( '' != $strToFields && '-' != $strToFields ) ? _getEmailsByFieldIds( $objWorkflow, $strToFields, $intEntityId) : '';
    $strMailCC = ( '' != $strCcFields && '-' != $strCcFields ) ? _getEmailsByFieldIds( $objWorkflow, $strCcFields, $intEntityId) : '';
    
    $success = $objApp->email_template_manage_model->send_workflow_mail( $templateId, $strMailTo, $strMailCC, $rel_type, $rel_id );

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

    /*logic:: 
        { grp[ con1 && con2 && con3 ] OR grp[ con1 && con2 && con3 ]  OR grp[ con1 && con2 && con3 ] }
        First create groups of conditions.
        each group will have must fullfill as in AND conditions.
        while checking group any condition false then we can move to next group.
        while checking groups any group evaluate to true then we dont need to further evaluate.
    */
    $groupNumber = 1;
	if( 1 == count( $arrAllConditions ) ){
		$arrgroup[$groupNumber] = $arrAllConditions;
	} else {
		$count=1;
		foreach( $arrAllConditions as $condition ){
			if( $count > 1 && false == $condition['is_and'] ){
				$groupNumber++;
			}
			$arrgroup[$groupNumber][] = $condition;
			$count++;
		}
	}

	foreach( $arrgroup as $group ){
        $groupResult = true;
        foreach( $group as $condition ) {
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
            $groupResult = $groupResult && $result;
            if( false == $groupResult ){
                continue 2;
            }
        }
        if( true == $groupResult ){
          return true;
        }
	}
    return $groupResult;
}

function _evaluateScheduleConditions( $arrWorkflow, $intEntityId ){
    $CI = &get_instance();
    $CI->db->where( 'workflow_id', $arrWorkflow['id'] );
    $arrAllConditions = $CI->db->get( db_prefix() . 'workflow_condition' )->result_array();

    /*logic:: 
        { grp[ con1 && con2 && con3 ] OR grp[ con1 && con2 && con3 ]  OR grp[ con1 && con2 && con3 ] }
        First create groups of conditions.
        each group will have must fullfill as in AND conditions.
        while checking group any condition false then we can move to next group.
        while checking groups any group evaluate to true then we dont need to further evaluate.
    */
    $groupNumber = 1;
	if( 1 == count( $arrAllConditions ) ){
		$arrgroup[$groupNumber] = $arrAllConditions;
	} else {
		$count=1;
		foreach( $arrAllConditions as $condition ){
			if( $count > 1 && false == $condition['is_and'] ){
				$groupNumber++;
			}
			$arrgroup[$groupNumber][] = $condition;
			$count++;
		}
	}

	foreach( $arrgroup as $group ){
        $groupResult = true;
        foreach( $group as $condition ) {
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
            $groupResult = $groupResult && $result;
            if( false == $groupResult ){
                continue 2;
            }
        }
        if( true == $groupResult ){
          return true;
        }
	}
    return $groupResult;
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
    $userEmailsOnFields = [];
    $strEmails = '';

    foreach( $arrFields as $fieldId ){
        if( is_numeric( $fieldId ) ){
            $arrfieldDetails = WF_FIELD_OPTION_MAP[$entityType][$fieldId];
            $fieldValue = _getFieldValue( $objWorkflow, $fieldId, $intEntityId );
            if( isset($arrfieldDetails['function_name']) && $arrfieldDetails['function_name'] == '_getUserOptions' ){
                $userIdsOnFields[] = $fieldValue;
            } else {
                if (filter_var($fieldValue, FILTER_VALIDATE_EMAIL)) {
                    $userEmailsOnFields[] = $fieldValue;
                } 
            }
        } else {
            list( $fieldType, $fieldName ) = explode('_', $fieldId, 2);
            switch( $fieldType ) {
                case 'role':
                    $slug = 'staff_designation';
                    //find all the staff members with matching role & return emails
                    //CustomFieldHelper::getRelIdsBySlugByValue
                    $arrRelIds = getRelIdsBySlugByValue( $slug, $fieldName );
                    if( is_array( $arrRelIds ) ){
                        $arrRelIds = array_column( $arrRelIds, 'relid');
                        $userIdsOnFields = array_unique( array_merge( $userIdsOnFields, $arrRelIds ) );
                    }
                    
                    break;
                default:
                    break;
            }
        }
        
    }

    if( !empty($userIdsOnFields) ){
        $CI->db->select('GROUP_CONCAT(email) as emails');  // Use GROUP_CONCAT to get comma-separated emails
        $CI->db->from( db_prefix() . 'staff'); // Specify the table

        $CI->db->where_in('staffid', $userIdsOnFields);
        $query = $CI->db->get();
    
        $result = $query->row();  // Get the first row (as it will only return one row)
        $strEmails = $result->emails;   // Return the comma-separated list of email
    }
    $usrEmails = '';
    if( !empty($userEmailsOnFields) ){
        $usrEmails = implode(',', $userEmailsOnFields);
    }

    $strEmails = '' == $strEmails ? $usrEmails : $strEmails . ',' . $usrEmails;
    return $strEmails;
}


function _createProcessSchedule( $arrWorkflow, $intEntityId ){
    //Schedule to Perform action
    //get schedule
    //prepare time & repeats

    $objApp = &get_instance();
    $objApp->load->model('workflow_delay');
    $objWorkflowDelay = $objApp->workflow_delay_model->getWorkflowDelayByWorkFlowId( $arrWorkflow['id'] );
    if( $objWorkflowDelay ){
        $baseScheduleDate = _getScheduleDate( $objWorkflowDelay, $intEntityId );
        $sign = $objWorkflowDelay->is_before ? '+' : '-';

        //$baseScheduleDate = "2024-10-11 12:30:00";
        $date = new DateTime( $baseScheduleDate );
        $startTime = null;
        switch( $objWorkflowDelay->pref_duration ) {
            case WFD_DURATION_TYPE['Minutes']:
                $startTime = $date->modify( $sign . $objWorkflowDelay->pref_count . ' minute');
                break;
            case WFD_DURATION_TYPE['Hours']:
                $startTime = $date->modify( $sign . $objWorkflowDelay->pref_count . ' hour');
                break;
            case WFD_DURATION_TYPE['Days']:
                $startTime = $date->modify( $sign . $objWorkflowDelay->pref_count . ' day');
                break;
            case WFD_DURATION_TYPE['Weeks']:
                $startTime = $date->modify( $sign . $objWorkflowDelay->pref_count . ' week');
                break;
            case WFD_DURATION_TYPE['Years']:
                $startTime = $date->modify( $sign . $objWorkflowDelay->pref_count . ' year');
                break;
            default:
                # code...
                break;
        }
        $arrSchedules = [];
        if( $objWorkflowDelay->repeat_type == WFD_REPEAT_TYPE['Do not repeat'] ){
            $arrSchedules[] = $startTime;
        } else {
            if( $objWorkflowDelay->is_recurance ){
                //frequency
                $arrSchedules = _generateFixedSchedules( $startDate, $repeatType, $count );
            } else {
                //untill date
                $arrSchedules = _generateSchedule( $startDate, $endDate, $repeatType );
            }
        }

        //insert these schedules to db now
    }
}

function _getScheduleDate( $objWorkflowDelay, $intEntityId ){
    //implement
    //delay_date_type
}

function _generateSchedule($startDate, $endDate, $repeatType) {
    // Convert the start and end dates to DateTime objects
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    
    // Array to hold the generated schedule
    $schedule = [];
    
    // Determine the interval based on the repeat type
    switch($repeatType) {
        case 'daily':
            $interval = new DateInterval('P1D'); // 1 day interval
            break;
        case 'weekly':
            $interval = new DateInterval('P1W'); // 1 week interval
            break;
        case 'monthly':
            $interval = new DateInterval('P1M'); // 1 month interval
            break;
        case 'yearly':
            $interval = new DateInterval('P1Y'); // 1 year interval
            break;
        default:
            throw new Exception("Invalid repeat type. Allowed values are: daily, weekly, monthly, yearly.");
    }
    
    // Iterate from the start date and generate occurrences until the end date
    while ($start <= $end) {
        $schedule[] = $start->format('Y-m-d H:i:s'); // Add the current date to the schedule
        
        // Move to the next occurrence by adding the interval
        $start->add($interval);
    }
    
    return $schedule;
}


function _generateFixedSchedules( $startDate, $repeatType, $count ) {
    // Convert the start date to a DateTime object
    $start = new DateTime($startDate);
    
    // Array to hold the generated schedule
    $schedule = [];
    
    // Determine the interval based on the repeat type
    switch($repeatType) {
        case 'daily':
            $interval = new DateInterval('P1D'); // 1 day interval
            break;
        case 'weekly':
            $interval = new DateInterval('P1W'); // 1 week interval
            break;
        case 'monthly':
            $interval = new DateInterval('P1M'); // 1 month interval
            break;
        case 'yearly':
            $interval = new DateInterval('P1Y'); // 1 year interval
            break;
        default:
            throw new Exception("Invalid repeat type. Allowed values are: daily, weekly, monthly, yearly.");
    }
    
    // Generate the required number of schedules
    for ($i = 0; $i < $count; $i++) {
        $schedule[] = $start->format('Y-m-d H:i:s'); // Add the current date to the schedule
        
        // Move to the next occurrence by adding the interval
        $start->add($interval);
    }
    
    return $schedule;
}