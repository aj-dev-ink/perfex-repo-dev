<?php

defined('BASEPATH') or exit('No direct script access allowed');


class Email_template_manage_model extends CI_Model
{

    public string $email_template_from_mail = '';
    public string $email_template_from_name = '';


    public function __construct()
    {

        parent::__construct();


        $this->email_template_from_mail = get_option('smtp_email') ;

        $this->email_template_from_name = $this->get_mail_from_company() ;


    }

    /**
     * Checking templates
     */
    public function send_templates( $timer_id = 0  , $is_echo = false)
    {

        $current_date = date('Y-m-d');

        $template_tab   = db_prefix().'email_template_manage_templates';
        $setting_tab    = db_prefix().'email_template_manage_timer';
        $group_table    = db_prefix().'customer_groups';

        if ( !empty( $timer_id ) )
            $this->db->where('set.id',$timer_id);
        else
            $this->db->where("set.sending_date <= '$current_date'",null , false );


        $templates = $this->db->select('temp.template_subject , temp.template_content , temp.template_name , set.id ,  set.template_id , set.sending_hour , set.client_groups , set.clients , set.not_clients , set.lead_sources , set.lead_statuses , set.leads ')
                            ->from( $setting_tab.' set' )
                            ->join( $template_tab.' temp','temp.id = set.template_id' )
                            ->where('set.status',1)
                            ->where('set.send_status',0)
                            ->where('temp.status',1)
                            //->where("set.sending_date <= '$current_date'",null , false )
                            ->get()
                            ->result();


        if( !empty( $templates ) )
        {



            $this->load->library('merge_fields/client_merge_fields');

            $this->load->library('merge_fields/leads_merge_fields');

            $this->load->library('merge_fields/other_merge_fields');

            $this->get_email_config();

            $this->load->config('email');


            foreach ( $templates as $template )
            {

                /**
                 * Checking hour
                 */
                if( empty( $timer_id ) && !empty( $template->sending_hour ) )
                {

                    if( (int)$template->sending_hour > (int)date('H') )
                        continue;

                }


                /**
                 * Client groups
                 */
                if( !empty( $template->client_groups ) )
                {
                    $groups = json_decode( $template->client_groups , 1 );

                    if( !empty( $groups ) )
                    {

                        foreach ( $groups as $group )
                        {

                            $group_clients = $this->db->select('groupid,customer_id')->from($group_table)->where('groupid',$group)->get()->result();

                            if( !empty( $group_clients ) )
                            {

                                foreach ( $group_clients as $group_client )
                                {

                                    $this->template_send_mail( $template , $is_echo , $group_client->customer_id );

                                }

                            }

                        }

                    }

                }


                /**
                 * Clients
                 */
                if ( !empty( $template->clients ) )
                {

                    $clients = json_decode( $template->clients , 1 );

                    if ( !empty( $clients ) )
                    {

                        foreach ( $clients as $client)
                        {

                            $this->template_send_mail( $template , $is_echo , $client );

                        }

                    }

                }


                /**
                 * Leads
                 */
                if ( !empty( $template->leads ) )
                {

                    $leads = json_decode( $template->leads , 1 );

                    if ( !empty( $leads ) )
                    {

                        foreach ( $leads as $lead )
                        {

                            $this->template_send_mail( $template , $is_echo , 0 , $lead );

                        }

                    }

                }


                /**
                 * Lead status
                 */
                if( !empty( $template->lead_statuses ) )
                {
                    $statuses = json_decode( $template->lead_statuses , 1 );

                    if( !empty( $statuses ) )
                    {

                        foreach ( $statuses as $status )
                        {

                            $leads = $this->db->select('id')->from(db_prefix().'leads')->where('status',$status)->get()->result();

                            if( !empty( $leads ) )
                            {

                                foreach ( $leads as $lead )
                                {

                                    $this->template_send_mail( $template , $is_echo , 0 , $lead->id );

                                }

                            }

                        }

                    }

                }


                /**
                 * Lead source
                 */
                if( !empty( $template->lead_sources ) )
                {
                    $sources = json_decode( $template->lead_sources , 1 );

                    if( !empty( $sources ) )
                    {

                        foreach ( $sources as $source )
                        {

                            $leads = $this->db->select('id')->from(db_prefix().'leads')->where('source',$source)->get()->result();

                            if( !empty( $leads ) )
                            {

                                foreach ( $leads as $lead )
                                {

                                    $this->template_send_mail( $template , $is_echo , 0 , $lead->id );

                                }

                            }

                        }

                    }

                }


                // mail sending error
                $send_info = $this->db->select( 'status' )->from(db_prefix().'email_template_manage_sending_logs')->where('mail_id',$template->id)->get()->result();

                $status = 9;
                $success_send = 0;
                if( !empty( $send_info ) )
                {

                    $total_mail = count( $send_info );

                    foreach ( $send_info as $send_i )
                    {

                        if( $send_i->status == 1 )
                            $success_send++;

                    }


                    if( $success_send > 0 )
                        $status = 1 ;

                    if ( $total_mail > $success_send )
                        $status = 2 ;

                    if ( $success_send == 0 )
                        $status = 3;

                }


                $this->db->set('send_status',$status)->where('id',$template->id)->update($setting_tab);


            }


        }


    }

    public function template_send_mail( $template , $is_echo , $client_id = 0 , $lead_id = 0 )
    {

        if( !empty( $client_id ) )
        {

            /**
             * Checking not send clients
             */

            if ( !empty( $template->not_clients ) )
            {
                $not_send_clients = json_decode( $template->not_clients , 1 );

                if ( !empty( $not_send_clients ) )
                {

                    if ( in_array( $client_id , $not_send_clients ) )
                        return true;

                }

            }

        }


        $this->email->from( $this->email_template_from_mail , $this->email_template_from_name );


        $systemBCC = get_option('bcc_emails');

        $mail_log_tab   = db_prefix().'email_template_manage_sending_logs';

        $template_id    = !empty( $template->template_id ) ? $template->template_id : $template->id;
        $record_id      = $template->id;



        $log_sql = [];

        $log_sql['mail_id'] = $record_id ;

        if ( !empty( $client_id ) )
            $log_sql['client_id'] = $client_id ;

        if ( !empty( $lead_id ) )
            $log_sql['lead_id'] = $lead_id ;

        $email_log_info = $this->db->select('id')->from($mail_log_tab)->where( $log_sql )->get()->row();

        if( !empty( $email_log_info ) )
            return true;


        $content        = $template->template_content;
        $mail_subject   = $template->template_subject;

        $merge_fields = [];

        if( !empty( $lead_id ) )
            $merge_fields = array_merge($merge_fields, $this->leads_merge_fields->format( $lead_id ) );

        if( !empty( $client_id ) )
            $merge_fields = array_merge($merge_fields,  $this->client_merge_fields->format( $client_id ) );

        $merge_fields = array_merge($merge_fields,      $this->other_merge_fields->format() );

        foreach ( $merge_fields as $key => $val )
        {

            if( stripos($content, $key) !== false )
                $content = str_ireplace($key, $val, $content);
            else
                $content = str_ireplace($key, '', $content);


            if( stripos($mail_subject, $key) !== false )
                $mail_subject = str_ireplace($key, $val, $mail_subject);
            else
                $mail_subject = str_ireplace($key, '', $mail_subject);

        }




        $to_text = $to_email = "";


        if( !empty( $client_id ) )
        {

            if( !empty( $merge_fields["{contact_email}"] ) )
            {

                $to_email   = $merge_fields["{contact_email}"];

                $to_text    = !empty( $merge_fields["{client_company}"] )   ? $merge_fields["{client_company}"] : '' ;

            }

        }


        if ( !empty( $lead_id ) )
        {

            if ( !empty( $merge_fields["{lead_email}"] ) )
            {

                $to_email = $merge_fields["{lead_email}"] ;

                if ( !empty( $merge_fields["{lead_company}"] ) )

                    $to_text = $merge_fields["{lead_company}"];

                elseif ( !empty( $merge_fields["{lead_name}"] ) )

                    $to_text = $merge_fields["{lead_name}"];

            }

        }


        if ( empty( $to_email ) )
        {

            log_activity( "Email template manage : mail address not found [ client id : $client_id | lead id : $lead_id ] " );

            return true;

        }



        // Saving mail to mailbox


        $insert_log = [
            'template_id'   => $template_id ,
            'company_name'  => $to_text ,
            'company_email' => $to_email,
            'mail_subject'  => $mail_subject,
            'content'       => $content,
        ];

        if( !empty( $client_id ) )
        {

            $insert_log['rel_type'] = 'customer';

            $insert_log['rel_id'] = $client_id;

        }
        elseif ( !empty( $lead_id ) )
        {

            $insert_log['rel_type'] = 'lead';

            $insert_log['rel_id'] = $lead_id;

        }

        $mail_log_id = $this->set_mail_send_log( $insert_log );

        $mail_opened_content = '<img src="' . site_url('email_template_manage/check_email/track/' . $mail_log_id) . '" alt="" width="1" height="1" border="0" style="height:1px!important;width:1px!important;border-width:0!important;margin-top:0!important;margin-bottom:0!important;margin-right:0!important;margin-left:0!important;padding-top:0!important;padding-bottom:0!important;padding-right:0!important;padding-left:0!important">';;


        $this->email->subject($mail_subject);

        $this->email->message($content.$mail_opened_content);

        if ($systemBCC != '')
        {
            $this->email->bcc($systemBCC);
        }


        /**
         * Attachment files
         */
        $attachment = $this->get_attachments( $template_id );

        if ( !empty( $attachment ) )
        {

            $path = get_upload_path_by_type('email_template');

            foreach ( $attachment as $attach )
            {

                $attach_file_path = $path.$template_id.'/'.$attach['file_name'];

                $this->email->attach( $attach_file_path );

                $log_attach = [
                    'mail_id' => $mail_log_id ,
                    'file_name' => $attach['file_name'] ,
                    'file_type' => $attach['filetype'] ,
                    'file_path' => $path.$template_id.'/'
                ];

                $this->mail_attachment_log( $log_attach );

            }

        }


        $this->email->to( $to_email );

        $log_data = [
            'mail_id'       => $record_id ,
            'client_id'     => $client_id ,
            'lead_id'       => $lead_id ,
            'mail_address'  => $to_email ,
            'mail_company'  => $to_text ,
            'date'          => date('Y-m-d') ,
        ];


        if( $this->email->send() )
        {

            log_activity( "Email template manage : mail send successfully to $to_text [ mail : $to_email ] " );

            if ($is_echo){
                echo "<p> TO :  $to_email | RESULT :  <span style='color: green;'>SUCCESSFULLY</span></p>";
                echo "<p> --------------------------------------------- </p>";
            }


            $log_data['status'] = 1;

            $update_log['status'] = 1;

        }
        else
        {

            $errorMessage = $this->email->print_debugger();

            if ($is_echo){
                echo "<p> TO :  $to_email | RESULT : <span style='color: red;'> Failed </span></p>";
                echo " --------------------------------------------- ";
            }


            log_activity( "Email template manage : mail send successfully to $to_text [ mail : $to_email - error : $errorMessage ]" );

            $log_data['status'] = 2;

            $log_data['error_message'] = $errorMessage;

            $update_log['error_message'] = $errorMessage;

            $update_log['status'] = 2;

        }


        $this->db->insert( $mail_log_tab , $log_data );


        $this->db->where('id',$mail_log_id)->update(db_prefix().'email_template_manage_mail_logs',$update_log);

        return true ;

    }

