<div id="page-content" class="page-wrapper clearfix">
    <?php echo form_hidden('site_url', get_uri()); ?>
    <div class="card">
        <div class="page-title clearfix">
            <h1 class="w-100"><?php echo html_entity_decode($title); ?><br>
              <small><a href="<?php echo get_uri('accounting/report'); ?>"><?php echo app_lang('back_to_report_list'); ?></a></small>
            </h1>
            
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-10">
              <?php echo form_open(get_uri('accounting/view_report'),array('id'=>'filter-form', "class" => "general-form", "role" => "form")); ?>
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="from_date" class=""><?php echo app_lang('from_date'); ?></label>
                        <?php
                        echo form_input(array(
                            "id" => "from_date",
                            "name" => "from_date",
                            "value" => $from_date,
                            "class" => "form-control",
                            "placeholder" => app_lang('from_date'),
                            "autocomplete" => "off",
                        ));
                        ?>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="to_date" class=""><?php echo app_lang('to_date'); ?></label>
                        <?php
                        echo form_input(array(
                            "id" => "to_date",
                            "name" => "to_date",
                            "value" => $to_date,
                            "class" => "form-control",
                            "placeholder" => app_lang('to_date'),
                            "autocomplete" => "off",
                        ));
                        ?>
                  </div>
                </div>
                <div class="col-md-3">
                  <?php 
                  $method = [
                          'cash' => app_lang('cash'),
                          'accrual' => app_lang('accrual'),
                         ];
                  ?>
                  
                  <div class="form-group">
                      <label for="accounting_method" class=""><?php echo app_lang('accounting_method'); ?></label>
                      <?php
                          echo form_dropdown("accounting_method", $method, array($accounting_method), "class='select2 validate-hidden' id='accounting_method'");
                          ?>
                  </div>
                </div>
                <div class="col-md-3 mt-1">
                  <?php echo form_hidden('type', 'profit_and_loss'); ?>
                  <a class="btn btn-info btn-submit mt-4 text-white" onclick="filter_form_handler(); return false;"><i data-feather="filter" class="icon-16"></i> <?php echo app_lang('filter'); ?></a>
                </div>
              </div>
              <?php echo form_close(); ?>
            </div>
            <div class="col-md-2">
              <span class="dropdown inline-block pull-right m-4">
                  <button class="btn btn-default dropdown-toggle caret mt0 mb0" type="button" data-bs-toggle="dropdown" aria-expanded="true" data-bs-display="static">
                      <i data-feather="printer" class="icon-16"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end" role="menu">
                    <li role="presentation">
                      <a href="#" class="dropdown-item" onclick="printDiv(); return false;">
                       <?php echo app_lang('export_to_pdf'); ?>
                       </a>
                    </li>
                    <li role="presentation">
                      <a href="#" class="dropdown-item" onclick="printExcel(); return false;">
                       <?php echo app_lang('export_to_excel'); ?>
                       </a>
                    </li>
                  </ul>
              </span>
            </div>
          </div>
          <div class="row"> 
            <div class="col-md-12"> 
              <hr>
            </div>
          </div>
        <div class="report-content"> 
          <div class="page" id="DivIdToPrint">
        </div>
        </div>
      </div>
    </div>
  </div>
</div>
      
<!-- box loading -->
<div id="box-loading"></div>

