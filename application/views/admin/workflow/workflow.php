<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="tw-mt-0 tw-text-lg tw-font-semibold tw-text-neutral-700">
                        Add Workflow
                        </h4>
                    </div>
                </div>

                <!--Workflow form-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel_s">
                            <div class="panel-body">
                                <!-- <form action="<?php echo site_url('admin/workflow/create'); ?>" id="workflow-form" method="post" accept-charset="utf-8" novalidate="novalidate"> -->
                                <?php echo form_open('admin/workflow/create', ['class' => '', 'id' => 'workflow-form']); ?>
                                
                                <div class="form-group f_client_id">
                                    <?php $value = (isset($workflow) ? $workflow->name : ''); ?>
                                    <?php 
                                        $labelName_with_asterisk = 'Name <span style="color: red;">*</span>';
                                        echo render_input('name', $labelName_with_asterisk, $value); 
                                    ?>
                                </div>
                                <div class="form-group" app-field-wrapper="description">
                                    <?php $value = (isset($workflow) ? $workflow->description : ''); ?>
                                    <?php echo render_textarea('description', 'Description', $value); ?>
                                </div>
  
                                <div>
                                <!-- 1 --><?php $this->load->view('admin/workflow/workflow_entity'); ?> 
                                <!-- 2 --><?php $this->load->view('admin/workflow/workflow_preference'); ?>
                                <!-- 3 --><?php $this->load->view('admin/workflow/workflow_condition_to_execute'); ?>
                                <!-- 4 --><?php $this->load->view('admin/workflow/workflow_action'); ?>
                                    <?php //$this->load->view('admin/workflow/actions/copyFieldValue'); ?>
                                </div>
                                

                                <div class="btn-bottom-toolbar text-right">
                                    <button type="button" class="btn btn-default save-and-add-contact customer-form-submiter" id="cancelButton">Cancel</button>

                                    <button type="submit" class="btn btn-primary" id="saveWorkflow" disabled>Save</button>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
                <!-- End -->

            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>

