<div id="page-content" class="page-wrapper clearfix">
    <div class="card clearfix">
        <ul id="payment-received-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white inner title" role="tablist">
            <li class="title-tab">
                <h4 class="pl15 pt10 pr15"><?php echo app_lang("vendor_bill_payment_recieved"); ?></h4>
            </li>
            <li><a role="presentation" data-bs-toggle="tab" href="javascript:;" data-bs-target="#payments-list"><?php echo app_lang("list"); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("vendor_bill_payment/yearly_chart/"); ?>" data-bs-target="#yearly-chart"><?php echo app_lang('chart'); ?></a></li>

            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php if ($can_add_new_vendor_bill_payment) { ?>
                        <?php echo modal_anchor(get_uri("vendor_bill_payment/payment_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_vendor_payment'), ["class" => "btn btn-default mb0", "title" => app_lang('add_vendor_payment')]); ?>
                    <?php } ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="payments-list">
                <div class="table-responsive">
                    <table id="invoice-payment-table" class="display" width="100%"></table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-chart"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#invoice-payment-table").appTable({
            source: '<?php echo_uri("vendor_bill_payment/payment_list_data") ?>',
            order: [
                [0, "asc"]
            ],
            smartFilterIdentity: "vendor_bill_payments",
            rangeRadioButtons: [{
                name: "range_radio_button",
                // selectedOption: 'yearly',
                options: ['monthly', 'yearly', 'custom', 'dynamic'],
                dynamicRanges: ['this_month', 'last_month', 'next_month', 'this_year', 'last_year']
            }],
            filterDropdown: [{
                    name: "payment_method_id",
                    class: "w200",
                    options: <?php echo $payment_method_dropdown; ?>
                }
                <?php if ($projects_dropdown) { ?>,
                    {
                        name: "project_id",
                        class: "w200",
                        options: <?php echo $projects_dropdown; ?>
                    }
                <?php } ?>
            ],
            columns: [{
                    title: '<?php echo app_lang("bill_number") ?>',
                    "class": "w10p all"
                }, // 0
                {
                    visible: false,
                    searchable: false
                }, // 1 (hidden sort for date)
                {
                    title: '<?php echo app_lang("payment_date") ?>',
                    "class": "w15p",
                    "iDataSort": 1
                }, // 2
                // {
                //     title: "<?php// echo app_lang("branch_plugin") ?>"
                // }, // 3
                {
                    title: '<?php echo app_lang("payment_method") ?>',
                    "class": "w15p"
                }, // 4
                {
                    title: '<?php echo app_lang("note") ?>'
                }, // 5
                {
                    title: '<?php echo app_lang("amount") ?>',
                    "class": "text-right w15p all"
                }, // 6
                {
                    title: '<i data-feather="menu" class="icon-16"></i>',
                    "class": "text-center w100"
                } // 7
            ],
            summation: [{
                column: 5,
                dataType: 'currency',
                currencySymbol: AppHelper.settings.currencySymbol,
                conversionRate: <?php echo $conversion_rate; ?>
            }],
            printColumns: [0, 2, 3, 4],
            xlsColumns: [0, 2, 3, 4]
        });
    });
</script>