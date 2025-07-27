<div class="tab-content">
    <?php echo form_open(get_uri("accounting/save_permissions"), array("id" => "permissions-form", "class" => "general-form dashed-row", "role" => "form")); ?>
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="card">
        <div class="card-header">
            <h4><?php echo app_lang('permissions') . ": " . $model_info->title; ?></h4>
        </div>
        <div class="card-body">

            <ul class="permission-list">
                <div>
                    <?php echo view('Accounting\Views\setting\accounting_permission'); ?>
                </div>
          
            </ul>

        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary mr10 pull-right <?php if(!acc_has_permission('acc_can_edit_setting')){echo 'hide';} ?>"><span data-feather="check-circle" class="icon-14"></span> <?php echo app_lang('save'); ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
