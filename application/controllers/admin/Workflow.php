<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Workflow extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('workflow_model');
        $this->load->model('Workflow_condition_model');
        $this->load->model('Workflow_edit_field_model');
        
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
            $data['arrWorkflowConditions'] = $workflow;//load workflow cons here
            $data['workflowEditField'] = $workflow;//load workflow cons here
        }

        $data['entityTypes'] = WF_ENTITY_TYPE;
        $data['actionTypes'] = WF_ACTION_TYPE;
        $data['triggerTypes'] = WF_TRIGGER_TYPE;

        $data['actionTypeMap'] = WF_ACTION_MAP;
        $data['conditionFieldMap'] = WF_FIELD_MAP;


        
        $data['stageTypes'] = Workflow_condition_model::$enumStageType;
        $data['valueTypes'] = Workflow_condition_model::$enumValueType;
        $data['operatorTypes'] = Workflow_condition_model::$enumOperatorType;
        $data['compareValueTypes'] = Workflow_condition_model::$enumCompareValueType;

        $data['entitytoEdit'] = Workflow_edit_field_model::$enumEntitytoEdit;
        $data['entityField'] = Workflow_edit_field_model::$enumEntityField;
        $data['entityFieldValue'] = Workflow_edit_field_model::$enumEntityFieldValue;

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
}
