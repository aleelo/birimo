<div id="page-content" class="page-wrapper clearfix grid-button">
    <div class="card">
        <div class="page-title clearfix items-page-title">
            <h1> <?php echo app_lang('supplier'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("supplier/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_supplier'), array("class" => "btn btn-default", "title" => app_lang('add_supplier'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="item-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#item-table").appTable({
            source: '<?php echo_uri("supplier/list_data") ?>',
            order: [[0, 'desc']],
            filterDropdown: [
            ],
            columns: [
                {title: "<?php echo app_lang('id') ?> ", "class": "w20p all"},
                {title: "<?php echo app_lang('supplier_name') ?>"},
                {title: "<?php echo app_lang('company') ?>,"},
                {title: "<?php echo app_lang('phone') ?>", "class": "w200"},
                {title: "<?php echo app_lang('email') ?>", "class": "w100"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4]
        });
    });
</script>