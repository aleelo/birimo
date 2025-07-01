<?php echo form_open(get_uri("estimates/save_item"), array("id" => "invoice-item-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        <input type="hidden" id="item_id" name="item_id" value="" />
        <input type="hidden" name="estimate_id" value="<?php echo $estimate_id; ?>" />
        <input type="hidden" name="add_new_item_to_library" value="" id="add_new_item_to_library" />
        <input type="hidden" name="new_account" value="" id="new_account" />
        <input type="hidden" name="is_section" value="1" />

         <div class="form-group">
            <div class="row">
                <label for="section_name" class="col-md-3"><?php echo app_lang('section_name'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "section_name",
                        "name" => "section_name",
                        "value" => $model_info->title ? $model_info->title : "",
                        "class" => "form-control",
                        "placeholder" => app_lang('section_name')
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
    $(document).ready(function () {
        $("#invoice-item-form").appForm({
            onSuccess: function (result) {
                $("#estimate-item-table").appTable({newData: result.data, dataId: result.id});
                $("#estimate-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            }
        });

        //show item suggestion dropdown when adding new item
        var isUpdate = "<?php echo $model_info->id; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }

        //re-initialize item suggestion dropdown on request
        $("#invoice_item_title_dropdwon_icon").click(function () {
            applySelect2OnItemTitle();
        })

    });

    function applySelect2OnItemTitle() {
        $("#invoice_item_title").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("invoices/get_invoice_item_suggestion"); ?>",
                type: 'POST',
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term // search term
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            }
        }).change(function (e) {
            if (e.val === "+") {
                //show simple textbox to input the new item
                $("#invoice_item_title").select2("destroy").val("").focus();
                $("#add_new_item_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_library").val(""); //reset the flag to add new item in library
                $.ajax({
                    url: "<?php echo get_uri("invoices/get_invoice_item_info_suggestion"); ?>",
                    data: {item_id: e.val},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {
                            $("#item_id").val(response.item_info.id);
                            $("#invoice_item_title").val(response.item_info.title);

                            $("#invoice_item_description").val(response.item_info.description);

                            $("#invoice_unit_type").val(response.item_info.unit_type);

                            $("#invoice_item_rate").val(response.item_info.rate);

                            $("#invoice_item_quantity").val(response.item_info.quantity ? to_decimal_format(response.item_info.quantity) : "");
                            $("#estimate_item_account_id").val(response.item_info.account_id ? response.item_info.account_id : "");

                            if (response.item_info.taxable == 1) {
                                $("#taxable").prop("checked", true);
                            } else {
                                $("#taxable").prop("checked", false);
                            }
                            if (response.item_info.supplier == 1) {
                                $("#supplier").prop("supplier", true);
                            } else {
                                $("#supplier").prop("supplier", false);
                            }
                        }
                    }
                });
            }

        });
    }
    $("#supplier_id").select2();
    function applySelect2OnAccountDropdown() {
    $("#estimate_item_account_id").select2({
        ajax: {
            url: "<?php echo get_uri("estimates/get_estimate_account_suggestion"); ?>",
            data: function (params) {
                return {
                    c: params.term // search term
                };
            },
            type: 'POST',
            dataType: 'json',
            quietMillis: 250,
            data: function (term, page) {
                return {
                    q: term
                };
            },
            results: function (data, page) {
                return { results: data };
            }
        }
    }).change(function (e) {
        if (e.val === "+") {
            $("#estimate_item_account_id").select2("destroy").val("").focus();
            $("#new_account").val(1);
        }
    });
}
applySelect2OnAccountDropdown();

$("#account_id_dropdown_icon").click(function () {
    applySelect2OnAccountDropdown();
});

    toggleSupplierFields();

$("#supplier").change(function () {
    toggleSupplierFields(); 
});

function toggleSupplierFields() {
    if ($("#supplier").is(":checked")) {
        $("#cheked").removeClass("hide");
    } else {
        $("#cheked").addClass("hide");
    }
}
</script>