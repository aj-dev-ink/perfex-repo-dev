<?php

defined('BASEPATH') or exit('No direct script access allowed');

hooks()->add_action('workflow_entity_updated', 'init_workflow_trigger');

function init_workflow_trigger( $strEntityType, $intEntityId, $objExistingEntity ) {
    // Your custom code here
    log_message('info', $strEntityType.' updated Id: ' . $intEntityId);

    $intEntityType = Workflow_model::$enumEntityType[$strEntityType]
    $objApp = &get_instance();
    $objApp->load->model('workflow_model');
    $workflows = $objApp->workflow_model->get_workflows_by_conditions($intEntityType);

    $arrobjWorkflow = [];//get workflows by entitytype & entity id
    if(!empty( $arrobjWorkflow )){
        foreach( $arrobjWorkflow as $objworkflow ){
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
            }
        }
    }
}