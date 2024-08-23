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
                                <form action="<?php echo site_url('admin/workflow/create'); ?>" id="workflow-form" method="post" accept-charset="utf-8" novalidate="novalidate">
                                    
                                <div class="form-group f_client_id">
                                    <?php $value = (isset($workflow) ? $workflow->name : ''); ?>
                                    <?php echo render_input('name', 'Name', $value); ?>
                                </div>
                                <div class="form-group" app-field-wrapper="description">
                                    <?php $value = (isset($workflow) ? $workflow->description : ''); ?>
                                    <?php echo render_textarea('description', 'Description', $value); ?>
                                </div>  
                                
                                
                                <div class="form-group" app-field-wrapper="Select Entity">
                                    <label for="description" class="control-label">Select Entity</label>
                                    <div class="form-group" app-field-wrapper="entity_type_id">
                                        <div class="radio chk">
                                            <?php foreach( $entityTypes as $label=>$value ) { ?>
                                                <input class="relative" type="radio" name="entity_type_id" id="entity_type_id" value="<?php echo $value; ?>" <?php if( isset( $workflow ) && $value == $workflow->entity_type_id ) echo 'selected';?> >
                                                <label for="entity_type_id"> <?php echo _l($label); ?> </label>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" app-field-wrapper="Select action types">
                                    <label for="description" class="control-label">Set action type and trigger preferences</label>
                                    <select class="form-control" id="actionTypeSelect" name="action_type_select" style="margin-bottom:10px;">
                                        <option value="-">Select</option>
                                        <?php foreach( $actionTypes as $label=>$value ) { ?>
                                            <option value="<?php echo $value;?>"
                                                    <?php if( isset( $workflow ) && $value == $workflow->action_type_id ) echo 'selected';?>
                                                >
                                                <?php echo $label; ?>
                                            </option>
                                        <?php } ?>
                                    </select>   
                                    <div class="radio-inline">
                                        <input class="relative" type="radio" name="is_trigger_now" id="immediate_trigger" value="1" 
                                            <?php if( isset( $workflow ) && $workflow->is_trigger_now ) echo 'selected';?>
                                        >
                                        <label for="immediate_trigger"> <?php echo _l('Immediate'); ?> </label>
                                    </div>
                                    <div class="radio-inline">
                                        <input class="relative" type="radio" name="is_trigger_now" id="dealyed_trigger" value="0" 
                                                <?php if( isset( $workflow ) && !$workflow->is_trigger_now ) echo 'selected';?>
                                        >
                                        <label for="dealyed_trigger"> <?php echo _l('Delayed Action'); ?> </label>
                                    </div>
                                </div>

                                <div class="form-group" app-field-wrapper="Set Conditions">
                                    <label for="description" class="control-label">Set Conditions</label>
                                    <div  style="margin-bottom:10px;">
                                        <div class="radio-inline">
                                            <input class="relative" type="radio" name="is_condition_based" id="all_deals" value="0" 
                                                <?php if( isset( $workflow ) && !$workflow->is_condition_based ) echo 'selected';?>
                                            >
                                            <label for="all_deals"> <?php echo _l('All Deals'); ?> </label>
                                        </div>
                                        <div class="radio-inline">
                                            <input class="relative" type="radio" name="is_condition_based" id="is_condition_based" value="1" 
                                                    <?php if( isset( $workflow ) && $workflow->is_condition_based ) echo 'selected';?>
                                            >
                                            <label for="is_condition_based"> <?php echo _l('Based on conditions'); ?> </label>
                                        </div>
                                    </div>
<?  /////////////////DONE TILL CONDITIONS ?>
                                    <div class="row" style="padding: 10px; background-color: #f5f5f5; margin: 0;">
                                        <div class="col-md-1 text-center" style="position:relative; top:7px;">
                                            1
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control" id="exampleSelect" name="example_select">
                                                <option value="Opportunity-pipeline">Opportunity pipeline</option>
                                            </select>  
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control" id="exampleSelect" name="example_select">
                                                <option value="Pipeline-stage">Pipeline stage</option> 
                                            </select>  
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control" id="exampleSelect" name="example_select">
                                                <option value="-">New Value</option> 
                                            </select>  
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control" id="exampleSelect" name="example_select">
                                                <option value="-">Equal</option>    
                                            </select>  
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control" id="exampleSelect" name="example_select">
                                                <option value="-">Negotiation</option>    
                                            </select>  
                                        </div>
                                        <div class="col-md-1 text-right" style="position:relative; top:7px;">
                                            <a href="#" class="!tw-px-0 tw-group !tw-text-white" data-toggle="dropdown">
                                                <span class="tw-rounded-full tw-bg-primary-600 tw-text-white tw-inline-flex tw-items-center tw-justify-center tw-h-7 tw-w-7 -tw-mt-1 group-hover:!tw-bg-primary-700">
                                                    <i class="fa-regular fa-plus fa-lg"></i>
                                                </span>
                                            </a>
                                        </div>
                                    </div>                                 
                                </div>  


                                <div class="form-group" app-field-wrapper="Select Entity">
                                    <label for="description" class="control-label">Set action to be performed</label>
                                    <div class="row" style="padding: 10px; background-color: #f5f5f5; margin: 0;">
                                        <div class="col-md-1 text-center" style="position:relative; top:7px;">
                                            1
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control" id="exampleSelect" name="example_select">
                                                <option value="sendEmail">Send Email</option>
                                            </select>  
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-info save-and-add-contact customer-form-submiter">Send Email</button>
                                        </div>
                                    </div>  
                                </div> 
                                
                                <div class="btn-bottom-toolbar text-right">
                                    <button type="submit" class="btn btn-default save-and-add-contact customer-form-submiter">cancel</button>

                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form> 
                        </div>
                    </div>
                </div>
                <!-- End -->

            </div>
        </div>
    </div>
</div>

</body>

</html>