<div id="webhookField" style="display:none;">
    <div class="col-md-9">
        <button type="button" class="btn btn-outline-primary" id="AddWebhook">Add Webhook</button>
    </div>
</div>

<!-- Add WebHook -->                                             
<div class="panel-body" id="addWebhookSec" style="display:none;">
    <div class="row">
        <div class="col-sm-12 panelHead">Add Webhook</div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group" app-field-wrapper="webhookName">
                <?php //$value = (isset($workflow) ? $workflow->description : ''); ?>
                <?php echo render_input('webhookName', 'Name', ''); ?>
            </div>
            <div class="form-group" app-field-wrapper="webhookDescription">
                <?php //$value = (isset($workflow) ? $workflow->description : ''); ?>
                <?php echo render_textarea('webhookDescription', 'Description', ''); ?>
            </div> 
            
            <div class="form-group" app-field-wrapper="webhookRequestType">
                <label  for="requestType" class="control-label d-block">Request Type</label>
                <div class="radio-inline pb-5">
                    <input class="relative" type="radio" name="requestType" id="typeGet" value="1" 
                        <?php //if( isset( $workflow ) && $workflow->is_trigger_now ) echo 'selected';?>
                    >
                    <label for="typeGet"> <?php echo 'Get'; ?> </label>
                </div>
                <div class="radio-inline pb-5">
                    <input class="relative" type="radio" name="requestType" id="typePost" value="2" 
                            <?php //if( isset( $workflow ) && !$workflow->is_trigger_now ) echo 'selected';?>
                    >
                    <label for="typePost"> <?php echo 'Post'; ?> </label>
                </div>
                <div class="radio-inline pb-5">
                    <input class="relative" type="radio" name="requestType" id="typePut" value="2" 
                            <?php //if( isset( $workflow ) && !$workflow->is_trigger_now ) echo 'selected';?>
                    >
                    <label for="typePut"> <?php echo 'Put'; ?> </label>
                </div>
            </div>
            <div class="form-group" app-field-wrapper="webhookRequestURL">
                <?php //$value = (isset($workflow) ? $workflow->description : ''); ?>
                <?php echo render_input('webhookRequestURL', 'Request URL', ''); ?>
            </div>
            <div class="form-group" app-field-wrapper="webhookAuthorization">
                <label  for="requestType" class="control-label d-block">Authorization</label>
                <div class="radio-inline pb-5">
                    <input class="relative" type="radio" name="authorization" id="noAuth" value="1" 
                        <?php //if( isset( $workflow ) && $workflow->is_trigger_now ) echo 'selected';?>
                    >
                    <label for="noAuth"> <?php echo 'No Authorization Required'; ?> </label>
                </div>
                <div class="radio-inline pb-5">
                    <input class="relative" type="radio" name="authorization" id="apiKey" value="2" 
                            <?php //if( isset( $workflow ) && !$workflow->is_trigger_now ) echo 'selected';?>
                    >
                    <label for="apiKey"> <?php echo 'API Key'; ?> </label>
                </div>
                <div class="radio-inline pb-5">
                    <input class="relative" type="radio" name="authorization" id="bearerToken" value="2" 
                            <?php //if( isset( $workflow ) && !$workflow->is_trigger_now ) echo 'selected';?>
                    >
                    <label for="bearerToken"> <?php echo 'Bearer Token'; ?> </label>
                </div>
                <div class="radio-inline pb-5">
                    <input class="relative" type="radio" name="authorization" id="basicAuth" value="2" 
                            <?php //if( isset( $workflow ) && !$workflow->is_trigger_now ) echo 'selected';?>
                    >
                    <label for="basicAuth"> <?php echo 'Basic Authantication'; ?> </label>
                </div>
            </div>
            <div class="form-group" app-field-wrapper="webhookAuthorization">
                <label  for="requestType" class="control-label d-block">URL Parameter</label>
                <div class="radio-inline pb-5">
                    <input class="relative" type="radio" name="webhookParam" id="noParam" value="1" 
                        <?php //if( isset( $workflow ) && $workflow->is_trigger_now ) echo 'selected';?>
                    >
                    <label for="noParam"> <?php echo 'No Parameter'; ?> </label>
                </div>
                <div class="radio-inline pb-5">
                    <input class="relative" type="radio" name="webhookParam" id="addParam" value="2" 
                            <?php //if( isset( $workflow ) && !$workflow->is_trigger_now ) echo 'selected';?>
                    >
                    <label for="addParam"> <?php echo 'Add Parameter'; ?> </label>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End -->