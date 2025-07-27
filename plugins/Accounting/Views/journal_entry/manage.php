<div id="page-content" class="page-wrapper clearfix">
    <?php echo form_hidden('site_url', get_uri()); ?>
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo html_entity_decode($title); ?></h1>
            <div class="title-button-group">
                <a href="<?php echo get_uri('accounting/new_journal_entry'); ?>" class="btn btn-default mbot15 <?php if(!acc_has_permission('acc_can_create_journal_entry')){echo 'hide';} ?>"><span data-feather="plus-circle" class="icon-16"></span> <?php echo app_lang('add'); ?></a>
            </div>
        </div>
        <div class="table-responsive">
            <table id="journal-entry-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>
<?php require 'plugins/Accounting/assets/js/journal_entry/manage_js.php';?>
