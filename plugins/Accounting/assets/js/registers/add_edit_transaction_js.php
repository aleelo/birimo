<script>	
	var site_url = $('input[name="site_url"]').val();
    var admin_url  = $('input[name="site_url"]').val();
		var product_tabs;

	(function($) {
		"use strict";  

		$(".select2").select2();
    setDatePicker("#from_date_filter");
    setDatePicker("#to_date_filter");

		$('li.menu-item-accounting_registers').addClass('active');

		var dataObject_pu = [];

		var hotElement1 = document.getElementById('product_tab_hs');

		product_tabs = new Handsontable(hotElement1, {
			licenseKey: 'non-commercial-and-evaluation',

			contextMenu: true,
			manualRowMove: true,
			manualColumnMove: true,
			stretchH: 'all',
			autoWrapRow: true,
			rowHeights: 30,
			defaultRowHeight: 100,
			// minRows: 100,
			// maxRows: 40,
			width: '100%',
    	height: 400,
			rowHeaders: true,
			colHeaders: true,
			autoColumnSize: {
				samplingRatio: 23
			},

			filters: true,
			manualRowResize: true,
			manualColumnResize: true,
			allowInsertRow: true,
			allowRemoveRow: true,
			columnHeaderHeight: 40,

			rowHeights: 30,
			rowHeaderWidth: [44],
			minSpareRows: 1,
			hiddenColumns: {
				columns: [0],
				indicators: true
			},

			columns: [
			{
				type: 'text',
				data: 'id',
			},

			{
				type: 'date',
				dateFormat: 'YYYY-MM-DD',
				correctFormat: true,
				defaultDate: "<?php echo date('Y-m-d'); ?>",
				data:'date'
			},
			{
				type: 'text',
				data: 'number',
			},
			
			{
				type: 'text',
				data: 'payee',
				renderer: customDropdownRenderer,
				editor: "chosen",
				chosenOptions: {
					data: <?php echo json_encode($payee); ?>
				},
			},
			{
				type: 'text',
				data: 'split',
				renderer: customDropdownRenderer,
				editor: "chosen",
				chosenOptions: {
					data: <?php echo json_encode($accounts); ?>
				},
				isRequired: true,
			},
			
			{
				data: 'credit',
				type: 'numeric',
			      numericFormat: {
			        pattern: '0,0.00',
			      },
			},
			{
				data: 'debit',
				type: 'numeric',
			      numericFormat: {
			        pattern: '0,0.00',
			      },
			},

			{
				data: 'balance',
				type: 'numeric',
			      numericFormat: {
			        pattern: '0,0.00',
			      },
			     readOnly: true,
			},

			
			],

			colHeaders: [
				'<?php echo _l('id'); ?>',
				'<?php echo _l('acc_date'); ?>',
				'<?php echo _l('number'); ?>',
				'<?php echo _l('payee'); ?>',
				'<?php echo _l('acc_account'); ?>',
				'<?php echo _l('debit'); ?>', //debit
				'<?php echo _l('credit'); ?>', //credit
				'<?php echo _l('balance'); ?>',
			],
			cells: function(row){
        let cp = {}
        if(row % 2 === 1){ cp.className = 'greyRow'}
        return cp
      },

			data: dataObject_pu,
		});

		product_tabs.addHook('afterChange', function(changes, src) {
			"use strict";

			if(changes !== null && changes !== undefined){
				changes.forEach(([row, col, prop, oldValue, newValue]) => {
					if(col == 'credit' && oldValue != ''){

						product_tabs.setDataAtCell(row,6,'');
						var date = product_tabs.getDataAtCell(row, 1);

						if(date == null){
							product_tabs.setDataAtCell(row,1, '<?php echo date('Y-m-d'); ?>');
						}

					}

					if(col == 'debit' && oldValue != ''){

						product_tabs.setDataAtCell(row,5,'');
						var date = product_tabs.getDataAtCell(row, 1);

						if(date == null){
							product_tabs.setDataAtCell(row,1, '<?php echo date('Y-m-d'); ?>');
						}

					}


				});
			}

		});


		$('input[name="from_date_filter"]').on('change', function() {
    	'use strict';

    	transaction_filter();
    });

    $('input[name="to_date_filter"]').on('change', function() {
    	'use strict';

    	transaction_filter();
    });

    $('input[name="number_filter"]').on('change', function() {
    	'use strict';

    	transaction_filter();
    });

    $('select[name="payee_filter[]"]').on('change', function() {
    	'use strict';

    	transaction_filter();

    });

    $('input[name="from_credit_filter"]').on('change', function() {
    	'use strict';

    	transaction_filter();
    });

    $('input[name="to_credit_filter"]').on('change', function() {
    	'use strict';

    	transaction_filter();
    });

    $('input[name="from_debit_filter"]').on('change', function() {
    	'use strict';

    	transaction_filter();
    });

    $('input[name="to_debit_filter"]').on('change', function() {
    	'use strict';

    	transaction_filter();
    });

    $('select[name="account_filter[]"]').on('change', function() {
    	'use strict';

    	transaction_filter();
    });
    

    $('.reset_filter').on('click', function() {
    	'use strict';

    	reset_filter();
    });

		transaction_filter();
		transaction_filter();

    
$('.add_user_register').on('click', function() {
	'use strict';

        var valid_product_tab_hs = $('#product_tab_hs').find('.htInvalid').html();

        $('input[name="save_and_send_request"]').val('false');

        if(valid_product_tab_hs){
           appAlert.error("<?php echo _l('data_must_number') ; ?>");
        }else{
          
          var warehouse_id = $('select[name="warehouse_id"]').val();

          var datasubmit = {};
          datasubmit.product_tabs = JSON.stringify(product_tabs.getData());
          datasubmit.account = $('input[name="account"]').val();
          datasubmit.company = $('input[name="company"]').val();

          datasubmit.from_date_filter = $('input[name="from_date_filter"]').val();
          datasubmit.to_date_filter = $('input[name="to_date_filter"]').val();
          datasubmit.number_filter = $('input[name="number_filter"]').val();
          datasubmit.payee_filter = $('select[name="payee_filter[]"]').val();
          datasubmit.from_credit_filter = $('input[name="from_credit_filter"]').val();
          datasubmit.to_credit_filter = $('input[name="to_credit_filter"]').val();
          datasubmit.from_debit_filter = $('input[name="from_debit_filter"]').val();
          datasubmit.to_debit_filter = $('input[name="to_debit_filter"]').val();
          datasubmit.account_filter = $('select[name="account_filter[]"]').val();

            $.post(admin_url + 'accounting/check_user_register_transaction', datasubmit).done(function(responsec){
              responsec = JSON.parse(responsec);

              if(responsec.status == true || responsec.status == 'true'){
                
              	$.post(admin_url + 'accounting/register_add_edit_transaction', datasubmit).done(function(response){
              		response = JSON.parse(response);

              		if(response.status == true || response.status == 'true'){
              			
              			product_tabs.updateSettings({
              				data: response.dataObject,

              			})

              			$('.ending_balance').html(format_money(response.ending_balance));
              			appAlert.success("<?php echo _l('acc_updated_successfully') ; ?>");
		            }else{
              			appAlert.success("<?php echo _l('acc_updated_successfully') ; ?>");
		            }
		        });

              	$('input[name="product_tab_hs"]').val(JSON.stringify(product_tabs.getData()));   
                // $('#add_update_transaction').submit(); 

              }else{
              			appAlert.error("<?php echo _l('acc_please_select_account') ; ?>");
              }

            });



        }
});
})(jQuery);



