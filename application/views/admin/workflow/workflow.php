<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="tw-mt-0 tw-text-lg tw-font-semibold tw-text-neutral-700">
                        Add Workflow
                        </h4>
                    </div>
                </div>

                <!--Workflow form-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel_s">
                            <div class="panel-body">
                                <!-- <form action="<?php echo site_url('admin/workflow/create'); ?>" id="workflow-form" method="post" accept-charset="utf-8" novalidate="novalidate"> -->
                                <?php echo form_open('admin/workflow/create', ['class' => '', 'id' => 'workflow-form']); ?>
                                <div class="form-group f_client_id">
                                    <?php $value = (isset($workflow) ? $workflow->name : ''); ?>
                                    <?php echo render_input('name', 'Name', $value); ?>
                                </div>
                                <div class="form-group" app-field-wrapper="description">
                                    <?php $value = (isset($workflow) ? $workflow->description : ''); ?>
                                    <?php echo render_textarea('description', 'Description', $value); ?>
                                </div>  
                                
                                <div class="formSection-sep-top formSection-sep-bottom">
                                    <div class="formSection-inner pt-15">
                                        <div class="form-group" app-field-wrapper="Select Entity">
                                            <span class="blue-circle mr-2">1</span>
                                            <label for="description" class="control-label">Select Entity 
                                                <i class="fa-regular fa-circle-question pull-right tw-mt-0.5 tw-ml-1"
                                                    data-toggle="tooltip"
                                                    data-title="<?php echo _l('customer_currency_change_notice'); ?>">
                                                </i>
                                            </label>
                                            <div class="form-group" app-field-wrapper="entity_type_id">
                                                <div class="radio-pull-left">
                                                    <?php foreach( $entityTypes as $label=>$value ) { ?>
                                                        <div class="custom-radio-button">
                                                            <input type="radio" name="entity_type_id" id="entity_type_id" value="<?php echo $value; ?>" <?php if( isset( $workflow ) && $value == $workflow->entity_type_id ) echo 'selected';?> >
                                                            <label for="entity_type_id" class="btn btn-default"><i class="fa-regular fa-thumbs-up pull-left radioThumb"></i> <?php echo _l($label); ?> </label>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="formSection-sep-bottom">
                                    <div class="formSection-inner pt-15">
                                        <div class="row">
                                            <div class="col-sm-12 col-12">
                                                <div app-field-wrapper="Select action types">
                                                    <span class="blue-circle mr-2">2</span>
                                                    <label for="description" class="control-label">Set action type and trigger preferences 
                                                        <i class="fa-regular fa-circle-question pull-right tw-mt-0.5 tw-ml-1"
                                                            data-toggle="tooltip"
                                                            data-title="<?php echo _l('customer_currency_change_notice'); ?>">
                                                        </i>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-5 col-12">
                                                <div class="" app-field-wrapper="Select action types">
                                                    <div class="section2 form-group">
                                                        <select class="form-control" id="actionTypeSelect" name="action_type_id" style="margin-bottom:10px;">
                                                            <option value="-">Select</option>
                                                            <!-- <?php foreach( $actionTypes as $label=>$value ) { ?>
                                                                <option value="<?php echo $value;?>"
                                                                        <?php if( isset( $workflow ) && $value == $workflow->action_type_id ) echo 'selected';?>
                                                                    >
                                                                    <?php echo $label; ?>
                                                                </option>
                                                            <?php } ?> -->
                                                        </select>   
                                                        <div class="radio-inline pb-5">
                                                            <input class="relative" type="radio" name="is_trigger_now" id="immediate_trigger" value="1" 
                                                                <?php if( isset( $workflow ) && $workflow->is_trigger_now ) echo 'selected';?>
                                                            >
                                                            <label for="immediate_trigger"> <?php echo _l('Immediate'); ?> </label>
                                                        </div>
                                                        <div class="radio-inline pb-5">
                                                            <input class="relative" type="radio" name="is_trigger_now" id="dealyed_trigger" value="0" 
                                                                    <?php if( isset( $workflow ) && !$workflow->is_trigger_now ) echo 'selected';?>
                                                            >
                                                            <label for="dealyed_trigger"> <?php echo _l('Delayed Action'); ?> </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Delayed Action Condition -->
                                            <div class="section2" id="sectionToggleToDelayed">
                                                    <div class="col-sm-12 col-12">
                                                        <div id="sectionContainerDelayed" class="form-group">
                                                        <div class="row graySection">
                                                            <div class="col-md-2">
                                                                <?php echo render_input('pref_count', 'Time Preference', ''); ?>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="name" class="control-label">&nbsp;</label>
                                                                <select class="form-control" id="stageSelect" name="pref_duration">
                                                                    <option value="-">Select</option>
                                                                    <?php foreach( $durationTypes as $label=>$value ) { ?>
                                                                        <option value="<?php echo $value;?>"
                                                                                <?php if( isset( $workflowDelayed ) && $value == $workflowDelayed->pref_duration ) echo 'selected';?>
                                                                            >
                                                                            <?php echo $label; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="name" class="control-label">&nbsp;</label>
                                                                <select class="form-control" id="valueSelect" name="is_before">
                                                                    <option value="-">Select</option>
                                                                    <?php foreach( $isBeforeAfter as $label=>$value ) { ?>
                                                                        <option value="<?php echo $value;?>"
                                                                                <?php if( isset( $workflowDelayed ) && $value == $workflowDelayed->is_before ) echo 'selected';?>
                                                                            >
                                                                            <?php echo $label; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="name" class="control-label">Date Properties</label>
                                                                <select class="form-control" id="operatorSelect" name="delay_date_type">
                                                                    <option value="-">Select</option>
                                                                    <?php foreach( $prefPropertyTypes as $label=>$value ) { ?>
                                                                        <option value="<?php echo $value;?>"
                                                                                <?php if( isset( $workflowDelayed ) && $value == $workflowDelayed->delay_date_type ) echo 'selected';?>
                                                                            >
                                                                            <?php echo $label; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="name" class="control-label">Repeat</label>
                                                                <select class="form-control" id="delayedActionRepeat" name="repeat_type">
                                                                    <option value="-">Select</option>
                                                                    <?php foreach( $repeatTypes as $label=>$value ) { ?>
                                                                        <option value="<?php echo $value;?>"
                                                                                <?php if( isset( $workflowDelayed ) && $value == $workflowDelayed->repeat_type ) echo 'selected';?>
                                                                            >
                                                                            <?php echo $label; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                            <div id="doRepeat" style="display:none;">
                                                                <div class="col-md-12">
                                                                    <div><label for="name" class="control-label">Recurrence</label></div>
                                                                    <div class="radio-inline pb-5">
                                                                        <input class="relative" type="radio" name="is_recurance" id="is_frequent" value="1" 
                                                                            <?php if( isset( $workflow ) && $workflow->is_recurance ) echo 'selected';?>
                                                                        >
                                                                        <label for="is_frequent"> <?php echo _l('Frequency'); ?> </label>
                                                                    </div>
                                                                    <div class="radio-inline pb-5">
                                                                        <input class="relative" type="radio" name="is_recurance" id="is_until_date" value="0" 
                                                                                <?php if( isset( $workflow ) && !$workflow->is_recurance ) echo 'selected';?>
                                                                        >
                                                                        <label for="is_until_date"> <?php echo _l('Until Date'); ?> </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3" id="recurranceCount" style="display:none; margin-top:5px;">
                                                                    <?php echo render_input('frequency', 'Count', ''); ?>
                                                                </div>
                                                                <div class="col-md-3" id="recurranceDate" style="display:none; margin-top:5px;">
                                                                    <?php echo render_date_input('until_date', 'Choose Date', '');?>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                    </div>  
                                                </div>    
                                            </div>  
                                            <!-- End -->

                                        </div>
                                    </div>
                                </div>
                                

                                <div class="formSection-sep-bottom">
                                    <div class="formSection-inner pt-15">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group" app-field-wrapper="Set Conditions">
                                                    <span class="blue-circle mr-2">3</span>
                                                    <label for="description" class="control-label">Set Conditions
                                                        <i class="fa-regular fa-circle-question pull-right tw-mt-0.5 tw-ml-1"
                                                            data-toggle="tooltip"
                                                            data-title="<?php echo _l('customer_currency_change_notice'); ?>">
                                                        </i>
                                                    </label>
                                                    <div class="section2 pb-5">
                                                        <div class="radio-inline">
                                                            <input class="relative" type="radio" name="is_condition_based" id="all_deals" value="0" 
                                                                <?php if( isset( $workflow ) && !$workflow->is_condition_based ) echo 'selected';?>
                                                            >
                                                            <label for="all_deals"> <?php echo _l('All Entities'); ?> </label>
                                                        </div>
                                                        <div class="radio-inline">
                                                            <input class="relative" type="radio" name="is_condition_based" id="is_condition_based" value="1" 
                                                                    <?php if( isset( $workflow ) && $workflow->is_condition_based ) echo 'selected';?>
                                                            >
                                                            <label for="is_condition_based"> <?php echo _l('Based on conditions'); ?> </label>
                                                        </div>
                                                    </div>

                                                    <div class="section2" id="sectionToToggle">
                                                        <div id="sectionContainer">
                                                            <div class="row graySection" id="incrementalSection">
                                                                <div class="col-md-3">
                                                                    <select class="form-control" id="conditionSelect" name="condition_type_id[]">
                                                                        <option value="-">Select</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-2">
                                                                    <select class="form-control" id="stageSelect" name="stage_type_id[]">
                                                                        <option value="-">Select</option>
                                                                        <?php foreach( $stageTypes as $label=>$value ) { ?>
                                                                            <option value="<?php echo $value;?>"
                                                                                    <?php if( isset( $workflowCondition ) && $value == $workflowCondition->stage_type_id ) echo 'selected';?>
                                                                                >
                                                                                <?php echo $label; ?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-2">
                                                                    <select class="form-control" id="valueSelect" name="value_type_id[]">
                                                                        <option value="-">Select</option>
                                                                        <?php foreach( $valueTypes as $label=>$value ) { ?>
                                                                            <option value="<?php echo $value;?>"
                                                                                    <?php if( isset( $workflowCondition ) && $value == $workflowCondition->value_type_id ) echo 'selected';?>
                                                                                >
                                                                                <?php echo $label; ?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-2">
                                                                    <select class="form-control" id="operatorSelect" name="operator_type_id[]">
                                                                        <option value="-">Select</option>
                                                                        <?php foreach( $operatorTypes as $label=>$value ) { ?>
                                                                            <option value="<?php echo $value;?>"
                                                                                    <?php if( isset( $workflowCondition ) && $value == $workflowCondition->operator_type_id ) echo 'selected';?>
                                                                                >
                                                                                <?php echo $label; ?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                
                                                                <div class="col-md-2">
                                                                    <select class="form-control" id="compareValueSelect" name="compare_value_type_id[]">
                                                                        <option value="-">Select</option>
                                                                        <?php foreach( $enumTimePreference2 as $label=>$value ) { ?>
                                                                            <option value="<?php echo $value;?>"
                                                                                    <?php if( isset( $workflowDelayed ) && $value == $workflowDelayed->compare_value_type_id ) echo 'selected';?>
                                                                                >
                                                                                <?php echo $label; ?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-1 text-right incrementalBtn">
                                                                    <a class="add-section !tw-px-0 tw-group !tw-text-white" data-toggle="dropdown">
                                                                        <span class="tw-rounded-full tw-bg-primary-600 tw-text-white tw-inline-flex tw-items-center tw-justify-center tw-h-7 tw-w-7 -tw-mt-1 group-hover:!tw-bg-primary-700">
                                                                            <i class="fa-regular fa-plus fa-lg"></i>
                                                                        </span>
                                                                    </a>
                                                                </div>
                                                            </div> 
                                                        </div>    
                                                    </div>                             
                                                </div>  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="formSection-sep-bottom">
                                    <div class="formSection-inner pt-15">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group" app-field-wrapper="Select Entity">
                                                    <span class="blue-circle mr-2">4</span>
                                                    <label for="description" class="control-label">Set action to be performed</label>
                                                    
                                                    <div class="section2">
                                                        <div class="row graySection">
                                                            <div class="col-md-3">
                                                                <select class="form-control" id="triggerSelect" name="trigger_type_id">
                                                                    <option value="-">Select</option>
                                                                    <?php foreach( $triggerTypes as $label=>$value ) { ?>
                                                                        <option value="<?php echo $value;?>"
                                                                                <?php if( isset( $workflow ) && $value == $workflowAction->trigger_type_id ) echo 'selected';?>
                                                                            >
                                                                            <?php echo $label; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div id="editFieldSec" style="display:none;">
                                                                <div class="col-md-3">
                                                                    <select class="form-control" id="editTypeSelect" name="edit_type_id">
                                                                        <option value="-">Select Entity</option>
                                                                        <?php foreach( $entitytoEdit as $label=>$value ) { ?>
                                                                            <option value="<?php echo $value;?>"
                                                                                    <?php if( isset( $workflowEditField ) && $value == $workflowEditField->edit_type_id ) echo 'selected';?>
                                                                                >
                                                                                <?php echo $label; ?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <select class="form-control" id="editFieldSelect" name="edit_field_id">
                                                                        <option value="-">Select Entity Field</option>
                                                                        <?php foreach( $entityField as $label=>$value ) { ?>
                                                                            <option value="<?php echo $value;?>"
                                                                                    <?php if( isset( $workflowEditField ) && $value == $workflowEditField->edit_field_id ) echo 'selected';?>
                                                                                >
                                                                                <?php echo $label; ?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <select class="form-control" id="editFieldValueSelect" name="field_value">
                                                                        <option value="-">Select Entity Field Values</option>
                                                                        <?php foreach( $entityFieldValue as $label=>$value ) { ?>
                                                                            <option value="<?php echo $value;?>"
                                                                                    <?php if( isset( $workflowEditField ) && $value == $workflowEditField->field_value ) echo 'selected';?>
                                                                                >
                                                                                <?php echo $label; ?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div id="webhookField" style="display:none;">
                                                                <div class="col-md-9">
                                                                    <button type="button" class="btn btn-outline-primary" id="AddWebhook">Add Webhook</button>
                                                                </div>
                                                            </div>
                                                            <div id="sendEmailSec" style="display:none;">
                                                                <div class="col-md-9">
                                                                    <button type="button" class="btn btn-outline-primary" id="sendEmailAction">Send Email</button>
                                                                </div> 
                                                            </div>
                                                            <div id="reassignSec" style="display:none;">
                                                                <div class="col-md-3">
                                                                    <select class="form-control" id="reassignField" name="edit_type_id">
                                                                        <option value="-">Select Entity</option>
                                                                        <?php foreach( $reassignUsers as $label=>$value ) { ?>
                                                                        <option value="<?php echo $value;?>"
                                                                                <?php if( isset( $workflowReassign ) && $value == $workflowReassign->reassignUsers ) echo 'selected';?>
                                                                            >
                                                                            <?php echo $label; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                    </select>
                                                                </div> 
                                                            </div>
                                                        </div>
                                                    </div> 
                                                    </div>  
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Add WebHook -->                                             
                                <div class="panel-body" id="addWebhookSec" style="display:none;">
                                    <div class="row">
                                        <div class="col-sm-12 panelHead">Add Webhook</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group" app-field-wrapper="webhookName">
                                                <?php //$value = (isset($workflow) ? $workflow->description : ''); ?>
                                                <?php echo render_input('webhookName', 'Name', ''); ?>
                                            </div>
                                            <div class="form-group" app-field-wrapper="webhookDescription">
                                                <?php //$value = (isset($workflow) ? $workflow->description : ''); ?>
                                                <?php echo render_textarea('webhookDescription', 'Description', ''); ?>
                                            </div> 
                                            
                                            <div class="form-group" app-field-wrapper="webhookRequestType">
                                                <label  for="requestType" class="control-label d-block">Request Type</label>
                                                <div class="radio-inline pb-5">
                                                    <input class="relative" type="radio" name="requestType" id="typeGet" value="1" 
                                                        <?php //if( isset( $workflow ) && $workflow->is_trigger_now ) echo 'selected';?>
                                                    >
                                                    <label for="typeGet"> <?php echo 'Get'; ?> </label>
                                                </div>
                                                <div class="radio-inline pb-5">
                                                    <input class="relative" type="radio" name="requestType" id="typePost" value="2" 
                                                            <?php //if( isset( $workflow ) && !$workflow->is_trigger_now ) echo 'selected';?>
                                                    >
                                                    <label for="typePost"> <?php echo 'Post'; ?> </label>
                                                </div>
                                                <div class="radio-inline pb-5">
                                                    <input class="relative" type="radio" name="requestType" id="typePut" value="2" 
                                                            <?php //if( isset( $workflow ) && !$workflow->is_trigger_now ) echo 'selected';?>
                                                    >
                                                    <label for="typePut"> <?php echo 'Put'; ?> </label>
                                                </div>
                                            </div>
                                            <div class="form-group" app-field-wrapper="webhookRequestURL">
                                                <?php //$value = (isset($workflow) ? $workflow->description : ''); ?>
                                                <?php echo render_input('webhookRequestURL', 'Request URL', ''); ?>
                                            </div>
                                            <div class="form-group" app-field-wrapper="webhookAuthorization">
                                                <label  for="requestType" class="control-label d-block">Authorization</label>
                                                <div class="radio-inline pb-5">
                                                    <input class="relative" type="radio" name="authorization" id="noAuth" value="1" 
                                                        <?php //if( isset( $workflow ) && $workflow->is_trigger_now ) echo 'selected';?>
                                                    >
                                                    <label for="noAuth"> <?php echo 'No Authorization Required'; ?> </label>
                                                </div>
                                                <div class="radio-inline pb-5">
                                                    <input class="relative" type="radio" name="authorization" id="apiKey" value="2" 
                                                            <?php //if( isset( $workflow ) && !$workflow->is_trigger_now ) echo 'selected';?>
                                                    >
                                                    <label for="apiKey"> <?php echo 'API Key'; ?> </label>
                                                </div>
                                                <div class="radio-inline pb-5">
                                                    <input class="relative" type="radio" name="authorization" id="bearerToken" value="2" 
                                                            <?php //if( isset( $workflow ) && !$workflow->is_trigger_now ) echo 'selected';?>
                                                    >
                                                    <label for="bearerToken"> <?php echo 'Bearer Token'; ?> </label>
                                                </div>
                                                <div class="radio-inline pb-5">
                                                    <input class="relative" type="radio" name="authorization" id="basicAuth" value="2" 
                                                            <?php //if( isset( $workflow ) && !$workflow->is_trigger_now ) echo 'selected';?>
                                                    >
                                                    <label for="basicAuth"> <?php echo 'Basic Authantication'; ?> </label>
                                                </div>
                                            </div>
                                            <div class="form-group" app-field-wrapper="webhookAuthorization">
                                                <label  for="requestType" class="control-label d-block">URL Parameter</label>
                                                <div class="radio-inline pb-5">
                                                    <input class="relative" type="radio" name="webhookParam" id="noParam" value="1" 
                                                        <?php //if( isset( $workflow ) && $workflow->is_trigger_now ) echo 'selected';?>
                                                    >
                                                    <label for="noParam"> <?php echo 'No Parameter'; ?> </label>
                                                </div>
                                                <div class="radio-inline pb-5">
                                                    <input class="relative" type="radio" name="webhookParam" id="addParam" value="2" 
                                                            <?php //if( isset( $workflow ) && !$workflow->is_trigger_now ) echo 'selected';?>
                                                    >
                                                    <label for="addParam"> <?php echo 'Add Parameter'; ?> </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End -->

                                <!-- Send EMail -->                                             
                                <div class="panel-body" id="sendEmailSection" style="display:none;">
                                    <div class="row">
                                        <div class="col-sm-12 panelHead">Send Email</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group" app-field-wrapper="sendEmailFrom">
                                                <label for="sendFrom_multiselect"><?php echo 'From'; ?></label>
                                                <select id="sendEmailFrom" name="sendEmailFrom[]" class="form-control selectpicker" multiple="multiple" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                    <option value="option1"><?php echo _l('option_1'); ?></option>
                                                    <option value="option2"><?php echo _l('option_2'); ?></option>
                                                    <option value="option3"><?php echo _l('option_3'); ?></option>
                                                </select>
                                            </div>
                                            <div class="form-group" app-field-wrapper="sendEmailTo">
                                                <?php //$value = (isset($workflow) ? $workflow->description : ''); ?>
                                                <?php echo render_input('sendTo', 'To', ''); ?>
                                            </div> 
                                            <div class="form-group" app-field-wrapper="sendEmailChooseTemplate">
                                                <label for="sendFrom_multiselect"><?php echo 'Choose Template'; ?></label>
                                                <select class="form-control" id="sendEmailTemplate" name="sendEmailTemplate">
                                                    <option value="-">Select</option>
                                                        <?php foreach( $emailTemplate as $label=>$value ) { ?>
                                                            <option value="<?php echo $value;?>"
                                                                    <?php if( isset( $workflowSendEmail ) && $value == $workflowSendEmail->emailTemplate ) echo 'selected';?>
                                                                >
                                                                <?php echo $label; ?>
                                                            </option>
                                                        <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group" app-field-wrapper="sendEmailSubject">
                                                <?php //$value = (isset($workflow) ? $workflow->description : ''); ?>
                                                <?php echo render_input('sendEmailSubject', 'Subject', ''); ?>
                                            </div>
                                            <div class="form-group" app-field-wrapper="sendEmailBody">
                                                <?php //$value = (isset($workflow) ? $workflow->description : ''); ?>
                                                <?php echo render_textarea('sendEmailBody', 'Body', '', array('rows' => 15)); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End -->

                                <div class="btn-bottom-toolbar text-right">
                                    <button type="submit" class="btn btn-default save-and-add-contact customer-form-submiter">Cancel</button>

                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
                <!-- End -->

            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>

<script>
    /*Jquery code for Set COndition*/
    $(document).ready(function(){
        /* Function for hide and show condition section based on condition radio button */
        $('#sectionToToggle').hide();

        // Handle the radio button change event
        $('input[name="is_condition_based"]').change(function(){
            if ($('#all_deals').is(':checked')) {
                // If Option 1 is selected, hide the section
                $('#sectionToToggle').hide();
            } else if ($('#is_condition_based').is(':checked')) {
                // If Option 2 is selected, show the section
                $('#sectionToToggle').show();
            }
        });

        /* Hide/show on clicked on Deleyed action radio */
        $('#sectionToggleToDelayed').hide();

        // Handle the radio button change event
        $('input[name="is_trigger_now"]').change(function(){
            if ($('#immediate_trigger').is(':checked')) {
                // If Option 1 is selected, hide the section
                $('#sectionToggleToDelayed').hide();
            } else if ($('#dealyed_trigger').is(':checked')) {
                // If Option 2 is selected, show the section
                $('#sectionToggleToDelayed').show();
            }
        });


        /* Hide and show onclick of Delayed Recurrance radio buttons */
        $('#is_frequent').click(function() {
            $('#recurranceCount').show();
            $('#recurranceDate').hide();
        });

        $('#is_until_date').click(function() {
            $('#recurranceCount').hide();
            $('#recurranceDate').show();
        });

        /* Hide recurrance section when click on "Do not repeat" option */
        $('#delayedActionRepeat').change(function() {
            if ($(this).val() == '1') {
                $('#doRepeat').hide();
            } else {
                $('#doRepeat').show();
            }
        });

        /* Show Edit field depend option of on "Set action to be performed" */
        $('#triggerSelect').change(function() {
            if ($(this).val() == '1') {
                $('#editFieldSec').show();
                $('#addWebhookSec').hide();
                $('#reassignSec').hide();
                $('#sendEmailSection').hide();
            } else {
                $('#editFieldSec').hide();
            }
        });

        /* Send EMail - SHow and hide webhook form */
        $('#triggerSelect').change(function() {
            if ($(this).val() == '2') {
                $('#sendEmailSec').show();
                $('#addWebhookSec').hide();
            } else {
                $('#sendEmailSec').hide();
            }
        });
        $('#sendEmailAction').click(function() {
            $('#sendEmailSection').show();
            $('#editFieldSec').hide();
            $('#addWebhookSec').hide();
            $('#reassignSec').hide();
        });
        

        /* Webhook - SHow and hide webhook form */
        $('#triggerSelect').change(function() {
            if ($(this).val() == '3') {
                $('#webhookField').show();
                $('#sendEmailSection').hide();
            } else {
                $('#webhookField').hide();
            }
        });
        $('#AddWebhook').click(function() {
            $('#addWebhookSec').show();
        });


        /* Reassign Field option hide/show */
        $('#triggerSelect').change(function() {
            if ($(this).val() == '4') {
                $('#reassignSec').show();
                $('#webhookField').hide()
                $('#addWebhookSec').hide();
                $('#sendEmailSection').hide();
            } else {
                $('#reassignSec').hide();
            }
        });




        const optionsFieldMap = <?php echo json_encode( $conditionFieldMap ); ?>;
        const actionTypeMap = <?php echo json_encode( $actionTypeMap ); ?>;
        
        // Handle the radio button change event for event type
        $('input[name="entity_type_id"]').change(function(){
            
            var selectedValue = $('input[name="entity_type_id"]:checked').val();

            var optionsFM = optionsFieldMap[selectedValue];
            var optionsAM = actionTypeMap[selectedValue];

            //Update Action  DD
            // Get the select box element by its ID
            var $actionTypeSelect = $('#actionTypeSelect');
            // Clear the existing options
            $actionTypeSelect.empty();
            // Populate the select box with new options
            $actionTypeSelect.append($('<option></option>').attr('value', "-").text('Select'));
            $.each(optionsAM, function(key, value) {
                $actionTypeSelect.append($('<option></option>').attr('value', value).text(key));
            });

            
            //Update Condition  DD
            // Get the select box element by its ID
            var $conditionSelect = $('#conditionSelect');
            // Clear the existing options
            $conditionSelect.empty();
            // Populate the select box with new options
            $conditionSelect.append($('<option></option>').attr('value', "-").text('Select'));
            $.each(optionsFM, function(key, value) {
                $conditionSelect.append($('<option></option>').attr('value', value).text(key));
            });


            // update editTypeSelect DD for edit field section
            // Get the select box element by its ID
            var $editTypeSelect = $('#editTypeSelect');
            // Clear the existing options
            $editTypeSelect.empty();
            // Populate the select box with new options
            $editTypeSelect.append($('<option></option>').attr('value', "-").text('Select'));
            $.each(optionsFM, function(key, value) {
                $editTypeSelect.append($('<option></option>').attr('value', value).text(key));
            });

        });



        /* Funtion for incremental section clicked on plus button */

        let sectionIndex = 1; // Counter to track the number of sections

        // Use event delegation to handle click events on dynamically added elements    
        
        $('#sectionContainer').on('click', '.add-section', function(e) {
            
            e.preventDefault();

            // Clone the section
            let $sectionToClone = $('#incrementalSection').clone();

            // Increment the section index
            sectionIndex++;

            // Update the id and name attributes in the cloned section
            $sectionToClone.attr('id', 'incrementalSection_' + sectionIndex);
            $sectionToClone.find('select').each(function() {
                //let nameAttr = $(this).attr('name');
                //$(this).attr('name', nameAttr + '_' + sectionIndex);
            });

            // Reset the select fields in the cloned section
            $sectionToClone.find('select').prop('selectedIndex', 0);

            // Append the "Remove Section" button to the cloned section
            $sectionToClone.find('.col-md-1').prepend(`
                <a class="remove-section-btn !tw-px-0 tw-group !tw-text-white mr-5" data-toggle="dropdown">
                    <span class="tw-rounded-full tw-bg-danger-600 tw-text-white tw-inline-flex tw-items-center tw-justify-center tw-h-7 tw-w-7 -tw-mt-1 group-hover:!tw-bg-primary-700">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#fff"><path d="M200-440v-80h560v80H200Z"/></svg>
                    </span>
                </a>
            `);

            // Append the cloned section to the container
            $('#sectionContainer').append($sectionToClone);

            // Event listener to remove a section when the Remove button is clicked
            $('#sectionContainer').on('click', '.remove-section-btn', function() {
                $(this).closest('.graySection').remove();
            });

        });
    });


</script>