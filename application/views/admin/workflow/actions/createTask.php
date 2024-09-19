<!-- Add Task -->  
 <!-- Send EMail -->
<div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Add Task</h4>
            </div>
            <div class="modal-body">
                <!-- Your div content goes here -->
                <div id="myDivContent">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group" app-field-wrapper="taskName">
                                <?php 
                                    $labelTaskName = 'Task Name <span style="color: red;">*</span>';
                                    echo render_input('taskName', $labelTaskName, ''); 
                                ?>
                                <span id="nameError" class="text-danger" style="display: none;">This is a required field.</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" app-field-wrapper="taskType">
                                <label>Type</label>
                                <select class="form-control" id="taskType" name="taskType">
                                    <option value="1">Select</option>
                                </select>
                            </div> 
                        </div>  
                        <div class="col-sm-12">
                            <div class="form-group" app-field-wrapper="taskDescription">
                                <?php //$value = (isset($workflow) ? $workflow->description : ''); ?>
                                <?php echo render_textarea('taskDescription', 'Description', ''); ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" app-field-wrapper="taskStatus">
                                <label for="taskType" class="control-label">Status</label>
                                <select class="form-control" id="taskStatus" name="taskStatus">
                                    <option value="-">Choose</option>
                                    <?php foreach( $addTaskStatus as $label=>$value ) { ?>
                                    <option value="<?php echo $value;?>"
                                            <?php if( isset( $workflowAddTask ) && $value == $workflowAddTask->addTaskStatus ) echo 'selected';?>
                                        >
                                        <?php echo $label; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" app-field-wrapper="taskPriority">
                                <label for="taskType" class="control-label">Priority</label>
                                <select class="form-control" id="taskPriority" name="taskPriority">
                                    <option value="-">Choose</option>
                                    <?php foreach( $addTaskPriority as $label=>$value ) { ?>
                                    <option value="<?php echo $value;?>"
                                            <?php if( isset( $workflowAddTask ) && $value == $workflowAddTask->addTaskPriority ) echo 'selected';?>
                                        >
                                        <?php echo $label; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div> 
                        </div>  
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="due_date_days">Due Date</label><br />
                                <div class="dFlex customInputWidth">
                                    <!-- Input for days -->
                                    <?php echo render_input('taskDueDay', '', ''); ?>
                                    <span class="input-group-text">day(s)</span>
                                    
                                    <!-- Input for hours -->
                                    <?php echo render_input('taskDueHours', '', ''); ?>
                                    <span class="input-group-text">hour(s) after workflow trigger date and time</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" app-field-wrapper="taskReminder">
                                <label>Reminder</label>
                                <select class="form-control" id="taskReminder" name="taskReminder">
                                    <option value="0">No Reminder</option>
                                    <option value="1">15 minutes before the due date and time</option>
                                    <option value="2">30 minutes before the due date and time</option>
                                    <option value="3">1 hour before the due date and time</option>
                                    <option value="4">2 hours before the due date and time</option>
                                    <option value="5">1 day before the due date and time</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" app-field-wrapper="assignedTo">
                                <label>Assigned To</label>
                                <select class="form-control" id="assignedTo" name="assignedTo">
                                    <option value="0">select</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" app-field-wrapper="selectAction">
                                <label>Relation</label>
                                <select class="form-control" id="copyFieldAction" name="copy_field_action" disabled>
                                    <option value="1">Lead</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" app-field-wrapper="selectAction">
                                <label>&nbsp;</label>
                                <select class="form-control" id="copyFieldAction" name="copy_field_action" disabled>
                                    <option value="1">Workflow Lead</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>                                             

<!-- End -->


