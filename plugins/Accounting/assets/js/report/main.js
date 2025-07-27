$(document).ready(function () {
(function($) {
	"use strict";
	$('.tree').treegrid();

  $(".select2").select2();
  setDatePicker("#from_date");
  setDatePicker("#to_date");


  filter_form_handler();

})(jQuery);
});


function printDiv() 
{
	"use strict";
    var element = document.getElementById('accordion');
    var opt = {
      margin:       0.5,
      filename:     $('input[name="type"]').val()+'.pdf',
      image:        { type: 'jpeg', quality: 1 },
      // html2canvas:  { scale: 2 },
      jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
    };

    // Old monolithic-style usage:
    html2pdf(element, opt);
}

function printDiv2() 
{
  "use strict";
    var element = document.getElementById('accordion');
    var opt = {
      margin:       0.5,
      filename:     $('input[name="type"]').val()+'.pdf',
      image:        { type: 'jpeg', quality: 1 },
      // html2canvas:  { scale: 2 },
      jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' }
    };

    // Old monolithic-style usage:
    html2pdf(element, opt);
}

function printExcel(){
	"use strict";
   $(".tree").tableHTMLExport({
      type:'csv',
      filename:$('input[name="type"]').val()+'.csv',
    });
}

function filter_form_handler() {
	"use strict";

  var site_url = $('input[name="site_url"]').val();
    if($('select[name="display_rows_by"]').val() != undefined){
      if($('select[name="display_rows_by"]').val() == $('select[name="display_columns_by"]').val()){
        alert('Warning: Row and column headings must be different.');
        return false;
      }
    }

    if($('input[name="type"]').val() == 'custom_summary_report'){
      if($('select[name="page_type"]').val() == 'vertical'){
        $('#DivIdToPrint').addClass('page');
        $('#DivIdToPrint').removeClass('page-size2');

        $('#export_to_pdf_btn').attr('onclick', 'printDiv(); return false;');
      }

      if($('select[name="page_type"]').val() == 'horizontal'){
        $('#DivIdToPrint').removeClass('page');
        $('#DivIdToPrint').addClass('page-size2');
        $('#export_to_pdf_btn').attr('onclick', 'printDiv2(); return false;');
      }
    }


    var formURL = site_url + 'accounting/view_report';
    var formData = new FormData($('#filter-form')[0]);

    //show box loading
    var html = '';
      html += '<div class="Box">';
      html += '<span>';
      html += '<span></span>';
      html += '</span>';
      html += '</div>';
      $('#box-loading').html(html);
    $.ajax({
        type: $("#filter-form").attr('method'),
        data: formData,
        mimeType: $("#filter-form").attr('enctype'),
        contentType: false,
        cache: false,
        processData: false,
        url: formURL
    }).done(function(response) {
    	$('#DivIdToPrint').html(response);
		  $('.tree').treegrid();

		//hide boxloading
	    $('#box-loading').html('');
    }).fail(function(error) {
        appAlert.error(error.responseJSON.message, {container: '.card-body', animate: false});
    });

    return false;
}