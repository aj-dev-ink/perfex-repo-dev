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
                                <?php //$value = (isset($workflow) ? $workflow->description : ''); ?>
                                <?php echo render_input('webhook[name]', 'Name', ''); ?>
                            </div>
                            <div class="form-group" app-field-wrapper="webhookDescription">
                                <?php //$value = (isset($workflow) ? $workflow->description : ''); ?>
                                <?php echo render_textarea('webhook[description]', 'Description', ''); ?>
                            </div> 
                            
                            <div class="form-group" app-field-wrapper="webhookRequestType">
                                <label  for="requestType" class="control-label d-block">Request Type</label>
                                <?php foreach( $webhookAuthType as $label=>$value ) { ?>
                                    <div class="radio-inline pb-5">
                                        <input class="relative"  type="radio" name="webhook[request_type]" id="webhook_request_type_<?php echo $value; ?>" value="<?php echo $value; ?>" <?php if( isset( $webhook ) && $value == $webhook->request_type ) echo 'selected';?> >
                                        <label for="webhook_request_type_<?php echo $value; ?>"> <?php echo _l($label); ?> </label>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="form-group">
                                <!-- API Key -->
                                <div id="apiKeySec" class="form-group" app-field-wrapper="webhookAPIKey" style="display:none;">
                                    <?php echo render_input('webhook[api_key]', 'API Key', ''); ?>                         
                                </div>
                                <!-- Bearer Token -->
                                <div id="bearerTokenSec" class="form-group" app-field-wrapper="webhookBearerToken" style="display:none;">
                                    <?php echo render_input('webhook[bearer_token]', 'Bearer Token', ''); ?>                         
                                </div>
                                <!-- Basic Auth Token -->
                                <div id="basicAuthSec" class="form-group" app-field-wrapper="webhookBasicAuth" style="display:none;">
                                    <?php echo render_input('webhook[auth_username]', 'Username', ''); ?>                         
                                    <?php echo render_input('webhook[auth_password]', 'Password', ''); ?>
                                </div>
                            </div>
                            
                            <div class="form-group" app-field-wrapper="webhookRequestURL">
                                <?php //$value = (isset($webhook) ? $webhook->request_url : ''); ?>
                                <?php echo render_input('webhook[request_url]', 'Request URL', ''); ?>
                            </div>
                            <div class="form-group" app-field-wrapper="webhookAuthorization">
                                <label  for="requestType" class="control-label d-block">Authorization</label>

                                <?php foreach( $webhookRequestType as $label=>$value ) { ?>
                                    <div class="radio-inline pb-5">
                                        <input class="relative"  type="radio" name="webhook[authorization_type]" id="webhook_authorization_type_<?php echo $value; ?>" value="<?php echo $value; ?>" <?php if( isset( $webhook ) && $value == $webhook->authorization_type ) echo 'selected';?> >
                                        <label for="webhook_authorization_type_<?php echo $value; ?>"> <?php echo _l($label); ?> </label>
                                    </div>
                                <?php } ?>
                            </div>

                            
                            
                            <div class="form-group" app-field-wrapper="webhookAuthorization">
                                <label  for="requestType" class="control-label d-block">URL Parameter</label>
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
                                    <?php echo render_textarea('webhook[url_params]', 'Parameter', ''); ?>                         
                                </div>
                            </div>
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