<script>
    /*Jquery code for Set COndition*/
    $(document).ready(function(){

        //Initial Save workflow button disabled
        $('#saveWorkflow').prop('disabled', true);

        // Initialize form validation
        $("#workflow-form").validate({
            rules: {
                name: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "This is a required field"
                }
            },
            submitHandler: function(form) {
                // Submit the form
                form.submit();
            }
        });

        // Optional: Prevent submission when clicking the save button if form is invalid
        $('#saveWorkflow').click(function(e) {
            e.preventDefault(); // Prevent default action
            if ($("#workflow-form").valid()) {
                // Submit the form if valid
                $("#workflow-form").submit();
            }
        });

        //If Cancel button click the redirect to Workflow grid
        $('#cancelButton').click(function() {
            window.history.back(); // Redirect to 1 step back
        });

        /* On input field losing focus
        $('input[name="name"]').blur(function() {
            var nameValue = $(this).val().trim();

            // Check if input is empty
            if (nameValue === '') {
                $('#nameError').show(); // Show error message
                $(this).addClass('is-invalid'); // Optional: add error class to the input
            } else {
                $('#nameError').hide(); // Hide error message if valid
                $(this).removeClass('is-invalid'); // Remove error class if valid
            }
        });*/

        /* Function for hide and show condition section based on condition radio button */
        $('#sectionToToggle').hide();
        $('#sectionToToggleToExecute').hide();

        // -------------------------------------------------------------------------------------
        // Step 2 =  Set action type and trigger preferences JS
        // -------------------------------------------------------------------------------------
        
            // Handle the radio button change event
            $('input[name="is_trigger_now"]').change(function(){
                
                /*Remove Step 3 disabled section to active*/
                $('#step3').find('.disabled').removeClass('disabled');
                $('#step3').find('.disabledSec').removeClass('disabledSec');

                // Check the value of the selected radio button
                var selectedValue = $('input[name="is_trigger_now"]:checked').val();

                if ($('#immediate_trigger').is(':checked')) {

                    // Change the section title based on the selected value
                    $('#sectionTitle').text('Set Conditions');

                    // If Option 1 is selected, hide the section
                    $('#sectionToggleToDelayed').hide();

                    // Set conditions to execute the action hide
                    $('#setConditionToSchedule').hide();

                    // If Set Condition to execute the action hide the step counter number should be change
                    $('#executeConditionText').text('3');

                    // If Set Condition to execute the action hide the step counter number should be change
                    $('#actionToPerformCount').text('4');

                    //Reset Based on condition section when click on immediate trigger radio button
                    $('input[name="is_condition_based"]').prop('checked', false);
                    $('input[name="is_condition_based_to_execute"]').prop('checked', false);
                    
                    $('#sectionToToggle').hide();
                    $('#sectionToToggleToExecute').hide();

                } else if ($('#dealyed_trigger').is(':checked')) {

                    // Change the section title based on the selected value
                    $('#sectionTitle').text('Set conditions to schedule the action');

                    /* Remove Step 3 disabled section to active*/
                    $('#step5').find('.disabled').removeClass('disabled');
                    $('#step5').find('.disabledSec').removeClass('disabledSec');
                    /* Remove STep 4 disabled section to active */
                    $('#StepToDelyed').find('.disabled').removeClass('disabled');
                    $('#StepToDelyed').find('.disabledSec').removeClass('disabledSec');
                    
                    //Reset Based on condition section when click on Delayed Action radio button
                    $('input[name="is_condition_based_to_execute"]').prop('checked', false);

                    // Disabled the step6 section
                    $('#step6').find('.formSection-sep-bottom').addClass('disabled');
                    $('#step6').find('#actionPerformHide').addClass('disabledSec');
                    

                    //Step 3
                    $('#setConditionToSchedule').show();

                    //Step 4
                    $('#sectionToggleToDelayed').show();
                    
                    // Step 5 - If Set Condition to execute the action show the step counter number should be change
                    $('#executeConditionText').text('5');
                    // Set conditions to execute the action show
                    $('#setConditionToExecute').show();

                    // Step 6 - If Set Condition to execute the action show the step counter number should be change
                    $('#actionToPerformCount').text('6');
                }
            });

        // -----------------------------------------------------------------------------------------------------------
        // Step 3 =  Set conditions JS 
        // -----------------------------------------------------------------------------------------------------------

            // Handle the radio button change event
            $('input[name="is_condition_based"]').change(function(){

                /*Active the step 4 and 5 */
                $('#StepToDelyed').find('#sectionToggleToDelayed').removeClass('disabled');
                $('#StepToDelyed').find('#sectionContainerDelayed').removeClass('disabledSec');
                $('#step3').find('#setConditionToExecute').removeClass('disabled');
                $('#step3').find('#whenDisabled').removeClass('disabledSec');

                if ($('#all_deals').is(':checked')) {
                    // If Option 1 is selected, hide the section
                    $('#sectionToToggle').hide();
                    /* Activate step 4 section
                    $('#step6').find('.formSection-sep-bottom').removeClass('disabled');
                    $('#step6').find('#actionPerformHide').removeClass('disabledSec');*/

                } else if ($('#is_condition_based').is(':checked')) {
                    // If Option 2 is selected, show the section
                    $('#sectionToToggle').show();
                    /* Activate step 4 section
                    $('#step6').find('.formSection-sep-bottom').removeClass('disabled');
                    $('#step6').find('#actionPerformHide').removeClass('disabledSec');*/

                }
            });

        /* Hide/show on clicked on Deleyed action radio */
        $('#sectionToggleToDelayed').hide();

        
        // ------------------------------------------------------------------------------------------------------
        // Step 3 : Set conditions to execute the action (If Set action type and trigger preferences = Immediate)
        // ------------------------------------------------------------------------------------------------------
        
        // Handle the radio button change event

            $('input[name="is_condition_based_to_execute"]').change(function(){

                if ($('#all_deals_to_execute').is(':checked')) {
                    // If Option 1 is selected, hide the section
                    $('#sectionToToggleToExecute').hide();

                    /* If "Set conditions to execute the action" => Immediate radio checked - Active step 4 section*/
                    $('#step6').find('.formSection-sep-bottom').removeClass('disabled');
                    $('#step6').find('#actionPerformHide').removeClass('disabledSec');

                } else if ($('#is_condition_based_to_execute').is(':checked')) {
                    // If Option 2 is selected, show the section
                    $('#sectionToToggleToExecute').show();

                    /* If "Set conditions to execute the action" => Based on conditions radio checked - Active step 4 section*/
                    $('#step6').find('.formSection-sep-bottom').removeClass('disabled');
                    $('#step6').find('#actionPerformHide').removeClass('disabledSec');
                }
            });


        /* Hide and show onclick of Delayed Recurrance radio buttons */
        $('#is_frequent').click(function() {
            $('#recurranceCount').show();
            $('#recurranceDate').hide();
        });

        $('#is_until_date').click(function() {
            $('#recurranceCount').hide();
            $('#recurranceDate').show();
        });

        /* Hide recurrance section when click on "Do not repeat" option */
        $('#delayedActionRepeat').change(function() {
            if ($(this).val() == '1') {
                $('#doRepeat').hide();
            } else {
                $('#doRepeat').show();
            }
        });

        /* Show Edit field depend option of on "Set action to be performed" */
        $('#triggerSelect').change(function() {
            if ($(this).val() == '1') {
                $('#editFieldSec').show();
                $('#addWebhookSec').hide();
                $('#reassignSec').hide();
                $('#sendEmailSection').hide();
                $('#addTaskArea').hide();

                //Active Save workflow button
                $('#saveWorkflow').prop('disabled', false);
            } else {
                $('#editFieldSec').hide();
            }
        });

        /* Send EMail - SHow and hide webhook form */
        $('#triggerSelect').change(function() {
            if ($(this).val() == '2') {
                $('#sendEmailSec').show();
                $('#addWebhookSec').hide();
                $('#addTaskArea').hide(); 
                
                //Active Save workflow button
                $('#saveWorkflow').prop('disabled', false);
            } else {
                $('#sendEmailSec').hide();
            }
        });
        $('#sendEmailAction').click(function() {
            $('#sendEmailSection').show();
            $('#editFieldSec').hide();
            $('#addWebhookSec').hide();
            $('#reassignSec').hide();
            $('#addTaskSec').hide();
            $('#addTaskArea').hide();
        });
        

        /* Webhook - SHow and hide webhook form */
        $('#triggerSelect').change(function() {
            if ($(this).val() == '3') {
                $('#webhookField').show();
                $('#sendEmailSection').hide();
                $('#addTaskSec').hide();
                $('#addTaskArea').hide();
                //Active Save workflow button
                $('#saveWorkflow').prop('disabled', false);
            } else {
                $('#webhookField').hide();
            }
        });
        

        $('input[name="webhook[request_type]"]').change(function(){
            
            if ($('input[name="webhook[request_type]"][value="2"]').is(':checked')) {
                // If Option 1 is selected, hide the section
                $('#apiKeySec').show();
                $('#bearerTokenSec').hide();
                $('#basicAuthSec').hide();
            } else if ($('input[name="webhook[request_type]"][value="3"]').is(':checked')) {
                // If Option 3 is selected, show the section
                $('#bearerTokenSec').show();
                $('#apiKeySec').hide();
                $('#basicAuthSec').hide();
            } else if ($('input[name="webhook[request_type]"][value="4"]').is(':checked')) {
                // If Option 4 is selected, show the section
                $('#basicAuthSec').show();
                $('#apiKeySec').hide();
                $('#bearerTokenSec').hide();
            } else if ($('input[name="webhook[request_type]"][value="1"]').is(':checked')) {
                // If Option 2 is selected, show the section
                $('#apiKeySec').hide();
                $('#bearerTokenSec').hide();
                $('#basicAuthSec').hide();
            }
        }); 

        $('input[name="webhook[is_url_param]"]').change(function(){
            if ($('#addParam').is(':checked')) {
                // If Option 1 is selected, hide the section
                $('#addParameterSec').show();
            } else if ($('#noParam').is(':checked')) {
                // If Option 2 is selected, show the section
                $('#addParameterSec').hide();
            }
        }); 
        /* End Webhook */


        /* Reassign Field option hide/show */
        $('#triggerSelect').change(function() {
            if ($(this).val() == '4') {
                $('#reassignSec').show();
                $('#webhookField').hide()
                $('#addWebhookSec').hide();
                $('#sendEmailSection').hide();
                $('#addTaskArea').hide();
                //Active Save workflow button
                $('#saveWorkflow').prop('disabled', false);
            } else {
                $('#reassignSec').hide();
            }
        });


        /* Add Task Action Hide/Show */
        $('#triggerSelect').change(function() {
            if ($(this).val() == '7') {
                $('#addTaskSec').show();
                $('#reassignSec').hide();
                $('#webhookField').hide()
                $('#addWebhookSec').hide();
                $('#sendEmailSection').hide();
                //Active Save workflow button
                $('#saveWorkflow').prop('disabled', false);
            } else {
                $('#addTaskSec').hide();
            }
        });
        
        $('#addTaskAction').click(function() {
            $('#addTaskArea').show();
        });


        /* Funtion for incremental section clicked on plus button */

        let sectionIndex = 1; // Counter to track the number of sections

        // Use event delegation to handle click events on dynamically added elements    
        
        $('#sectionContainer').on('click', '.add-section', function(e) {
            
            e.preventDefault();

            // Clone the section
            let $sectionToClone = $('#incrementalSection').clone();

            // Increment the section index
            sectionIndex++;

            // Update the id and name attributes in the cloned section
            $sectionToClone.attr('id', 'incrementalSection_' + sectionIndex);
            $sectionToClone.find('select').each(function() {
                //let nameAttr = $(this).attr('name');
                //$(this).attr('name', nameAttr + '_' + sectionIndex);
            });

            // Reset the select fields in the cloned section
            $sectionToClone.find('select').prop('selectedIndex', 0);

            // Append the "Remove Section" button to the cloned section
            $sectionToClone.find('.col-md-1').prepend(`
                <a class="remove-section-btn !tw-px-0 tw-group !tw-text-white mr-5" data-toggle="dropdown">
                    <span class="tw-rounded-full tw-bg-danger-600 tw-text-white tw-inline-flex tw-items-center tw-justify-center tw-h-7 tw-w-7 -tw-mt-1 group-hover:!tw-bg-primary-700">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#fff"><path d="M200-440v-80h560v80H200Z"/></svg>
                    </span>
                </a>
            `);

            // Append the cloned section to the container
            $('#sectionContainer').append($sectionToClone);

            // Event listener to remove a section when the Remove button is clicked
            $('#sectionContainer').on('click', '.remove-section-btn', function() {
                $(this).closest('.graySection').remove();
            });

        });


        /* Set conditions to execute the action => Funtion for incremental section clicked on plus button */

        let sectionIndexExecute = 1; // Counter to track the number of sections

        // Use event delegation to handle click events on dynamically added elements    
        
        $('#sectionContainerExecute').on('click', '.add-section', function(e) {
            
            e.preventDefault();

            // Clone the section
            let $sectionToCloneExecute = $('#incrementalSectionToExecute').clone();

            // Increment the section index
            sectionIndexExecute++;

            // Update the id and name attributes in the cloned section
            $sectionToCloneExecute.attr('id', 'incrementalSectionToExecute_' + sectionIndexExecute);
            $sectionToCloneExecute.find('select').each(function() {
                //let nameAttr = $(this).attr('name');
                //$(this).attr('name', nameAttr + '_' + sectionIndex);
            });

            // Reset the select fields in the cloned section
            $sectionToCloneExecute.find('select').prop('selectedIndex', 0);

            // Append the "Remove Section" button to the cloned section
            $sectionToCloneExecute.find('.col-md-1').prepend(`
                <a class="remove-section-btn !tw-px-0 tw-group !tw-text-white mr-5" data-toggle="dropdown">
                    <span class="tw-rounded-full tw-bg-danger-600 tw-text-white tw-inline-flex tw-items-center tw-justify-center tw-h-7 tw-w-7 -tw-mt-1 group-hover:!tw-bg-primary-700">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#fff"><path d="M200-440v-80h560v80H200Z"/></svg>
                    </span>
                </a>
            `);

            // Append the cloned section to the container
            $('#sectionContainerExecute').append($sectionToCloneExecute);

            // Event listener to remove a section when the Remove button is clicked
            $('#sectionContainerExecute').on('click', '.remove-section-btn', function() {
                $(this).closest('.graySection').remove();
            });
        });


        /*  Set action to be performed => Funtion for incremental section clicked on plus button 
        let sectionIndexAction = 1; // Counter to track the number of sections

        // Use event delegation to handle click events on dynamically added elements    
        
        $('#sectionContainerAction').on('click', '.add-section', function(e) {
            
            e.preventDefault();

            // Clone the section
            let $sectionToCloneAction = $('#incrementalSectionToAction').clone();

            
            // Increment the section index
            sectionIndexAction++;

            // Update the id and name attributes in the cloned section
            $sectionToCloneAction.attr('id', 'incrementalSectionToAction' + sectionIndexAction);
            $sectionToCloneAction.find('select').each(function() {
                //let nameAttr = $(this).attr('name');
                //$(this).attr('name', nameAttr + '_' + sectionIndex);
            });

            // Reset the select fields in the cloned section
            $sectionToCloneAction.find('select').prop('selectedIndex', 0);

            // Append the "Remove Section" button to the cloned section
            $sectionToCloneAction.find('.col-md-1').prepend(`
                <a class="remove-section-btn !tw-px-0 tw-group !tw-text-white mr-5" data-toggle="dropdown">
                    <span class="tw-rounded-full tw-bg-danger-600 tw-text-white tw-inline-flex tw-items-center tw-justify-center tw-h-7 tw-w-7 -tw-mt-1 group-hover:!tw-bg-primary-700">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#fff"><path d="M200-440v-80h560v80H200Z"/></svg>
                    </span>
                </a>
            `);

            // Append the cloned section to the container
            $('#sectionContainerAction').append($sectionToCloneAction);

            // Event listener to remove a section when the Remove button is clicked
            $('#sectionContainerAction').on('click', '.remove-section-btn', function() {
                $(this).closest('.graySection').remove();
            });
        });
        */

/* Set action to be performed "Edit Field" section JS */
        $('#editTypeSelect').change(function() {
            $('#editFieldSelect').show();
        });

        $('#editFieldSelect').change(function() {
            var selectedValue = $(this).val();
            if (selectedValue === '2') {
                $('#editFieldValueSelect').show(); // Show the input field section
                $('#editCopyFieldValue').hide(); // Hide the input field section if other options are selected
            } else {
                $('#editCopyFieldValue').show();
                $('#editFieldValueSelect').hide(); // Hide the input field section if other options are selected
            }
        });

        $('#copyFieldAction').click(function() {
            $('#copyFieldModal').show();
        });
        
        //Open Copy field value modal
        $('#copyFieldAction').on('click', function(){
            console.log('jshhdjhsd');
            $('#copyFieldModal').modal('show');
        });        

    });

