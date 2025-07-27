<div id="page-content" class="page-wrapper clearfix">
    <?php echo form_hidden('site_url', get_uri()); ?>
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo html_entity_decode($title); ?></h1>
            <div class="title-button-group">
                <?php if(acc_has_permission('acc_can_create_transfer')){
                    echo modal_anchor(get_uri("accounting/transfer_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add'), array("class" => "btn btn-default ", "title" => app_lang('add')));
                } ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="transfer-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>
<?php require 'plugins/Accounting/assets/js/transfer/manage_js.php'; ?>
