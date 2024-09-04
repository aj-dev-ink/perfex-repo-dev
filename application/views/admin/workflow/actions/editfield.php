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