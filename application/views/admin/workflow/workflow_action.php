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
                            <?php $this->load->view('admin/workflow/actions/editfield'); ?>
                            <?php $this->load->view('admin/workflow/actions/webhook'); ?>
                            <?php $this->load->view('admin/workflow/actions/sendemail'); ?>
                            <?php $this->load->view('admin/workflow/actions/reassign'); ?>
                        </div>
                    </div> 
                    </div>  
                </div> 
            </div>
        </div>
    </div>
</div>