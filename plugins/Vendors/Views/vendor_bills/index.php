<?php
// Vendor Bills — List page (no dark header)
// expects: $can_add_new_payments, $can_add_new_invoices, $tab, $status,
// $currencies_dropdown, $custom_field_filters, $custom_field_headers, $conversion_rate
?>

<style>
    /* light, minimal tweaks only */
    .card.clearfix {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, .06);
        border-radius: 16px;
        box-shadow: 0 10px 24px rgba(2, 8, 23, .05);
    }

    #invoices-tabs.nav {
        border-bottom: none;
        padding: 6px 8px;
    }

    #invoices-tabs .title-tab h4 {
        margin: 0;
        font-weight: 800;
        color: #0f172a;
    }

    #invoices-tabs .tab-title {
        margin-left: auto;
    }

    .title-button-group {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .title-button-group .btn {
        border-radius: 12px;
    }

    #invoice-list-table {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
    }

    .actions-inline {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        justify-content: center
    }

    .action-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 9999px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px rgba(2, 8, 23, .05);
        transition: background .15s, border-color .15s, transform .15s;
    }

    .action-chip svg {
        width: 16px;
        height: 16px;
        stroke: #334155
    }

    .action-chip:hover {
        background: #eef2ff;
        border-color: #c7d2fe;
        transform: translateY(-1px)
    }

    .action-chip:hover svg {
        stroke: #1d4ed8
    }

    /* Delete tone */
    .action-chip--danger svg {
        stroke: #b91c1c
    }

    .action-chip--danger:hover {
        background: #fef2f2;
        border-color: #fecaca
    }

    .action-chip--danger:hover svg {
        stroke: #dc2626
    }
</style>

