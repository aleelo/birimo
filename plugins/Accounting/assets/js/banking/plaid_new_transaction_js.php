<script type="text/javascript">
    var site_url = $('input[name="site_url"]').val();
    var admin_url = $('input[name="site_url"]').val();
$(document).ready(function () {

var fnServerParams = {
     "bank_account": '[name="bank_account"]',
    };

(function($) {
"use strict";

    $(".select2").select2();
    setDatePicker("#from_date");

    init_banking_table();

    $('select').on('change', function() {

        var bank_id = this.value;

        let here = new URL(window.location.href);

        console.log(here);

        here.searchParams.append('id', bank_id);

        window.location.href = here

    });

})(jQuery);

function init_banking_table() {
  "use strict";

 $('.table-banking').appTable({
    source: site_url + 'accounting/import_banking_table',
            
            filterParams: {bank_account: "<?php echo (isset($_GET['id']) ? $_GET['id'] :""); ?>",},
            
            columns: [
              {title: "<?php echo app_lang("date") ?>"},
              {title: "<?php echo app_lang("payee") ?>"},
              {title: "<?php echo app_lang("description") ?>"},
              {title: "<?php echo app_lang("withdrawals") ?>"},
              {title: "<?php echo app_lang("deposits") ?>"},
              {title: '<?php echo app_lang("imported_date") ?>', "class": "text-center option w100"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6]),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6])
  });
}



var csrf = $('input[name="csrf_token"]').val();

var bankId = $('select').val();



(async function() {

const fetchLinkToken = async () => {

    const response = await fetch(admin_url + 'accounting/create_plaid_token');

    const responseJSON = await response.json();

    return responseJSON.link_token;
};



const configs = {

    // 1. Pass a new link_token to Link.

    token: await fetchLinkToken(),

    onSuccess: async function(public_token, metadata) {

    // 2a. Send the public_token to your app server.

    // The onSuccess function is called when the user has successfully

    // authenticated and selected an account to use.

    const successMsg = await fetch(admin_url+'accounting/update_plaid_bank_accounts?public_token='+ public_token + '&bankId='+ bankId, {

    });

    const successJSON = await successMsg.json();

    if(successJSON.error == ''){
        window.location.reload();
    }else{
        window.location.reload();
    }
    setTimeout(function() {
        location.reload();
    }, 2000);
    },

    onExit: async function(err, metadata) {

    // 2b. Gracefully handle the invalid link token error. A link token

    // can become invalidated if it expires, has already been used

    // for a link session, or is associated with too many invalid logins.

    if (err != null && err.error_code === 'INVALID_LINK_TOKEN') {

        linkHandler.destroy();

        linkHandler = Plaid.create({

        ...configs,

        token: await fetchLinkToken(),

        });

    }

    if (err != null) {

    // Handle any other types of errors.

    }

    appAlert.error('Connection failed, please check your settings: Setting -> Plald environment');


    // metadata contains information about the institution that the

    // user selected and the most recent API request IDs.

    // Storing this information can be helpful for support.

    },

};

var linkHandler = Plaid.create(configs);

    document.getElementById('linkButton').onclick = function() {

    linkHandler.open();

};

})();


    });

//submit form on click

function submitForm(){
    "use strict";

  var from_date = $('#from_date').val();

  var bank_id = $('#bank_account').find(":selected").val();

    if($('#from_date').val() == ''){

        appAlert.error("<?php echo _l('please_select_a_start_date') ?>");
      
        return false;
    }

  $('#import_button').prop('disabled',true);



  $.ajax({

       url: admin_url + 'accounting/update_plaid_transaction',

       type: 'POST',

       data: {bank_id: bank_id, from_date : from_date},

       error: function() {

          alert('Something is wrong');

       },

       success: function(response) {

           window.location.reload();

       }

    });      

}



function updatePlaidStatus(){
    "use strict";

    var bank_id = $('#bank_account').find(":selected").val();

      $('#delete_button').prop('disabled',true);

     $.ajax({

       url: admin_url + 'accounting/update_plaid_status',

       type: 'POST',

       data: {bank_id: bank_id},

       error: function() {

          alert('Something is wrong');

       },

       success: function(response) {

            window.location.reload();

       }

    });

}
</script>
