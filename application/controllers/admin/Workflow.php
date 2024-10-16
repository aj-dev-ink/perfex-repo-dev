<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Workflow extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('workflow_model');
        $this->load->model('workflow_schedule_condition_model');//schedule condition
        $this->load->model('workflow_condition_model');//execute condition
        $this->load->model('workflow_delay_model');
        $this->load->model('workflow_edit_field_model');
        $this->load->model('workflow_send_email_model');
        $this->load->model('workflow_webhook_model');
        $this->load->model('email_template_manage_model');

        
    }

    /* List all Workflow */
    public function index()
    {
        if( staff_cant('view', 'workflow') ) {
            ajax_access_denied();
        }

        $data['workflows'] = $this->workflow_model->get();

        $data['entityTypes'] = array_flip( WF_ENTITY_TYPE );
        $data['actionTypes'] = array_flip( WF_ACTION_TYPE );
        $data['triggerTypes'] = array_flip( WF_TRIGGER_TYPE );


        $data['title'] = _l('Workflows');
        $this->load->view('admin/workflow/manage', $data);
    }

    public function create($id=null)
    {
        if (staff_cant('create', 'workflow')) {
            ajax_access_denied();
        }

        $data = [];
        if( is_numeric($id) ){
            $workflow = $this->workflow_model->get($id);
        };
        
        if ($this->input->post()) {
            //out($this->input->post());
            $insert_id = $this->workflow_model->add( $this->input->post() );
            //return to list workflow here

            if( is_numeric( $insert_id ) ) {
                set_alert('success', _l('added_successfully', _l('workflow')));
            }
            
            redirect(admin_url('workflow'));
        }

        if( isset( $workflow ) ) {
            $data['workflow'] = $workflow;
            $data['arrWorkflowScheduleConditions'] = $workflow;//load workflow cons here
            $data['arrWorkflowConditions'] = $workflow;//load workflow cons here
            $data['workflowEditField'] = $workflow;//load workflow cons here
            $data['workflowDelay'] = $workflow;//load workflow cons here
            $data['workflowReassign'] = $workflow;//load workflow cons here
            $data['workflowSendEmail'] = $workflow;//load workflow cons here
            $data['workflowWebhook'] = $workflow;//load workflow cons here
            
        }

        $data['templates'] = $this->email_template_manage_model->get_templates();

        $data['entityTypes'] = WF_ENTITY_TYPE;
        $data['actionTypes'] = WF_ACTION_TYPE;
        $data['triggerTypes'] = WF_TRIGGER_TYPE;

        $data['actionTypeMap'] = WF_ACTION_MAP;
        $data['conditionFieldMap'] = WF_FIELD_MAP;
      
        $data['stageTypes'] = Workflow_condition_model::$enumStageType;
        $data['valueTypes'] = Workflow_condition_model::$enumValueType;
        $data['operatorTypes'] = WFC_OPERATOR_TYPE;

        $data['durationTypes'] = WFD_DURATION_TYPE;
        $data['isBeforeAfter'] = WFD_IS_BEFORE;
        $data['prefPropertyTypes'] = WFD_PREF_PROPERTY;
        $data['repeatTypes'] = WFD_REPEAT_TYPE;
        $data['reassignUsers'] = WFD_REASSIGN;

        $data['emailTemplate'] = WFD_EMAIL_TEMPLATE;

        $data['entitytoEdit'] = [];
        $data['entityField'] = WFEF_EDIT_TYPE;

        $data['webhookAuthType'] = WFW_AUTH_TYPE;
        $data['webhookRequestType'] = WFW_REQUEST_TYPE;
        $data['conditionFieldOptionMap'] = WF_FIELD_OPTION_MAP;
    
       
        $this->load->view('admin/workflow/workflow', $data);
    }

    /* Delete workflow from database */
    public function delete($id)
    {
        if (!$id) {
            redirect(admin_url('workflow'));
        }
        if (staff_cant('delete', 'workflow')) {
            access_denied('Delete Workflow');
        }

        $response = $this->workflow_model->delete_workflow($id);

        if ($response) {
            set_alert('success', _l('deleted', _l('workflow')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('workflow_lowercase')));
        }
        redirect(admin_url('workflow'));
    }

    public function toggleStatus() {
        $id = $this->input->post('id');
        $new_status = $this->input->post('status');
    
        if (is_numeric($id)) {
            $result = $this->workflow_model->toggle_status($id,$new_status);
            if ( $result ) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        } else {
            echo json_encode( ['success' => false] );
        }
    }

    public function getCompareOptions() {
        $fieldId = $this->input->post('fieldId');
        $entityType = $this->input->post('entityType');
    
        if( is_numeric( $fieldId ) && is_numeric( $entityType ) ) {
            $arrOptions = $this->workflow_model->getOptionsByFieldEntity( $fieldId, $entityType );
            if( is_array( $arrOptions ) ) {
                echo json_encode( ['success' => true, 'data'=>$arrOptions ] );
            } else {
                echo json_encode( ['success' => false, 'message'=>'Unable to load options.'] );
            }
        } else {
            echo json_encode( ['success' => false, 'message'=>'Invalid entity or field.'] );
        }
    }

    public function getEmailFieldOptions() {
        $entityType = $this->input->post('entityType');

        //WorkflowHelper::_getEmailFieldOptions()
        $arrOptions = _getEmailFieldOptions( $entityType );
        if( is_array( $arrOptions ) ) {
            echo json_encode( ['success' => true, 'data'=>$arrOptions ] );
        } else {
            echo json_encode( ['success' => false, 'message'=>'Unable to load options.'] );
        }
    }
    
}
