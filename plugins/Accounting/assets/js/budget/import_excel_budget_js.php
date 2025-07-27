<script>

  var site_url = $('input[name="site_url"]').val();
  var admin_url = $('input[name="admin_url"]').val();
   (function($) {
    
    "use strict";

  $(".select2").select2();
      // function 

      if('<?php echo html_entity_decode($active_language) ?>' == 'vietnamese')
      {
        $( "#dowload_file_sample" ).append( '<a href="'+ site_url+'/plugins/Accounting/uploads/file_sample/Sample_import_budget_file_for_month_vi.xlsx" class="btn btn-primary mright5" ><?php echo _l('download_sample_for_month') ?></a>' );
        $( "#dowload_file_sample" ).append( '<a href="'+ site_url+'/plugins/Accounting/uploads/file_sample/Sample_import_budget_file_for_quarter_vi.xlsx" class="btn btn-primary mright5" ><?php echo _l('download_sample_for_quarter') ?></a>' );
        $( "#dowload_file_sample" ).append( '<a href="'+ site_url+'/plugins/Accounting/uploads/file_sample/Sample_import_budget_file_for_year_vi.xlsx" class="btn btn-primary mright5" ><?php echo _l('download_sample_for_year') ?></a>' );
      }else{
        $( "#dowload_file_sample" ).append( '<a href="'+ site_url+'/plugins/Accounting/uploads/file_sample/Sample_import_budget_file_for_month_en.xlsx" class="btn btn-primary mright5" ><?php echo _l('download_sample_for_month') ?></a>' );
        $( "#dowload_file_sample" ).append( '<a href="'+ site_url+'/plugins/Accounting/uploads/file_sample/Sample_import_budget_file_for_quarter_en.xlsx" class="btn btn-primary mright5" ><?php echo _l('download_sample_for_quarter') ?></a>' );
        $( "#dowload_file_sample" ).append( '<a href="'+ site_url+'/plugins/Accounting/uploads/file_sample/Sample_import_budget_file_for_year_en.xlsx" class="btn btn-primary mright5" ><?php echo _l('download_sample_for_year') ?></a>' );
      }

      $('a').click(function() {
        $(window).unbind('beforeunload');
      });

  })(jQuery);

function uploadfilecsv(){
  "use strict";

    $('button[id="uploadfile"]').prop('disabled', true);

    if(($("#file_csv").val() != '') && ($("#file_csv").val().split('.').pop() == 'xlsx')){
    var formData = new FormData();
    formData.append("file_csv", $('#file_csv')[0].files[0]);
    formData.append("rise_csrf_token", $('input[name="rise_csrf_token"]').val());
    formData.append("year", $('input[name="fiscal_year_for_this_budget"]').val());
    formData.append("type", $('input[name=budget_type]:checked').val());
    formData.append("name", $('input[name="name"]').val());
    formData.append("import_type", $('select[name="import_type"]').val());

    //show box loading
    var html = '';
    html += '<div class="Box">';
    html += '<span>';
    html += '<span></span>';
    html += '</span>';
    html += '</div>';
    $('#box-loading').html(html);

    $.ajax({ 
      url: admin_url + 'accounting/import_file_xlsx_budget', 
      method: 'post', 
      data: formData, 
      contentType: false, 
      processData: false
      
    }).done(function(response) {
      response = JSON.parse(response);
      $("#file_csv").val(null);
      $("#file_csv").change();

      $( "#file_upload_response").html('');

        $( "#file_upload_response").append( "<h4><?php echo _l("_Result") ?></h4><h5><?php echo _l('import_line_number') ?> :"+response.total_rows+" </h5>");
   

     
        $( "#file_upload_response").append( "<h5><?php echo _l('import_line_number_success') ?> :"+response.total_row_success+" </h5>");



        $( "#file_upload_response").append( "<h5><?php echo _l('import_line_number_failed') ?> :"+response.total_row_false+" </h5>");


      if((response.total_row_false > 0) || (response.total_rows_data_error > 0))
      {
        $( "#file_upload_response").append( '<a href="'+site_url +'/'+response.filename+'" class="btn btn-warning"  ><?php echo _l('download_file_error') ?></a>');
      }
      if(response.total_rows < 1){
        appAlert.error(response.message);
      }

      //hide boxloading
      $('#box-loading').html('');
    });
    $('button[id="uploadfile"]').prop('disabled', false);
    return false;
    }else if($("#file_csv").val() != ''){
        appAlert.error("<?php echo _l('_please_choose_the_correct_file_format') ?>");
      $('button[id="uploadfile"]').prop('disabled', false);
    }else{
        appAlert.error("<?php echo _l('_please_select_a_file') ?>");
      $('button[id="uploadfile"]').prop('disabled', false);

    }


}
</script>