<div id="step5">
    <div class="formSection-sep-bottom disabled" id="setConditionToExecute" style="display:none;">
        <div class="formSection-inner pt-15">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group" app-field-wrapper="Set Conditions">
                        <span class="blue-circle mr-2">5</span>
                        <label for="description" class="labelHead control-label">Set conditions to execute the action.
                            <i class="fa-regular fa-circle-question pull-right tw-mt-0.5 tw-ml-1"
                                data-toggle="tooltip"
                                data-title="<?php echo _l('workflow_set_execution_condition'); ?>">
                            </i>
                        </label>
                        <div id="whenDisabled" class="disabledSec">
                            <div class="section2 pb-5">
                                <div class="radio-inline">
                                    <input class="relative" type="radio" name="is_condition_based_to_execute" id="all_deals_to_execute" value="0" 
                                        <?php if( isset( $workflow ) && !$workflow->is_condition_based ) echo 'selected';?>
                                    >
                                    <label for="all_deals_to_execute"> <?php echo _l('All Entities'); ?> </label>
                                </div>
                                <div class="radio-inline">
                                    <input class="relative" type="radio" name="is_condition_based_to_execute" id="is_condition_based_to_execute" value="1" 
                                            <?php if( isset( $workflow ) && $workflow->is_condition_based ) echo 'selected';?>
                                    >
                                    <label for="is_condition_based_to_execute"> <?php echo _l('Based on conditions'); ?> </label>
                                </div>
                            </div>

                            <div class="section2" id="sectionToToggleToExecute">
                                <div id="sectionContainerExecute">
                                    <div class="row graySection clsIncrementalSection" id="incrementalSectionToExecute">
                                        <div class="col-md-3">
                                            <select class="form-control clsConditionSelect" id="executeConditionSelect" name="condition_type_id[]">
                                                <option value="-">Select</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2 hide divStageSelect">
                                            <select class="form-control" id="stageSelect" name="stage_type_id[]">
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
                                            <select class="form-control" id="valueSelect" name="value_type_id[]">
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
                                            <select class="form-control clsOperatorSelect" id="operatorSelect" name="operator_type_id[]">
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
                                            <?php echo render_input('actual_compare_value[]', '', $value); ?>
                                        </div>

                                        <div class="col-md-2 hide divCompareValueSelect">
                                            <select class="form-control clsCompareValueSelect" id="compareValueSelect" name="compare_value_type_id[]">
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
                            </div>     
                        </div>                        
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>