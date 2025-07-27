<div id="accordion">
  <div class="card">
    <table class="tree">
      <tbody>
        <tr>
          <td colspan="7">
              <h3 class="text-center no-margin-top-20 no-margin-left-24"><?php echo get_setting('companyname'); ?></h3>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="7">
            <h4 class="text-center no-margin-top-20 no-margin-left-24"><?php echo app_lang('journal'); ?></h4>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="7">
            <p class="text-center no-margin-top-20 no-margin-left-24"><?php echo html_entity_decode($data_report['from_date'] .' - '. $data_report['to_date']); ?></p>
          </td>
          <td></td>
          <td></td>
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
          <td></td>
          <td></td>
        </tr>
        <tr class="tr_header">
          <td class="text-bold"><?php echo app_lang('date'); ?></td>
          <td class="text-bold"><?php echo app_lang('transaction_type'); ?></td>
          <td class="text-bold"><?php echo app_lang('customer'); ?></td>
          <td class="text-bold"><?php echo app_lang('description'); ?></td>
          <td class="text-bold"><?php echo app_lang('acc_account'); ?></td>
          <td class="total_amount text-bold"><?php echo app_lang('debit'); ?></td>
          <td class="total_amount text-bold"><?php echo app_lang('credit'); ?></td>
        </tr>
        <?php
         $row_index = 0; 
         $parent_index = 0; 
         $total_debit = 0; 
         $total_credit = 0; 
         ?>

         <?php foreach ($data_report['data'] as $val) { 
              $row_index += 1;
              $total_debit += $val['debit'];
              $total_credit += $val['credit'];
            ?>
            <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-10000 ">
              <td>
              <?php echo _d($val['date']); ?> 
              </td>
              <td>
              <?php echo html_entity_decode($val['type']); ?> 
              </td>
              <td>
              <?php echo get_company_name($val['customer']); ?> 
              </td>
              <td>
              <?php echo html_entity_decode($val['description']); ?> 
              </td>
              <td>
              <?php echo html_entity_decode($val['name']); ?> 
              </td>
              <td class="total_amount">
              <?php echo to_currency($val['debit'], $currency_symbol); ?> 
              </td>
              <td class="total_amount">
              <?php echo to_currency($val['credit'], $currency_symbol); ?> 
              </td>
            </tr>
          <?php }
            $row_index += 1;
           ?>
          
           <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?>  parent-node expanded tr_total">
            <td class="parent"><?php echo app_lang('total'); ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount"><?php echo to_currency($total_debit, $currency_symbol); ?> </td>
            <td class="total_amount"><?php echo to_currency($total_credit, $currency_symbol); ?> </td>
          </tr>
      </tbody>
    </table>
  </div>
</div>