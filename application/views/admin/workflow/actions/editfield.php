<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

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

            <?php echo render_input('edit_custom_value', '', ''); ?>

            <div>
                <select class="form-control" id="edit_field_value" name="edit_field_value">
                    <option value="-">Select Option</option>
                </select>
            </div>

        </div>
        <div class="col-md-4" id="editCopyFieldValue" style="display:none;">
            <button type="button" class="btn btn-outline-primary" id="copyFieldAction">Copy Field Value</button>
        </div>
    </div>
</div>