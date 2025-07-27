
<div id="page-content" class="page-wrapper clearfix">
    <?php echo form_hidden('site_url', get_uri()); ?>
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo html_entity_decode($title); ?></h1>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-registers" id="registers-table">
                
              </table>
            </div>
        </div>
    </div>
</div>

<?php require 'plugins/Accounting/assets/js/registers/manage_js.php'; ?>
