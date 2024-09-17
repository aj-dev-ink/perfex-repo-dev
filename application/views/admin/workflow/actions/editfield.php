

    <div class="col-sm-8" id="editFieldSec" style="display:none;">
        <div class="row">
            <div class="col-md-4">
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
            <div class="col-md-4">
                <select class="form-control" id="editFieldSelect" name="edit_field_id" style="display:none">
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
            <div class="col-md-4" id="editFieldValueSelect" style="display:none;">
                <?php echo render_input('webhook[field_value]', '', ''); ?>
            </div>
            <div class="col-md-4" id="editCopyFieldValue" style="display:none;">
                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#copyFieldModal" id="copyFieldAction">Copy Field Value</button>
            </div>
        </div>
    </div>

    <!-- Add Copy Field Value Modal Form 
        <div class="modal fade" id="copyFieldModal" tabindex="-1" role="dialog" aria-labelledby="copyFieldValuemodal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Copy Field Value</h4>
                    </div>
                    <div class="modal-body">
                        
                        <div id="myDivContent">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group" app-field-wrapper="selectAction">
                                        <label>Select Action</label>
                                        <select class="form-control" id="copyFieldAction" name="copy_field_action" disabled>
                                            <option value="1">Copy Field Value</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group" app-field-wrapper="copyFieldForm">
                                    <?php echo render_input('select_field_to_copy', 'Select Field To Copy', ''); ?>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group" app-field-wrapper="selectTextToCopy">
                                        <?php echo render_textarea('select_text_to_copy', 'Text to Copy', ''); ?>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group" app-field-wrapper="fieldCopyTo">
                                        <label>Copy To</label>
                                        <select class="form-control" name="fieldCopyTo" disabled>
                                            <option value="1">Copy To</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>                                             
         -->




