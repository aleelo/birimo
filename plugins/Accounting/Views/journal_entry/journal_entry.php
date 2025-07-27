<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
      <div class="page-title clearfix">
        <h4 class="no-margin font-bold"><?php echo html_entity_decode($title); ?></h4> 
      </div>

          <?php echo form_hidden('site_url', get_uri()); ?>
          <?php $arrAtt = array();
                $arrAtt['data-type']='currency';
                $url = get_uri('accounting/new_journal_entry');
                if(isset($journal_entry)){
                  $url = get_uri('accounting/new_journal_entry/'.$journal_entry->id);
                }
                ?>

          <?php echo form_open($url,array('id'=>'journal-entry-form','autocomplete'=>'off', 'class'=>'general-form', "role" => "form")); ?>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
              <?php $value = (isset($journal_entry) ? format_to_date($journal_entry->journal_date) : get_my_local_time('Y-m-d')); ?>
                <label for="journal_date" class=""><?php echo app_lang('journal_date'); ?></label>
                    <?php
                    echo form_input(array(
                        "id" => "journal_date",
                        "name" => "journal_date",
                        "value" => $value,
                        "class" => "form-control recurring_element",
                        "placeholder" => app_lang('journal_date'),
                        "autocomplete" => "off",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
              </div>
            </div>
            <div class="col-md-6">
              <?php $value = (isset($journal_entry) ? $journal_entry->number : $next_number); ?>
              <div class="form-group">
                    <label for="number" class=""><?php echo app_lang('number'); ?></label>
                        <?php
                        echo form_input(array(
                            "id" => "number",
                            "name" => "number",
                            "value" => $value,
                            "class" => "form-control",
                            "placeholder" => app_lang('number'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                </div>
            </div>
          </div>
            <div id="journal_entry_container"></div>
          <div class="col-md-8 col-md-offset-4">
         <table class="table text-right">
            <tbody>
                <tr>
                  <td></td>
                  <td class="text-right bold"><?php echo app_lang('debit'); ?></td>
                  <td class="text-right bold"><?php echo app_lang('credit'); ?></td>
                </tr>
               <tr>
                  <td><span class="bold"><?php echo app_lang('total'); ?> :</span>
                  </td>
                  <td class="total_debit">
                    <?php $value = (isset($journal_entry) ? $journal_entry->amount : 0); ?>
                    <?php echo to_decimal_format($value); ?>
                  </td>
                  <td class="total_credit">
                    <?php $value = (isset($journal_entry) ? $journal_entry->amount : 0); ?>
                    <?php echo to_decimal_format($value); ?>
                  </td>
               </tr>
            </tbody>
         </table>
        </div>
          <?php echo form_hidden('journal_entry'); ?>
          <?php echo form_hidden('amount'); ?>
          <div class="row">
            <div class="col-md-12">
              <?php $value = (isset($journal_entry) ? $journal_entry->description : ''); ?>
              <div class="form-group">
                    <label for="description" class=""><?php echo app_lang('description'); ?></label>
                        <?php
                        echo form_textarea(array(
                            "id" => "description",
                            "name" => "description",
                            "value" => $value,
                            "class" => "form-control",
                            "placeholder" => app_lang('description'),
                            "data-rich-text-editor" => true
                        ));
                        ?>
            </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">    
              <div class="modal-footer">
                <a href="<?php echo get_uri('accounting/journal_entry'); ?>" class="btn btn-default"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></a>
                <button type="button" class="btn btn-primary journal-entry-form-submiter"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
              </div>
            </div>
          </div>
      </div>
          <?php echo form_close(); ?>
    </div>
</div>
<?php require 'plugins/Accounting/assets/js/journal_entry/journal_entry_js.php';?>