<div class="card">
    <div class="tab-title clearfix">
        <h4> <?php echo app_lang('invoice_payment_list'); ?></h4>
                <div class="title-button-group">
 <?php if ($invoice_status !== "cancelled" && $invoice_info->status !== "credited" && $can_edit_invoices) { ?>
                                <?php echo modal_anchor(get_uri("invoice_payments/payment_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_invoice_payment'), array("class" => "btn btn-default", "title" => app_lang('add_payment'), "data-post-invoice_id" => $invoice_info->id)); ?>
                            <?php } ?>
 </div>
    </div>
    <div class="table-responsive">
        <table id="invoice-payment-table" class="display" cellspacing="0" width="100%">            
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var optionVisibility = false;
        if ("<?php echo $can_edit_invoices ?>") {
            optionVisibility = true;
        }

        $("#invoice-payment-table").appTable({
            source: '<?php echo_uri("invoice_payments/payment_list_data/" . $invoice_id) ?>',
            order: [[0, "asc"]],
            filterDropdown:[
                <?php if ((get_array_value($login_user->permissions, "company") === "all") && $login_user->department== "0") 
 { ?>
           , {name: "can_view_all_invoice", class: "w200 ", options: <?php echo $company; ?>}

            <?php  }  ?>
            ],
            columns: [
                {targets: [0], visible: false, searchable: false},
                {visible: false, searchable: false},
                {title: '<?php echo app_lang("payment_date") ?> ', "class": "w15p all", "iDataSort": 1},
                {title: '<?php echo app_lang("payment_method") ?>', "class": "w15p all"},
                {title: '<?php echo app_lang("note") ?>'},
                {title: '<?php echo app_lang("amount") ?>', "class": "text-right w15p"},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100", visible: true}
            ],
            onDeleteSuccess: function (result) {
                $("#invoice-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            },
            onUndoSuccess: function (result) {
                $("#invoice-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            }
        });
    });
</script>