    public function get_attachments($template_id, $id = '')
    {

        // If is passed id get return only 1 attachment
        if (is_numeric($id))
        {

            $this->db->where('id', $id);

        }
        else
        {

            $this->db->where('rel_id', $template_id);

        }

        $this->db->where('rel_type', 'email_template');

        $result = $this->db->get(db_prefix() . 'files');

        if( is_numeric( $id ) )
        {

            return $result->row();

        }

        return $result->result_array();
    }

    public function get_templates( $rel_type = '' )
    {

        if ( $rel_type == 'customer' )
            $rel_type = 'client';

        if ( !is_admin() )
            $this->db->where(" ( is_public = 1 OR added_staff_id = ".get_staff_user_id()." ) ",null,false);

        if ( !empty( $rel_type ) )
            $this->db->where(" ( related_type = 'all' OR related_type = '$rel_type' ) ",null,false);

        return $this->db->select('*')->from(db_prefix().'email_template_manage_templates')->where('status',1)->get()->result();

    }

    public function get_template( $template_id )
    {

        return $this->db->select('*')->from(db_prefix().'email_template_manage_templates')->where('status',1)->where('id',$template_id)->get()->row();

    }

    /**
     * @return mixed | ALl acite templates
     */
    public function get_all_templates_array( $rel_types = [] )
    {

        if ( !empty( $rel_types ) )
            $this->db->where_in('related_type',$rel_types);

        return $this->db->select('id,template_name')->from(db_prefix().'email_template_manage_templates')->where('status',1)->order_by('template_name')->get()->result_array();

    }


    /**
     * Version 1.0.2
     */

    public $last_uploaded_files = [];

    public function send_mail( $mail_to = "" )
    {

        $rel_type       = $this->input->post('rel_type');
        $rel_id         = $this->input->post('rel_id');

        $template_id    = $this->input->post('compose_template_id');
        $mail_cc        = $this->input->post('mail_cc');
        $subject        = $this->input->post('subject');
        $content        = $this->input->post('template_content',false);


        $smtp_setting_id= $this->input->post('smtp_setting_id');


        if ( empty( $mail_to ) && !empty( $this->input->post('mail_to') ) )
            $mail_to        = $this->input->post('mail_to');

        list( $email , $mail_company , $send_rel_type , $send_rel_id ) = $this->send_mail_modal_info( $rel_type , $rel_id , $mail_to );

        $insert_log = [
            'template_id'   => $template_id ,
            'company_email' => $mail_to ,
            'company_cc'    => $mail_cc ,
            'mail_subject'  => $subject ,
            'content'       => $content ,
            'send_rel_type' => $rel_type ,
            'send_rel_id'   => $rel_id ,
            'company_name'  => $mail_company ,
            'rel_type'      => $send_rel_type ,
            'rel_id'        => $send_rel_id ,
            'smtp_setting_id' => $smtp_setting_id
        ];

        $mail_log_id = $this->set_mail_send_log( $insert_log );

        $mail_opened_content = '<img src="' . site_url('email_template_manage/check_email/track/' . $mail_log_id) . '" alt="" width="1" height="1" border="0" style="height:1px!important;width:1px!important;border-width:0!important;margin-top:0!important;margin-bottom:0!important;margin-right:0!important;margin-left:0!important;padding-top:0!important;padding-bottom:0!important;padding-right:0!important;padding-left:0!important">';;


        /**
         * Email template config information
         */
        $this->get_email_config();

        $this->load->config('email');


        /**
         * @Version 1.1.5
         */
        if ( !empty( $smtp_setting_id ) )
        {

            $initialize_data = $this->mail_smtp_initialize( $smtp_setting_id );

            if ( !empty( $initialize_data ) )
            {

                $this->email->initialize( $initialize_data );

            }

        }


        $this->email->from( $this->email_template_from_mail , $this->email_template_from_name );


        $systemBCC = get_option('bcc_emails');

        $this->email->subject( $subject );

        $this->email->message( $content.$mail_opened_content );

        if( $systemBCC != '' )
        {

            $this->email->bcc( $systemBCC );

        }

        $this->email->to( $mail_to );

        if( !empty( $mail_cc ) )
            $this->email->cc( $mail_cc );



        /**
         * Attachment files
         */
        $attachment = $this->get_attachments( $template_id );

        if ( !empty( $attachment ) )
        {

            $path = get_upload_path_by_type('email_template');

            foreach ( $attachment as $attach )
            {

                $attach_file_path = $path.$template_id.'/'.$attach['file_name'];

                $this->email->attach( $attach_file_path );

                $log_attach = [
                    'mail_id' => $mail_log_id ,
                    'file_name' => $attach['file_name'] ,
                    'file_type' => $attach['filetype'] ,
                    'file_path' => $path.$template_id.'/'
                ];

                $this->mail_attachment_log( $log_attach );

            }

        }


        $file_upload = $this->upload_attachment( $mail_log_id );


        if ( !empty( $file_upload ) )
        {

            $this->last_uploaded_files = $file_upload;

        }
        elseif ( !empty( $this->last_uploaded_files ) )
        {

            $file_upload = $this->last_uploaded_files;

        }


        if ( !empty( $file_upload ) )
        {

            foreach ( $file_upload as $f_upload )
            {

                $attach_file_path = $f_upload['file_path'].$f_upload['file_name'];

                $this->email->attach( $attach_file_path );

                $log_attach = [
                    'mail_id' => $mail_log_id ,
                    'file_name' => $f_upload['file_name'] ,
                    'file_type' => $f_upload['file_type'] ,
                    'file_path' => $f_upload['file_path']
                ];

                $this->mail_attachment_log( $log_attach );

            }

        }

        if( $this->email->send() )
        {

            $update_log['status'] = 1;

        }
        else
        {

            $errorMessage = $this->email->print_debugger();

            $update_log['error_message'] = $errorMessage;

            $update_log['status'] = 2;

        }


        $this->db->where('id',$mail_log_id)->update(db_prefix().'email_template_manage_mail_logs',$update_log);

        // send success
        if ( $update_log['status'] == 1 )
            return true;

        return false;

    }


    public function set_mail_send_log( $log_data )
    {

        $log_data['date'] = date('Y-m-d H:i:s');

        // is staff login
        if( is_staff_logged_in() )
        {
            $log_data['added_staff_id'] = get_staff_user_id();
        }


        $this->db->insert(db_prefix().'email_template_manage_mail_logs',$log_data);

        $log_id = $this->db->insert_id();

        log_activity( "Email template manage send mail [ mail log id : $log_id ]" );

        /**
         * Last contact address will change
         */
        if( !empty( $log_data['rel_type'] ) && $log_data['rel_type'] == 'lead' )
        {

            $rel_id = $log_data['rel_id'];

            $this->db->where('id',$rel_id)->update(db_prefix().'leads' , [ 'lastcontact' => date('Y-m-d H:i:s') ] );

        }



        return $log_id;

    }


    public function upload_attachment( $mail_id = 0 )
    {

        if( !empty( $mail_id ) && isset( $_FILES['attachments'] ) && !empty($_FILES['attachments']) && $_FILES['attachments']['error'][0] != 4 )
        {

            return $this->handle_email_template_manage_attachments( $mail_id );

        }

    }


    private function handle_email_template_manage_attachments( $mail_id , $index_name = 'attachments' )
    {

        $uploaded_files = [];

        $path           =  FCPATH.'uploads/email_template/mail_' . $mail_id . '/';

        if (
            isset($_FILES[$index_name]['name'])
            &&
            ( $_FILES[$index_name]['name'] != '' || is_array( $_FILES[$index_name]['name'] ) && count( $_FILES[$index_name]['name'] ) > 0 )
        )
        {

            if( !is_array( $_FILES[$index_name]['name'] ) )
            {
                $_FILES[$index_name]['name']     = [$_FILES[$index_name]['name']];
                $_FILES[$index_name]['type']     = [$_FILES[$index_name]['type']];
                $_FILES[$index_name]['tmp_name'] = [$_FILES[$index_name]['tmp_name']];
                $_FILES[$index_name]['error']    = [$_FILES[$index_name]['error']];
                $_FILES[$index_name]['size']     = [$_FILES[$index_name]['size']];
            }

            _file_attachments_index_fix($index_name);

            for ( $i = 0 ; $i < count( $_FILES[$index_name]['name'] ); $i++ )
            {

                // Get the temp file path
                $tmpFilePath = $_FILES[$index_name]['tmp_name'][$i];

                // Make sure we have a filepath
                if (!empty($tmpFilePath) && $tmpFilePath != '')
                {

                    _maybe_create_upload_path($path);

                    $filename    = unique_filename($path, $_FILES[$index_name]['name'][$i]);

                    $newFilePath = $path . $filename;

                    if (move_uploaded_file($tmpFilePath, $newFilePath))
                    {

                        $uploaded_files[] = [
                            'file_name' => $filename,
                            'file_path' => $path,
                            'file_type' => $_FILES[$index_name]['type'][$i],
                        ];

                    }

                }

            }

        }


        return $uploaded_files;

    }

    private function mail_attachment_log( $log_data )
    {

        $this->db->insert(db_prefix().'email_template_manage_mail_attachments' , $log_data );

    }


    /**
     * The customer's email notification open contact records are returned.
     */
    private function get_contacts_for_notification( $client_id = 0 , $where = [] )
    {

        if ( empty( $client_id ) )
            return null;

        $this->db->where('active',1);

        $this->db->where('userid', $client_id);

        if ( !empty( $where ) )
            $this->db->where($where);

        return $this->db->select('id,email,firstname,lastname,userid')->get(db_prefix() . 'contacts')->result();

    }

    public function include_merge_libraries()
    {

        $this->load->library('merge_fields/client_merge_fields');

        $this->load->library('merge_fields/staff_merge_fields');

        $this->load->library('merge_fields/leads_merge_fields');

        $this->load->library('merge_fields/invoice_merge_fields');

        $this->load->library('merge_fields/projects_merge_fields');

        $this->load->library('merge_fields/tasks_merge_fields');

        $this->load->library('merge_fields/proposals_merge_fields');

        $this->load->library('merge_fields/estimate_merge_fields');

        $this->load->library('merge_fields/contract_merge_fields');

        $this->load->library('merge_fields/other_merge_fields');

        $this->load->library('merge_fields/estimate_request_merge_fields');

    }

