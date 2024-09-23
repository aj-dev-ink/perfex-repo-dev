<!-- Add WebHook -->
<div class="modal fade" id="webhookModal" tabindex="-1" role="dialog" aria-labelledby="webhookModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Add Webhook</h4>
            </div>
            <div class="modal-body">
                <!-- Your div content goes here -->
                <div id="myDivContent">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group" app-field-wrapper="webhookName">
                                <?php $labelWebhookName = 'Name <span style="color: red;">*</span>';  ?>
                                <?php echo render_input('webhook[name]', $labelWebhookName, ''); ?>
                                <small class="text-danger" id="webhookNameError" style="display:none;">This field is required</small>
                            </div>
                            <div class="form-group" app-field-wrapper="webhookDescription">
                                <?php //$value = (isset($workflow) ? $workflow->description : ''); ?>
                                <?php echo render_textarea('webhook[description]', 'Description', ''); ?>
                            </div> 

                            <div  app-field-wrapper="webhookRequestType">
                                <label  for="requestType" class="control-label d-block">Request Type <span style="color: red;">*</span></label>

                                <?php foreach( $webhookRequestType as $label=>$value ) { ?>
                                    <div class="radio-inline pb-5">
                                        <input class="relative"  type="radio" name="webhook[request_type]" id="inputField1" value="<?php echo $value; ?>" <?php if( isset( $webhook ) && $value == $webhook->request_type ) echo 'selected';?> >
                                        <label for="webhook_request_type_<?php echo $value; ?>"> <?php echo _l($label); ?> </label>
                                    </div>
                                <?php } ?>
                                
                            </div>
                            
                            <div class="form-group" app-field-wrapper="webhookRequestURL">
                                <?php
                                    $labelRequestURL = 'Request URL <span style="color: red;">*</span> <i class="fa-regular fa-circle-question pull-right tw-mt-0.5 tw-ml-1"
                                    data-toggle="tooltip"
                                    data-title="' . lang('workflow_request_url') . '">
                                </i>';
                                ?>
                                <?php echo render_input('webhook[request_url]', $labelRequestURL, ''); ?>
                                <small class="text-danger" id="webhookRequestURLError" style="display:none;">This field is required</small>
                            </div>

                            <div class="form-group" app-field-wrapper="webhookAuthorization">
                                <label  for="authorizationType" class="control-label d-block">Authorization <span style="color: red;">*</span></label>
                                <?php foreach( $webhookAuthType as $label=>$value ) { ?>
                                    <div class="radio-inline pb-5">
                                        <input class="relative"  type="radio" name="webhook[authorization_type]" id="webhook_authorization_type_<?php echo $value; ?>" value="<?php echo $value; ?>" <?php if( isset( $webhook ) && $value == $webhook->authorization_type ) echo 'selected';?> >
                                        <label for="webhook_authorization_type_<?php echo $value; ?>"> <?php echo _l($label); ?> </label>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <!-- API Key -->
                                <div id="apiKeySec" class="row graySection" style="display:none;">
                                    <div class="col-sm-4" app-field-wrapper="webhookAPIKey">
                                        <?php 
                                            $labelApiKey = 'API Key <span style="color: red;">*</span>'; 
                                            echo render_input('webhook[api_key]', $labelApiKey, '');
                                        ?>                        
                                    </div>
                                    <div class="col-sm-4" app-field-wrapper="webhookAPIKeyValue">
                                        <?php 
                                            $labelkeyValue = 'Key Value <span style="color: red;">*</span>'; 
                                            echo render_input('webhook[api_key_value]', $labelkeyValue, '');
                                        ?>                         
                                    </div>
                                    <div class="col-sm-4" app-field-wrapper="webhookAPIKeyAddTo">
                                        <?php 
                                        $input = render_input('webhook[api_key_addTo]', 'Add To', 'Header');
                                        echo str_replace('<input', '<input disabled="disabled"', $input);
                                        ?>                         
                                    </div>
                                </div>
                                <!-- Bearer Token -->
                                <div id="bearerTokenSec" class="form-group" app-field-wrapper="webhookBearerToken" style="display:none;">
                                    <?php 
                                        $labelBearerToken = 'Bearer Token <span style="color: red;">*</span>'; 
                                        echo render_input('webhook[bearer_token]', $labelBearerToken, '');
                                    ?>    
                                </div>
                                <!-- Basic Auth Token -->
                                <div id="basicAuthSec" class="form-group" app-field-wrapper="webhookBasicAuth" style="display:none;">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <?php 
                                                $labelUserName = 'Username <span style="color: red;">*</span>'; 
                                                echo render_input('webhook[auth_username]', $labelUserName, '');
                                            ?>
                                            <small class="text-danger" id="webhookAuthUserNameError" style="display:none;">This field is required</small>
                                        </div>
                                        <div class="col-sm-6">
                                            <?php 
                                                $labelPassword = 'Password <span style="color: red;">*</span>'; 
                                                echo render_input('webhook[auth_password]', $labelPassword, '', 'password');
                                            ?>
                                            <small class="text-danger" id="webhookAuthPasswordError" style="display:none;">This field is required</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" app-field-wrapper="webhookAuthorization">
                                <label  for="requestType" class="control-label d-block">URL Parameters <span style="color: red;">*</span></label>
                                <div class="radio-inline pb-5">
                                    <input class="relative" type="radio" name="webhook[is_url_param]" id="noParam" value="0" 
                                        <?php //if( isset( $workflow ) && $workflow->is_trigger_now ) echo 'selected';?>
                                    >
                                    <label for="noParam"> <?php echo 'No Parameter'; ?> </label>
                                </div>
                                <div class="radio-inline pb-5">
                                    <input class="relative" type="radio" name="webhook[is_url_param]" id="addParam" value="1" 
                                            <?php //if( isset( $workflow ) && !$workflow->is_trigger_now ) echo 'selected';?>
                                    >
                                    <label for="addParam"> <?php echo 'Add Parameter'; ?> </label>
                                </div>
                            </div>
                            <!-- Add Parameter -->
                            <div class="form-group">
                                <div id="addParameterSec" class="form-group" app-field-wrapper="webhookAddParam" style="display:none;">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <?php 
                                                $labelParameterName = 'Parameter Name <span style="color: red;">*</span>'; 
                                                echo render_input('webhook[url_params]', $labelParameterName, '');
                                            ?>
                                            <small class="text-danger" id="webhookUrlParamError" style="display:none;">This field is required</small>
                                        </div>
                                        <div class="col-sm-6">
                                            <?php 
                                                $labelParameterType = 'Parameter Type <span style="color: red;">*</span>'; 
                                                echo render_input('webhook[url_params_type]', $labelParameterType, '');
                                            ?>
                                            <small class="text-danger" id="webhookUrlParamTypeError" style="display:none;">This field is required</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveWebhook">Save changes</button>
            </div>
        </div>
    </div>
</div>                                             

<!-- End -->