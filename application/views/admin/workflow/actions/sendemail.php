<!-- Send EMail -->
<div class="modal fade" id="sendEmailModal" tabindex="-1" role="dialog" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Send Email</h4>
            </div>
            <div class="modal-body">
                <!-- Your div content goes here -->
                <div id="myDivContent">
                    <div class="row">
                        <div class="col-sm-12">
                        <div class="form-group" app-field-wrapper="sendEmailChooseTemplate">
                                <label for="sendFrom_multiselect"><?php echo 'Choose Template'; ?></label>
                                <select class="form-control" id="sendEmailAction" name="sendEmail[template_id]">
                                    <option value="-">Select Email Template</option>
                                    <?php foreach( $templates as $objTemplate ) { ?>
                                        <option value="<?php echo $objTemplate->id;?>"
                                                <?php if( isset( $sendEmail ) && $objTemplate->id == $sendEmail->template_id ) echo 'selected';?>
                                            >
                                            <?php echo $objTemplate->template_name; ?>
                                        </option>
                                    <?php } ?>
                                </select> 
                            </div>

                            <div class="form-group" app-field-wrapper="sendEmailTo">
                                <label for="sendFrom_multiselect"><?php echo 'To Email'; ?></label>
                                <select id="sendEmailTo" name="sendEmail[email_to_fields]" class="form-control selectpicker" multiple="multiple" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                    <option value="option1"><?php echo _l('option_1'); ?></option>
                                    <option value="option2"><?php echo _l('option_2'); ?></option>
                                    <option value="option3"><?php echo _l('option_3'); ?></option>
                                </select>
                                
                            </div>
                            <!-- <div class="form-group sendEMailTo" app-field-wrapper="sendEmailTo">
                                <?php //$value = (isset($workflow) ? $workflow->description : ''); ?>
                                <?php //echo render_input('sendTo', 'To', ''); ?>
                                <div class="input-group-append addCCBCC">
                                    <button id="addCc" class="addCc-btn" type="button">+CC</button>
                                    <button id="addBcc" class="addBcc-btn" type="button">+BCC</button>
                                </div>
                            </div> -->
                            
                            <div class="addCCBCC">
                                <button id="addCc" class="addCc-btn" type="button">+CC</button>
                            </div>
                            <!-- Add CC Field -->
                            <div id="ccField" style="display:none;">
                                <label for="sendFrom_multiselect"><?php echo 'CC'; ?></label>
                                <select id="sendEmailTo" name="sendEmail[email_cc_fields]" class="form-control selectpicker" multiple="multiple" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                    <option value="option1"><?php echo _l('option_1'); ?></option>
                                    <option value="option2"><?php echo _l('option_2'); ?></option>
                                    <option value="option3"><?php echo _l('option_3'); ?></option>
                                </select>
                                <?php //echo render_input('cc', 'CC', ''); ?>
                            </div>
                            
                            <!-- Add BCC Field 
                            <div id="bccField" style="display:none;">
                                <?php //echo render_input('bcc', 'BCC', ''); ?>
                            </div>-->

                            <!-- <div class="form-group" app-field-wrapper="sendEmailSubject">
                                <?php //$value = (isset($workflow) ? $workflow->description : ''); ?>
                                <?php //echo render_input('sendEmailSubject', 'Subject', ''); ?>
                            </div>
                            <div class="form-group" app-field-wrapper="sendEmailBody">
                                <?php //$value = (isset($workflow) ? $workflow->description : ''); ?>
                                <?php //echo render_textarea('sendEmailBody', 'Body', '', array('rows' => 15)); ?>
                            </div> -->

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>                                             

<!-- End -->
