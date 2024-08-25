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

                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($workflows as $workflow): ?>
                                <tr>
                                    <td><?php echo $workflow['id']; ?></td>
                                    <td><?php echo $workflow['name']; ?></td>
                                    <td><?php echo $workflow['description']; ?></td>
                                    <td>
                                        <a href="<?php echo admin_url('workflow/create/' . $workflow['id']); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil"></i></a>
                                        <a href="#" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
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

</script>
</body>

</html>