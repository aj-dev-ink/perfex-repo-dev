<!-- Step 1 -->
<?php $entityTypeIdVal =  isset($workflow) ? $workflow->entity_type_id : '' ?>

<div class="formSection-sep-top formSection-sep-bottom">
    <div class="formSection-inner pt-15">
        <div class="form-group" app-field-wrapper="Select Entity">
            <span class="blue-circle mr-2">1</span>
            <label for="description" class="labelHead control-label">Select Entity 
                <i class="fa-regular fa-circle-question pull-right tw-mt-0.5 tw-ml-1"
                    data-toggle="tooltip"
                    data-title="<?php echo _l('workflow_select_entity_step'); ?>">
                </i>
            </label>
            
            <div class="form-group" app-field-wrapper="entity_type_id">
                <div class="radio-pull-left">
                <?php $entityTypeIdVal =  isset($workflow) ? $workflow->description : '' ?>
                    <?php foreach( $entityTypes as $label=>$value ) { ?>
                        <div class="custom-radio-button">
                            <input type="radio" name="entity_type_id" id="entity_type_id" value="<?php echo $value; ?>" <?php if( isset( $workflow ) && $value == $workflow->entity_type_id ) echo 'checked';?> >
                            <label for="entity_type_id" class="btn btn-default"><i class="fa-regular fa-thumbs-up pull-left radioThumb"></i> <?php echo _l($label); ?> </label>
                        </div>
                    <?php } ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>