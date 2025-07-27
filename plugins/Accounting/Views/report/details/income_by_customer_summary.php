<div id="accordion">
  <div class="card">
    <table class="tree">
      <tbody>
        <tr>
          <td colspan="4">
              <h3 class="text-center no-margin-top-20 no-margin-left-24"><?php echo get_setting('companyname'); ?></h3>
          </td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="4">
            <h4 class="text-center no-margin-top-20 no-margin-left-24"><?php echo app_lang('income_by_customer_summary'); ?></h4>
          </td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="4">
            <p class="text-center no-margin-top-20 no-margin-left-24"><?php echo html_entity_decode($data_report['from_date'] .' - '. $data_report['to_date']); ?></p>
          </td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr class="tr_header">
          <td class="text-bold"><?php echo app_lang('customer'); ?></td>
          <td class="th_total text-bold"><?php echo app_lang('acc_income'); ?></td>
          <td class="th_total text-bold"><?php echo app_lang('expenses'); ?></td>
          <td class="th_total text-bold"><?php echo app_lang('acc_net_income'); ?></td>
        </tr>
        <?php
          $row_index = 1;
          $total_income = 0;
          $total_expenses = 0;
          $total_net_income = 0;
          ?>
          <?php 
        foreach ($data_report['list_customer'] as $key => $value) {
          if($value == ''){
            continue;
          }
          $income = isset($data_report['total']['income'][$value]) ? $data_report['total']['income'][$value] : 0;
          $expenses = isset($data_report['total']['expenses'][$value]) ? $data_report['total']['expenses'][$value] : 0;
          $cost_of_sales = isset($data_report['total']['cost_of_sales'][$value]) ? $data_report['total']['cost_of_sales'][$value] : 0;
          $other_income = isset($data_report['total']['other_income'][$value]) ? $data_report['total']['other_income'][$value] : 0;
          $other_expenses = isset($data_report['total']['other_expenses'][$value]) ? $data_report['total']['other_expenses'][$value] : 0;

          $_income = $income + $other_income;
          $_expenses = $expenses + $other_expenses + $cost_of_sales;
          $row_index += 1;
          $total_income += $_income;
          $total_expenses += $_expenses;
          $total_net_income += $_income - $_expenses;
          ?>
          <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> parent-node expanded">
            <td class="parent"><?php echo get_company_name($value); ?></td>
            <td class="total_amount"><?php echo to_currency($_income, $currency_symbol); ?> </td>
            <td class="total_amount"><?php echo to_currency($_expenses, $currency_symbol); ?> </td>
            <td class="total_amount"><?php echo to_currency(($_income - $_expenses), $currency_symbol); ?> </td>
          </tr>
        <?php } ?>
          <?php
            $row_index += 1;
           ?>
        <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?>  parent-node expanded tr_total">
            <td class="parent"><?php echo app_lang('total'); ?></td>
            <td class="total_amount"><?php echo to_currency($total_income, $currency_symbol); ?> </td>
            <td class="total_amount"><?php echo to_currency($total_expenses, $currency_symbol); ?> </td>
            <td class="total_amount"><?php echo to_currency($total_net_income, $currency_symbol); ?> </td>
          </tr>
        </tbody>
    </table>
  </div>
</div>