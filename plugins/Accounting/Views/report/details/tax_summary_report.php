<div id="accordion">
  <div class="card">
    <table class="tree">
      <tbody>
        <tr>
          <td colspan="2">
              <h3 class="text-center no-margin-top-20 no-margin-left-24"><?php echo get_setting('companyname'); ?></h3>
          </td>
          <td></td>
        </tr>
        <tr>
          <td colspan="2">
            <h4 class="text-center no-margin-top-20 no-margin-left-24"><?php echo app_lang('tax_summary_report'); ?></h4>
          </td>
          <td></td>
        </tr>
        <tr>
          <td colspan="2">
            <p class="text-center no-margin-top-20 no-margin-left-24"><?php echo html_entity_decode($data_report['from_date'] .' - '. $data_report['to_date']); ?></p>
          </td>
          <td></td>
        </tr>
        <tr>
          <td>
          </td>
          <td></td>
        </tr>
        <tr class="tr_header">
          <td></td>
          <td class="th_total text-bold"><?php echo app_lang('total'); ?></td>
        </tr>
        <?php
          $row_index = 1;
          ?>
          <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-10000">
            <td >
              <?php echo app_lang('total_taxable_sales_in_period_before_tax'); ?> 
            </td>
            <td class="total_amount">
              <?php echo to_currency($data_report['data']['total_taxable_sales_in_period_before_tax'], $currency_symbol); ?> 
            </td>
          </tr>
          <?php $row_index += 1; ?>
          <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-10000">
            <td >
              <?php echo app_lang('tax_collected_on_sales'); ?> 
            </td>
            <td class="total_amount">
              <?php echo to_currency($data_report['data']['tax_collected_on_sales'], $currency_symbol); ?> 
            </td>
          </tr>
          <?php $row_index += 1; ?>
          <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-10000">
            <td >
              <?php echo app_lang('adjustments_to_tax_on_sales'); ?> 
            </td>
            <td class="total_amount">
              <?php echo to_currency($data_report['data']['adjustments_to_tax_on_sales'], $currency_symbol); ?> 
            </td>
          </tr>
          <?php $row_index += 1; ?>
          <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-10000 border_top">
            <td >
              <?php echo app_lang('subtotal_of_tax_on_sales_uppercase'); ?> 
            </td>
            <td class="total_amount">
              <?php echo to_currency($data_report['data']['tax_collected_on_sales']+$data_report['data']['adjustments_to_tax_on_sales'], $currency_symbol); ?> 
            </td>
          </tr>
          <?php $row_index += 1; ?>
          <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-10000">
            <td >
              <?php echo app_lang('total_taxable_purchases_in_period_before_tax'); ?> 
            </td>
            <td class="total_amount">
              <?php echo to_currency($data_report['data']['total_taxable_purchases_in_period_before_tax'], $currency_symbol); ?> 
            </td>
          </tr>
          <?php $row_index += 1; ?>
          <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-10000">
            <td >
              <?php echo app_lang('tax_reclaimable_on_purchases'); ?> 
            </td>
            <td class="total_amount">
              <?php echo to_currency($data_report['data']['tax_reclaimable_on_purchases'], $currency_symbol); ?> 
            </td>
          </tr>
          <?php $row_index += 1; ?>
          <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-10000">
            <td >
              <?php echo app_lang('adjustments_to_reclaimable_tax_on_purchases'); ?> 
            </td>
            <td class="total_amount">
              <?php echo to_currency($data_report['data']['adjustments_to_reclaimable_tax_on_purchases'], $currency_symbol); ?> 
            </td>
          </tr>
          <?php $row_index += 1; ?>
          <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-10000 border_top">
            <td >
              <?php echo app_lang('subtotal_of_tax_on_purchases_uppercase'); ?> 
            </td>
            <td class="total_amount">
              <?php echo to_currency($data_report['data']['adjustments_to_reclaimable_tax_on_purchases'] + $data_report['data']['tax_reclaimable_on_purchases'], $currency_symbol); ?> 
            </td>
          </tr>
          <?php $row_index += 1; ?>
          <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-10000 border_top">
            <td >
              <?php echo app_lang('balance_owing_for_period_uppercase'); ?> 
            </td>
            <td class="total_amount">
              <?php echo to_currency(($data_report['data']['tax_collected_on_sales']+$data_report['data']['adjustments_to_tax_on_sales']) - ($data_report['data']['adjustments_to_reclaimable_tax_on_purchases'] + $data_report['data']['tax_reclaimable_on_purchases']), $currency_symbol); ?> 
            </td>
          </tr>
          <?php $row_index += 1; ?>
          <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-10000">
            <td >
              <?php echo app_lang('other_adjustments'); ?> 
            </td>
            <td class="total_amount">
              <?php echo to_currency($data_report['data']['other_adjustments'], $currency_symbol); ?> 
            </td>
          </tr>
          <?php $row_index += 1; ?>
          <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-10000 border_top">
            <td >
              <?php echo app_lang('current_balance_owing_for_period_uppercase'); ?> 
            </td>
            <td class="total_amount">
              <?php echo to_currency((($data_report['data']['tax_collected_on_sales']+$data_report['data']['adjustments_to_tax_on_sales']) - ($data_report['data']['adjustments_to_reclaimable_tax_on_purchases'] + $data_report['data']['tax_reclaimable_on_purchases'])) + $data_report['data']['other_adjustments'], $currency_symbol); ?> 
            </td>
          </tr>
          <?php $row_index += 1; ?>
          <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-10000">
            <td >
              <?php echo app_lang('tax_due_or_credit_from_previous_periods'); ?> 
            </td>
            <td class="total_amount">
              <?php echo to_currency($data_report['data']['tax_due_or_credit_from_previous_periods'], $currency_symbol); ?> 
            </td>
          </tr>
          <?php $row_index += 1; ?>
          <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-10000">
            <td >
              <?php echo app_lang('tax_payments_made_this_period'); ?> 
            </td>
            <td class="total_amount">
              <?php echo to_currency($data_report['data']['tax_payments_made_this_period'], $currency_symbol); ?> 
            </td>
          </tr>
          <?php $row_index += 1; ?>
          <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-10000 border_top">
            <td >
              <?php echo app_lang('total_amount_due_uppercase'); ?> 
            </td>
            <td class="total_amount">
              <?php echo to_currency((($data_report['data']['tax_collected_on_sales']+$data_report['data']['adjustments_to_tax_on_sales']) - ($data_report['data']['adjustments_to_reclaimable_tax_on_purchases'] + $data_report['data']['tax_reclaimable_on_purchases'])) + $data_report['data']['other_adjustments'] + $data_report['data']['tax_due_or_credit_from_previous_periods'] + $data_report['data']['tax_payments_made_this_period'], $currency_symbol); ?> 
            </td>
          </tr>
          <?php $row_index += 1; ?>
        </tbody>
    </table>
  </div>
</div>