<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('Workflows'); ?></h4>
                        <hr class="hr-panel-heading" />

                        <?php if (staff_can('create',  'workflow')) { ?>
                            <a href="<?php echo admin_url('workflow/create'); ?>"  class="btn btn-info mbot25"><?php echo _l('Create Workflow'); ?></a>
                        <?php }; ?>

                        <div class="clearfix"></div>
                        <table class="table dt-table scroll-responsive">
                            <thead>
                                <tr>
                                    <th><?php echo _l('ID'); ?></th>
                                    <th><?php echo _l('Name'); ?></th>
                                    <th><?php echo _l('Description'); ?></th>
                                    <th><?php echo _l('Is Active'); ?></th>
                                    <th><?php echo _l('Entity'); ?></th>
                                    <th><?php echo _l('Action Preference'); ?></th>
                                    <th><?php echo _l('Trigger'); ?></th>
                                    <th><?php echo _l('Action'); ?></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($workflows as $workflow): ?>
                                <tr>
                                    <td><?php echo $workflow['id']; ?></td>
                                    <td><?php echo $workflow['name']; ?></td>
                                    <td><?php echo $workflow['description']; ?></td>
                                    <td>
                                        <a href="javascript:void(0);" class="toggle-status" data-id="<?php echo $workflow['id']; ?>" data-status="<?php echo $workflow['is_active']; ?>">
                                            <i class="fa <?php echo ($workflow['is_active']) ? 'fa-check-circle text-success' : 'fa-times-circle text-danger'; ?>"></i>
                                        </a>
                                    </td>

                                    <td><?php echo ( is_numeric( $workflow['entity_type_id'] ) && isset( $entityTypes[ $workflow['entity_type_id'] ] )  ? $entityTypes[ $workflow['entity_type_id'] ] :'-') ?></td>
                                    <td><?php echo ( is_numeric( $workflow['action_type_id'] ) && isset( $actionTypes[ $workflow['action_type_id'] ] )  ? $actionTypes[ $workflow['action_type_id'] ] :'-') ?></td>
                                    <td><?php echo ( is_numeric( $workflow['trigger_type_id'] ) && isset( $triggerTypes[ $workflow['trigger_type_id'] ] )  ? $triggerTypes[ $workflow['trigger_type_id'] ] :'-') ?></td>
                                    <td>
                                        <a href="<?php echo admin_url('workflow/create/' . $workflow['id']); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil"></i></a>
                                        <a href="<?php echo admin_url('workflow/delete/' . $workflow['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>

$(document).ready(function(){
    $('.toggle-status').on('click', function(){
        var workflowId = $(this).data('id');
        var currentStatus = $(this).data('status');
        var newStatus = (currentStatus == 1) ? 0 : 1;
        var $icon = $(this).find('i');
        var thisObj = $(this);

        $.ajax({
            url: '<?php echo admin_url("workflow/toggleStatus"); ?>',
            type: 'POST',
            data: { id: workflowId, status: newStatus},
            success: function(response) {
                response = JSON.parse(response);

                if(response && response.success) {
                    $icon.toggleClass('fa-check-circle text-success fa-times-circle text-danger');
                    thisObj.data('status', newStatus);
                } else {
                    alert('Failed to update status.');
                }
            }
        });
    });
});


</script>
</body>

</html>