<?php echo form_open(get_uri("expense_categories/save"), array("id" => "category-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

                <?php if ($has_all_permission){ ?>

        <div class="form-group">
        <div class="row">

                    <label for="company_id" class="col-md-3"><?php echo app_lang('company'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_dropdown("company_id", $companies_dropdown, $model_info->company_id, "class='select2 form-control validate-hidden' id='company_id' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
                
                    ?>
        </div>
        </div>
        </div>
            <?php } else{ ?>
                <input type="hidden" name="company_id" value="<?php echo $login_user->department; ?>">
                <?php } ?>

        <div class="form-group">
            <div class="row">
                <label for="title" class=" col-md-3"><?php echo app_lang('title'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "title",
                        "name" => "title",
                        "value" => $model_info->title,
                        "class" => "form-control",
                        "placeholder" => app_lang('title'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>


                <div class="form-group">
            <div class="row">
                <label for="expense_type" class="col-md-3"><?php echo ('Expense type'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown(
                        "expense_type",
                        array(
                            "" => ("-"),
                            "client" => app_lang("client"),
                            "supplier" => app_lang("supplier")
                        ),
                        $model_info->expense_type ?? "",
                        "class='form-control select2' id='expense_type' data-rule-required='true' data-msg-required='" . app_lang("field_required") . "'"
                    );
                    ?>
                </div>
            </div>
        </div>

          <?php if (class_exists('\Accounting\Models\Accounting_model')): ?>

        <div class="form-group">
            <div class="row">
                <label for="account_id" class="col-md-3"><?php echo app_lang('account'); ?></label>
                <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "account_id",
                    "name" => "account_id",
                    "value" => $model_info->account_id,
                    "class" => "form-control validate-hidden",
                    "placeholder" => ('select account'),
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required"),
                ));
                ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
                $("#account_id").select2({
        ajax: {
            url: "<?php echo get_uri("expense_categories/get_account_suggestion"); ?>",
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
    })
    $("#company_id").select2();
        $("#category-form").appForm({
            onSuccess: function (result) {
                $("#category-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $('#expense_type').select2();
        setTimeout(function () {
            $("#title").focus();
        }, 200);
    });
</script>