    /**
     * library merge fields
     */
    public function get_libraries_merge_fields()
    {

        $merge_fields = [

            'client'    => $this->client_merge_fields->build() ,
            'lead'      => $this->leads_merge_fields->build() ,
            'invoice'   => $this->invoice_merge_fields->build() ,
            'staff'     => $this->staff_merge_fields->build() ,
            'project'   => $this->projects_merge_fields->build() ,
            'task'      => $this->tasks_merge_fields->build() ,
            'proposal'  => $this->proposals_merge_fields->build() ,
            'estimate'  => $this->estimate_merge_fields->build() ,
            'estimate_request'  => $this->estimate_request_merge_fields->build() ,
            'contract'  => $this->contract_merge_fields->build() ,
            'other'     => $this->other_merge_fields->build() ,

        ];


        #custom fields
        // company leads customers contacts staff contracts tasks expenses invoice items credit_note estimate proposal projects tickets
        foreach ( [ 'company' , 'customers' , 'leads' , 'invoice' , 'contacts' , 'staff' , 'contracts' , 'tasks' , 'estimate' , 'proposal' , 'projects' ] as $rel_type )
        {

            $rel_type_inc = $rel_type;

            switch ( $rel_type )
            {

                case 'company':     $rel_type_inc = 'other'; break ;

                case 'customers':
                case 'contacts':    $rel_type_inc = 'client'; break ;

                case 'leads':       $rel_type_inc = 'lead'; break ;

                case 'contracts':   $rel_type_inc = 'contract'; break ;

                case 'tasks':       $rel_type_inc = 'task'; break ;

                case 'projects':    $rel_type_inc = 'project'; break ;

            }


            $custom_fields = get_custom_fields($rel_type);

            if ( !empty( $custom_fields ) && !empty( $merge_fields[$rel_type_inc] ) )
            {

                foreach ($custom_fields as $field)
                {

                    $merge_fields[$rel_type_inc][] = [ 'key' => '{'.$field['slug'].'}' ,        'name' => $field['name'] ];

                }

            }

        }




        # Projects
        $merge_fields['project'][] = [ 'key' => '{project_id}' ,        'name' => _l('email_template_manage_project_id') ];
        $merge_fields['project'][] = [ 'key' => '{project_status}' ,    'name' => _l('email_template_manage_project_status') ];


        # Proposal
        $merge_fields['proposal'][] = [ 'key' => '{proposal_status}' ,    'name' => _l('email_template_manage_proposal_status') ];




        return $merge_fields;

    }

    public function send_mail_modal_info( $rel_type , $rel_id , $mail = "" )
    {

        $client_id      = 0 ;

        $emails         = [];
        $mail_company   = "" ;
        $send_rel_type  = "" ;
        $send_rel_id    = 0  ;

        if ( $rel_type == 'customer' )
        {

            $send_rel_type  = "customer" ;
            $send_rel_id    = $rel_id ;
            $client_id      = $rel_id;

            $contacts = $this->get_contacts_for_notification( $rel_id );

            if ( !empty( $contacts ) )
            {

                foreach ( $contacts as $contact )
                {
                    if( valid_email( $contact->email ) )
                    {
                        $emails["$contact->email"] = "$contact->firstname $contact->lastname ( $contact->email )" ;
                    }

                }

            }


        }
        elseif ( $rel_type == 'lead' )
        {

            $send_rel_type = "lead" ;
            $send_rel_id = $rel_id ;

            $lead = $this->db->select('email,company,name')->from(db_prefix().'leads')->where('id',$rel_id)->get()->row();

            if ( valid_email( $lead->email ) )
            {
                $mail_company = !empty( $lead->company ) ? $lead->company : $lead->name;
                $emails["$lead->email"] = "$lead->name ( $lead->email )" ;
            }

        }
        elseif ( $rel_type == 'invoice' )
        {

            $invoice = $this->db->select('id,clientid,sale_agent')->where('id',$rel_id)->get(db_prefix().'invoices')->row();

            $staff_info = $this->db->select('email,firstname,lastname')->from(db_prefix().'staff')->where('staffid',$invoice->sale_agent)->get()->row();

            if( valid_email( $staff_info->email ) )
            {

                if ( $mail == $staff_info->email )
                {
                    $send_rel_type  = "staff" ;
                    $send_rel_id    = $invoice->sale_agent ;
                    $mail_company   = $staff_info->firstname." ".$staff_info->lastname ;
                }

                $emails["$staff_info->email"] = "$staff_info->firstname $staff_info->lastname ( $staff_info->email )" ;
            }


            $contacts = $this->get_contacts_for_notification( $invoice->clientid , [ 'invoice_emails' => 1 ] );

            if ( !empty( $contacts ) )
            {

                foreach ( $contacts as $contact )
                {
                    if( valid_email( $contact->email ) )
                    {

                        if ( $mail == $contact->email )
                        {
                            $client_id  = $contact->userid ;
                        }

                        $emails["$contact->email"] = "$contact->firstname $contact->lastname ( $contact->email )" ;
                    }

                }

            }

        }
        elseif ( $rel_type == 'project' )
        {

            $project = $this->db->select('id,clientid')->where('id',$rel_id)->get(db_prefix().'projects')->row();

            // Mail sending to staff
            $members = $this->db->select('s.email, s.firstname, s.lastname, s.staffid')
                                ->from(db_prefix().'project_members p')
                                ->join(db_prefix().'staff s','s.staffid = p.staff_id')
                                ->where('p.project_id',$project->id)
                                ->get()
                                ->result();

            if ( !empty( $members ) )
            {
                foreach ( $members as $member )
                {

                    if( valid_email( $member->email ) )
                    {

                        if ( $mail == $member->email )
                        {
                            $send_rel_type  = "staff" ;
                            $send_rel_id    = $member->staffid ;
                            $mail_company   = $member->firstname." ".$member->lastname ;
                        }

                        $emails["$member->email"] = "$member->firstname $member->lastname ( $member->email )" ;
                    }

                }

            }


            $contacts = $this->get_contacts_for_notification( $project->clientid , [ 'project_emails' => 1 ] );

            if ( !empty( $contacts ) )
            {

                foreach ( $contacts as $contact )
                {
                    if( valid_email( $contact->email ) )
                    {

                        if ( $mail == $contact->email )
                        {
                            $client_id  = $contact->userid ;
                        }

                        $emails["$contact->email"] = "$contact->firstname $contact->lastname ( $contact->email )" ;
                    }

                }

            }

        }
        elseif ( $rel_type == 'task' )
        {

            // Mail sending to staff
            $members = $this->db->select('s.email, s.firstname, s.lastname, s.staffid')
                                ->from(db_prefix().'task_assigned p')
                                ->join(db_prefix().'staff s','s.staffid = p.staffid')
                                ->where('p.taskid',$rel_id)
                                ->get()
                                ->result();

            if ( !empty( $members ) )
            {
                foreach ( $members as $member )
                {

                    if( valid_email( $member->email ) )
                    {

                        if ( $mail == $member->email )
                        {
                            $send_rel_type  = "staff" ;
                            $send_rel_id    = $member->staffid ;
                            $mail_company   = $member->firstname." ".$member->lastname ;
                        }

                        $emails["$member->email"] = "$member->firstname $member->lastname ( $member->email )" ;
                    }

                }

            }


        }
        elseif ( $rel_type == 'proposal' )
        {

            $proposal = $this->db->select('id,rel_type,rel_id,assigned')->where('id', $rel_id)->get(db_prefix() . 'proposals')->row();

            if ( !empty( $proposal->assigned ) )
            {

                $staff_info = $this->db->select('email,firstname,lastname')->from(db_prefix().'staff')->where('staffid',$proposal->assigned)->get()->row();

                if( valid_email( $staff_info->email ) )
                {
                    if ( $mail == $staff_info->email )
                    {
                        $send_rel_type  = "staff" ;
                        $send_rel_id    = $proposal->assigned ;
                        $mail_company   = $staff_info->firstname." ".$staff_info->lastname ;
                    }

                    $emails["$staff_info->email"] = "$staff_info->firstname $staff_info->lastname ( $staff_info->email )" ;
                }

            }


            if ( !empty( $proposal->rel_type ) && !empty( $proposal->rel_id ) )
            {

                if( $proposal->rel_type == 'customer' )
                {

                    $contacts = $this->get_contacts_for_notification( $proposal->rel_id , [ 'is_primary' => 1 ] );

                    if ( !empty( $contacts ) )
                    {

                        foreach ( $contacts as $contact )
                        {
                            if( valid_email( $contact->email ) )
                            {
                                if ( $mail == $contact->email )
                                {
                                    $client_id  = $contact->userid ;
                                }

                                $emails["$contact->email"] = "$contact->firstname $contact->lastname ( $contact->email )" ;
                            }

                        }

                    }

                }
                elseif( $proposal->rel_type == 'lead' )
                {

                    $lead = $this->db->select('email,company,name')->from(db_prefix().'leads')->where('id',$proposal->rel_id)->get()->row();

                    if ( valid_email( $lead->email ) )
                    {

                        if ( $mail == $lead->email )
                        {
                            $mail_company   = !empty( $lead->company ) ? $lead->company : $lead->name;
                            $send_rel_type  = 'lead';
                            $send_rel_id    = $proposal->rel_id;
                        }

                        $emails["$lead->email"] = "$lead->name ( $lead->email )" ;
                    }

                }

            }


        }
        elseif ( $rel_type == 'estimate' )
        {

            $estimate = $this->db->select('id,clientid,sale_agent')->where('id', $rel_id)->get(db_prefix() . 'estimates')->row();

            $staff_info = $this->db->select('email,firstname,lastname')->from(db_prefix().'staff')->where('staffid',$estimate->sale_agent)->get()->row();

            if( valid_email( $staff_info->email ) )
            {

                if ( $mail == $staff_info->email )
                {
                    $mail_company   = $staff_info->firstname." ".$staff_info->lastname ;
                    $send_rel_type  = 'staff';
                    $send_rel_id    = $estimate->sale_agent;
                }

                $emails["$staff_info->email"] = "$staff_info->firstname $staff_info->lastname ( $staff_info->email )" ;
            }


            $contacts = $this->get_contacts_for_notification( $estimate->clientid , [ 'estimate_emails' => 1 ] );

            if ( !empty( $contacts ) )
            {

                foreach ( $contacts as $contact )
                {
                    if( valid_email( $contact->email ) )
                    {
                        if ( $mail == $contact->email )
                        {
                            $client_id  = $contact->userid ;
                        }

                        $emails["$contact->email"] = "$contact->firstname $contact->lastname ( $contact->email )" ;
                    }

                }

            }

        }
        elseif ( $rel_type == 'estimate_request' )
        {

            $estimate = $this->db->select('id,assigned')->where('id', $rel_id)->get(db_prefix() . 'estimate_requests')->row();

            $staff_info = $this->db->select('email,firstname,lastname')->from(db_prefix().'staff')->where('staffid',$estimate->assigned)->get()->row();

            if( valid_email( $staff_info->email ) )
            {

                if ( $mail == $staff_info->email )
                {
                    $mail_company   = $staff_info->firstname." ".$staff_info->lastname ;
                    $send_rel_type  = 'staff';
                    $send_rel_id    = $estimate->sale_agent;
                }

                $emails["$staff_info->email"] = "$staff_info->firstname $staff_info->lastname ( $staff_info->email )" ;
            }

        }
        elseif ( $rel_type == 'contract' )
        {

            $contract = $this->db->select('id,client')->where('id', $rel_id)->get(db_prefix() . 'contracts')->row();

            $contacts = $this->get_contacts_for_notification( $contract->client , [ 'contract_emails' => 1 ] );

            if ( !empty( $contacts ) )
            {

                foreach ( $contacts as $contact )
                {
                    if( valid_email( $contact->email ) )
                    {
                        if ( $mail == $contact->email )
                        {
                            $client_id  = $contact->userid ;
                        }

                        $emails["$contact->email"] = "$contact->firstname $contact->lastname ( $contact->email )" ;
                    }

                }

            }

        }
        elseif ( $rel_type == 'staff' )
        {

            $staff_info = $this->db->select('email,firstname,lastname')->from(db_prefix().'staff')->where('staffid',$rel_id)->get()->row();

            if( valid_email( $staff_info->email ) )
            {

                if ( $mail == $staff_info->email )
                {
                    $send_rel_type  = "staff" ;
                    $send_rel_id    = $rel_id ;
                    $mail_company   = $staff_info->firstname." ".$staff_info->lastname ;
                }

                $emails["$staff_info->email"] = "$staff_info->firstname $staff_info->lastname ( $staff_info->email )" ;
            }

        }


        if ( !empty( $client_id ) )
        {

            $client_data = $this->db->select('company')->from(db_prefix().'clients')->where('userid',$client_id)->get()->row();

            if ( !empty( $client_data ) )
                $mail_company = $client_data->company ;

            $send_rel_type  = "customer" ;
            $send_rel_id    = $client_id ;

        }


        return [ $emails , $mail_company , $send_rel_type , $send_rel_id ];

    }

