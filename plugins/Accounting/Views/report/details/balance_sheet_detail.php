<?php $Accounting_model = model("Accounting\Models\Accounting_model"); ?>
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
            <h4 class="text-center no-margin-top-20 no-margin-left-24"><?php echo app_lang('balance_sheet_detail'); ?></h4>
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
          <td class="text-bold"><?php echo app_lang('description'); ?></td>
          <td class="total_amount text-bold"><?php echo app_lang('debit'); ?></td>
          <td class="total_amount text-bold"><?php echo app_lang('credit'); ?></td>
          <td class="total_amount text-bold"><?php echo app_lang('acc_amount'); ?></td>
          <td class="total_amount text-bold"><?php echo app_lang('balance'); ?></td>
        </tr>
        <tr class="treegrid-100000 parent-node expanded">
          <td class="parent"><?php echo app_lang('acc_assets'); ?></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <?php
         $row_index = 0;
         $parent_index = 100000;
         $total_assets = 0;
          $data = $Accounting_model->get_html_balance_sheet_detail($data_report['data']['accounts_receivable'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0], $parent_index, $currency_symbol);
          $row_index = $data['row_index'];
          echo html_entity_decode($data['html']);
         $total_assets += $data['total_amount'];

          $data = $Accounting_model->get_html_balance_sheet_detail($data_report['data']['cash_and_cash_equivalents'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0], $parent_index, $currency_symbol);
          $row_index = $data['row_index'];
          echo html_entity_decode($data['html']);
          $total_assets += $data['total_amount'];

          $data = $Accounting_model->get_html_balance_sheet_detail($data_report['data']['current_assets'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0], $parent_index, $currency_symbol);
          $row_index = $data['row_index'];
          echo html_entity_decode($data['html']);
          $total_assets += $data['total_amount'];

          $data = $Accounting_model->get_html_balance_sheet_detail($data_report['data']['fixed_assets'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0], $parent_index, $currency_symbol);
          $row_index = $data['row_index'];
          echo html_entity_decode($data['html']);
          $total_assets += $data['total_amount'];

          $data = $Accounting_model->get_html_balance_sheet_detail($data_report['data']['non_current_assets'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0], $parent_index, $currency_symbol);
          $row_index = $data['row_index'];
          echo html_entity_decode($data['html']);
          $total_assets += $data['total_amount'];

          ?>
        <?php $row_index += 1; ?>
          <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> parent-node expanded tr_total">
            <td class="parent"><?php echo app_lang('total_assets'); ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount"><?php echo to_currency($total_assets, $currency_symbol); ?> </td>
            <td></td>
          </tr>
        <tr class="treegrid-100001 parent-node expanded">
          <td class="parent"><?php echo app_lang('liabilities_and_shareholders_equity'); ?></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <?php $row_index += 1;
          $_parent_index = $row_index; 
          ?>
        <tr class="treegrid-<?php echo html_entity_decode($_parent_index); ?> treegrid-parent-100001 parent-node expanded">
          <td class="parent"><?php echo app_lang('liabilities'); ?></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <?php 
        $total_liabilities = 0;
          $data = $Accounting_model->get_html_balance_sheet_detail($data_report['data']['accounts_payable'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0], $_parent_index, $currency_symbol);
          $row_index = $data['row_index'];
          echo html_entity_decode($data['html']);
         $total_liabilities += $data['total_amount'];

         $data = $Accounting_model->get_html_balance_sheet_detail($data_report['data']['credit_card'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0], $_parent_index, $currency_symbol);
          $row_index = $data['row_index'];
          echo html_entity_decode($data['html']);
         $total_liabilities += $data['total_amount'];

         $data = $Accounting_model->get_html_balance_sheet_detail($data_report['data']['current_liabilities'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0], $_parent_index, $currency_symbol);
          $row_index = $data['row_index'];
          echo html_entity_decode($data['html']);
         $total_liabilities += $data['total_amount'];

         $data = $Accounting_model->get_html_balance_sheet_detail($data_report['data']['non_current_liabilities'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0], $_parent_index, $currency_symbol);
          $row_index = $data['row_index'];
          echo html_entity_decode($data['html']);
         $total_liabilities += $data['total_amount'];

         $row_index += 1;
         ?>
         <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-100001 parent-node expanded tr_total">
            <td class="parent"><?php echo sprintf(app_lang('total_for'), app_lang('liabilities')); ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount"><?php echo to_currency($total_liabilities, $currency_symbol); ?> </td>
            <td></td>
          </tr>
         <?php
         $row_index += 1;
          $_parent_index = $row_index ; 
          $total_equity = 0;
         ?>
         <tr class="treegrid-<?php echo html_entity_decode($_parent_index); ?> treegrid-parent-100001 parent-node expanded">
          <td class="parent"><?php echo app_lang('equity'); ?></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
         <?php 
         $data = $Accounting_model->get_html_balance_sheet_detail($data_report['data']['owner_equity'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0], $_parent_index, $currency_symbol);
          $row_index = $data['row_index'];
          echo html_entity_decode($data['html']);
         $total_equity += $data['total_amount'];
         $total_equity += $data_report['net_income'];
         $row_index += 1;

        ?>
        <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-<?php echo html_entity_decode($_parent_index); ?> parent-node expanded tr_total">
            <td class="parent"><?php echo app_lang('acc_net_income'); ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount"><?php echo to_currency($data_report['net_income'], $currency_symbol); ?> </td>
            <td></td>
          </tr>
          <?php $row_index += 1; ?>
        
        <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-100001 parent-node expanded tr_total">
            <td class="parent"><?php echo sprintf(app_lang('total_for'), app_lang('equity')); ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount"><?php echo to_currency($total_equity, $currency_symbol); ?> </td>
            <td></td>
          </tr>
          <?php $row_index += 1; 
          $total_liabilities_and_equity = $total_equity + $total_liabilities;
        ?>
          <?php $row_index += 1; ?>
          <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-100011 parent-node expanded tr_total">
            <td class="parent"><?php echo app_lang('total_liabilities_and_equity'); ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount"><?php echo to_currency($total_liabilities_and_equity, $currency_symbol); ?> </td>
            <td></td>
          </tr>
      </tbody>
    </table>
  </div>
</div>