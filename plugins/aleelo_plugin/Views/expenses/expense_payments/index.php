<div id="page-content" class="page-wrapper clearfix grid-button">
<div class="card">
    <div class="tab-title clearfix">
        <h4><?php echo app_lang('expense_payments'); ?></h4>
                <div class="title-button-group">
            <?php echo modal_anchor(get_uri("expense_payments/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_expense_payment'), array("class" => "btn btn-default", "title" => app_lang('add_expense_payment'), "data-post-invoice_id" => $expense_id)); ?>

    </div>

    </div>

    <div class="table-responsive">
        <table id="expense-payment-table" class="display" cellspacing="0" width="100%">            
        </table>
    </div>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#expense-payment-table").appTable({
            source: '<?= get_uri("expense_payments/datatable/" . ($expense_id ?? 0)); ?>',
            order: [[0, "desc"]],
            columns: [
                {title: '<?= app_lang("expense_id") ?>', "class": "w10p"},
                {visible: false, searchable: false},
                {title: '<?= app_lang("payment_date") ?>', "class": "w15p"},
                {title: '<?= app_lang("note") ?>'},
                {title: '<?= app_lang("amount") ?>', "class": "text-right w15p"},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w200"}
            ],
            printColumns: [0, 2, 3, 4],
            xlsColumns: [0, 2, 3, 4],
            summation: [{column: 4, dataType: 'currency'}]
        });
    });
</script>