    public function get_merge_rel_data( $rel_type , $rel_id )
    {

        $rel_data = new stdClass();

        if ( $rel_type == 'customer' )
            $rel_data->client_id = $rel_id;
        elseif ( $rel_type == 'lead' )
            $rel_data->lead_id = $rel_id;
        elseif ( $rel_type == 'invoice' )
            $rel_data = $this->db->select('id, clientid, sale_agent')->from(db_prefix().'invoices')->where('id',$rel_id)->get()->row();
        elseif ( $rel_type == 'project' )
            $rel_data = $this->db->select('id, clientid')->where('id', $rel_id)->get(db_prefix() . 'projects')->row();
        elseif ( $rel_type == 'task' )
            $rel_data = $this->db->select('id, rel_id, rel_type')->where('id', $rel_id)->get(db_prefix() . 'tasks')->row();
        elseif ( $rel_type == 'proposal' )
            $rel_data = $this->db->select('id,rel_type,rel_id,assigned')->where('id', $rel_id)->get(db_prefix() . 'proposals')->row();
        elseif ( $rel_type == 'estimate' )
            $rel_data = $this->db->select('id,clientid,sale_agent')->where('id', $rel_id)->get(db_prefix() . 'estimates')->row();
        elseif ( $rel_type == 'estimate_request' )
            $rel_data = $this->db->select('id,assigned')->where('id', $rel_id)->get(db_prefix() . 'estimate_requests')->row();
        elseif ( $rel_type == 'contract' )
            $rel_data = $this->db->select('id,client')->where('id', $rel_id)->get(db_prefix() . 'contracts')->row();
        elseif ( $rel_type == 'staff' )
            $rel_data->staff_id = $rel_id;


        return $rel_data;

    }

    /**
     * Get all merge fields with data
     *
     * @return merge field array
     */
    public function get_merge_fields( $rel_type , $rel_data , $for_client = false )
    {

        if( empty( $rel_data ) )
            return [];


        /**
         * Rel type
         */
        if ( $rel_type == 'invoice' )
        {
            $invoice_id = $rel_data->id;
            $client_id  = $rel_data->clientid;
            $staff_id   = $rel_data->sale_agent;
        }
        elseif ( $rel_type == 'estimate' )
        {
            $estimate_id= $rel_data->id;
            $client_id  = $rel_data->clientid;
            $staff_id   = $rel_data->sale_agent;
        }
        elseif ( $rel_type == 'estimate_request' )
        {
            $estimate_request_id= $rel_data->id;
            $staff_id           = $rel_data->assigned;
        }
        elseif ( $rel_type == 'project' )
        {
            $project_id = $rel_data->id;
            $client_id  = $rel_data->clientid;
        }
        elseif ( $rel_type == 'task' )
        {
            $task_id    = $rel_data->id;


            /**
             * Task rel type checking
             */
            if ( !empty(  $rel_data->rel_id ) )
            {

                if ( $rel_data->rel_type == 'project' )
                    $project_id = $rel_data->rel_id;
                elseif ( $rel_data->rel_type == 'invoice' )
                    $invoice_id = $rel_data->rel_id;
                elseif ( $rel_data->rel_type == 'customer' )
                    $client_id = $rel_data->rel_id;
                elseif ( $rel_data->rel_type == 'estimate' )
                    $estimate_id = $rel_data->rel_id;
                elseif ( $rel_data->rel_type == 'contract' )
                    $contract_id = $rel_data->rel_id;
                elseif ( $rel_data->rel_type == 'lead' )
                    $lead_id = $rel_data->rel_id;
                elseif ( $rel_data->rel_type == 'proposal' )
                    $proposal_id = $rel_data->rel_id;

            }


        }
        elseif ( $rel_type == 'proposal' )
        {
            $proposal_id= $rel_data->id;

            if ( $rel_data->rel_type == 'customer' )
                $client_id  = $rel_data->rel_id;
            elseif ( $rel_data->rel_type == 'lead' )
                $lead_id  = $rel_data->rel_id;
        }
        elseif ( $rel_type == 'contract' )
        {
            $contract_id= $rel_data->id;
            $client_id  = $rel_data->client;
        }
        elseif ( $rel_type == 'customer' )
        {
            $client_id  = $rel_data->client_id;
        }
        elseif ( $rel_type == 'lead' )
        {
            $lead_id    = $rel_data->lead_id;
        }
        elseif ( $rel_type == 'staff' )
        {
            $staff_id    = $rel_data->staff_id;
        }

        $merge_fields = [];

        if( !empty( $invoice_id ) )
            $merge_fields = array_merge($merge_fields,  $this->invoice_merge_fields->format( $invoice_id ) );

        if( !empty( $staff_id ) )
            $merge_fields = array_merge($merge_fields,  $this->staff_merge_fields->format( $staff_id ) );

        if( !empty( $lead_id ) )
            $merge_fields = array_merge($merge_fields,  $this->leads_merge_fields->format( $lead_id ) );

        if( !empty( $client_id ) )
            $merge_fields = array_merge($merge_fields,  $this->client_merge_fields->format( $client_id ) );

        if( !empty( $project_id ) )
            $merge_fields = array_merge($merge_fields,  $this->projects_merge_fields->format( $project_id ) );

        if( !empty( $task_id ) )
            $merge_fields = array_merge($merge_fields,  $this->tasks_merge_fields->format( $task_id , $for_client ) );

        if( !empty( $proposal_id ) )
            $merge_fields = array_merge($merge_fields,  $this->proposals_merge_fields->format( $proposal_id ) );

        if( !empty( $estimate_id ) )
            $merge_fields = array_merge($merge_fields,  $this->estimate_merge_fields->format( $estimate_id ) );

        if( !empty( $estimate_request_id ) )
            $merge_fields = array_merge($merge_fields,  $this->estimate_request_merge_fields->format( $estimate_request_id ) );

        if( !empty( $contract_id ) )
            $merge_fields = array_merge($merge_fields,  $this->contract_merge_fields->format( $contract_id ) );

        $merge_fields = array_merge($merge_fields,      $this->other_merge_fields->format() );


        return $merge_fields;

    }

