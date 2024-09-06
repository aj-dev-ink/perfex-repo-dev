<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    // Define a multi-dimensional array constant

    define('WF_ENTITY_TYPE', array('Lead'=>1, 'Task'=>2, 'Call log'=>3, 'Meeting'=>4) );

    define('WF_ACTION_TYPE', array('When a lead is created'=>1, 'When a lead is updated.'=>2, 'Execute as an associated entity action'=>3, 'Marketplace app trigger'=>4) );

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

    define('WF_FIELD_MAP', array( 
        WF_ENTITY_TYPE['Lead']=>[
            'Salutation' => 1,
            'First name' => 2,
            'Last name' => 3,
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
            'Company country' => 23,
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
            'Forecasting type' => 66
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
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Salutation']                => ['field_name' => 'Salutation', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['First name']                => ['field_name' => 'First name', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Last name']                 => ['field_name' => 'Last name', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Owner']                     => ['field_name' => 'Owner', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company']                   => ['field_name' => 'Company', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Pipeline']                  => ['field_name' => 'Pipeline', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Pipeline stage']            => ['field_name' => 'Pipeline stage', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Time zone']                 => ['field_name' => 'Time zone', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['City']                      => ['field_name' => 'City', 'is_textbox'=>false, 'function_name' => '_getCityOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['State']                     => ['field_name' => 'State', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Zip code']                  => ['field_name' => 'Zip code', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Country']                   => ['field_name' => 'Country', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Department']                => ['field_name' => 'Department', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Do not disturb']            => ['field_name' => 'Do not disturb', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Facebook']                  => ['field_name' => 'Facebook', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Twitter']                   => ['field_name' => 'Twitter', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['LinkedIn']                  => ['field_name' => 'LinkedIn', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Address']                   => ['field_name' => 'Address', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company address']           => ['field_name' => 'Company address', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company city']              => ['field_name' => 'Company city', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company state']             => ['field_name' => 'Company state', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company zip code']          => ['field_name' => 'Company zip code', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company country']           => ['field_name' => 'Company country', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company employees']         => ['field_name' => 'Company employees', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company website']           => ['field_name' => 'Company website', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company annual revenue']    => ['field_name' => 'Company annual revenue', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company industry']          => ['field_name' => 'Company industry', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Lead type']                 => ['field_name' => 'Lead type', 'is_textbox'=>false, 'function_name' => '_getLeadTypeOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Requirement']               => ['field_name' => 'Requirement', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Currency']                  => ['field_name' => 'Currency', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Budget']                    => ['field_name' => 'Budget', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Expected closure on']       => ['field_name' => 'Expected closure on', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Actual closure date']       => ['field_name' => 'Actual closure date', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Products or services']      => ['field_name' => 'Products or services', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Converted at']              => ['field_name' => 'Converted at', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Converted by']              => ['field_name' => 'Converted by', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Designation']               => ['field_name' => 'Designation', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Campaign']                  => ['field_name' => 'Campaign', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Lead source']               => ['field_name' => 'Lead source', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Imported by']               => ['field_name' => 'Imported by', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Created via id']            => ['field_name' => 'Created via id', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Created via name']          => ['field_name' => 'Created via name', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Created via type']          => ['field_name' => 'Created via type', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Updated via id']            => ['field_name' => 'Updated via id', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Updated via name']          => ['field_name' => 'Updated via name', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Updated via type']          => ['field_name' => 'Updated via type', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Created by']                => ['field_name' => 'Created by', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Created at']                => ['field_name' => 'Created at', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Updated by']                => ['field_name' => 'Updated by', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Updated at']                => ['field_name' => 'Updated at', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Lead source keywords']      => ['field_name' => 'Lead source keywords', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Lead source URL']           => ['field_name' => 'Lead source URL', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['UTM campaign']              => ['field_name' => 'UTM campaign', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['UTM medium']                => ['field_name' => 'UTM medium', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['UTM term']                  => ['field_name' => 'UTM term', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Is MQL ?']                  => ['field_name' => 'Is MQL ?', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Existing lead created on']  => ['field_name' => 'Existing lead created on', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Business unit']             => ['field_name' => 'Business unit', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Automation activate']       => ['field_name' => 'Automation activate', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Company revenue size']      => ['field_name' => 'Company revenue size', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Score']                     => ['field_name' => 'Score', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Task due on']               => ['field_name' => 'Task due on', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Meeting scheduled on']      => ['field_name' => 'Meeting scheduled on', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Latest activity on']        => ['field_name' => 'Latest activity on', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Is new']                    => ['field_name' => 'Is new', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Lead']]['Forecasting type']          => ['field_name' => 'Forecasting type', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
            ],
            WF_ENTITY_TYPE['Task'] => [
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Task name']                => ['field_name' => 'Task name', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Type']                     => ['field_name' => 'Type', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Assigned to']              => ['field_name' => 'Assigned to', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Due date']                 => ['field_name' => 'Due date', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Reminder']                 => ['field_name' => 'Reminder', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Status']                   => ['field_name' => 'Status', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Priority']                 => ['field_name' => 'Priority', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Relation']                 => ['field_name' => 'Relation', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Original due date']        => ['field_name' => 'Original due date', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Completed at']             => ['field_name' => 'Completed at', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Owner']                    => ['field_name' => 'Owner', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Created by']               => ['field_name' => 'Created by', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Created at']               => ['field_name' => 'Created at', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Updated by']               => ['field_name' => 'Updated by', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Updated at']               => ['field_name' => 'Updated at', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Created via id']           => ['field_name' => 'Created via id', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Created via name']         => ['field_name' => 'Created via name', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Created via type']         => ['field_name' => 'Created via type', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Updated via id']           => ['field_name' => 'Updated via id', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Updated via name']         => ['field_name' => 'Updated via name', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Updated via type']         => ['field_name' => 'Updated via type', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Cancelled at']             => ['field_name' => 'Cancelled at', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Task']]['Imported by']              => ['field_name' => 'Imported by', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
            ],
            WF_ENTITY_TYPE['Call log'] => [
                WF_FIELD_MAP[WF_ENTITY_TYPE['Call log']]['Edit field']                  => ['field_name' => 'Edit field', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Call log']]['Webhook']                     => ['field_name' => 'Webhook', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Call log']]['Send email']                  => ['field_name' => 'Send email', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Call log']]['Marketplace actions']         => ['field_name' => 'Marketplace actions', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Call log']]['Execute another workflow']    => ['field_name' => 'Execute another workflow', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
            ],
            WF_ENTITY_TYPE['Meeting'] => [
                WF_FIELD_MAP[WF_ENTITY_TYPE['Meeting']]['Status']                       => ['field_name' => 'Status', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Meeting']]['Title']                        => ['field_name' => 'Title', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
                WF_FIELD_MAP[WF_ENTITY_TYPE['Meeting']]['All day']                      => ['field_name' => 'All day', 'is_textbox'=>true, 'function_name' => 'getUserOptions'],
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
            'Expected Closure On'=>1,
            'Actual Closure Date'=>2,
            'Converted At'=>3,
            'Created At'=>4,
            'Updated At'=>5,
            'Existing Lead Created On'=>6,
            'Task Due On'=>7,
            'Meeting Scheduled On'=>8,
            'Latest Activity On'=>9
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
