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


    
?>