    /**
     * Version 1.1.1
     * @date 2024-02-18
     * @note The created triggers are triggered.
     */
    public function send_triggers()
    {




        $current_date = date('Y-m-d');

        $trigger_table = db_prefix().'email_template_manage_triggers';

        $triggers = $this->db->select('*')->from($trigger_table)->where('status',1)->get()->result();

        if ( !empty( $triggers ) )
        {

            $this->include_merge_libraries();

            $this->get_email_config();

            $this->load->config('email');


            foreach ( $triggers as $trigger )
            {


                if( (int)$trigger->sending_hour > (int)date('H') )
                    continue;


                $rel_type       = $trigger->rel_type;
                $staff_active   = $trigger->staff_active;
                $client_active  = $trigger->client_active;


                $record_status  = [];
                $record_priority= [];
                $record_date    = "";
                $record_day     = "";

                if ( !empty( $trigger->options ) )
                {

                    $options   = json_decode( $trigger->options );

                    if ( !empty( $options->status ) )
                        $record_status = (array)$options->status;

                    if ( !empty( $options->priority ) )
                        $record_priority = (array)$options->priority;

                    if ( !empty( $options->record_date ) )
                        $record_date = $options->record_date;

                    if ( isset( $options->record_day ) )
                        $record_day = $options->record_day;

                }

                if ( empty( $record_date ) )
                    continue;



                $query_date = $current_date;

                if( !empty( $record_day ) )
                    $query_date = date('Y-m-d' , strtotime($current_date.' '.$record_day.' days ') ) ;


                if ( $rel_type == 'invoice' )
                {

                    if ( !empty( $record_status ) )
                        $this->db->where_in('status',$record_status);

                    $this->db->where('cancel_overdue_reminders',0);

                    $this->db->where("DATE( $record_date ) = '$query_date'",null,false);

                    $invoices = $this->db->select('id,clientid,sale_agent')->get(db_prefix().'invoices')->result();

                    if ( !empty( $invoices ) )
                    {

                        foreach ( $invoices as $invoice )
                        {

                            // Mail sending to staff
                            if ( !empty( $staff_active ) && !empty( $invoice->sale_agent ) )
                            {

                                $this->trigger_send_mail( $trigger , $invoice , 'staff' , $invoice->sale_agent );

                            }

                            // Mail sending to client
                            if ( !empty( $client_active ) && !empty( $invoice->clientid ) )
                            {

                                $contacts = $this->get_contacts_for_notification( $invoice->clientid , [ 'invoice_emails' => 1 ] );

                                if ( !empty( $contacts ) )
                                {

                                    foreach ( $contacts  as $contact )
                                    {

                                        $this->trigger_send_mail( $trigger , $invoice , 'contact' , $contact->id );

                                    }

                                }

                            }


                        }

                    }


                } // end if invoice rel type

                elseif ( $rel_type == 'project' )
                {

                    if ( !empty( $record_status ) )
                        $this->db->where_in('status',$record_status);

                    $this->db->where("DATE( $record_date ) = '$query_date'",null,false);

                    $projects = $this->db->select('id,clientid')->get(db_prefix().'projects')->result();

                    if ( !empty( $projects ) )
                    {

                        foreach ( $projects as $project )
                        {

                            // Mail sending to staff
                            if( !empty( $staff_active ) )
                            {
                                $members = $this->db->select('staff_id')->where('project_id',$project->id)->get(db_prefix().'project_members')->result();

                                if ( !empty( $members ) )
                                {
                                    foreach ( $members as $member )
                                    {

                                        if( !empty( $member->staff_id ) )
                                            $this->trigger_send_mail( $trigger , $project , 'staff' , $member->staff_id );

                                    }

                                }

                            }

                            // Mail sending to client
                            if ( !empty( $client_active ) && !empty( $project->clientid ) )
                            {

                                $contacts = $this->get_contacts_for_notification( $project->clientid , [ 'project_emails' => 1 ] );

                                if ( !empty( $contacts ) )
                                {

                                    foreach ( $contacts  as $contact )
                                    {

                                        $this->trigger_send_mail( $trigger , $project , 'contact' , $contact->id );

                                    }

                                }

                            }

                        }

                    }

                } // end if project rel type

                elseif ( $rel_type == 'task' )
                {

                    if ( !empty( $record_status ) )
                        $this->db->where_in('status',$record_status);

                    if ( !empty( $record_priority ) )
                        $this->db->where_in('priority',$record_priority);

                    $this->db->where("DATE( $record_date ) = '$query_date'",null,false);

                    $tasks = $this->db->select('id, rel_id, rel_type')->get(db_prefix().'tasks')->result();

                    if ( !empty( $tasks ) )
                    {

                        foreach ( $tasks as $task )
                        {

                            // Mail sending to staff
                            if( !empty( $staff_active ) )
                            {

                                $members = $this->db->select('staffid')->where('taskid',$task->id)->get(db_prefix().'task_assigned')->result();

                                if ( !empty( $members ) )
                                {
                                    foreach ( $members as $member )
                                    {

                                        if( !empty( $member->staffid ) )
                                            $this->trigger_send_mail( $trigger , $task , 'staff' , $member->staffid );

                                    }

                                }

                            }

                        }

                    }

                } // end if task rel type

                elseif ( $rel_type == 'proposal' )
                {

                    if ( !empty( $record_status ) )
                        $this->db->where_in('status',$record_status);

                    $this->db->where("DATE( $record_date ) = '$query_date'",null,false);

                    $proposals = $this->db->select('id,rel_type,rel_id,assigned')->get(db_prefix().'proposals')->result();

                    if ( !empty( $proposals ) )
                    {

                        foreach ( $proposals as $proposal )
                        {

                            // Mail sending to staff
                            if ( !empty( $staff_active ) && !empty( $proposal->assigned ) )
                            {

                                $this->trigger_send_mail( $trigger , $proposal , 'staff' , $proposal->assigned );

                            }

                            // Mail sending to client
                            if ( !empty( $client_active ) && !empty( $proposal->rel_type ) && !empty( $proposal->rel_id ) )
                            {

                                if( $proposal->rel_type == 'customer' )
                                {

                                    $contacts = $this->get_contacts_for_notification( $proposal->rel_id , [ 'is_primary' => 1 ] );

                                    if ( !empty( $contacts ) )
                                    {

                                        foreach ( $contacts  as $contact )
                                        {

                                            $this->trigger_send_mail( $trigger , $proposal , 'contact' , $contact->id );

                                        }

                                    }

                                }
                                elseif( $proposal->rel_type == 'lead' )
                                {

                                    $this->trigger_send_mail( $trigger , $proposal , 'lead' , $proposal->rel_id );

                                }


                            }


                        }

                    }



                } // end if proposal rel type

                elseif ( $rel_type == 'estimate' )
                {

                    if ( !empty( $record_status ) )
                        $this->db->where_in('status',$record_status);

                    $this->db->where("DATE( $record_date ) = '$query_date'",null,false);

                    $estimates = $this->db->select('id,clientid,sale_agent')->get(db_prefix().'estimates')->result();

                    if ( !empty( $estimates ) )
                    {

                        foreach ( $estimates as $estimate )
                        {

                            // Mail sending to staff
                            if ( !empty( $staff_active ) && !empty( $estimate->sale_agent ) )
                            {

                                $this->trigger_send_mail( $trigger , $estimate , 'staff' , $estimate->sale_agent );

                            }

                            // Mail sending to client
                            if ( !empty( $client_active ) && !empty( $estimate->clientid ) )
                            {

                                $contacts = $this->get_contacts_for_notification( $estimate->clientid , [ 'estimate_emails' => 1 ] );

                                if ( !empty( $contacts ) )
                                {

                                    foreach ( $contacts  as $contact )
                                    {

                                        $this->trigger_send_mail( $trigger , $estimate , 'contact' , $contact->id );

                                    }

                                }

                            }


                        }

                    }


                } // end if estimate rel type

                elseif ( $rel_type == 'contract' )
                {

                    if ( !empty( $record_status ) )
                        $this->db->where_in('contract_type',$record_status);

                    $this->db->where("DATE( $record_date ) = '$query_date'",null,false);

                    $contracts = $this->db->select('id,client')->get(db_prefix().'contracts')->result();

                    if ( !empty( $contracts ) )
                    {

                        foreach ( $contracts as $contract )
                        {

                            // Mail sending to client
                            if ( !empty( $client_active ) && !empty( $contract->client ) )
                            {

                                $contacts = $this->get_contacts_for_notification( $contract->client , [ 'contract_emails' => 1 ] );

                                if ( !empty( $contacts ) )
                                {

                                    foreach ( $contacts  as $contact )
                                    {

                                        $this->trigger_send_mail( $trigger , $contract , 'contact' , $contact->id );

                                    }

                                }

                            }


                        }

                    }


                } // end if contract rel type

            }

        }


    }

    private function check_the_trigger_mail_already_send( $trigger_id , $trigger_rel_id , $send_rel_type , $send_rel_id )
    {

        $info = $this->db->select('id')
                        ->from(db_prefix().'email_template_manage_trigger_logs')
                        ->where('trigger_id',$trigger_id)
                        ->where('trigger_rel_id',$trigger_rel_id)
                        ->where('send_rel_type',$send_rel_type)
                        ->where('send_rel_id',$send_rel_id)
                        ->get()
                        ->row();

        if ( !empty( $info ) )
            return true; // already send
        else
            return false;

    }

    /**
     * Sending trigger mails
     */
    private function trigger_send_mail( $trigger , $rel_data = [] , $send_rel_type = '' , $send_rel_id = 0 )
    {

        $trigger_id     = $trigger->id;
        $template_id    = $trigger->template_id;
        $rel_type       = $trigger->rel_type;
        $rel_id         = $rel_data->id;

        if ( empty( $rel_data->id ) )
            return true;


        if ( $this->check_the_trigger_mail_already_send( $trigger_id , $rel_id , $send_rel_type , $send_rel_id ) )
            return true;

        /**
         * Template info
         */
        $template = $this->get_template( $template_id );

        if ( empty( $template ) )
            return true;


        $to_text = $to_email = "";

        $for_client = false;

        $insert_log = [
            'template_id'   => $template_id ,
            'trigger_id'    => $trigger_id ,
            'send_rel_type' => $rel_type ,
            'send_rel_id'   => $rel_id
        ];

        if ( $send_rel_type == 'contact' )
        {

            $client_info = $this->db->select('userid,email,firstname,lastname')->from(db_prefix().'contacts')->where('id',$send_rel_id)->get()->row();

            $to_email       = $client_info->email;
            $to_text        = "$client_info->firstname $client_info->lastname";

            $for_client = true;

            $insert_log['rel_type'] = 'customer';
            $insert_log['rel_id'] = $client_info->userid;

        }
        elseif ( $send_rel_type == 'staff' )
        {

            $staff_info = $this->db->select('email,firstname,lastname')->from(db_prefix().'staff')->where('staffid',$send_rel_id)->get()->row();

            $to_email = $staff_info->email;
            $to_text = "$staff_info->firstname $staff_info->lastname";

            $insert_log['rel_type'] = 'staff';
            $insert_log['rel_id']   = $send_rel_id;


        }
        elseif ( $send_rel_type == 'lead' )
        {

            $lead_info = $this->db->select('email,company,name')->from(db_prefix().'leads')->where('id',$send_rel_id)->get()->row();

            $to_email = $lead_info->email;
            $to_text = !empty( $lead_info->company ) ? $lead_info->company : $lead_info->name;

            $insert_log['rel_type'] = 'lead';
            $insert_log['rel_id']   = $send_rel_id;

        }


        if ( empty( $to_email ) )
        {

            log_activity( "Email template manage trigger : mail address not found [ rel type : $send_rel_type | rel id : $send_rel_id ] " );

            return true;

        }


        $insert_log['company_name'] = $to_text;
        $insert_log['company_email'] = $to_email;



        $content        = $template->template_content;

        $mail_subject   = $template->template_subject;

        $merge_fields   = $this->get_merge_fields( $rel_type , $rel_data , $for_client );


        foreach ( $merge_fields as $key => $val )
        {

            if( stripos($content, $key) !== false )
                $content = str_ireplace($key, $val, $content);
            else
                $content = str_ireplace($key, '', $content);


            if( stripos($mail_subject, $key) !== false )
                $mail_subject = str_ireplace($key, $val, $mail_subject);
            else
                $mail_subject = str_ireplace($key, '', $mail_subject);

        }



        $insert_log['mail_subject'] = $mail_subject;
        $insert_log['content']      = $content;


        $this->email->from( $this->email_template_from_mail , $this->email_template_from_name );


        $systemBCC  = get_option('bcc_emails');

        $mail_log_id = $this->set_mail_send_log( $insert_log );

        $mail_opened_content = '<img src="' . site_url('email_template_manage/check_email/track/' . $mail_log_id) . '" alt="" width="1" height="1" border="0" style="height:1px!important;width:1px!important;border-width:0!important;margin-top:0!important;margin-bottom:0!important;margin-right:0!important;margin-left:0!important;padding-top:0!important;padding-bottom:0!important;padding-right:0!important;padding-left:0!important">';;


        $this->email->subject($mail_subject);

        $this->email->message($content.$mail_opened_content);

        if ($systemBCC != '')
        {
            $this->email->bcc($systemBCC);
        }


        /**
         * Attachment files
         */
        $attachment = $this->get_attachments( $template_id );

        if ( !empty( $attachment ) )
        {

            $path = get_upload_path_by_type('email_template');

            foreach ( $attachment as $attach )
            {

                $attach_file_path = $path.$template_id.'/'.$attach['file_name'];

                $this->email->attach( $attach_file_path );

                $log_attach = [
                    'mail_id' => $mail_log_id ,
                    'file_name' => $attach['file_name'] ,
                    'file_type' => $attach['filetype'] ,
                    //'file_path' => $path.'trg_'.$trigger_id.'_'.$mail_log_id.'/'
                    'file_path' => $path.$template_id.'/'
                ];

                $this->mail_attachment_log( $log_attach );

            }

        }


        $this->email->to( $to_email );


        if( $this->email->send() )
        {

            log_activity( "Email template manage trigger : mail send successfully to $to_text [ mail : $to_email ] " );

            $update_log['status'] = 1;

        }
        else
        {

            $errorMessage = $this->email->print_debugger();

            log_activity( "Email template manage trigger : mail send successfully to $to_text [ mail : $to_email - error : $errorMessage ]" );

            $update_log['error_message'] = $errorMessage;

            $update_log['status'] = 2;

        }

        /**
         * Save trigger log
         */
        $this->db->insert(db_prefix().'email_template_manage_trigger_logs', [
            'trigger_id'    => $trigger_id,
            'trigger_rel_id'=> $rel_id ,
            'send_rel_type' => $send_rel_type ,
            'send_rel_id'   => $send_rel_id,
            'mail_id'       => $mail_log_id ,
            'date'          => date('Y-m-d H:i:s') ,
        ] );

        $this->db->where('id',$mail_log_id)->update(db_prefix().'email_template_manage_mail_logs',$update_log);

        return true ;

    }


