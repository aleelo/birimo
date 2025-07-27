<script type="text/javascript">
var fnServerParams;
var site_url = $('input[name="site_url"]').val();
(function($) {
		"use strict";

		fnServerParams = {
    };
    init_banking_rules_table();
    
})(jQuery);

function init_banking_rules_table() {
  "use strict";

  $('.table-banking-rules').appTable({
        source: site_url + 'accounting/banking_rules_table',
                columns: [
                  {title: "<?php echo app_lang('name'); ?>"},
                  {title: "<?php echo app_lang('transaction'); ?>"},
                  {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
                ],
                printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6]),
                xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6])
      });
}

</script>