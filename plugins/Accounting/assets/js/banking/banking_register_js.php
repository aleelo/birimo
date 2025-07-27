<script type="text/javascript">
$(document).ready(function () {
    var site_url = $('input[name="site_url"]').val();
var fnServerParams = {
 "from_date": '[name="from_date"]',
 "to_date": '[name="to_date"]',
 "bank_account": '[name="bank_account"]',
 "status": '[name="status"]',
};

var id, type, amount, transaction_banking_id ;

(function($) {
	"use strict";
	init_banking_table();

  $('select[name="bank_account"]').on('change', function() {
    var bank_id = $(this).val();
    requestGet(site_url+ 'accounting/check_plaid_connect/' + bank_id).done(function(response) {
      response = JSON.parse(response);
      if(response === true || response === 'true'){
        $('#update_bank_transactions').removeAttr('disabled');
        $('#update_bank_transactions').attr('href', admin_url+'accounting/plaid_bank_new_transactions?id='+bank_id);
      }else{
        $('#update_bank_transactions').attr('disabled', true);
      }
    });
  });
})(jQuery);

function init_banking_table() {
  "use strict";

 $('.table-banking-registers').appTable({
    source: site_url + 'accounting/banking_register_table',
            multiSelect: [
              {
                name: "status",
                text: "<?php echo app_lang('status'); ?>",
                options: [
                    {text: '<?php echo app_lang("cleared") ?>', value: "converted"},
                    {text: '<?php echo app_lang("uncleared") ?>', value: "has_not_been_converted"},
                ]
              }
            ],
            filterDropdown: [
              {name: "bank_account", class: "w200", options: <?php echo html_entity_decode($bank_account_dropdown); ?>},
            ],
            rangeDatepicker: [{startDate: {name: "from_date", value: ""}, endDate: {name: "to_date", value: ""}, showClearButton: true}],
            columns: [
              {title: "<?php echo app_lang("date") ?>"},
              {title: "<?php echo app_lang("payee") ?>"},
              {title: "<?php echo app_lang("description") ?>"},
              {title: "<?php echo app_lang("withdrawals") ?>"},
              {title: "<?php echo app_lang("deposits") ?>"},
              {title: '<?php echo app_lang("cleared") ?>', "class": "text-center option w100"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6]),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6])
  });

}
  });
</script>
