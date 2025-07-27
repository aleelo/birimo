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
            <h4 class="text-center no-margin-top-20 no-margin-left-24"><?php echo app_lang('account_history'); ?></h4>
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
          <td class="text-bold"><?php echo app_lang('split'); ?></td>
          <td class="text-bold"><?php echo app_lang('description'); ?></td>
          <?php if($data_report['account_type'] == 3){ ?>
            <td class="total_amount text-bold"><?php echo app_lang('payment'); ?></td>
            <td class="total_amount text-bold"><?php echo app_lang('deposit'); ?></td>
          <?php }elseif($data_report['account_type'] == 7 || $data_report['account_type'] == 1){ ?>
            <td class="total_amount text-bold"><?php echo app_lang('charge'); ?></td>
            <td class="total_amount text-bold"><?php echo app_lang('payment'); ?></td>
          <?php }else{ ?>
            <td class="total_amount text-bold"><?php echo app_lang('decrease'); ?></td>
            <td class="total_amount text-bold"><?php echo app_lang('increase'); ?></td>
          <?php } ?>
          <td class="total_amount text-bold"><?php echo app_lang('balance'); ?></td>
        </tr>
        <?php
         $row_index = 0; 
         ?>

         <?php foreach ($data_report['data'] as $val) { 
              $row_index += 1;
            ?>
            <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-10000 ">
              <td>
              <?php echo _d($val['date']); ?> 
              </td>
              <td>
              <?php echo html_entity_decode($val['type']); ?> 
              </td>
              <td>
              <?php echo html_entity_decode($val['split']); ?> 
              </td>
              <td>
              <?php echo html_entity_decode($val['description']); ?> 
              </td>
              <td class="total_amount">
              <?php echo to_currency($val['decrease'], $currency_symbol); ?> 
              </td>
              <td class="total_amount">
              <?php echo to_currency($val['increase'], $currency_symbol); ?> 
              </td>
              <td class="total_amount">
              <?php echo to_currency($val['balance'], $currency_symbol); ?> 
              </td>
            </tr>
          <?php }
            $row_index += 1;
           ?>
      </tbody>
    </table>
  </div>
</div>