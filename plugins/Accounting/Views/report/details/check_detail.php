<div id="accordion">
  <div class="card">
    <table class="tree">
      <tbody>
        <tr>
            <td colspan="5">
                <h3 class="text-center no-margin-top-20 no-margin-left-24"><?php echo get_setting('companyname'); ?></h3>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="5">
              <h4 class="text-center no-margin-top-20 no-margin-left-24"><?php echo app_lang('cheque_detail'); ?></h4>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="5">
              <p class="text-center no-margin-top-20 no-margin-left-24"><?php echo html_entity_decode($data_report['from_date'] .' - '. $data_report['to_date']); ?></p>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr class="tr_header">
            <td class="text-bold"><?php echo app_lang('date'); ?></td>
            <td class="text-bold"><?php echo app_lang('transaction_type'); ?></td>
            <td class="text-bold"><?php echo app_lang('customer'); ?></td>
            <td class="text-bold"><?php echo app_lang('description'); ?></td>
            <td class="text-bold"><?php echo app_lang('acc_amount'); ?></td>
          </tr>
        <?php
          $row_index = 1;
          $total = 0;
          ?>
          <?php 
        foreach ($data_report['data']['cash_and_cash_equivalents'] as $key => $value) {
          $row_index += 1;
          $parent_index = $row_index;
          $total_amount = 0;
          ?>
          <?php if(count($value['details']) > 0){ ?>

          <tr class="treegrid-<?php echo html_entity_decode($parent_index); ?> parent-node expanded">
            <td class="parent"><?php echo html_entity_decode($value['name']); ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <?php foreach ($value['details'] as $val) { 
              $row_index += 1;
            ?>
            <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-<?php echo html_entity_decode($parent_index); ?>">
              <td>
              <?php echo _d($val['date']); ?> 
              </td>
              <td>
              <?php echo app_lang($val['rel_type']); ?> 
              </td>
              <td>
              <?php echo html_entity_decode(get_company_name($val['customer'])); ?> 
              </td>
              <td>
              <?php echo html_entity_decode($val['description']); ?> 
              </td>
              <td>
              <?php echo to_currency($val['debit'] - $val['credit'], $currency_symbol); ?> 
              </td>
            </tr>
          <?php }
            $row_index += 1;
           ?>
        <?php } ?>
        <?php } ?>
        </tbody>
    </table>
  </div>
</div>