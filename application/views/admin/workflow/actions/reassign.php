<div id="reassignSec" style="display:none;">
    <div class="col-md-3">
        <select class="form-control" id="reassignField" name="edit_type_id">
            <option value="-">Select Entity</option>
            <?php foreach( $reassignUsers as $label=>$value ) { ?>
            <option value="<?php echo $value;?>"
                    <?php if( isset( $workflowReassign ) && $value == $workflowReassign->reassignUsers ) echo 'selected';?>
                >
                <?php echo $label; ?>
            </option>
        <?php } ?>
        </select>
    </div> 
</div>