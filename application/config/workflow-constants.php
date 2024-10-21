<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    // Define a multi-dimensional array constant

    define('WF_ENTITY_TYPE', array('Lead'=>1, 'Task'=>2, 'Call log'=>3, 'Meeting'=>4) );

    define('WF_ACTION_TYPE', array('When a lead is created'=>1, 'When a lead is updated'=>2, 'Execute as an associated entity action'=>3, 'Marketplace app trigger'=>4) );

    define('WF_TRIGGER_TYPE', 
        array(
            'Edit Field'=>1,
            'Send Email'=>2,
            'Webhook'=>3,
            'Reassign'=>4,
            'Share'=>5,
            'Convert'=>6,
            'Create task'=>7,
            'Marketplace actions'=>8
        )
    );

    define('WF_ACTION_MAP', array( 
        WF_ENTITY_TYPE['Lead']=>[
            'When a lead is created' => 1,
            'When a lead is updated' => 2,
            'Execute as an associated entity action' => 3,
            'Marketplace app trigger' => 4,
        ],
        WF_ENTITY_TYPE['Task']=>[
            'When a task is created' => 1,
            'When a task is updated' => 2,
            'Marketplace app trigger' => 3,
        ],
        WF_ENTITY_TYPE['Call log']=>[
            'When a call log is created' => 1,
            'When a call log is updated' => 2,
            'Marketplace app trigger' => 3,
        ],
        WF_ENTITY_TYPE['Meeting']=>[
            'When a meeting is created' => 1,
            'When a meeting is updated' => 2,
            'Marketplace app trigger' => 3,
        ]
        )
    );

    define('WFC_OPERATOR_TYPE', 
        array(
            'Equal To'=>1,
            'Not Equal To'=>2,
            'Contains'=>3,
            'Not contains'=>4,
            'Is empty'=>5,
            'Is not empty'=>6,
            'Begins with'=>7,
            'Is set'=>8,
            'Is not set'=>9,
            'In'=>10,
            'Not in'=>11
        )
    );

    define('WFEF_EDIT_TYPE', 
        array(
            'select'=>0,
            //'Copy Field Value'=>1,
            'Enter Custom Value'=>2
        )
    );

    define('WF_FIELD_MAP', array( 
        WF_ENTITY_TYPE['Lead']=>[
            'Salutation' => 1,
            'Full name' => 2,
            'Owner' => 4,
            'Company' => 5,
            'Pipeline' => 6,
            'Pipeline stage' => 7,
            'Time zone' => 8,
            'City' => 9,
            'State' => 10,
            'Zip code' => 11,
            'Country' => 12,
            'Department' => 13,
            'Do not disturb' => 14,
            'Facebook' => 15,
            'Twitter' => 16,
            'LinkedIn' => 17,
            'Address' => 18,
            'Company address' => 19,
            'Company city' => 20,
            'Company state' => 21,
            'Company zip code' => 22,
            'Contact country' => 23,
            'Company employees' => 24,
            'Company website' => 25,
            'Company annual revenue' => 26,
            'Company industry' => 27,
            'Lead type' => 28,
            'Requirement' => 29,
            'Currency' => 30,
            'Budget' => 31,
            'Expected closure on' => 32,
            'Actual closure date' => 33,
            'Products or services' => 34,
            'Converted at' => 35,
            'Converted by' => 36,
            'Designation' => 37,
            'Campaign' => 38,
            'Lead source' => 39,
            'Imported by' => 40,
            'Created via id' => 41,
            'Created via name' => 42,
            'Created via type' => 43,
            'Updated via id' => 44,
            'Updated via name' => 45,
            'Updated via type' => 46,
            'Created by' => 47,
            'Created at' => 48,
            'Updated by' => 49,
            'Updated at' => 50,
            'Lead source keywords' => 51,
            'Lead source URL' => 52,
            'UTM campaign' => 53,
            'UTM medium' => 54,
            'UTM term' => 55,
            'Is MQL ?' => 56,
            'Existing lead created on' => 57,
            'Business unit' => 58,
            'Automation activate' => 59,
            'Company revenue size' => 60,
            'Score' => 61,
            'Task due on' => 62,
            'Meeting scheduled on' => 63,
            'Latest activity on' => 64,
            'Is new' => 65,
            'Forecasting type' => 66,
            'Lead Email'=>67
        ],
        WF_ENTITY_TYPE['Task']=>[
            'Task name' => 1,
            'Type' => 2,
            'Assigned to' => 3,
            'Due date' => 4,
            'Reminder' => 5,
            'Status' => 6,
            'Priority' => 7,
            'Relation' => 8,
            'Original due date' => 9,
            'Completed at' => 10,
            'Owner' => 11,
            'Created by' => 12,
            'Created at' => 13,
            'Updated by' => 14,
            'Updated at' => 15,
            'Created via id' => 16,
            'Created via name' => 17,
            'Created via type' => 18,
            'Updated via id' => 19,
            'Updated via name' => 20,
            'Updated via type' => 21,
            'Cancelled at' => 22,
            'Imported by' => 23
        ],
        WF_ENTITY_TYPE['Call log']=>[
            'Edit field' => 1,
            'Webhook' => 2,
            'Send email' => 3,
            'Marketplace actions' => 4,
            'Execute another workflow' => 5
        ],
        WF_ENTITY_TYPE['Meeting']=>[
            'Status' => 1,
            'Title' => 2,
            'All day' => 3
        ]
        )
    );

    //functions getCityOptions

    define('WF_FIELD_OPTION_MAP',
        array( 
            WF_ENTITY_TYPE['Lead'] => [
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Salutation']                => ['field_label' => 'Salutation', 'is_textbox'=>false, 'function_name' => '_getSalutationOptions', 'table_name'=>'customfieldsvalues', 'field_name'=>'leads_salutation'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Full name']                 => ['field_label' => 'Full name', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'leads', 'field_name'=>'name'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Owner']                     => ['field_label' => 'Owner', 'is_textbox'=>false, 'function_name' => '_getUserOptions', 'is_email_field'=>true, 'table_name'=>'leads', 'field_name'=>'assigned'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company']                   => ['field_label' => 'Company', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'leads', 'field_name'=>'company'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Pipeline']                  => ['field_label' => 'Pipeline', 'is_textbox'=>false, 'function_name' => '_getPipelineOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Pipeline stage']            => ['field_label' => 'Pipeline stage', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Time zone']                 => ['field_label' => 'Time zone', 'is_textbox'=>false, 'function_name' => '_getTimeZoneOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['City']                      => ['field_label' => 'City', 'is_textbox'=>true, 'function_name' => 'getCityOptions', 'table_name'=>'leads', 'field_name'=>'city'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['State']                     => ['field_label' => 'State', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'leads', 'field_name'=>'state'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Zip code']                  => ['field_label' => 'Zip code', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'leads', 'field_name'=>'zip'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Country']                   => ['field_label' => 'Country', 'is_textbox'=>false, 'function_name' => '_getCountryOptions', 'table_name'=>'leads', 'field_name'=>'country'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Department']                => ['field_label' => 'Department', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Do not disturb']            => ['field_label' => 'Do not disturb', 'is_textbox'=>false, 'function_name' => '_getYesNoOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Facebook']                  => ['field_label' => 'Facebook', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Twitter']                   => ['field_label' => 'Twitter', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['LinkedIn']                  => ['field_label' => 'LinkedIn', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Address']                   => ['field_label' => 'Address', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'leads', 'field_name'=>'address'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company address']           => ['field_label' => 'Company address', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'customfieldsvalues', 'field_name'=>'leads_company_address'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company city']              => ['field_label' => 'Company city', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'customfieldsvalues', 'field_name'=>'leads_company_city'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company state']             => ['field_label' => 'Company state', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'customfieldsvalues', 'field_name'=>'leads_state'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company zip code']          => ['field_label' => 'Company zip code', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'customfieldsvalues', 'field_name'=>'leads_company_zip_code'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Contact country']           => ['field_label' => 'Contact country', 'is_textbox'=>false, 'function_name' => '_getCountryOptions', 'table_name'=>'customfieldsvalues', 'field_name'=>'leads_company_country'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company employees']         => ['field_label' => 'Company employees', 'is_textbox'=>true, 'function_name' => '_getCompEmloyeeOptions', 'table_name'=>'customfieldsvalues', 'field_name'=>'leads_company_employees'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company website']           => ['field_label' => 'Company website', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'leads', 'field_name'=>'website'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company annual revenue']    => ['field_label' => 'Company annual revenue', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company industry']          => ['field_label' => 'Company industry', 'is_textbox'=>false, 'function_name' => '_getCompIndustryOptions', 'table_name'=>'customfieldsvalues', 'field_name'=>'leads_industry'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Lead type']                 => ['field_label' => 'Lead type', 'is_textbox'=>false, 'function_name' => '_getLeadTypeOptions', 'table_name'=>'customfieldsvalues', 'field_name'=>'leads_lead_type'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Requirement']               => ['field_label' => 'Requirement', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Currency']                  => ['field_label' => 'Currency', 'is_textbox'=>false, 'function_name' => '_getCurrencyOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Budget']                    => ['field_label' => 'Budget', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'customfieldsvalues', 'field_name'=>'leads_campaign_budget'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Expected closure on']       => ['field_label' => 'Expected closure on', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Actual closure date']       => ['field_label' => 'Actual closure date', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Products or services']      => ['field_label' => 'Products or services', 'is_textbox'=>false, 'function_name' => '_getProductServicesOptions', 'table_name'=>'customfieldsvalues', 'field_name'=>'leads_products_or_services'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Converted at']              => ['field_label' => 'Converted at', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Converted by']              => ['field_label' => 'Converted by', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Designation']               => ['field_label' => 'Designation', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Campaign']                  => ['field_label' => 'Campaign', 'is_textbox'=>false, 'function_name' => '_getCampaignOptions', 'table_name'=>'customfieldsvalues', 'field_name'=>'leads_campaign_name'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Lead source']               => ['field_label' => 'Lead source', 'is_textbox'=>false, 'function_name' => '_getLeadSourceOptions', 'table_name'=>'leads', 'field_name'=>'source'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Imported by']               => ['field_label' => 'Imported by', 'is_textbox'=>false, 'function_name' => '_getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Created via id']            => ['field_label' => 'Created via id', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Created via name']          => ['field_label' => 'Created via name', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Created via type']          => ['field_label' => 'Created via type', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Updated via id']            => ['field_label' => 'Updated via id', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Updated via name']          => ['field_label' => 'Updated via name', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Updated via type']          => ['field_label' => 'Updated via type', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Created by']                => ['field_label' => 'Created by', 'is_textbox'=>false, 'function_name' => '_getUserOptions', 'table_name'=>'leads', 'field_name'=>'addedfrom'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Created at']                => ['field_label' => 'Created at', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Updated by']                => ['field_label' => 'Updated by', 'is_textbox'=>false, 'function_name' => '_getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Updated at']                => ['field_label' => 'Updated at', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Lead source keywords']      => ['field_label' => 'Lead source keywords', 'is_textbox'=>true, 'function_name' => '_getOptionsDefault', 'table_name'=>'customfieldsvalues', 'field_name'=>'leads_lead_source_keywords'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Lead source URL']           => ['field_label' => 'Lead source URL', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'customfieldsvalues', 'field_name'=>'leads_lead_source_url'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['UTM campaign']              => ['field_label' => 'UTM campaign', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['UTM medium']                => ['field_label' => 'UTM medium', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['UTM term']                  => ['field_label' => 'UTM term', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Is MQL ?']                  => ['field_label' => 'Is MQL ?', 'is_textbox'=>false, 'function_name' => '_getYesNoOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Existing lead created on']  => ['field_label' => 'Existing lead created on', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Business unit']             => ['field_label' => 'Business unit', 'is_textbox'=>false, 'function_name' => '_getBuissnessUnit', 'table_name'=>'customfieldsvalues', 'field_name'=>'leads_business_unit'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Automation activate']       => ['field_label' => 'Automation activate', 'is_textbox'=>false, 'function_name' => '_getYesNoOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company revenue size']      => ['field_label' => 'Company revenue size', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'leads', 'field_name'=>'leads_company_revenue_size'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Score']                     => ['field_label' => 'Score', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Task due on']               => ['field_label' => 'Task due on', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Meeting scheduled on']      => ['field_label' => 'Meeting scheduled on', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Latest activity on']        => ['field_label' => 'Latest activity on', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Is new']                    => ['field_label' => 'Is new', 'is_textbox'=>false, 'function_name' => '_getYesNoOptions', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Forecasting type']          => ['field_label' => 'Forecasting type', 'is_textbox'=>false, 'function_name' => '_getForastingType', 'table_name'=>'', 'field_name'=>''],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Lead Email']                => ['field_label' => 'Lead Email', 'is_textbox'=>true, 'function_name' => 'getUserOptions', 'is_email_field'=>true, 'table_name'=>'leads', 'field_name'=>'email'],
            ],
            WF_ENTITY_TYPE['Task'] => [
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Task name']                => ['field_label' => 'Task name', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Type']                     => ['field_label' => 'Type', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Assigned to']              => ['field_label' => 'Assigned to', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Due date']                 => ['field_label' => 'Due date', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Reminder']                 => ['field_label' => 'Reminder', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Status']                   => ['field_label' => 'Status', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Priority']                 => ['field_label' => 'Priority', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Relation']                 => ['field_label' => 'Relation', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Original due date']        => ['field_label' => 'Original due date', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Completed at']             => ['field_label' => 'Completed at', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Owner']                    => ['field_label' => 'Owner', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Created by']               => ['field_label' => 'Created by', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Created at']               => ['field_label' => 'Created at', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Updated by']               => ['field_label' => 'Updated by', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Updated at']               => ['field_label' => 'Updated at', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Created via id']           => ['field_label' => 'Created via id', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Created via name']         => ['field_label' => 'Created via name', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Created via type']         => ['field_label' => 'Created via type', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Updated via id']           => ['field_label' => 'Updated via id', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Updated via name']         => ['field_label' => 'Updated via name', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Updated via type']         => ['field_label' => 'Updated via type', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Cancelled at']             => ['field_label' => 'Cancelled at', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Imported by']              => ['field_label' => 'Imported by', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
            ],
            WF_ENTITY_TYPE['Call log'] => [
                WF_FIELD_MAP[WF_ENTITY_TYPE['Call log']]['Edit field']                  => ['field_label' => 'Edit field', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Call log']]['Webhook']                     => ['field_label' => 'Webhook', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Call log']]['Send email']                  => ['field_label' => 'Send email', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Call log']]['Marketplace actions']         => ['field_label' => 'Marketplace actions', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Call log']]['Execute another workflow']    => ['field_label' => 'Execute another workflow', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
            ],
            WF_ENTITY_TYPE['Meeting'] => [
                WF_FIELD_MAP[WF_ENTITY_TYPE['Meeting']]['Status']                       => ['field_label' => 'Status', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Meeting']]['Title']                        => ['field_label' => 'Title', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Meeting']]['All day']                      => ['field_label' => 'All day', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
            ]

        )
    );
    
    define('WFD_DURATION_TYPE', 
        array(
            'Minutes'=>1,
            'Hours'=>2,
            'Days'=>3,
            'Weeks'=>4,
            'Years'=>5
        )
    );

    define('WFD_IS_BEFORE', 
        array(
            'Before'=>0,
            'After'=>1
        )
    );

    define('WFD_PREF_PROPERTY', 
        array(
            //'Expected Closure On'=>1,
            //'Actual Closure Date'=>2,
            //'Converted At'=>3,
            'Created At'=>4,
            //'Updated At'=>5,
            //'Existing Lead Created On'=>6,
            //'Task Due On'=>7,
            //'Meeting Scheduled On'=>8,
            //'Latest Activity On'=>9
        )
    );

    define('WFD_REPEAT_TYPE', 
        array(
            'Do not repeat'=>1,
            'Daily'=>2,
            'Weekly'=>3,
            'Monthly'=>4,
            'Yearly'=>5
        )
    );

    define('WFD_REASSIGN', 
        array(
            'Select User'=>1,
        )
    );

    define('WFD_EMAIL_TEMPLATE', 
        array(
            'Custom Email Template'=>1,
            'Task Created Email Template'=>2,
        )
    );

    define('WFW_REQUEST_TYPE', 
        array(
            'GET'=>1,
            'POST'=>2,
            'PUT'=>3,
        )
    );

    define('WFW_AUTH_TYPE', 
        array(
            'No Authorization Required'=>1,
            'API Key'=>2,
            'Bearer Token'=>3,
            'Basic Authantication'=>4
        )
    );
   
?>
