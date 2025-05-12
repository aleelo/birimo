<?php echo form_open(get_uri("invoices/save_delivery_note"), array("id" => "add-page-form", "class" => "general-form bg-white", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
      
        <div class="form-group">
            <div class="row">
                <label for="delivery_note" class=" col-md-2"><?php echo app_lang('delivery_note'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_textarea(array(
                        "id" => "delivery_note",
                        "name" => "delivery_note",
                        "value" => process_images_from_content($model_info->delivery_note, false),
                        "class" => "form-control",
                        "data-toolbar" => "page_builder_toolbar",
                        "data-encode_ajax_post_data" => "1",
                        "data-height" => 350,
                        "data-encode_ajax_post_data" => "1"
                    ));
                    ?>
                </div>
            </div>
        </div>
   
        
        
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#add-page-form").appForm({
            onSuccess: function (result) {
                $("#pages-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        initWYSIWYGEditor("#delivery_note");
        setTimeout(function () {
            $("#title").focus();
        }, 200);
        $("#add-page-form .select2").select2();

        //show/hide visible to details area
   

       
    });
</script>    