<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head();?>

<div id="wrapper" >

    <div class="content">

        <div class="row">

            <div class="panel_s">

                <div class="panel-heading">

                    <div>

                        <strong style="font-size: 20px"> <?php echo _l('email_template_manage_imap_setting')?> </strong>

                        <a style="float: right" href="#" onclick="fnc_imap_setting_dlg( 0 ) " class="btn btn-primary" > <i class="fa fa-add"> </i> <?php echo _l('email_template_manage_new_imap')?> </a>

                    </div>

                </div>


                <div class="panel-body">

                    <div class="table-responsive ">

                        <table class="table table-templates">
                            <thead>
                                <tr>
                                    <th><?php echo _l('id')?></th>
                                    <th><?php echo _l('settings_general_company_name')?></th>
                                    <th><?php echo _l('settings_email')?></th>
                                    <th><?php echo _l('email_template_manage_status')?></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<div class="modal fade" id="template_definition" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content" id="template_definition_content"  >


        </div>

    </div>

</div>


<?php init_tail(); ?>


<script>


    $(document).ready(function (){

        initDataTable('.table-templates', admin_url + 'email_template_manage/imap_setting_lists' , false , false , [] , [1,"desc"] );

    })


    function fnc_imap_setting_dlg( record_id )
    {

        $('#template_definition').modal();

        $('#template_definition_content').html(' <div style="margin: 20px; padding: 20px;"> <div class="email-template-loading-spinner"></div> </div>');


        requestGet( admin_url+"email_template_manage/imap_detail/"+record_id ).done(function ( response ){

            $('#template_definition_content').html( response ).promise().done(function (){

                init_selectpicker();

            });

        }).fail(function(error) {

            $('#template_definition').modal('hide');

            var response = JSON.parse(error.responseText);

            alert_float('danger', response.message);

        });

    }

</script>


<style>

    .email-template-loading-spinner {
        border: 4px solid #3498db; /* Spinner color */
        border-radius: 50%;
        border-top: 4px solid #fff;
        width: 35px;
        height: 35px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

</style>
