<!-- Add Task -->  
 <!-- Send EMail -->
<div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Send Email</h4>
            </div>
            <div class="modal-body">
                <!-- Your div content goes here -->
                <div id="myDivContent">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group" app-field-wrapper="taskTitle">
                                <?php //$value = (isset($workflow) ? $workflow->description : ''); ?>
                                <?php echo render_input('taskTitle', 'Title', ''); ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" app-field-wrapper="taskType">
                                <label for="taskType" class="control-label">Type</label>
                                <select class="form-control" id="taskType" name="taskType_id">
                                    <option value="-">Choose</option>
                                    <?php foreach( $addTaskType as $label=>$value ) { ?>
                                    <option value="<?php echo $value;?>"
                                            <?php if( isset( $workflowAddTask ) && $value == $workflowAddTask->addTaskType ) echo 'selected';?>
                                        >
                                        <?php echo $label; ?>
                                    </option>
                                    <?php } ?>
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
                                <select class="form-control" id="taskStatus" name="taskStatus_id">
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
                            <div class="form-group" app-field-wrapper="taskPriority`">
                                <label for="taskType" class="control-label">Type</label>
                                <select class="form-control" id="taskPriority" name="taskPriority_id">
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


