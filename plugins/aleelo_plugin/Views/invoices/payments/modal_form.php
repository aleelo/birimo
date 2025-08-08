<?php echo form_open(get_uri("expense_payments/save"), array("id" => "expense-payment-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">

    <?php echo form_hidden("id", $model_info->id ?? ""); ?>
    <?php echo form_hidden("expense_id", $expense_id ?? $model_info->expense_id); ?>

    <div class="form-group">
        <label for="amount" class="col-md-12"><?php echo app_lang('amgount'); ?></label>
        <div class="col-md-12">
            <?php
            echo form_input([
                "id" => "amount",
                "name" => "amount",
                "value" => $model_info->amount ?? "",
                "class" => "form-control",
                "placeholder" => app_lang('amount'),
                "data-rule-required" => true,
                "data-msg-required" => app_lang("field_required"),
            ]);
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="payment_date" class="col-md-12"><?php echo app_lang('payment_date'); ?></label>
        <div class="col-md-12">
            <?php
            echo form_input([
                "id" => "payment_date",
                "name" => "payment_date",
                "value" => $model_info->payment_date ?? get_today_date(),
                "class" => "form-control",
                "placeholder" => app_lang('payment_date'),
                "autocomplete" => "off",
                "data-rule-required" => true,
                "data-msg-required" => app_lang("field_required"),
            ]);
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="payment_method" class="col-md-12"><?php echo app_lang('payment_method'); ?></label>
        <div class="col-md-12">
            <?php
            echo form_input([
                "id" => "payment_method",
                "name" => "payment_method",
                "value" => $model_info->payment_method ?? "",
                "class" => "form-control",
                "placeholder" => app_lang('payment_method')
            ]);
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="deposit_to" class="col-md-12"><?php echo app_lang('deposit_to'); ?></label>
        <div class="col-md-12">
            <?php
            echo form_dropdown("deposit_to", $accounts_dropdown ?? [], $model_info->deposit_to ?? "", "class='form-control'");
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="note" class="col-md-12"><?php echo app_lang('note'); ?></label>
        <div class="col-md-12">
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

<div class="modal-footer">
    <button class="btn btn-default" data-bs-dismiss="modal" type="button"><span class="fa fa-close"></span> <?php echo app_lang('close'); ?></button>
    <button class="btn btn-primary" type="submit"><span class="fa fa-check-circle"></span> <?php echo app_lang('save'); ?></button>
</div>

<?php echo form_close(); ?>
