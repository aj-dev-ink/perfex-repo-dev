<div id="step5"> <!-- Step 5 -->
    <div class="formSection-sep-bottom disabled" id="setConditionToSchedule" style="display:none;">
        <div class="formSection-inner pt-15">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group" app-field-wrapper="Set Conditions">
                        <span class="blue-circle mr-2" id="scheduleConditionCount">3</span>
                        <label for="description" class="labelHead control-label"><span  id="sectionTitle">Set conditions to schedule the action</span>
                            <i class="fa-regular fa-circle-question pull-right tw-mt-0.5 tw-ml-1"
                                data-toggle="tooltip"
                                data-title="<?php echo _l('workflow_select_condition_filter'); ?>">
                            </i>
                        </label>
                        <div class="section2 pb-5 disabledSec">
                            <div class="radio-inline">
                                <input class="relative" type="radio" name="is_condition_based_schedule" id="all_deals" value="0" 
                                    <?php if( isset( $workflow ) && !$workflow->is_condition_based_schedule ) echo 'selected';?>
                                >
                                <label for="all_deals"> <?php echo _l('All Entities'); ?> </label>
                            </div>
                            <div class="radio-inline">
                                <input class="relative" type="radio" name="is_condition_based_schedule" id="is_condition_based_schedule" value="1" 
                                        <?php if( isset( $workflow ) && $workflow->is_condition_based ) echo 'selected';?>
                                >
                                <label for="is_condition_based_schedule"> <?php echo _l('Set conditions to schedule the action'); ?> </label>
                            </div>
                        </div>

                        <div class="section2" id="sectionToToggle">
                            <div id="sectionContainer">
                                <!-- Hidden radio button for first condition -->
                                <input type="hidden" id="toggleRadio" name="sched_is_and[]" class="hidden-radio" value="1">

                                <div class="defaultCondition-label">1</div> 

                                <div class="row graySection clsIncrementalSection" id="incrementalSection">
                                    <div class="col-md-3">
                                        <select class="form-control clsConditionSelect" id="scheduleConditionSelect" name="sched_condition_type_id[]">
                                            <option value="-">Select</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2 hide divStageSelect">
                                        <select class="form-control" id="stageSelect" name="sched_stage_type_id[]">
                                            <option value="-">Select</option>
                                            <?php foreach( $stageTypes as $label=>$value ) { ?>
                                                <option value="<?php echo $value;?>"
                                                        <?php if( isset( $workflowCondition ) && $value == $workflowCondition->stage_type_id ) echo 'selected';?>
                                                    >
                                                    <?php echo $label; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-2 hide divValueSelect">
                                        <select class="form-control" id="valueSelect" name="sched_value_type_id[]">
                                            <option value="-">Select</option>
                                            <?php foreach( $valueTypes as $label=>$value ) { ?>
                                                <option value="<?php echo $value;?>"
                                                        <?php if( isset( $workflowCondition ) && $value == $workflowCondition->value_type_id ) echo 'selected';?>
                                                    >
                                                    <?php echo $label; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-2 hide divOperatorSelect">
                                        <select class="form-control clsOperatorSelect" id="operatorSelect" name="sched_operator_type_id[]">
                                            <option value="-">Select</option>
                                            <?php foreach( $operatorTypes as $label=>$value ) { ?>
                                                <option value="<?php echo $value;?>"
                                                        <?php if( isset( $workflowCondition ) && $value == $workflowCondition->operator_type_id ) echo 'selected';?>
                                                    >
                                                    <?php echo $label; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-2 hide divActualCompareValue">
                                        <?php $value = (isset($workflowDelayed) ? $workflowDelayed->actual_compare_value : ''); ?>
                                        <?php echo render_input('sched_actual_compare_value[]', '', $value); ?>
                                    </div>

                                    <div class="col-md-2 hide divCompareValueSelect">
                                        <select class="form-control clsCompareValueSelect" id="compareValueSelect" name="sched_compare_value_type_id[]">
                                            <option value="-">Select</option>
                                            <?php foreach( $enumTimePreference2 as $label=>$value ) { ?>
                                                <option value="<?php echo $value;?>"
                                                        <?php if( isset( $workflowDelayed ) && $value == $workflowDelayed->compare_value_type_id ) echo 'selected';?>
                                                    >
                                                    <?php echo $label; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-1 text-right incrementalBtn">
                                        <a class="add-section !tw-px-0 tw-group !tw-text-white" data-toggle="dropdown">
                                            <span class="tw-rounded-full tw-bg-primary-600 tw-text-white tw-inline-flex tw-items-center tw-justify-center tw-h-7 tw-w-7 -tw-mt-1 group-hover:!tw-bg-primary-700">
                                                <i class="fa-regular fa-plus fa-lg"></i>
                                            </span>
                                        </a>
                                    </div>
                                </div> 
                            </div> 
                            
                            <div id="conditionSummary">Condition </div>
                        </div>     
                        
                        
                    </div>  
                </div>
            </div>
        </div>
    </div>
    
</div>
