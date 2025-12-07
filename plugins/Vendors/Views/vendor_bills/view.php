<div class="page-content invoice-details-view xs-full-width clearfix">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div id="invoice-details-top-bar">
                    <?php echo view("Sales_and_crm\Views/invoices/invoice_top_bar"); ?>
                </div>

                <div class="details-view-wrapper d-flex">
                    <div class="w-100">
                        <?php echo view("Sales_and_crm\Views/invoices/details"); ?>
                    </div>
                    <div class="flex-shrink-0 details-view-right-section">
                        <?php echo view("Sales_and_crm\Views/invoices/invoice_info"); ?>
                        <?php echo view("Sales_and_crm\Views/invoices/invoice_actions"); ?>

                        <?php if ($can_add_new_payments && $invoice_info->type == "invoice") {
                            echo view("Sales_and_crm\Views/invoices/payments/index");

                            if ($invoice_info->recurring) {
                                echo view("Sales_and_crm\Views/invoices/sub_invoices");
                            }
                        } ?>
                        <?php if ($can_add_new_supplier && $invoice_info->type == "invoice") {
                            echo view("Sales_and_crm\Views/invoices/payments/supplier_payment_index");

                            if ($invoice_info->recurring) {
                                echo view("Sales_and_crm\Views/invoices/sub_invoices");
                            }
                        } ?>
                        <!-- ✅ Injected Invoice Activity Logs Below -->
                        <?php if (!empty($invoice_info)) { ?>
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h4><?php echo app_lang("invoice_activity_logs"); ?></h4>
                                </div>
                                <div class="card-body">
                                    <?php
                                    activity_logs_widget([
                                        "log_type" => "item",           // logs of item updates
                                        "log_for" => "invoice",         // context is invoice
                                        "log_for_id" => $invoice_id     // filter by this invoice ID
                                    ]);
                                    ?>
                                </div>
                            </div>
                        <?php } ?>


                        <!-- ✅ End log card -->
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        //modify the delete confirmation texts
        $("#confirmationModalTitle").html("<?php echo app_lang('cancel') . "?"; ?>");
        $("#confirmDeleteButton").html("<i data-feather='x' class='icon-16'></i> <?php echo app_lang("cancel"); ?>");

        if (isMobile()) {
            $(".pdf-view-btn").addClass("d-none");

            $(".mobile-pdf-view-btn").on('click', function(e) {
                setTimeout(function() {
                    $(".app-modal-content-area").css({
                        "height": "100%",
                        "width": "100%"
                    });
                })
            })
        }

        <?php if ($can_edit_invoices) { ?>

            $('body').on('click', '[data-act=invoice-modifier]', function(e) {
                $(this).appModifier({
                    dropdownData: {
                        labels: <?php echo json_encode($label_suggestions); ?>
                    }
                });
                return false;
            });

        <?php } ?>


        appContentBuilder.init("<?php echo get_uri('invoices/view/' . $invoice_info->id); ?>", {
            id: "invoice-details-page-builder",
            data: {
                view_type: "invoice_meta"
            },
            reloadHooks: [{
                    type: "app_form",
                    id: "invoice-form"
                },
                {
                    type: "app_form",
                    id: "discount-form"
                },
                {
                    type: "app_form",
                    id: "invoice-payment-form"
                },
                {
                    type: "app_form",
                    id: "invoice-item-form"
                },
                {
                    type: "app_form",
                    id: "send-invoice-form"
                },
                {
                    type: "ajax_request",
                    group: "invoice_status"
                },
                {
                    type: "app_modifier",
                    group: "invoice_info"
                },
                {
                    type: "app_table_row_delete",
                    tableId: "invoice-details-page-payment-table"
                },
                {
                    type: "app_table_row_delete",
                    tableId: "invoice-item-table"
                }
            ],
            reload: function(bind, result) {
                bind("#invoice-details-top-bar", result.top_bar);
                bind("#invoice-total-section", result.invoice_total_section);
            }
        });

    });


    //print invoice
    $("#print-invoice-btn").click(function() {
        appLoader.show();

        $.ajax({
            url: "<?php echo get_uri('invoices/print_invoice/' . $invoice_info->id) ?>",
            dataType: 'json',
            success: function(result) {
                if (result.success) {
                    document.body.innerHTML = result.print_view; //add invoice's print view to the page
                    $("html").css({
                        "overflow": "visible"
                    });

                    setTimeout(function() {
                        window.print();
                    }, 200);
                } else {
                    appAlert.error(result.message);
                }

                appLoader.hide();
            }
        });
    });

    //reload page after finishing print action
    window.onafterprint = function() {
        location.reload();
    };
</script>