</script>



<script>
    // Select Entity on change event
    // Handle the radio button change of event
    // Updates Set action type and trigger preferences
    // updates first select box of condition for Schedule
    // updates first select box of condition for Execute
    // Updates Select box for Edit Field ( fields ) under action to be performed

    const optionsFieldMap = <?php echo json_encode( $conditionFieldMap ); ?>;
    const actionTypeMap = <?php echo json_encode( $actionTypeMap ); ?>;
    
    $('input[name="entity_type_id"]').change(function(){
        
        var selectedValue = $('input[name="entity_type_id"]:checked').val();

        var optionsFM = optionsFieldMap[selectedValue];
        var optionsAM = actionTypeMap[selectedValue];

        //Remove Step 2 disabled section to active
        $('#step2').find('.disabled').removeClass('disabled');
        $('#step2').find('.disabledSec').removeClass('disabledSec');
        

        // Get the select box element by its ID
        var $actionTypeSelect = $('#actionTypeSelect');
        // Clear the existing options
        $actionTypeSelect.empty();
        // Populate the select box with new options
        $actionTypeSelect.append($('<option></option>').attr('value', "-").text('Select'));
        $.each(optionsAM, function(key, value) {
            $actionTypeSelect.append($('<option></option>').attr('value', value).text(key));
        });

        //Update Condition DD Scedule
        // Get the select box element by its ID
        var $sceheduleCondSlct = $('#scheduleConditionSelect');
        // Clear the existing options
        $sceheduleCondSlct.empty();
        // Populate the select box with new options
        $sceheduleCondSlct.append($('<option></option>').attr('value', "-").text('Select'));
        $.each(optionsFM, function(key, value) {
            $sceheduleCondSlct.append($('<option></option>').attr('value', value).text(key));
        });

        //Update Condition DD execute
        // Get the select box element by its ID
        var $exeCondSlct = $('#executeConditionSelect');
        // Clear the existing options
        $exeCondSlct.empty();
        // Populate the select box with new options
        $exeCondSlct.append($('<option></option>').attr('value', "-").text('Select'));
        $.each(optionsFM, function(key, value) {
            $exeCondSlct.append($('<option></option>').attr('value', value).text(key));
        });

        // update editTypeSelect DD for edit field section
        // Get the select box element by its ID
        var $editTypeSelect = $('#editTypeSelect');
        // Clear the existing options
        $editTypeSelect.empty();
        // Populate the select box with new options
        $editTypeSelect.append($('<option></option>').attr('value', "-").text('Select'));
        $.each(optionsFM, function(key, value) {
            $editTypeSelect.append($('<option></option>').attr('value', value).text(key));
        });

    });
