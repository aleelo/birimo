<?php echo form_open(get_uri("invoices/save_section"), array("id" => "invoice-section-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo isset($model_info->id) ? $model_info->id : ""; ?>" />
        <input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>" />

        <div class="form-group">
            <div class="row">
                <label for="section_name" class="col-md-3"><?php echo ('section name'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "section_name",
                        "name" => "section_name",
                        "value" => isset($model_info->title) ? $model_info->title : "",
                        "class" => "form-control",
                        "placeholder" => ('section name')
                    ));
                    ?>
                </div>
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
    $("#invoice-section-form").appForm({
        onSuccess: function(result) {
            const dt = $("#invoice-item-table").DataTable();

            // 1) reload rows only (no full page refresh)
            dt.ajax.reload(function() {
                // keep sort ascending so newest (max sort) stays at the bottom
                dt.order([0, "asc"]).draw(false);
            }, /* resetPaging */ false);

            // 2) force-close & clean the Bootstrap modal so no UI remains
            const $m = $("#ajaxModal");
            $m.modal("hide");

            // when fully hidden, clear content to avoid leftovers
            $m.one("hidden.bs.modal", function() {
                $m.removeData("bs.modal");
                $m.find(".modal-content").empty();
            });

            // safety: remove any stray backdrops/body state
            $(".modal-backdrop").remove();
            $("body").removeClass("modal-open");

            // 3) prevent any default modal behavior from appForm
            return false;
        }
    });
</script>