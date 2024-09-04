<div class="formSection-sep-bottom">
    <div class="formSection-inner pt-15">
        <div class="row">
            <div class="col-sm-12 col-12">
                <div app-field-wrapper="Select action types">
                    <span class="blue-circle mr-2">2</span>
                    <label for="description" class="control-label">Set action type and trigger preferences 
                        <i class="fa-regular fa-circle-question pull-right tw-mt-0.5 tw-ml-1"
                            data-toggle="tooltip"
                            data-title="<?php echo _l('customer_currency_change_notice'); ?>">
                        </i>
                    </label>
                </div>
            </div>
            <div class="col-sm-5 col-12">
                <div class="" app-field-wrapper="Select action types">
                    <div class="section2 form-group">
                        <select class="form-control" id="actionTypeSelect" name="action_type_id" style="margin-bottom:10px;">
                            <option value="-">Select</option>
                            <!-- <?php foreach( $actionTypes as $label=>$value ) { ?>
                                <option value="<?php echo $value;?>"
                                        <?php if( isset( $workflow ) && $value == $workflow->action_type_id ) echo 'selected';?>
                                    >
                                    <?php echo $label; ?>
                                </option>
                            <?php } ?> -->
                        </select>   
                        <div class="radio-inline pb-5">
                            <input class="relative" type="radio" name="is_trigger_now" id="immediate_trigger" value="1" 
                                <?php if( isset( $workflow ) && $workflow->is_trigger_now ) echo 'selected';?>
                            >
                            <label for="immediate_trigger"> <?php echo _l('Immediate'); ?> </label>
                        </div>
                        <div class="radio-inline pb-5">
                            <input class="relative" type="radio" name="is_trigger_now" id="dealyed_trigger" value="0" 
                                    <?php if( isset( $workflow ) && !$workflow->is_trigger_now ) echo 'selected';?>
                            >
                            <label for="dealyed_trigger"> <?php echo _l('Delayed Action'); ?> </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delayed Action Condition -->
            <div class="section2" id="sectionToggleToDelayed">
                    <div class="col-sm-12 col-12">
                        <div id="sectionContainerDelayed" class="form-group">
                        <div class="row graySection">
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
            <!-- End -->

        </div>
    </div>
</div>