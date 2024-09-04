<div id="sendEmailSec" style="display:none;">
    <div class="col-md-9">
        <button type="button" class="btn btn-outline-primary" id="sendEmailAction">Send Email</button>
    </div> 
</div>

<!-- Send EMail -->
<div class="panel-body" id="sendEmailSection" style="display:none;">
    <div class="row">
        <div class="col-sm-12 panelHead">Send Email</div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group" app-field-wrapper="sendEmailFrom">
                <label for="sendFrom_multiselect"><?php echo 'From'; ?></label>
                <select id="sendEmailFrom" name="sendEmailFrom[]" class="form-control selectpicker" multiple="multiple" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                    <option value="option1"><?php echo _l('option_1'); ?></option>
                    <option value="option2"><?php echo _l('option_2'); ?></option>
                    <option value="option3"><?php echo _l('option_3'); ?></option>
                </select>
            </div>
            <div class="form-group" app-field-wrapper="sendEmailTo">
                <?php //$value = (isset($workflow) ? $workflow->description : ''); ?>
                <?php echo render_input('sendTo', 'To', ''); ?>
            </div> 
            <div class="form-group" app-field-wrapper="sendEmailChooseTemplate">
                <label for="sendFrom_multiselect"><?php echo 'Choose Template'; ?></label>
                <select class="form-control" id="sendEmailTemplate" name="sendEmailTemplate">
                    <option value="-">Select</option>
                        <?php foreach( $emailTemplate as $label=>$value ) { ?>
                            <option value="<?php echo $value;?>"
                                    <?php if( isset( $workflowSendEmail ) && $value == $workflowSendEmail->emailTemplate ) echo 'selected';?>
                                >
                                <?php echo $label; ?>
                            </option>
                        <?php } ?>
                </select>
            </div>
            <div class="form-group" app-field-wrapper="sendEmailSubject">
                <?php //$value = (isset($workflow) ? $workflow->description : ''); ?>
                <?php echo render_input('sendEmailSubject', 'Subject', ''); ?>
            </div>
            <div class="form-group" app-field-wrapper="sendEmailBody">
                <?php //$value = (isset($workflow) ? $workflow->description : ''); ?>
                <?php echo render_textarea('sendEmailBody', 'Body', '', array('rows' => 15)); ?>
            </div>
        </div>
    </div>
</div>
<!-- End -->
