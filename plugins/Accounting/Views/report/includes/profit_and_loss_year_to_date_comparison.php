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
                  <?php echo form_hidden('type', 'profit_and_loss_year_to_date_comparison'); ?>
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

