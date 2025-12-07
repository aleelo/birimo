<div id="page-content" class="page-wrapper clearfix grid-button">
    <div class="card">
        <div class="page-title clearfix items-page-title">
            <h1> <?php echo app_lang('vendor_items'); ?></h1>
            <div class="title-button-group">
                <?php
                // Use the variable passed from the controller instead of calling a method.
                if ($can_add_new_items) {
                    // echo modal_anchor(get_uri("vendor_items/import_modal_form"), "<i data-feather='upload' class='icon-16'></i> " . app_lang('import_items'), array("class" => "btn btn-default", "title" => app_lang('import_items')));
                    echo modal_anchor(get_uri("vendor_items/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_vendor_item'), array("class" => "btn btn-default", "title" => app_lang('add_vendor_item')));
                }
                ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="item-table" class="display" cellspacing="0" width="100%">
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#item-table").appTable({
            source: '<?php echo_uri("vendor_items/list_data") ?>',
            order: [
                [0, 'desc']
            ],
            filterDropdown: [
            <?php echo $custom_field_filters; ?>],
            columns: [{
                    title: "<?php echo app_lang('title') ?> ",
                    "class": "w20p all"
                },
                {
                    title: "<?php echo app_lang('description') ?>"
                },
                // {
                //     title: "<?php echo app_lang('category') ?>"
                // },
                {
                    title: "<?php echo app_lang('unit_type') ?>",
                    "class": "w100"
                },
                {
                    title: "<?php echo app_lang('rate') ?>",
                    "class": "text-right w100"
                }
                <?php echo $custom_field_headers; ?>,
                {
                    title: "<i data-feather='menu' class='icon-16'></i>",
                    "class": "text-center option w100"
                }
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3], '<?php echo $custom_field_headers; ?>')
        });
    });
</script>