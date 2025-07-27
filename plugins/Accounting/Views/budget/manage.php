<div id="page-content" class="page-wrapper clearfix">
    <?php echo form_hidden('site_url', get_uri()); ?>
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo html_entity_decode($title); ?></h1>
        </div>
        <div class="card-body">
          <?php echo form_open(get_uri('accounting/budget'),array('id'=>'budget-form','autocomplete'=>'off', "class" => "general-form", "role" => "form")); ?>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
                <label for="budget" class=""><?php echo app_lang('budget'); ?></label>
                <?php
                    echo form_dropdown("budget", $budgets_dropdown, array(), "class='select2 validate-hidden' id='budget'");
                    ?>
            </div>
          </div>
          <div class=" col-md-9 pull-right">
            <a href="<?php echo get_uri('accounting/budget_import'); ?>" class="btn btn-success mtop25 pull-right mleft5 <?php if(!acc_has_permission('acc_can_create_budget')){echo 'hide';} ?>"><i data-feather='upload' class='icon-16'></i> <?php echo app_lang('import_budget'); ?></a>
            <a href="#" onclick="creating_a_budget(); return false;" class="btn btn-default mtop25 pull-right <?php if(!acc_has_permission('acc_can_create_budget')){echo 'hide';} ?>"><i data-feather='plus-circle' class='icon-16'></i> <?php echo app_lang('add'); ?></a>
          </div>
        </div>
        <div id="div_data">
          <div class="mx-auto mt-3 btn-group fc" role="group">
            <button type="button" class="button-text-transform fc-wl-monthly-button btn btn-sm btn-default active mright2"><?php echo app_lang('monthly') ?></button>
            <button type="button" class="button-text-transform fc-wl-quarterly-button btn btn-sm btn-default mright2"><?php echo app_lang('quarterly') ?></button>
            <button type="button" class="button-text-transform fc-wl-yearly-button btn btn-sm btn-default"><?php echo app_lang('yearly') ?></button>
            <?php echo form_hidden('view_type', 'monthly'); ?>
          </div>
          <br>
          <br>
          <div class="budget-notifi hide">
            <h4 class="text-danger"><?php echo app_lang('no_budget_has_been_created'); ?></h4>
          </div>
          <div id="workload"></div>
          <?php echo form_hidden('budget_data'); ?>
          <br>
          <div class="row">
            <div class=" col-md-12">
              <hr>
              <a href="#" onclick="save_budget(); return false;" class="btn btn-info display-block mleft5 pull-right text-white <?php if(!acc_has_permission('acc_can_edit_budget')){echo 'hide';} ?>"><i data-feather='check-circle' class='icon-16'></i> <?php echo app_lang('save'); ?></a>
              <a href="#" onclick="clear_budget(); return false;" class="btn btn-default display-block mleft5 pull-right <?php if(!acc_has_permission('acc_can_edit_budget')){echo 'hide';} ?>"><i data-feather='x' class='icon-16'></i> <?php echo app_lang('clear'); ?></a>
              <a href="#" onclick="delete_budget(); return false;" class="btn btn-danger display-block mleft5 pull-right <?php if(!acc_has_permission('acc_can_delete_budget')){echo 'hide';} ?>"><i data-feather='x-circle' class='icon-16'></i> <?php echo app_lang('delete'); ?></a>
            </div>
          </div>
        </div>
        <?php echo form_close(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="creating-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app_lang('creating_a_budget')?></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <p><?php echo app_lang('creating_a_budget_note_1'); ?></p>
            <?php echo app_lang('creating_a_budget_note_2'); ?>
            <br>
            <?php echo app_lang('creating_a_budget_note_3'); ?>
            <br>
            <?php echo app_lang('creating_a_budget_note_4'); ?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a href="#" onclick="year_and_type(); return false;" class="btn btn-info text-white"><i data-feather='arrow-right' class='icon-16'></i> <?php echo app_lang('next'); ?></a>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="finish-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app_lang('ready_to_create_your_budget')?></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <p><?php echo app_lang('ready_to_create_your_budget_note_1'); ?></p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a href="#" onclick="previous_year_and_type(); return false;" class="btn btn-default"><i data-feather='arrow-left' class='icon-16'></i> <?php echo app_lang('previous'); ?></a>
        <a href="#" onclick="new_budget(); return false;" class="btn btn-info text-white"><i data-feather='check-circle' class='icon-16'></i> <?php echo app_lang('fisnish'); ?></a>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="budget-exists-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app_lang('budget_already_exists')?></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <?php echo app_lang('budget_already_exists_note_1'); ?><br>
            <?php echo app_lang('budget_already_exists_note_2'); ?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a href="#" onclick="previous_year_and_type(); return false;" class="btn btn-default"><i data-feather='arrow-left' class='icon-16'></i> <?php echo app_lang('previous'); ?></a>
        <a href="#" onclick="update_budget(); return false;" class="btn btn-info text-white"><i data-feather='check-circle' class='icon-16'></i> <?php echo app_lang('fisnish'); ?></a>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="year-and-type-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app_lang('year_and_type')?></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <?php echo render_input('fiscal_year_for_this_budget','fiscal_year_for_this_budget',date('Y'),'number'); ?>
            <?php echo app_lang('year_and_type_note_1'); ?>
            <br>
            <?php echo app_lang('year_and_type_note_2'); ?>
            <br>
            <div class="form-group">
              <div class="radio radio-primary">
                <input type="radio" id="profit_and_loss_accounts" name="budget_type" value="profit_and_loss_accounts" checked>
                <label for="profit_and_loss_accounts"><?php echo app_lang('profit_and_loss_accounts'); ?></label>
              </div>

              <div class="radio radio-primary">
                <input type="radio" id="balance_sheet_accounts" name="budget_type" value="balance_sheet_accounts">
                <label for="balance_sheet_accounts"><?php echo app_lang('balance_sheet_accounts'); ?></label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a href="#" onclick="previous_creating_a_budget(); return false;" class="btn btn-default"><i data-feather='arrow-left' class='icon-16'></i> <?php echo app_lang('previous'); ?></a>
        <a href="#" onclick="data_source(); return false;" class="btn btn-info text-white"><i data-feather='arrow-right' class='icon-16'></i> <?php echo app_lang('next'); ?></a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="data-source-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app_lang('data_source')?></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <p><?php echo app_lang('creating_a_budget_note_1'); ?></p>
            <div class="form-group">
              <div class="radio radio-primary">
                <input type="radio" id="create_budget_from_scratch" name="data_source" value="create_budget_from_scratch" checked>
                <label for="create_budget_from_scratch"><?php echo app_lang('create_budget_from_scratch'); ?></label>
              </div>

              <div class="radio radio-primary">
                <input type="radio" id="create_budget_from_prior_fiscal_year_transactions" name="data_source" value="create_budget_from_prior_fiscal_year_transactions">
                <label for="create_budget_from_prior_fiscal_year_transactions"><?php echo app_lang('create_budget_from_prior_fiscal_year_transactions'); ?></label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a href="#" onclick="previous_year_and_type(); return false;" class="btn btn-default"><i data-feather='arrow-left' class='icon-16'></i> <?php echo app_lang('previous'); ?></a>
        <a href="#" onclick="new_budget(); return false;" class="btn btn-info text-white"><i data-feather='check-circle' class='icon-16'></i> <?php echo app_lang('done'); ?></a>
      </div>
    </div>
  </div>
</div>

<!-- box loading -->
<div id="box-loading"></div>

<?php require 'plugins/Accounting/assets/js/budget/budget_js.php';?>
