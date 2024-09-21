<!-- Delayed Action Condition Step 4 -->
 <div id="StepToDelyed">

        <div id="sectionToggleToDelayed"  class="formSection-sep-bottom ">
            <div class="formSection-inner pt-15 pb-10">
                <div class="row">
                    <div class="col-sm-12 col-12">
                        <div app-field-wrapper="Select action types">
                            <span class="blue-circle mr-2">4</span>
                            <label for="description" class="labelHead control-label">Select when to perform action 
                                <i class="fa-regular fa-circle-question pull-right tw-mt-0.5 tw-ml-1"
                                    data-toggle="tooltip"
                                    data-title="<?php echo _l('workflow_set_execution_condition'); ?>">
                                </i>
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-12">
                        <div id="sectionContainerDelayed" class="section2 pb-5">
                            <div class="row">
                                <div class="col-md-2">
                                    <?php echo render_input('pref_count', 'Time Preference', ''); ?>
                                </div>
                                <div class="col-md-2">
                                    <label for="name" class="control-label">&nbsp;</label>
                                    <select class="form-control" id="stageSelect" name="pref_duration">
                                        <option value="-">Select</option>
                                        <?php foreach( $durationTypes as $label=>$value ) { ?>
                                            <option value="<?php echo $value;?>"
                                                    <?php if( isset( $workflowDelayed ) && $value == $workflowDelayed->pref_duration ) echo 'selected';?>
                                                >
                                                <?php echo $label; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="name" class="control-label">&nbsp;</label>
                                    <select class="form-control" id="valueSelect" name="is_before">
                                        <option value="-">Select</option>
                                        <?php foreach( $isBeforeAfter as $label=>$value ) { ?>
                                            <option value="<?php echo $value;?>"
                                                    <?php if( isset( $workflowDelayed ) && $value == $workflowDelayed->is_before ) echo 'selected';?>
                                                >
                                                <?php echo $label; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="name" class="control-label">Date Properties</label>
                                    <select class="form-control" id="operatorSelect" name="delay_date_type">
                                        <option value="-">Select</option>
                                        <?php foreach( $prefPropertyTypes as $label=>$value ) { ?>
                                            <option value="<?php echo $value;?>"
                                                    <?php if( isset( $workflowDelayed ) && $value == $workflowDelayed->delay_date_type ) echo 'selected';?>
                                                >
                                                <?php echo $label; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="name" class="control-label">Repeat</label>
                                    <select class="form-control" id="delayedActionRepeat" name="repeat_type">
                                        <option value="-">Select</option>
                                        <?php foreach( $repeatTypes as $label=>$value ) { ?>
                                            <option value="<?php echo $value;?>"
                                                    <?php if( isset( $workflowDelayed ) && $value == $workflowDelayed->repeat_type ) echo 'selected';?>
                                                >
                                                <?php echo $label; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="clearfix"></div>
                                <div id="doRepeat" style="display:none;">
                                    <div class="col-md-12">
                                        <div><label for="name" class="control-label">Recurrence</label></div>
                                        <div class="radio-inline pb-5">
                                            <input class="relative" type="radio" name="is_recurance" id="is_frequent" value="1" 
                                                <?php if( isset( $workflow ) && $workflow->is_recurance ) echo 'selected';?>
                                            >
                                            <label for="is_frequent"> <?php echo _l('Frequency'); ?> </label>
                                        </div>
                                        <div class="radio-inline pb-5">
                                            <input class="relative" type="radio" name="is_recurance" id="is_until_date" value="0" 
                                                    <?php if( isset( $workflow ) && !$workflow->is_recurance ) echo 'selected';?>
                                            >
                                            <label for="is_until_date"> <?php echo _l('Until Date'); ?> </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="recurranceCount" style="display:none; margin-top:5px;">
                                        <?php echo render_input('frequency', 'Count', ''); ?>
                                    </div>
                                    <div class="col-md-3" id="recurranceDate" style="display:none; margin-top:5px;">
                                        <?php echo render_date_input('until_date', 'Choose Date', '');?>
                                    </div>
                                </div>
                            </div> 
                        </div> 
                    </div> 
                </div>    
            </div> 
        </div> 
    
</div>
<!-- End -->