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
                        
                        <!-- Actio To Perform Section -->
                        <div id="actionPerformHide" class="section2 disabledSec">
                            <div id="sectionContainerAction">
                                <div class="row graySection clsIncrementalActionSection" id="incrementalSectionToAction">
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
                                        <div class="col-sm-8" id="webhookField" style="display:none;">
                                            <div class="col-md-7">
                                                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#webhookModal" id="AddWebhook">Add Webhook</button>
                                            </div>
                                        </div>
                                    <!-- Send Email -->
                                        <div class="col-sm-8" id="sendEmailSec" style="display:none;">
                                            <div class="col-md-7">
                                                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#sendEmailModal" id="sendEmailAction">Send Email</button>
                                            </div> 
                                        </div>
                                    
                                    <!-- Add Task -->
                                        <div class="col-sm-8" id="addTaskSec" style="display:none;">
                                            <div class="col-md-7">
                                                <button type="button" class="btn btn-outline-primary"  data-toggle="modal" data-target="#addTaskModal" id="addTaskAction">Create Task</button>
                                            </div> 
                                        </div>
                                    
                                    <!-- increment button 
                                    <div class="col-md-1 text-right incrementalBtn">
                                        <a class="add-section !tw-px-0 tw-group !tw-text-white" data-toggle="dropdown">
                                            <span class="tw-rounded-full tw-bg-primary-600 tw-text-white tw-inline-flex tw-items-center tw-justify-center tw-h-7 tw-w-7 -tw-mt-1 group-hover:!tw-bg-primary-700">
                                                <i class="fa-regular fa-plus fa-lg"></i>
                                            </span>
                                        </a>
                                    </div>-->
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