    private function get_mail_from_company()
    {

        $company_name = "";

        if( is_staff_logged_in() )
            $company_name = get_staff_full_name( get_staff_user_id() ).' | '.get_option('companyname');

        if ( empty( $company_name ) )
            $company_name = get_option('companyname') ;

        return $company_name;

    }


    private function get_email_config()
    {

        $cnf = [

            'from_email' => get_option('smtp_email') ,

            'from_name'  => $this->get_mail_from_company() ,

        ];

        $cnf = hooks()->apply_filters('before_send_simple_email', $cnf);


        if ( !empty( $cnf['from_email'] ) )
            $this->email_template_from_mail = $cnf['from_email'] ;

        if ( !empty( $cnf['from_name'] ) )
            $this->email_template_from_name = $cnf['from_name'] ;

    }


    /**
     * @version 1.1.5
     */
    public function get_smtp_settings()
    {

        $smtp_settings = [];

        $records = $this->db->select('id, company_name, is_public, active_staff')->from(db_prefix().'email_template_smtp_settings')->where('status',1)->get()->result();

        if ( !empty( $records ) )
        {

            $staff_id = get_staff_user_id();

            foreach ( $records as $record )
            {

                $add_to_add = false;

                if ( is_admin() )
                {
                    $add_to_add = true;
                }
                else
                {

                    if ( $record->is_public == 1 )
                    {
                        $add_to_add = true;
                    }
                    elseif ( !empty( $record->active_staff ) )
                    {

                        $active_staff = json_decode( $record->active_staff , 1 );

                        if ( !empty( $active_staff ) && in_array( $staff_id , $active_staff ) )
                            $add_to_add = true;

                    }

                }


                if ( $add_to_add )
                {

                    $smtp_settings[] = $record;

                }

            }

        }


        return $smtp_settings;

    }

    public function get_smtp_detail( $record_id )
    {

        return $this->db->select('*')->from(db_prefix().'email_template_smtp_settings')->where('id',$record_id)->get()->row();

    }


    /**
     * smtp information
     */
    private function mail_smtp_initialize( $detail_id = 0 )
    {

        $detail = $this->get_smtp_detail( $detail_id );

        if ( empty( $detail ) )
            return null;


        $config['protocol']     = $detail->email_protocol;
        $config['smtp_host']    = $detail->smtp_host;
        $config['smtp_port']    = $detail->smtp_port;
        $config['smtp_user']    = $detail->smtp_username;
        $config['smtp_pass']    = $detail->smtp_password;

        $config['useragent']    = $detail->mail_engine;
        $config['smtp_crypto']  = $detail->smtp_encryption;

        $config['mailtype']     = 'html';
        $config['charset']      = $detail->smtp_email_charset;


        /**
         * @note Smtp from and company info setting
         */
        $this->email_template_from_mail = $detail->smtp_username ;

        $this->email_template_from_name = $detail->company_name ;

        return $config;

    }



    public function get_rel_types()
    {

        $related_types = [];

        $related_types[] = [ 'id' => 'customer' , 'value' => _l('client') ];
        $related_types[] = [ 'id' => 'lead'     , 'value' => _l('lead') ];
        $related_types[] = [ 'id' => 'invoice'  , 'value' => _l('invoice') ];
        $related_types[] = [ 'id' => 'project'  , 'value' => _l('project') ];
        $related_types[] = [ 'id' => 'task'     , 'value' => _l('task') ];
        $related_types[] = [ 'id' => 'proposal' , 'value' => _l('proposal') ];
        $related_types[] = [ 'id' => 'contract' , 'value' => _l('contract') ];
        $related_types[] = [ 'id' => 'estimate' , 'value' => _l('estimate') ];

        return $related_types;

    }

    public function get_rel_type_links( $rel_type = '' , $rel_id = '' )
    {

        $href = '';
        $onclick = '';

        switch ( $rel_type )
        {

            case 'client';
            case 'customer';
                $href = admin_url().'clients/client/'.$rel_id;
                break;

            case 'lead';
                $onclick = "init_lead($rel_id);";
                break;

            case 'task';
                $onclick = "init_task_modal($rel_id);";
                break;

            case 'invoice';
                $href = admin_url().'invoices/list_invoices/'.$rel_id;
                break;

            case 'project';
                $href = admin_url().'projects/view/'.$rel_id;
                break;

            case 'proposal';
                $href = admin_url().'proposals/list_proposals/'.$rel_id;
                break;

            case 'estimate';
                $href = admin_url().'estimates/list_estimates/'.$rel_id;
                break;

            case 'contract';
                $href = admin_url().'contracts/contract/'.$rel_id;
                break;


        }

        if ( !empty( $href ) )
            $rel_type_link = 'href="'.$href.'"';

        if ( !empty( $onclick ) )
            $rel_type_link = ' href="#" onclick="'.$onclick.' return false;"';

        return $rel_type_link;

    }


    /**
     * @Version 1.1.6 WebHooks
     */
    public function get_webhooks()
    {

        $webhooks = [];

        $webhooks[] = [
            'value' => 'project_status_changed' ,
            'text' => _l('email_template_manage_project_status_changed')
        ];


        $webhooks[] = [
            'value' => 'task_status_changed' ,
            'text' => _l('email_template_manage_task_status_changed')
        ];


        $webhooks[] = [
            'value' => 'after_add_task' ,
            'text' => _l('email_template_manage_task_created')
        ];


        $webhooks[] = [
            'value' => 'invoice_status_changed' ,
            'text' => _l('email_template_manage_invoice_status_changed')
        ];


        $webhooks[] = [
            'value' => 'lead_status_changed' ,
            'text' => _l('email_template_manage_lead_status_changed')
        ];


        $webhooks[] = [
            'value' => 'lead_created' ,
            'text' => _l('email_template_manage_lead_created')
        ];




        return $webhooks;

    }


    /**
     * @note webhook trigger mails sending
     *
     * @param $webhook_id
     * @param $rel_type
     * @param $rel_id
     */
    public function send_webhook( $webhook_id = 0 , $rel_type = '' , $rel_id = '' )
    {

        if ( empty( $webhook_id ) || empty( $rel_type ) || empty( $rel_id ) )
            return false;

        $webhook_table = db_prefix().'email_template_manage_webhooks';

        $webhook = $this->db->select('*')->from($webhook_table)->where('id',$webhook_id)->get()->row();

        if ( empty( $webhook ) )
            return false;

        if ( empty( $webhook->staff_active ) && empty( $webhook->client_active ) )
            return false;


        $this->include_merge_libraries();

        $this->get_email_config();

        $this->load->config('email');

        $webhook->rel_type = $rel_type;
        $staff_active   = $webhook->staff_active;
        $client_active  = $webhook->client_active;


        if ( $rel_type == 'project' )
        {

            $project = $this->db->select('id,clientid')->where('id',$rel_id)->get(db_prefix().'projects')->row();

            if ( !empty( $project ) )
            {

                // Mail sending to staff
                if( !empty( $staff_active ) )
                {
                    $members = $this->db->select('staff_id')->where('project_id',$project->id)->get(db_prefix().'project_members')->result();

                    if ( !empty( $members ) )
                    {
                        foreach ( $members as $member )
                        {

                            if( !empty( $member->staff_id ) )
                                $this->webhook_send_mail( $webhook , $project , 'staff' , $member->staff_id );

                        }

                    }

                }

                // Mail sending to client
                if ( !empty( $client_active ) && !empty( $project->clientid ) )
                {

                    $contacts = $this->get_contacts_for_notification( $project->clientid , [ 'project_emails' => 1 ] );

                    if ( !empty( $contacts ) )
                    {

                        foreach ( $contacts  as $contact )
                        {

                            $this->webhook_send_mail( $webhook , $project , 'contact' , $contact->id );

                        }

                    }

                }


            }

        } // end if project rel type

        elseif ( $rel_type == 'task' )
        {

            $task = $this->db->select('id, rel_id, rel_type')->where('id',$rel_id)->get(db_prefix().'tasks')->row();

            if ( !empty( $task ) )
            {

                // Mail sending to staff
                if( !empty( $staff_active ) )
                {

                    $members = $this->db->select('staffid')->where('taskid',$task->id)->get(db_prefix().'task_assigned')->result();

                    if ( !empty( $members ) )
                    {
                        foreach ( $members as $member )
                        {

                            if( !empty( $member->staffid ) )
                                $this->webhook_send_mail( $webhook , $task , 'staff' , $member->staffid );

                        }

                    }

                }

            }

        } // end if task rel type

        elseif ( $rel_type == 'invoice' )
        {


            $invoice = $this->db->select('id,clientid,sale_agent')->where('id',$rel_id)->get(db_prefix().'invoices')->row();

            if ( !empty( $invoice ) )
            {


                // Mail sending to staff
                if ( !empty( $staff_active ) && !empty( $invoice->sale_agent ) )
                {

                    $this->webhook_send_mail( $webhook , $invoice , 'staff' , $invoice->sale_agent );

                }

                // Mail sending to client
                if ( !empty( $client_active ) && !empty( $invoice->clientid ) )
                {

                    $contacts = $this->get_contacts_for_notification( $invoice->clientid , [ 'invoice_emails' => 1 ] );

                    if ( !empty( $contacts ) )
                    {

                        foreach ( $contacts  as $contact )
                        {

                            $this->webhook_send_mail( $webhook , $invoice , 'contact' , $contact->id );

                        }

                    }

                }


            }


        } // end if invoice rel type

        elseif ( $rel_type == 'lead' )
        {


            $lead = $this->db->select('assigned,id')->where('id',$rel_id)->get(db_prefix().'leads')->row();

            if ( !empty( $lead ) )
            {


                // Mail sending to staff
                if ( !empty( $staff_active ) && !empty( $lead->assigned ) )
                {

                    $this->webhook_send_mail( $webhook , $lead , 'staff' , $lead->assigned );

                }

                // Mail sending to client
                if ( !empty( $client_active ) )
                {

                    $this->webhook_send_mail( $webhook , $lead , 'lead' , $lead->id );

                }


            }


        } // end if invoice rel type



    }


