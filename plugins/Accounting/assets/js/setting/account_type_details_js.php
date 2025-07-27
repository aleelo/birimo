<script type="text/javascript">
  var site_url = $('input[name="site_url"]').val();
  var admin_url = $('input[name="site_url"]').val();
$(document).ready(function () { 
  var fnServerParams;

  (function($) {
  		"use strict";
      $(".select2").select2();

      init_account_type_details_table();
      
      $('.add-new-account-type-detail').on('click', function(){
        $('#account-type-detail-modal').find('button[type="submit"]').prop('disabled', false);

        $('input[name="name"]').val('');

        $('textarea[name="note"]').val('');
        $('input[name="id"]').val('');
        $('#account-type-detail-modal').modal('show');
      });
  })(jQuery);
});

function init_account_type_details_table() {
  "use strict";

  $('.table-account-type-details').appTable({
    source: site_url + 'accounting/account_type_details_table',
            columns: [
              {title: "<?php echo app_lang("title") ?>"},
              {title: "<?php echo app_lang("account_type") ?>"},
              {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4]),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4])
  });
}


function edit_account_type_detail(id) {
  "use strict";
    $('#account-type-detail-modal').find('button[type="submit"]').prop('disabled', false);

  requestGetJSON(admin_url + 'accounting/get_data_account_type_detail/'+id).done(function(response) {
      $('#account-type-detail-modal').modal('show');

      $('select[name="account_type_id"]').val(response.account_type_id).change();
      $('select[name="statement_of_cash_flows"]').val(response.statement_of_cash_flows).change();
      
      $('input[name="name"]').val(response.name);
      $('input[name="id"]').val(id);

      $('textarea[name="note"]').val(response.note);
  });
}

function account_type_detail_form_handler(form) {
    "use strict";
    $('#account-type-detail-modal').find('button[type="submit"]').prop('disabled', true);

    var formURL = form.action;
    var formData = new FormData($(form)[0]);

    $.ajax({
        type: $(form).attr('method'),
        data: formData,
        mimeType: $(form).attr('enctype'),
        contentType: false,
        cache: false,
        processData: false,
        url: formURL
    }).done(function(response) {
        response = JSON.parse(response);
        if (response.success === true || response.success == 'true' || $.isNumeric(response.success)) {
        appAlert.success(response.message);

	 		    init_account_type_details_table();
        }else{
        appAlert.error(response.message);
        }
        $('#account-type-detail-modal').modal('hide');
    }).fail(function(error) {
        appAlert.error(JSON.parse(error.mesage));
    });

    return false;
}
</script>