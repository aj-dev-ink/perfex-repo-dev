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
                                    <?php echo render_input('name', 'Name', $value); ?>
                                </div>
                                <div class="form-group" app-field-wrapper="description">
                                    <?php $value = (isset($workflow) ? $workflow->description : ''); ?>
                                    <?php echo render_textarea('description', 'Description', $value); ?>
                                </div>
  
                                <?php $this->load->view('admin/workflow/workflow_entity'); ?>
                                <?php $this->load->view('admin/workflow/workflow_preference'); ?>
                                <?php $this->load->view('admin/workflow/workflow_condition'); ?>
                                <?php $this->load->view('admin/workflow/workflow_action'); ?>
                                
                                <div class="btn-bottom-toolbar text-right">
                                    <button type="submit" class="btn btn-default save-and-add-contact customer-form-submiter">Cancel</button>

                                    <button type="submit" class="btn btn-primary">Save</button>
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
        /* Function for hide and show condition section based on condition radio button */
        $('#sectionToToggle').hide();

        // Handle the radio button change event
        $('input[name="is_condition_based"]').change(function(){
            if ($('#all_deals').is(':checked')) {
                // If Option 1 is selected, hide the section
                $('#sectionToToggle').hide();
            } else if ($('#is_condition_based').is(':checked')) {
                // If Option 2 is selected, show the section
                $('#sectionToToggle').show();
            }
        });

        /* Hide/show on clicked on Deleyed action radio */
        $('#sectionToggleToDelayed').hide();

        // Handle the radio button change event
        $('input[name="is_trigger_now"]').change(function(){
            if ($('#immediate_trigger').is(':checked')) {
                // If Option 1 is selected, hide the section
                $('#sectionToggleToDelayed').hide();
            } else if ($('#dealyed_trigger').is(':checked')) {
                // If Option 2 is selected, show the section
                $('#sectionToggleToDelayed').show();
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
            } else {
                $('#editFieldSec').hide();
            }
        });

        /* Send EMail - SHow and hide webhook form */
        $('#triggerSelect').change(function() {
            if ($(this).val() == '2') {
                $('#sendEmailSec').show();
                $('#addWebhookSec').hide();
            } else {
                $('#sendEmailSec').hide();
            }
        });
        $('#sendEmailAction').click(function() {
            $('#sendEmailSection').show();
            $('#editFieldSec').hide();
            $('#addWebhookSec').hide();
            $('#reassignSec').hide();
        });
        

        /* Webhook - SHow and hide webhook form */
        $('#triggerSelect').change(function() {
            if ($(this).val() == '3') {
                $('#webhookField').show();
                $('#sendEmailSection').hide();
            } else {
                $('#webhookField').hide();
            }
        });
        $('#AddWebhook').click(function() {
            $('#addWebhookSec').show();
        });


        /* Reassign Field option hide/show */
        $('#triggerSelect').change(function() {
            if ($(this).val() == '4') {
                $('#reassignSec').show();
                $('#webhookField').hide()
                $('#addWebhookSec').hide();
                $('#sendEmailSection').hide();
            } else {
                $('#reassignSec').hide();
            }
        });




        const optionsFieldMap = <?php echo json_encode( $conditionFieldMap ); ?>;
        const actionTypeMap = <?php echo json_encode( $actionTypeMap ); ?>;
        
        // Handle the radio button change event for event type
        $('input[name="entity_type_id"]').change(function(){
            
            var selectedValue = $('input[name="entity_type_id"]:checked').val();

            var optionsFM = optionsFieldMap[selectedValue];
            var optionsAM = actionTypeMap[selectedValue];

            //Update Action  DD
            // Get the select box element by its ID
            var $actionTypeSelect = $('#actionTypeSelect');
            // Clear the existing options
            $actionTypeSelect.empty();
            // Populate the select box with new options
            $actionTypeSelect.append($('<option></option>').attr('value', "-").text('Select'));
            $.each(optionsAM, function(key, value) {
                $actionTypeSelect.append($('<option></option>').attr('value', value).text(key));
            });

            
            //Update Condition  DD
            // Get the select box element by its ID
            var $conditionSelect = $('#conditionSelect');
            // Clear the existing options
            $conditionSelect.empty();
            // Populate the select box with new options
            $conditionSelect.append($('<option></option>').attr('value', "-").text('Select'));
            $.each(optionsFM, function(key, value) {
                $conditionSelect.append($('<option></option>').attr('value', value).text(key));
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
    });


</script>