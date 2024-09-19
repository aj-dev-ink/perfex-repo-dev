<div  class="modal fade" id="copyFieldModal" tabindex="-1" role="dialog" aria-labelledby="copyFieldValuemodal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Copy Field Value</h4>
            </div>
            <div class="modal-body">
                
                <div id="myDivContent">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group" app-field-wrapper="selectAction">
                                <label>Select Action</label>
                                <select class="form-control" id="copyFieldAction" name="copy_field_action" disabled>
                                    <option value="1">Copy Field Value</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group" app-field-wrapper="copyFieldForm">
                                <label>Select Field To Copy</label>
                                <select class="form-control" id="copyFieldAction" name="copy_field_action">
                                    <option value="1">Select</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group" app-field-wrapper="selectTextToCopy">
                                <?php echo render_textarea('select_text_to_copy', 'Text to Copy', ''); ?>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group" app-field-wrapper="fieldCopyTo">
                                <label>Copy To</label>
                                <select class="form-control" name="fieldCopyTo" disabled>
                                    <option value="1">Copy To</option>
                                </select>
                            </div>
                        </div>
                    </div>
                <div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div> 