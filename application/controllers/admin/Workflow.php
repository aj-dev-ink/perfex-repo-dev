<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Workflow extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('workflow_model');
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
            $insert_id = $this->workflow_model->add( $this->input->post() );
            //return to list workflow here
        }

        if( isset( $workflow ) ) {
            $data['workflow'] = $workflow;
        }
        $data['entityTypes'] = Workflow_model::$enumEntityType;
        $data['actionTypes'] = Workflow_model::$enumActionType;
        

        $this->load->view('admin/workflow/workflow', $data);
    }
}
