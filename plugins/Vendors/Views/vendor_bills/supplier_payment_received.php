<div id="page-content" class="page-wrapper clearfix">
    <div class="card clearfix">
        <ul id="payment-received-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white inner title" role="tablist">
            <li class="title-tab">
                <h4 class="pl15 pt10 pr15"><?php echo ("supplier payment"); ?></h4>
            </li>
            <li><a role="presentation" data-bs-toggle="tab" href="javascript:;" data-bs-target="#payments-list"><?php echo app_lang("list"); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("invoice_supplier_payments/yearly_chart/"); ?>" data-bs-target="#yearly-chart"><?php echo app_lang('chart'); ?></a></li>

            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php if ($can_edit_invoices) { ?>
                        <?php echo modal_anchor(get_uri("invoice_supplier_payments/payment_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_payment'), array("class" => "btn btn-default mb0", "title" => app_lang('add_payment'))); ?>
                    <?php } ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="payments-list">
                <div class="table-responsive">
                    <table id="invoice-payment-table" class="display" width="100%">
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-chart"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#invoice-payment-table").appTable({
            source: '<?php echo_uri("invoice_supplier_payments/payment_list_data/") ?>',
            order: [
                [0, "asc"]
            ],
            smartFilterIdentity: "invoice_supplier_payments", //a to z and _ only. should be unique to avoid conflicts
            rangeRadioButtons: [{
                name: "range_radio_button",
                selectedOption: 'monthly',
                options: ['monthly', 'yearly', 'custom', 'dynamic'],
                dynamicRanges: ['this_month', 'last_month', 'next_month', 'this_year', 'last_year']
            }],
            filterDropdown: [{
                    name: "payment_method_id",
                    class: "w200",
                    options: <?php echo $payment_method_dropdown; ?>
                },
                // Branch functionality removed - not needed in vendors plugin
                // {
                //     name: "branch_id",
                //     class: "w150",
                //     options: <?php echo $payment_dropdown;; ?>
                // },
                <?php if ($currencies_dropdown) { ?> {
                        name: "currency",
                        class: "w150",
                        options: <?php echo $currencies_dropdown; ?>
                    },
                <?php } ?>
                <?php if ($projects_dropdown) { ?> {
                        name: "project_id",
                        class: "w200",
                        options: <?php echo $projects_dropdown; ?>
                    }
                <?php } ?>
            ],
            columns: [{
                    title: '<?php echo app_lang("invoice_id") ?> ',
                    "class": "w10p all"
                },
                {
                    visible: false,
                    searchable: false
                },
                {
                    title: '<?php echo app_lang("payment_date") ?> ',
                    "class": "w15p",
                    "iDataSort": 1
                },
                {
                    title: '<?php echo app_lang("payment_method") ?>',
                    "class": "w15p"
                },
                // Branch functionality removed - not needed in vendors plugin
                // {
                //     title: "<?php echo app_lang("branch_plugin") ?>"
                // },
                {
                    title: '<?php echo app_lang("note") ?>'
                },
                {
                    title: '<?php echo app_lang("amount") ?>',
                    "class": "text-right w15p all"
                }
            ],
            summation: [{
                column: 6,
                dataType: 'currency',
                currencySymbol: AppHelper.settings.currencySymbol,
                conversionRate: <?php echo $conversion_rate; ?>
            }],
            printColumns: [0, 2, 3, 4, 5],
            xlsColumns: [0, 2, 3, 4, 5]
        });
    });
</script>