function customDropdownRenderer(instance, td, row, col, prop, value, cellProperties) {
	"use strict";

	var selectedId;
	var optionsList = cellProperties.chosenOptions.data;

	if(typeof optionsList === "undefined" || typeof optionsList.length === "undefined" || !optionsList.length) {
		Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
		return td;
	}

	var values = (value + "").split("|");
	value = [];
	for (var index = 0; index < optionsList.length; index++) {

		if (values.indexOf(optionsList[index].id + "") > -1) {
			selectedId = optionsList[index].id;
			value.push(optionsList[index].label);
		}
	}
	value = value.join(", ");

	Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
	return td;
}



    //filter
    function transaction_filter (){
    	'use strict';

    	var data = {};

    	data.csrf_token_name = $('input[name="csrf_token_name"]').val();
    	data.account = $('input[name="account"]').val();
    	data.company = $('input[name="company"]').val();

    	data.from_date_filter = $('input[name="from_date_filter"]').val();
    	data.to_date_filter = $('input[name="to_date_filter"]').val();
    	data.number_filter = $('input[name="number_filter"]').val();
    	data.payee_filter = $('select[name="payee_filter[]"]').val();
    	data.from_credit_filter = $('input[name="from_credit_filter"]').val();
    	data.to_credit_filter = $('input[name="to_credit_filter"]').val();
    	data.from_debit_filter = $('input[name="from_debit_filter"]').val();
    	data.to_debit_filter = $('input[name="to_debit_filter"]').val();
    	data.account_filter = $('select[name="account_filter[]"]').val();

    	$.post(admin_url + 'accounting/transaction_filter', data).done(function(response) {
    		response = JSON.parse(response);

    		product_tabs.updateSettings({
    			data: response.dataObject,
    		});

    		$('.ending_balance').html(format_money(response.ending_balance));
    	});
    };

    

    function reset_filter() {
    	$('input[name="from_date_filter"]').val('');
    	$('input[name="to_date_filter"]').val('');
    	$('input[name="number_filter"]').val('');
    	$('select[name="payee_filter[]"]').val('').change();
    	$('input[name="from_credit_filter"]').val('');
    	$('input[name="to_credit_filter"]').val('');
    	$('input[name="from_debit_filter"]').val('');
    	$('input[name="to_debit_filter"]').val('');
    	$('select[name="account_filter[]"]').val('').change();
    }


function formatNumber(n) {
  "use strict";
  // format number 1000000 to 1,234,567
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}
function format_money(input_val) {
  "use strict";
  // appends $ to value, validates decimal side
  // and puts cursor back in right position.
  input_val = input_val.toString();
  // don't validate empty input
  if (input_val === "") { return; }

  // original length
  var original_len = input_val.length;

  // check for decimal
  if (input_val.indexOf(".") >= 0) {

    // get position of first decimal
    // this prevents multiple decimals from
    // being entered
    var decimal_pos = input_val.indexOf(".");

    // split number by decimal point
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);

    // add commas to left side of number
    left_side = formatNumber(left_side);

    // validate right side
    right_side = formatNumber(right_side);

    // Limit decimal to only 2 digits
    right_side = right_side.substring(0, 2);

    // join number by .
    input_val = left_side + "." + right_side;

  } else {
    // no decimal entered
    // add commas to number
    // remove all non-digits
    input_val = formatNumber(input_val);
    input_val = input_val;

  }

  return input_val;
  
}
</script>