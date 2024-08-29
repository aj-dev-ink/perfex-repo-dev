<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Workflow_delayed_model extends App_Model
{
    /* Workflow Delayed Action */
    public static $enumDelayedTime = array('Minutes'=>1, 'Hours'=>2, 'Days'=>3, 'Weeks'=>4, 
    'Years'=>5 );
    public static $enumDelayedAction = array('Before'=>1, 'After'=>2);
    public static $enumDelayedType = array('Expected Closure On'=>1, 'Actual Closure Date'=>2, 
    'Converted At'=>3,'Created At'=>4, 'Updated At'=>5, 'Existing Lead Created On'=>6, 
    'Task Due On'=>7, 'Meeting Scheduled On'=>8,'Latest Activity On'=>9);
    public static $enumDelayedRepeat = array('Do not repeat'=>1, 'Daily'=>2,'Weekly'=>3, 
    'Monthly'=>4, 'Yearly'=>5);

}
