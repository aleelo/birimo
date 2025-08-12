<?php echo form_open(get_uri("expense_payments/save"), array("id" => "expense-payment-form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body">
    <input type="hidden" name="id" value="<?php echo $model_info->id ?? ''; ?>" />
    <input type="hidden" name="expense_id" value="<?php echo $expense_id ?? "";
                                                    ?>" />

    <!-- Vendor -->
    <!-- <div class="form-group">
        <div class="row">
            <label for="vendor_id" class="col-md-3"><?php echo 'Vendor'; ?></label>
            <div class="col-md-9">
                <?php
                // echo form_dropdown("vendor_id", $vendors_dropdown, "", "class='select2 form-control validate-hidden' id='vendor_id' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'");
                ?>
            </div>
        </div>
    </div> -->

    <!-- Category -->
    <!-- <div class="form-group">
        <div class="row">
            <label for="category_id" class="col-md-3"><?php echo app_lang('category'); ?></label>
            <div class="col-md-9">
                <?php
                // echo form_dropdown("category_id", $categories_dropdown, "", "class='select2 form-control validate-hidden' id='category_id' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'");
                ?>
            </div>
        </div>
    </div> -->

    <!-- Expense -->
    <?php if (empty($hide_expense_dropdown)) : ?>
        <div class="form-group">
            <div class="row">
                <label for="expense_id" class="col-md-3"><?php echo app_lang('expense'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown(
                        "expense_id",
                        $expenses_dropdown,
                        $model_info->expense_id ?? "",
                        "class='select2 form-control validate-hidden' id='expense_id' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'"
                    );
                    ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <input type="hidden" name="expense_id" value="<?php echo $expense_id; ?>" />
    <?php endif; ?>


    <!-- Payment Date -->
    <div class="form-group">
        <div class="row">
            <label for="payment_date" class="col-md-3"><?php echo app_lang('payment_date'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input([
                    "id" => "payment_date",
                    "name" => "payment_date",
                    "value" => $model_info->payment_date ?? "",
                    "class" => "form-control datepicker",
                    "placeholder" => app_lang('payment_date'),
                    "required" => true
                ]);
                ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <label for="invoice_payment_amount" class=" col-md-3"><?php echo app_lang('amount'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "invoice_payment_amount",
                    "name" => "invoice_payment_amount",
                    "value" => $amount,
                    "class" => "form-control",
                    "placeholder" => app_lang('amount'),
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required"),
                ));
                ?>
            </div>
        </div>
    </div>
    <!-- Payment Method -->
    <div class="form-group">
        <div class="row">
            <label for="payment_method" class="col-md-3"><?php echo app_lang('payment_method'); ?></label>
            <div class="col-md-9">
                <?php
                // helper('cookie');
                echo form_dropdown("payment_method", $payment_methods_dropdown, $model_info->payment_method ?? "", "class='select2 form-control validate-hidden' id='payment_method' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'");
                ?>
            </div>
        </div>
    </div>

    <!-- Deposit To -->
    <!-- <div class="form-group">
    <div class="row">

        <label for="deposit_to" class="col-md-3"><?php echo ('deposit_to'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_dropdown("deposit_to", $accounts_dropdown, $model_info->deposit_to ?? "", "class='select2 form-control validate-hidden' id='deposit_to' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
            ?>
        </div>
    </div>
</div> -->

    <!-- Note -->
    <div class="form-group">
        <div class="row">
            <label for="note" class="col-md-3"><?php echo app_lang('note'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_textarea([
                    "id" => "note",
                    "name" => "note",
                    "value" => $model_info->note ?? "",
                    "class" => "form-control",
                    "placeholder" => app_lang('note'),
                    "rows" => 3
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-primary"><?php echo app_lang('save'); ?></button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo app_lang('close'); ?></button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {

        let fixedExpenseId = "<?php echo $expense_id ?? ''; ?>";

        if (fixedExpenseId) {
            $("#expense-payment-form").appForm({
                onSuccess: function(result) {
                    $("#expense-table").appTable({
                        reload: true
                    });
                }
            });
        } else {
            $("#expense-payment-form").appForm({
                onSuccess: function(result) {
                    $("#expense-payment-table").appTable({
                        reload: true
                    });
                }
            });
        }
        setDatePicker("#payment_date");
        $("#vendor_id, #category_id, #expense_id, #payment_method,#deposit_to").select2();


        if (fixedExpenseId) {
            $.ajax({
                url: "<?php echo get_uri('expense_payments/get_invoice_payment_amount_suggestion'); ?>/" + fixedExpenseId,
                cache: false,
                type: 'POST',
                dataType: "json",
                success: function(response) {
                    if (response && response.success) {
                        $("#invoice_payment_amount").val(response.invoice_total_summary.balance_due);
                    }
                }
            });
        }

        $("#expense_id").on("change", function() {
            var expense_id = $(this).val();
            if (expense_id) {
                $.ajax({
                    url: "<?php echo get_uri('expense_payments/get_invoice_payment_amount_suggestion'); ?>/" + expense_id,
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function(response) {
                        if (response && response.success) {
                            $("#invoice_payment_amount").val(response.invoice_total_summary.balance_due);
                        }
                    }
                });
            }
        });
    });
</script>