<div id="page-content" class="page-wrapper clearfix grid-button">
    <div class="card clearfix">
        <ul id="invoices-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab">
                <h4 class="pl15 pt10 pr10"><?php echo app_lang("vendor_bills"); ?></h4>
            </li>

            <li>
                <a role="presentation" data-bs-toggle="tab" href="javascript:;" data-bs-target="#invoices-list">
                    <?php echo app_lang("list"); ?>
                </a>
            </li>

            <div class="tab-title clearfix no-border invoices-view">
                <div class="title-button-group pr10">
                    <?php if ($can_add_new_vendor_bill_payment) { ?>
                        <?php echo modal_anchor(
                            get_uri("vendor_bill_payment/payment_modal_form"),
                            "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_payment'),
                            ["class" => "btn btn-default mb0", "title" => app_lang('add_payment')]
                        ); ?>
                    <?php } ?>
                    <?php if ($can_add_vendor_bills) { ?>
                        <a href="<?php echo get_uri('vendor_bills/create'); ?>"
                            class="btn btn-primary mb0"
                            onclick="event.stopPropagation(); window.location=this.href; return false;">
                            <span data-feather="plus-circle" class="icon-16"></span>
                            <?php echo ('Add Vendor Bills'); ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="invoices-list">
                <div class="table-responsive">
                    <table id="invoice-list-table" class="display" cellspacing="0" width="100%"></table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="recurring-invoices"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        if (window.feather) feather.replace();

        var ignoreSavedFilter = false;
        var optionVisibility = false;
        if ("<?php echo $can_update_vendor_bill ?>") {
            optionVisibility = true;
        }

        var idColumnClass = isMobile() ? "" : "w10p";

        var RangeButtonSelectedOption = 'yearly';
        var tab = "<?php echo $tab; ?>";
        if (tab === "custom") {
            ignoreSavedFilter = true;
            RangeButtonSelectedOption = 'custom';
        }

        var status = "<?php echo $status; ?>";
        var vendor_bills_statuses_dropdown = <?php echo view("Vendors\Views/vendor_bills/invoice_statuses_dropdown"); ?>;
        if (status !== "") {
            var idx = vendor_bills_statuses_dropdown.findIndex(function(x) {
                return x.id === status;
            });
            if (idx > -1) {
                vendor_bills_statuses_dropdown[idx].isSelected = true;
                ignoreSavedFilter = true;
            }
        }

        $("#invoice-list-table").appTable({
            source: '<?php echo_uri("vendor_bills/list_data") ?>',
            order: [
                [0, "desc"]
            ],
            smartFilterIdentity: 'invoice_list',
            ignoreSavedFilter: ignoreSavedFilter,
            rangeRadioButtons: [{
                name: "range_radio_button",
                options: ['monthly', 'yearly', 'custom', 'dynamic'],
                dynamicRanges: ['this_month', 'last_month', 'next_month', 'this_year', 'last_year']
            }],
            filterDropdown: [{
                    name: "status",
                    class: "w150",
                    options: vendor_bills_statuses_dropdown
                }
                <?php if (!empty($vendors_dropdown)) { ?>,
                    {
                        name: "vendor_id",
                        class: "w180",
                        options: <?php echo $vendors_dropdown; ?> // << JSON here
                    }
                <?php } ?>
                <?php if ($currencies_dropdown) { ?>,
                    {
                        name: "currency",
                        class: "w150",
                        options: <?php echo $currencies_dropdown; ?>
                    }
                <?php } ?>,
                <?php echo $custom_field_filters; ?>
            ],
            columns: [{
                    visible: false,
                    searchable: false
                }, // 0 hidden id/sort
                {
                    title: "<?php echo app_lang("bill_number") ?>",
                    class: idColumnClass + " all",
                    iDataSort: 0
                }, // 1
                {
                    title: "<?php echo app_lang("vendor") ?>",
                    class: "all"
                }, // 2
                {
                    visible: false,
                    searchable: false
                }, // 3 hidden (client id or similar)
                {
                    title: "<?php echo app_lang("bill_date") ?>",
                    class: "w10p",
                    iDataSort: 4
                }, // 4
                // (removed extra hidden col that used to sit here)
                {
                    title: "<?php echo app_lang("total_invoiced") ?>",
                    class: "w10p text-right"
                }, // 5
                {
                    title: "<?php echo app_lang("payment_received") ?>",
                    class: "w10p text-right"
                }, // 6
                {
                    title: "<?php echo app_lang("due") ?>",
                    class: "w10p text-right"
                }, // 7
                {
                    title: "<?php echo app_lang("status") ?>",
                    class: "w10p text-center"
                } // 8
                <?php echo $custom_field_headers; ?>,
                // {
                //     title: '<i data-feather="menu" class="icon-16"></i>',
                //     class: "text-center w100",
                //     visible: optionVisibility
                // }
                {
                    title: '<i data-feather="menu" class="icon-16"></i>',
                    class: "text-center w80",
                    visible: optionVisibility
                }

            ],
            printColumns: combineCustomFieldsColumns([1, 2, 4, 5, 6, 7, 8], '<?php echo $custom_field_headers; ?>'),
            xlsColumns:   combineCustomFieldsColumns([1, 2, 4, 5, 6, 7, 8], '<?php echo $custom_field_headers; ?>'),
            summation: [{
                    column: 5,
                    dataType: 'currency',
                    currencySymbol: AppHelper.settings.currencySymbol,
                    conversionRate: <?php echo $conversion_rate; ?>
                },
                {
                    column: 6,
                    dataType: 'currency',
                    currencySymbol: AppHelper.settings.currencySymbol,
                    conversionRate: <?php echo $conversion_rate; ?>
                },
                {
                    column: 7,
                    dataType: 'currency',
                    currencySymbol: AppHelper.settings.currencySymbol,
                    conversionRate: <?php echo $conversion_rate; ?>
                }
            ]
        });

        // After your $("#invoice-list-table").appTable({...})
        $("#invoice-list-table").on("init.dt draw.dt", function() {
            if (window.feather) feather.replace();
            // enable tooltips if you want titles to show
            if (window.bootstrap && $('[data-bs-toggle="tooltip"]').length) {
                $('[data-bs-toggle="tooltip"]').each(function() {
                    new bootstrap.Tooltip(this);
                });
            }
        });

    });
</script>