<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="panel_s">
        <div class="panel-body">
          <h4 class="no-margin font-bold"><?php echo app_lang($title); ?></h4>
          <a href="<?php echo admin_url('accounting/report'); ?>"><?php echo app_lang('back_to_report_list'); ?></a>
          <hr />
          <div class="row">
            <div class="col-md-10">
              <div class="row">
              <?php echo form_open(admin_url('accounting/view_report'),array('id'=>'filter-form')); ?>
                <div class="col-md-3">
                  <?php echo render_date_input('from_date','from_date', _d($from_date)); ?>
                </div>
                <div class="col-md-3">
                  <?php echo render_date_input('to_date','to_date', _d($to_date)); ?>
                </div>
                <div class="col-md-3">
                <?php 
                $display_rows_by = [
                  1 => ['id' => 'customers', 'name' => app_lang('customers')],
                  2 => ['id' => 'vendors', 'name' => app_lang('vendors')],
                  3 => ['id' => 'employees', 'name' => app_lang('employees')],
                  4 => ['id' => 'product_service', 'name' => app_lang('product_service')],
                  5 => ['id' => 'income_statement', 'name' => app_lang('income_statement')],
                  6 => ['id' => 'balance_sheet', 'name' => app_lang('balance_sheet')],
                  7 => ['id' => 'balance_sheet_summary', 'name' => app_lang('balance_sheet_summary')],
                ];
                echo render_select('display_rows_by', $display_rows_by, array('id', 'name'),'display_rows_by', $accounting_display_rows_by, array(), array(), '', '', false);
                ?>
              </div>
              <div class="col-md-3">
                <?php 
                $display_columns_by = [
                  1 => ['id' => 'total_only', 'name' => app_lang('total_only')],
                  2 => ['id' => 'months', 'name' => app_lang('months')],
                  3 => ['id' => 'quarters', 'name' => app_lang('quarters')],
                  4 => ['id' => 'years', 'name' => app_lang('years')],
                  5 => ['id' => 'customers', 'name' => app_lang('customers')],
                  6 => ['id' => 'vendors', 'name' => app_lang('vendors')],
                  7 => ['id' => 'employees', 'name' => app_lang('employees')],
                  8 => ['id' => 'product_service', 'name' => app_lang('product_service')],
                ];
                echo render_select('display_columns_by', $display_columns_by, array('id', 'name'),'display_columns_by', $accounting_display_columns_by, array(), array(), '', '', false);
                ?>
              </div>
                <div class="col-md-3">
                  <?php 
                  $method = [
                          1 => ['id' => 'cash', 'name' => app_lang('cash')],
                          2 => ['id' => 'accrual', 'name' => app_lang('accrual')],
                         ];
                  echo render_select('accounting_method', $method, array('id', 'name'),'accounting_method', $accounting_method, array(), array(), '', '', false);
                  ?>
                </div>
                <div class="col-md-3">
                  <?php 
                  $page_type = [
                          1 => ['id' => 'vertical', 'name' => app_lang('vertical')],
                          2 => ['id' => 'horizontal', 'name' => app_lang('horizontal')],
                         ];
                  echo render_select('page_type', $page_type, array('id', 'name'),'page_type', '', array(), array(), '', '', false);
                  ?>
                </div>
                <div class="col-md-3">
                  <?php echo form_hidden('type', 'custom_summary_report'); ?>
                  <button type="submit" class="btn btn-info btn-submit mtop25"><?php echo app_lang('filter'); ?></button>
                </div>
              <?php echo form_close(); ?>
              </div>
            </div>
            <div class="col-md-2">
              <div class="btn-group pull-right mtop25">
                 <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-print"></i><?php if(is_mobile()){echo ' PDF';} ?> <span class="caret"></span></a>
                 <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                       <a href="#" id="export_to_pdf_btn" onclick="printDiv(); return false;">
                       <?php echo app_lang('export_to_pdf'); ?>
                       </a>
                    </li>
                    <li>
                       <a href="#" onclick="printExcel(); return false;">
                       <?php echo app_lang('export_to_excel'); ?>
                       </a>
                    </li>
                 </ul>
              </div>
            </div>
          </div>
          <div class="row"> 
            <div class="col-md-12"> 
              <hr>
            </div>
          </div>
          <div class="page" id="DivIdToPrint">
            
        </div>
      </div>
    </div>
  </div>
</div>
<!-- box loading -->
<div id="box-loading"></div>
<?php init_tail(); ?>
</body>
</html>
