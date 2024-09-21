<div id="step2"> <!-- Step 2 -->
    <div class="formSection-sep-bottom disabled">
        <div class="formSection-inner pt-15 pb-15 ">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div app-field-wrapper="Select action types">
                        <span class="blue-circle mr-2">2</span>
                        <label for="description" class="labelHead control-label">Set action type and trigger preferences 
                            <i class="fa-regular fa-circle-question pull-right tw-mt-0.5 tw-ml-1"
                                data-toggle="tooltip"
                                data-title="<?php echo _l('workflow_select_action_type'); ?>">
                            </i>
                        </label>
                    </div>
                </div>
                <div class="col-sm-5 col-12 disabledSec">
                    <div class="" app-field-wrapper="Select action types">
                        <div class="section2">
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
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/workflow/workflow_schedule_condition'); ?>
<?php $this->load->view('admin/workflow/workflow_when_to_perform'); ?>