    private function webhook_send_mail( $webhook , $rel_data = [] , $send_rel_type = '' , $send_rel_id = 0 )
    {

        $webhook_id     = $webhook->id;
        $webhook_name   = $webhook->webhook_name;
        $template_id    = $webhook->template_id;
        $rel_type       = !empty( $webhook->rel_type ) ? $webhook->rel_type : $webhook->webhook_trigger;
        $rel_id         = $rel_data->id;

        /**
         * Template info
         */
        $template = $this->get_template( $template_id );

        if ( empty( $template ) )
            return true;


        $to_text = $to_email = "";

        $for_client = false;

        $insert_log = [
            'template_id'   => $template_id ,
            'webhook_id'    => $webhook_id ,
            'send_rel_type' => $rel_type ,
            'send_rel_id'   => $rel_id
        ];

        if ( $send_rel_type == 'contact' )
        {

            $client_info = $this->db->select('userid,email,firstname,lastname')->from(db_prefix().'contacts')->where('id',$send_rel_id)->get()->row();

            $to_email       = $client_info->email;
            $to_text        = "$client_info->firstname $client_info->lastname";

            $for_client = true;

            $insert_log['rel_type'] = 'customer';
            $insert_log['rel_id'] = $client_info->userid;

        }
        elseif ( $send_rel_type == 'staff' )
        {

            $staff_info = $this->db->select('email,firstname,lastname')->from(db_prefix().'staff')->where('staffid',$send_rel_id)->get()->row();

            $to_email = $staff_info->email;
            $to_text = "$staff_info->firstname $staff_info->lastname";

            $insert_log['rel_type'] = 'staff';
            $insert_log['rel_id']   = $send_rel_id;


        }
        elseif ( $send_rel_type == 'lead' )
        {

            $lead_info = $this->db->select('email,company,name')->from(db_prefix().'leads')->where('id',$send_rel_id)->get()->row();

            $to_email = $lead_info->email;
            $to_text = !empty( $lead_info->company ) ? $lead_info->company : $lead_info->name;

            $insert_log['rel_type'] = 'lead';
            $insert_log['rel_id']   = $send_rel_id;

        }


        if ( empty( $to_email ) )
        {

            log_activity( "Email template manage trigger : mail address not found [ rel type : $send_rel_type | rel id : $send_rel_id | trigger name : $webhook_name ] " );

            return true;

        }


        $insert_log['company_name'] = $to_text;
        $insert_log['company_email'] = $to_email;



        $content        = $template->template_content;

        $mail_subject   = $template->template_subject;

        $merge_fields   = $this->get_merge_fields( $rel_type , $rel_data , $for_client );


        foreach ( $merge_fields as $key => $val )
        {

            if( stripos($content, $key) !== false )
                $content = str_ireplace($key, $val, $content);
            else
                $content = str_ireplace($key, '', $content);


            if( stripos($mail_subject, $key) !== false )
                $mail_subject = str_ireplace($key, $val, $mail_subject);
            else
                $mail_subject = str_ireplace($key, '', $mail_subject);

        }



        $insert_log['mail_subject'] = $mail_subject;
        $insert_log['content']      = $content;


        $this->email->from( $this->email_template_from_mail , $this->email_template_from_name );


        $systemBCC  = get_option('bcc_emails');

        $mail_log_id = $this->set_mail_send_log( $insert_log );

        $mail_opened_content = '<img src="' . site_url('email_template_manage/check_email/track/' . $mail_log_id) . '" alt="" width="1" height="1" border="0" style="height:1px!important;width:1px!important;border-width:0!important;margin-top:0!important;margin-bottom:0!important;margin-right:0!important;margin-left:0!important;padding-top:0!important;padding-bottom:0!important;padding-right:0!important;padding-left:0!important">';;


        $this->email->subject($mail_subject);

        $this->email->message($content.$mail_opened_content);

        if ($systemBCC != '')
        {
            $this->email->bcc($systemBCC);
        }


        /**
         * Attachment files
         */
        $attachment = $this->get_attachments( $template_id );

        if ( !empty( $attachment ) )
        {

            $path = get_upload_path_by_type('email_template');

            foreach ( $attachment as $attach )
            {

                $attach_file_path = $path.$template_id.'/'.$attach['file_name'];

                $this->email->attach( $attach_file_path );

                $log_attach = [
                    'mail_id' => $mail_log_id ,
                    'file_name' => $attach['file_name'] ,
                    'file_type' => $attach['filetype'] ,
                    'file_path' => $path.'trg_'.$trigger_id.'_'.$mail_log_id.'/'
                ];

                $this->mail_attachment_log( $log_attach );

            }

        }


        $this->email->to( $to_email );


        if( $this->email->send() )
        {

            log_activity( "Email template manage trigger name $webhook_name : mail send successfully to $to_text [ mail : $to_email ] " );

            $update_log['status'] = 1;

        }
        else
        {

            $errorMessage = $this->email->print_debugger();

            log_activity( "Email template manage trigger name $webhook_name : mail send successfully to $to_text [ mail : $to_email - error : $errorMessage ]" );

            $update_log['error_message'] = $errorMessage;

            $update_log['status'] = 2;

        }

        $this->db->where('id',$mail_log_id)->update(db_prefix().'email_template_manage_mail_logs',$update_log);

        return true ;

    }


    /**
     * @Version 1.2.2
     * Special
     */
    public function get_special_reltypes()
    {

        $rel_types = [];

        $rel_types[] = [
            'value' => 'staff' ,
            'text' => _l('staff')
        ];


        $rel_types[] = [
            'value' => 'contact' ,
            'text' => _l('client').' ( '._l('contact').' ) '
        ];

        return $rel_types;

    }

    public function get_special_staff_options()
    {

        $options = [];

        $options[] = [ 'field' => 'datecreated' , 'text' => _l('date_created') ];


        $custom_fields = get_custom_fields('staff' , [ 'type' => 'date_picker' ]);

        if ( !empty( $custom_fields ) )
        {

            foreach ($custom_fields as $field)
            {

                $options[] = [ 'field' => $field['id'] , 'text' => $field['name'] ];

            }

        }


        return $options;

    }

    public function get_special_contact_options()
    {

        $options = [];

        $options[] = [ 'field' => 'datecreated' , 'text' => _l('date_created') ];


        $custom_fields = get_custom_fields('contacts' , [ 'type' => 'date_picker' ]);

        if ( !empty( $custom_fields ) )
        {

            foreach ($custom_fields as $field)
            {

                $options[] = [ 'field' => $field['id'] , 'text' => $field['name'] ];

            }

        }


        return $options;

    }

    public function get_special_reply_data()
    {

        $options = [];

        $options[] = [ 'value' => '1-month' , 'text' => '1 '. _l('month') ];
        $options[] = [ 'value' => '2-month' , 'text' => '2 '. _l('month') ];
        $options[] = [ 'value' => '3-month' , 'text' => '3 '. _l('month') ];
        $options[] = [ 'value' => '4-month' , 'text' => '4 '. _l('month') ];
        $options[] = [ 'value' => '6-month' , 'text' => '6 '. _l('month') ];
        $options[] = [ 'value' => '1-year'  , 'text' => _l('year') ];


        return $options;

    }


    /**
     * Special mails sending
     */

    private function special_mail_date_sql_query( $field , $period = '' )
    {

        if ( empty( $period ) )
            return ' 1 = 2 ';

        $current_date = date('Y-m-d');


        if ( $period == '1-year' ) // yearly
        {

            $sql = " MONTH('$current_date') = MONTH($field) AND DAY('$current_date') = DAY($field);";

        }
        else // monthly
        {

            $month_period = (int)str_replace( '-month' , '' , $period );

            if ( empty( $month_period ) )
                return ' 1 = 2 ';


            $sql = "MOD( MONTH('$current_date') - MONTH($field) + 12 * (YEAR('$current_date') - YEAR($field) ) , $month_period ) = 0 
                    AND DAY('$current_date') = DAY($field)";

        }


        return $sql;

    }

    public function send_specials()
    {

        $current_date = date('Y-m-d');


        $special_table = db_prefix().'email_template_manage_special';

        $special_mails = $this->db->select('*')->from($special_table)->where('status',1)->get()->result();

        if ( !empty( $special_mails ) )
        {


            $this->load->library('merge_fields/client_merge_fields');

            $this->load->library('merge_fields/staff_merge_fields');

            $this->load->library('merge_fields/other_merge_fields');


            $this->get_email_config();

            $this->load->config('email');


            foreach ( $special_mails as $special )
            {


                if( (int)$special->sending_hour > (int)date('H') )
                    continue;


                $rel_type       = $special->rel_type;
                $custom_field   = $special->is_custom_field;
                $db_field       = $special->date_field_name;
                $repeat_period  = $special->repeat_every;


                if ( $rel_type == 'staff' )
                {

                    if ( $custom_field )
                    {

                        $date_sql = $this->special_mail_date_sql_query( 'value' , $repeat_period );

                        $records = $this->db->select('relid as staffid')
                                            ->from(db_prefix().'customfieldsvalues')
                                            ->where('fieldid',$db_field)
                                            ->where('fieldto','staff')
                                            ->where($date_sql,null,false)
                                            ->get()
                                            ->result();

                    }
                    else
                    {

                        $date_sql = $this->special_mail_date_sql_query( $db_field , $repeat_period );

                        $records = $this->db->select('staffid')
                                            ->from(db_prefix().'staff')
                                            ->where($date_sql,null,false)
                                            ->get()
                                            ->result();

                    }


                    if ( !empty( $records ) )
                    {

                        foreach ( $records as $record )
                        {

                            $this->special_send_mail( $special , $record->staffid );

                        }

                    }



                } // end if staff rel type

                elseif ( $rel_type == 'contact' )
                {


                    if ( $custom_field )
                    {

                        $records = $this->db->select('relid')
                                            ->from(db_prefix().'customfieldsvalues')
                                            ->where('fieldid',$db_field)
                                            ->where('fieldto','contacts')
                                            ->get()
                                            ->result();

                    }
                    else
                    {

                        $records = $this->db->select('relid')
                                            ->from(db_prefix().'customfieldsvalues')
                                            ->where('fieldid',$db_field)
                                            ->where('fieldto','contacts')
                                            ->get()
                                            ->result();

                    }




                    if ( !empty( $record_status ) )
                        $this->db->where_in('contract_type',$record_status);

                    $this->db->where("DATE( $record_date ) = '$query_date'",null,false);

                    $contracts = $this->db->select('id,client')->get(db_prefix().'contracts')->result();

                    if ( !empty( $contracts ) )
                    {

                        foreach ( $contracts as $contract )
                        {

                            // Mail sending to client
                            if ( !empty( $client_active ) && !empty( $contract->client ) )
                            {

                                $contacts = $this->get_contacts_for_notification( $contract->client , [ 'contract_emails' => 1 ] );

                                if ( !empty( $contacts ) )
                                {

                                    foreach ( $contacts  as $contact )
                                    {

                                        $this->trigger_send_mail( $trigger , $contract , 'contact' , $contact->id );

                                    }

                                }

                            }


                        }

                    }


                } // end if contact rel type

            }

        }


    }

