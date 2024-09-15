<div id="step6">
    <div class="formSection-sep-bottom remBottomBrd disabled">
        <div class="formSection-inner pt-15">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group" app-field-wrapper="Select Entity">
                        <span class="blue-circle mr-2" id="actionToPerformCount">4</span>
                        <label for="description" class="labelHead control-label">Set action to be performed
                            <i class="fa-regular fa-circle-question pull-right tw-mt-0.5 tw-ml-1"
                                data-toggle="tooltip"
                                data-title="<?php echo _l('workflow_set_any_action'); ?>">
                            </i>
                        </label>
                        
                        <div id="actionPerformHide" class="section2 disabledSec">
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
                                <!-- Edit Field -->
                                <?php $this->load->view('admin/workflow/actions/editfield'); ?>
                                <!-- Reassign -->
                                
                                <?php $this->load->view('admin/workflow/actions/reassign'); ?>
                                <!-- Add Webhook -->
                                <div id="webhookField" style="display:none;">
                                    <div class="col-md-9">
                                        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#webhookModal" id="AddWebhook">Add Webhook</button>
                                    </div>
                                </div>
                                <!-- Send Email -->
                                <div id="sendEmailSec" style="display:none;">
                                    <div class="col-md-9">
                                        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#sendEmailModal" id="sendEmailAction">Send Email</button>
                                    </div> 
                                </div>
                                <!-- Add Task -->
                                <div id="addTaskSec" style="display:none;">
                                    <div class="col-md-9">
                                        <button type="button" class="btn btn-outline-primary"  data-toggle="modal" data-target="#addTaskModal" id="addTaskAction">Create Task</button>
                                    </div> 
                                </div>
                            </div>
                        </div>  
                        <?php $this->load->view('admin/workflow/actions/webhook'); ?>
                        <?php $this->load->view('admin/workflow/actions/sendemail'); ?>
                        <?php $this->load->view('admin/workflow/actions/createTask'); ?>
                        
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>