</script>

<script>
//Function to update CompareValue selection on change of field to comapre "clsConditionSelect"
$(document).on('change', '.clsIncrementalSection .clsConditionSelect', function () {

    const conditionFieldOptionMap = <?php echo json_encode( $conditionFieldOptionMap ); ?>;

    // Get the selected value
    var selectedValue = $(this).val();
    var entityTypeValue = $('#entity_type_id').val();

    // Reference to the parent div
    var parentDiv = $(this).closest('.clsIncrementalSection');

    if( '-' == selectedValue ){
        parentDiv.find('.divStageSelect, .divValueSelect, .divOperatorSelect, .divActualCompareValue, .divCompareValueSelect').addClass('hide');    
        return;
    } 


    const selectedFieldName = conditionFieldOptionMap[entityTypeValue][selectedValue]["field_name"];
    const isTextBoxValue = conditionFieldOptionMap[entityTypeValue][selectedValue]["is_textbox"];

    if( isTextBoxValue ){
        parentDiv.find('.divStageSelect, .divValueSelect, .divCompareValueSelect').addClass('hide');    
        parentDiv.find('.divOperatorSelect, .divActualCompareValue').removeClass('hide');    
    } else {
        parentDiv.find('.divStageSelect, .divValueSelect, .divActualCompareValue').addClass('hide');    
        parentDiv.find('.divOperatorSelect, .divCompareValueSelect').removeClass('hide');    

        // AJAX request to the backend
        $.ajax({
            url: '<?php echo site_url('admin/workflow/getCompareOptions'); ?>', // Replace with your backend URL
            type: 'POST',
            data: {
                fieldId: selectedValue, // Sending the selected value to the backend
                entityType: entityTypeValue, // Sending the Entity type value to the backend
            },
            success: function (response) {
                response = JSON.parse(response);
                // Assuming response contains an array of options in the format [{id: 1, name: 'Option1'}, ...]
                var optionsData = response.data; // Adjust according to the actual structure of your response
                
                // Find the select box with class clsCompareValueSelect within the parent div
                var compareSelect = parentDiv.find('.clsCompareValueSelect');
                
                // Clear existing options
                compareSelect.empty();
                // Populate the select box with new options
                compareSelect.append($('<option></option>').attr('value', "-").text('Select'));
                $.each(optionsData, function (index, option) {
                    //compareSelect.append($('<option></option>').attr('value', option.id).text(option.name));
                    compareSelect.append($('<option>', { value: option.id, text: option.name }));
                });
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });
    }
});

</script>