    private function special_send_mail( $special , $rel_id )
    {

        $special_id     = $special->id;
        $template_id    = $special->template_id;
        $rel_type       = $special->rel_type;

        if ( empty( $rel_id ) )
            return true;

        $current_date = date('Y-m-d');




        $log_info = $this->db->select('id')
                            ->from(db_prefix().'email_template_manage_special_logs')
                            ->where('special_id',$special_id)
                            ->where('send_rel_type',$rel_type)
                            ->where('send_rel_id',$rel_id)
                            ->where('date',$current_date)
                            ->get()
                            ->row();

        if ( !empty( $log_info ) )
            return true;


        /**
         * Template info
         */
        $template = $this->get_template( $template_id );

        if ( empty( $template ) )
            return true;


        $to_text = $to_email = "";


        $insert_log = [
            'template_id'   => $template_id ,
            'special_id'    => $special_id ,
            'send_rel_type' => $rel_type ,
            'send_rel_id'   => $rel_id
        ];

        $client_id = 0;
        if ( $rel_type == 'contact' )
        {

            $client_info = $this->db->select('userid,email,firstname,lastname')
                                    ->from(db_prefix().'contacts')
                                    ->where('id',$rel_id)
                                    ->where('active',1)
                                    ->get()->row();

            if ( empty( $client_info ) )
                return true;

            $to_email       = $client_info->email;
            $to_text        = "$client_info->firstname $client_info->lastname";


            $insert_log['rel_type'] = 'customer';
            $insert_log['rel_id']   = $client_info->userid;
            $client_id              = $client_info->userid;

        }
        elseif ( $rel_type == 'staff' )
        {

            $staff_info = $this->db->select('email,firstname,lastname')
                                    ->from(db_prefix().'staff')
                                    ->where('staffid',$rel_id)
                                    ->where('active',1)
                                    ->get()
                                    ->row();

            if ( empty( $staff_info ) )
                return true;


            $to_email = $staff_info->email;
            $to_text = "$staff_info->firstname $staff_info->lastname";

            $insert_log['rel_type'] = 'staff';
            $insert_log['rel_id']   = $rel_id;


        }

        if ( empty( $to_email ) )
        {

            log_activity( "Email template manage special : mail address not found [ rel type : $rel_type | rel id : $rel_id ] " );

            return true;

        }


        $insert_log['company_name'] = $to_text;
        $insert_log['company_email'] = $to_email;



        $content        = $template->template_content;

        $mail_subject   = $template->template_subject;


        $merge_fields = [];

        if ( $rel_type == 'staff' )
            $merge_fields = array_merge($merge_fields, $this->staff_merge_fields->format( $rel_id ) );

        if ( $rel_type == 'contact' )
            $merge_fields = array_merge($merge_fields,  $this->client_merge_fields->format( $client_id , $rel_id ) );

        $merge_fields = array_merge($merge_fields,      $this->other_merge_fields->format() );



        foreach ( $merge_fields as $key => $val )
        {

            if( stripos($content, $key) !== false )
                $content = str_ireplace($key, $val, $content);
            else
                $content = str_ireplace($key, '', $content);


            if( stripos($mail_subject, $key) !== false )
                $mail_subject = str_ireplace($key, $val, $mail_subject);
            else
                $mail_subject = str_ireplace($key, '', $mail_subject);

        }



        $insert_log['mail_subject'] = $mail_subject;
        $insert_log['content']      = $content;


        $this->email->from( $this->email_template_from_mail , $this->email_template_from_name );


        $systemBCC  = get_option('bcc_emails');

        $mail_log_id = $this->set_mail_send_log( $insert_log );

        $mail_opened_content = '<img src="' . site_url('email_template_manage/check_email/track/' . $mail_log_id) . '" alt="" width="1" height="1" border="0" style="height:1px!important;width:1px!important;border-width:0!important;margin-top:0!important;margin-bottom:0!important;margin-right:0!important;margin-left:0!important;padding-top:0!important;padding-bottom:0!important;padding-right:0!important;padding-left:0!important">';;


        $this->email->subject($mail_subject);

        $this->email->message($content.$mail_opened_content);

        if ($systemBCC != '')
        {
            $this->email->bcc($systemBCC);
        }


        /**
         * Attachment files
         */
        $attachment = $this->get_attachments( $template_id );

        if ( !empty( $attachment ) )
        {

            $path = get_upload_path_by_type('email_template');

            foreach ( $attachment as $attach )
            {

                $attach_file_path = $path.$template_id.'/'.$attach['file_name'];

                $this->email->attach( $attach_file_path );

                $log_attach = [
                    'mail_id' => $mail_log_id ,
                    'file_name' => $attach['file_name'] ,
                    'file_type' => $attach['filetype'] ,
                    //'file_path' => $path.'trg_'.$trigger_id.'_'.$mail_log_id.'/'
                    'file_path' => $path.$template_id.'/'
                ];

                $this->mail_attachment_log( $log_attach );

            }

        }


        $this->email->to( $to_email );


        if( $this->email->send() )
        {

            log_activity( "Email template manage special : mail send successfully to $to_text [ mail : $to_email ] " );

            $update_log['status'] = 1;

        }
        else
        {

            $errorMessage = $this->email->print_debugger();

            log_activity( "Email template manage special : mail send successfully to $to_text [ mail : $to_email - error : $errorMessage ]" );

            $update_log['error_message'] = $errorMessage;

            $update_log['status'] = 2;

        }

        /**
         * Save trigger log
         */
        $this->db->insert(db_prefix().'email_template_manage_special_logs', [
            'special_id'    => $special_id,
            'send_rel_type' => $rel_type ,
            'send_rel_id'   => $rel_id,
            'date'          => $current_date ,
        ] );

        $this->db->where('id',$mail_log_id)->update(db_prefix().'email_template_manage_mail_logs',$update_log);

        return true ;

    }


    /**
     * Imap sections
     */
    public function get_imap_settings()
    {

        $smtp_settings = [];

        $records = $this->db->select('id, company_name, is_public, active_staff')->from(db_prefix().'email_template_imap_settings')->get()->result();

        if ( !empty( $records ) )
        {

            $staff_id = get_staff_user_id();

            foreach ( $records as $record )
            {

                $add_to_add = false;

                if ( is_admin() )
                {
                    $add_to_add = true;
                }
                else
                {

                    if ( $record->is_public == 1 )
                    {
                        $add_to_add = true;
                    }
                    elseif ( !empty( $record->active_staff ) )
                    {

                        $active_staff = json_decode( $record->active_staff , 1 );

                        if ( !empty( $active_staff ) && in_array( $staff_id , $active_staff ) )
                            $add_to_add = true;

                    }

                }


                if ( $add_to_add )
                {

                    $smtp_settings[] = $record;

                }

            }

        }


        return $smtp_settings;

    }

    public function get_imap_detail( $record_id )
    {

        return $this->db->select('*')->from(db_prefix().'email_template_imap_settings')->where('id',$record_id)->get()->row();

    }


    /**
     * @return void
     */
    public function get_email_detail( $email_id = 0 )
    {

        $this->db->where('id',$email_id)->update(db_prefix().'email_template_manage_inbox' , ['unread' => 0]);

        return $this->db->select('*')->from(db_prefix().'email_template_manage_inbox')->where('id',$email_id)->get()->row();

    }

    public function get_email_attachments( $email_id = 0 )
    {

        return $this->db->select('*')->from(db_prefix().'email_template_inbox_attachments')->where('inbox_id',$email_id)->get()->result_array();

    }

    public function get_email_response( $email_id = 0 )
    {

        return $this->db->select('*')
                        ->from(db_prefix().'email_template_manage_mail_logs')
                        ->where('send_rel_id',$email_id)
                        ->where("send_rel_type in ( 'forward' , 'reply' )",null,false)
                        ->order_by('id','DESC')
                        ->get()
                        ->result();

    }

    public function send_workflow_mail( $templateId, $strMailTo, $strMailCC, $rel_type, $rel_id  )
    {
        /*
        dig( $templateId );
        dig( $strMailTo ); 
        dig( $strMailCC );
        dig( $rel_type );
        dig(  $rel_id );
        die;
        */

        

        $template_id    = $templateId;
        $mail_to        = $strMailTo;
        $mail_cc        = $strMailCC;


        /**/
        //$templateId = 1;
        //$strMailTo = 'test@test.com';
        //$strMailCC = 'testCC@test.com';
        //$rel_type       =  'lead';
        //$rel_id         =  1;//overridingthis
        /**/

        $objTemplate    = $this->get_template( $template_id );
        $subject        = $objTemplate->template_subject;
        $content        = $objTemplate->template_content;

        $smtp_setting_id = null;


        list( $email , $mail_company , $send_rel_type , $send_rel_id ) = $this->send_mail_modal_info( $rel_type , $rel_id , $mail_to );

        $insert_log = [
            'template_id'   => $template_id ,
            'company_email' => $mail_to ,
            'company_cc'    => $mail_cc ,
            'mail_subject'  => $subject ,
            'content'       => $content ,
            'send_rel_type' => $rel_type ,
            'send_rel_id'   => $rel_id ,
            'company_name'  => $mail_company ,
            'rel_type'      => $send_rel_type ,
            'rel_id'        => $send_rel_id ,
            'smtp_setting_id' => $smtp_setting_id
        ];

        $mail_log_id = $this->set_mail_send_log( $insert_log );

        $mail_opened_content = '<img src="' . site_url('email_template_manage/check_email/track/' . $mail_log_id) . '" alt="" width="1" height="1" border="0" style="height:1px!important;width:1px!important;border-width:0!important;margin-top:0!important;margin-bottom:0!important;margin-right:0!important;margin-left:0!important;padding-top:0!important;padding-bottom:0!important;padding-right:0!important;padding-left:0!important">';;


        /**
         * Email template config information
         */
        $this->get_email_config();

        $this->load->config('email');


        /**
         * @Version 1.1.5
         */
        if ( !empty( $smtp_setting_id ) )
        {

            $initialize_data = $this->mail_smtp_initialize( $smtp_setting_id );

            if ( !empty( $initialize_data ) )
            {

                $this->email->initialize( $initialize_data );

            }

        }


        $this->email->from( $this->email_template_from_mail , $this->email_template_from_name );


        $systemBCC = get_option('bcc_emails');

        $this->email->subject( $subject );

        $this->email->message( $content.$mail_opened_content );

        if( $systemBCC != '' )
        {

            $this->email->bcc( $systemBCC );

        }

        $this->email->to( $mail_to );

        if( !empty( $mail_cc ) )
            $this->email->cc( $mail_cc );



        /**
         * Attachment files
         */
        $attachment = $this->get_attachments( $template_id );

        if ( !empty( $attachment ) )
        {

            $path = get_upload_path_by_type('email_template');

            foreach ( $attachment as $attach )
            {

                $attach_file_path = $path.$template_id.'/'.$attach['file_name'];

                $this->email->attach( $attach_file_path );

                $log_attach = [
                    'mail_id' => $mail_log_id ,
                    'file_name' => $attach['file_name'] ,
                    'file_type' => $attach['filetype'] ,
                    'file_path' => $path.$template_id.'/'
                ];

                $this->mail_attachment_log( $log_attach );

            }

        }


        $file_upload = $this->upload_attachment( $mail_log_id );


        if ( !empty( $file_upload ) )
        {

            $this->last_uploaded_files = $file_upload;

        }
        elseif ( !empty( $this->last_uploaded_files ) )
        {

            $file_upload = $this->last_uploaded_files;

        }


        if ( !empty( $file_upload ) )
        {

            foreach ( $file_upload as $f_upload )
            {

                $attach_file_path = $f_upload['file_path'].$f_upload['file_name'];

                $this->email->attach( $attach_file_path );

                $log_attach = [
                    'mail_id' => $mail_log_id ,
                    'file_name' => $f_upload['file_name'] ,
                    'file_type' => $f_upload['file_type'] ,
                    'file_path' => $f_upload['file_path']
                ];

                $this->mail_attachment_log( $log_attach );

            }

        }

        if( $this->email->send() )
        {

            $update_log['status'] = 1;

        }
        else
        {

            $errorMessage = $this->email->print_debugger();

            $update_log['error_message'] = $errorMessage;

            $update_log['status'] = 2;

        }


        $this->db->where('id',$mail_log_id)->update(db_prefix().'email_template_manage_mail_logs',$update_log);

        // send success
        if ( $update_log['status'] == 1 )
            return true;

        return false;

    }



}

