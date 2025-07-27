<script type="text/javascript">
$(document).ready(function () {
var fnServerParams;
var site_url = $('input[name="site_url"]').val();

(function($) {
		"use strict";

		fnServerParams = {
      "from_date": '[name="from_date"]',
      "to_date": '[name="to_date"]',
    };
    init_journal_entry_table();
    $('input[name="from_date"]').on('change', function() {
      init_journal_entry_table();
    });

    $('input[name="to_date"]').on('change', function() {
      init_journal_entry_table();
    });

	$("input[data-type='currency']").on({
      keyup: function() {
        formatCurrency($(this));
      },
      blur: function() {
        formatCurrency($(this), "blur");
      }
    });
})(jQuery);

function init_journal_entry_table() {
  "use strict";

  $('#journal-entry-table').appTable({
    source: site_url + 'accounting/journal_entry_table',
            dateRangeType: "",
            rangeDatepicker: [{startDate: {name: "from_date", value: ""}, endDate: {name: "to_date", value: ""}, showClearButton: true}],
            columns: [
              {title: "<?php echo app_lang("journal_date") ?>"},
              {title: "<?php echo app_lang("number") ?> - <?php echo app_lang("description") ?>"},
              {title: "<?php echo app_lang("amount") ?>"},
              {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center dropdown-option w10p"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3]),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3])
  });
}


// journal entry bulk actions action
function bulk_action(event) {
  "use strict";
    if (confirm_delete()) {
        var ids = [],
            data = {};
            data.mass_delete = $('#mass_delete').prop('checked');

        var rows = $($('#journal_entry_bulk_actions').attr('data-table')).find('tbody tr');

        $.each(rows, function() {
            var checkbox = $($(this).find('td').eq(0)).find('input');
            if (checkbox.prop('checked') === true) {
                ids.push(checkbox.val());
            }
        });
        data.ids = ids;
        $(event).addClass('disabled');
        setTimeout(function() {
            $.post(admin_url + 'accounting/journal_entry_bulk_action', data).done(function() {
                window.location.reload();
            });
        }, 200);
    }
}
});
</script>