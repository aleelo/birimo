<script type="text/javascript">
$(document).ready(function () {
	var commodity_type_value, data;
(function($) {
	"use strict";

  setDatePicker("#journal_date");

  <?php if(isset($journal_entry)){ ?>
    data = <?php echo json_encode($journal_entry->details); ?>
  <?php }else{ ?>
  	data = [
              {"account":"","debit":"","credit":"","description":""},
              {"account":"","debit":"","credit":"","description":""},
              {"account":"","debit":"","credit":"","description":""},
              {"account":"","debit":"","credit":"","description":""},
              {"account":"","debit":"","credit":"","description":""},
              {"account":"","debit":"","credit":"","description":""},
              {"account":"","debit":"","credit":"","description":""},
              {"account":"","debit":"","credit":"","description":""},
              {"account":"","debit":"","credit":"","description":""},
              {"account":"","debit":"","credit":"","description":""},
              {"account":"","debit":"","credit":"","description":""},
              {"account":"","debit":"","credit":"","description":""},
              {"account":"","debit":"","credit":"","description":""},
              {"account":"","debit":"","credit":"","description":""},
              {"account":"","debit":"","credit":"","description":""},
              {"account":"","debit":"","credit":"","description":""},
              {"account":"","debit":"","credit":"","description":""},
              {"account":"","debit":"","credit":"","description":""},
              {"account":"","debit":"","credit":"","description":""},
              {"account":"","debit":"","credit":"","description":""},
                ];
  <?php } ?>

	var hotElement1 = document.querySelector('#journal_entry_container');

    var commodity_type = new Handsontable(hotElement1, {
      width: '100%',
      height: 'auto',
      colHeaders: true,
      rowHeaders: true,
      stretchH: 'all', // 'none' is default
      contextMenu: true,
      colWidths: [300, 100, 100, 300],

      manualRowMove: true,
      autoWrapRow: true,
      rowHeights: 30,
      defaultRowHeight: 100,
      minRows: 10,
      licenseKey: 'non-commercial-and-evaluation',
      autoColumnSize: {
        samplingRatio: 23
      },
      filters: true,
      manualRowResize: true,
      manualColumnResize: true,
      columnHeaderHeight: 40,
      rowHeights: 30,
      columns: [
		          {
			        data: 'account',
			        renderer: customDropdownRenderer,
			        editor: "chosen",
			        chosenOptions: {
			            data: <?php echo json_encode($account_to_select); ?>
			        }
			      },
                  {
                    type: 'numeric',
                    data: 'debit',
                    numericFormat: {
				        pattern: '0,0.00',
				    },
                  },
                  {
                    type: 'numeric',
                    data: 'credit',
                    numericFormat: {
				        pattern: '0,0.00',
				    },
                  },
                  {
                    type: 'text',
                    data: 'description',
                  },
                
                ],
      colHeaders: [
	    '<?php echo app_lang('acc_account'); ?>',
	    '<?php echo app_lang('debit'); ?>',
	    '<?php echo app_lang('credit'); ?>',
	    '<?php echo app_lang('description'); ?>'
	  ],
      data: data,
      afterChange: (changes) => {
        if(changes != null){
          var journal_entry = JSON.parse(JSON.stringify(commodity_type_value.getData()));
          var total_debit = 0, total_credit = 0;

          $.each(journal_entry, function(index, value) {
            if(value[0] != ''){
              if(value[1] != '' && value[1] != null){
                total_debit += parseFloat(value[1]);
              }
              if(value[2] != '' && value[2] != null){
                total_credit += parseFloat(value[2]);
              }
            }
          });
          
          $('.total_debit').html(format_money(total_debit));
          $('.total_credit').html(format_money(total_credit));
        }
      }
    });
    commodity_type_value = commodity_type;

    $('.journal-entry-form-submiter').on('click', function() {
      
	    $('input[name="journal_entry"]').val(JSON.stringify(commodity_type_value.getData()));
    	var journal_entry = JSON.parse($('input[name="journal_entry"]').val());
      var total_debit = 0, total_credit = 0;
	    $.each(journal_entry, function(index, value) {
        if(value[0] != ''){
          if(value[1] != '' && value[1] != null){
            total_debit += parseFloat(value[1]);
          }
          if(value[2] != '' && value[2] != null){
            total_credit += parseFloat(value[2]);
          }
        }
      });
      
	    if(total_debit.toFixed(2) == total_credit.toFixed(2)){
	    	if(total_debit > 0){
	    		$('input[name="amount"]').val(total_debit.toFixed(2));
	    		$('#journal-entry-form').submit();
	    	}else{
	    		alert('<?php echo app_lang('you_must_fill_out_at_least_two_detail_lines'); ?>');
	    	}
	    }else{
          alert('<?php echo app_lang('please_balance_debits_and_credits'); ?>');
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

function calculate_amount_total(){
  "use strict";
  var journal_entry = JSON.parse(JSON.stringify(commodity_type_value.getData()));
  var total_debit = 0, total_credit = 0;
  $.each(journal_entry, function(index, value) {
    if(value[1] != ''){
      total_debit += parseFloat(value[1]);
    }
    if(value[2] != ''){
      total_credit += parseFloat(value[2]);
    }
  });

  $('.total_debit').html(format_money(total_debit));
  $('.total_credit').html(format_money(total_credit));
}

  });


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