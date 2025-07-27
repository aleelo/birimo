<?php $Accounting_model = model("Accounting\Models\Accounting_model"); ?>
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
            <h4 class="text-center no-margin-top-20 no-margin-left-24"><?php echo app_lang('statement_of_cash_flows'); ?></h4>
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
          $parent_index = 1;
          $total_cash_flows_from_operating_activities = 0;
          $total = 0;
          ?>
        <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> parent-node expanded">
          <td class="parent"><?php echo app_lang('cash_flows_from_operating_activities'); ?></td>
          <td></td>
        </tr>
        <?php $row_index += 1; ?>
        <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-<?php echo html_entity_decode($parent_index); ?> parent-node expanded">
          <td class="parent"><?php echo app_lang('profit_for_the_year'); ?></td>
          <td class="total_amount"><?php echo to_currency($data_report['net_income'], $currency_symbol); ?> </td>
        </tr>
        <?php
          $total_cash_flows_from_operating_activities += $data_report['net_income'];
          ?>
        <?php $row_index += 1; ?>
        <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-<?php echo html_entity_decode($parent_index); ?> parent-node expanded">
          <td class="parent"><?php echo app_lang('adjustments_for_non_cash_income_and_expenses'); ?></td>
          <td></td>
        </tr>
        <?php $parent_index = $row_index; ?>
        <?php $row_index += 1; ?>
          <?php 
            $_index = $row_index;

            $data = $Accounting_model->get_html_statement_of_cash_flows($data_report['data']['accounts_receivable'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total += $data['total_amount'];

            $data = $Accounting_model->get_html_statement_of_cash_flows($data_report['data']['current_assets_1'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total += $data['total_amount'];

            $data = $Accounting_model->get_html_statement_of_cash_flows($data_report['data']['fixed_assets_1'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total += $data['total_amount'];

            $data = $Accounting_model->get_html_statement_of_cash_flows($data_report['data']['non_current_assets_1'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total += $data['total_amount'];

            $data = $Accounting_model->get_html_statement_of_cash_flows($data_report['data']['accounts_payable'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total += $data['total_amount'];

            $data = $Accounting_model->get_html_statement_of_cash_flows($data_report['data']['credit_card'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total += $data['total_amount'];

            $data = $Accounting_model->get_html_statement_of_cash_flows($data_report['data']['current_liabilities'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total += $data['total_amount'];

            $data = $Accounting_model->get_html_statement_of_cash_flows($data_report['data']['non_current_liabilities_1'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total += $data['total_amount'];

            $data = $Accounting_model->get_html_statement_of_cash_flows($data_report['data']['cash_flows_from_operating_activities'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total += $data['total_amount'];

            $row_index += 1;
           ?>
            
            <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> treegrid-parent-<?php echo html_entity_decode($parent_index); ?> parent-node expanded tr_total">
              <td class="parent"><?php echo app_lang('total_adjustments_for_non_cash_income_and_expenses'); ?></td>
              <td class="total_amount"><?php echo to_currency($total, $currency_symbol); ?> </td>
            </tr>
            <?php $total_cash_flows_from_operating_activities += $total; ?>
            <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> treegrid-parent-1 parent-node expanded tr_total">
              <td class="parent"><?php echo app_lang('net_cash_from_operating_activities'); ?></td>
              <td class="total_amount"><?php echo to_currency($total_cash_flows_from_operating_activities, $currency_symbol); ?> </td>
            </tr>
            <?php 
              $row_index += 1; 
              $net_cash_used_in_investing_activities = 0;
            ?>
            <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> parent-node expanded">
              <td class="parent"><?php echo app_lang('cash_flows_from_investing_activities'); ?></td>
              <td></td>
            </tr>
            <?php $parent_index = $row_index; ?>
            <?php 
            $data = $Accounting_model->get_html_statement_of_cash_flows($data_report['data']['current_assets_2'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $net_cash_used_in_investing_activities += $data['total_amount'];

            $data = $Accounting_model->get_html_statement_of_cash_flows($data_report['data']['fixed_assets_2'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $net_cash_used_in_investing_activities += $data['total_amount'];

            $data = $Accounting_model->get_html_statement_of_cash_flows($data_report['data']['non_current_assets_2'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $net_cash_used_in_investing_activities += $data['total_amount'];

            $data = $Accounting_model->get_html_statement_of_cash_flows($data_report['data']['cash_flows_from_investing_activities'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $net_cash_used_in_investing_activities += $data['total_amount'];

            $row_index += 1;
          ?>
            <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> treegrid-parent-<?php echo html_entity_decode($parent_index); ?> parent-node expanded tr_total">
              <td class="parent"><?php echo app_lang('net_cash_used_in_investing_activities'); ?></td>
              <td class="total_amount"><?php echo to_currency($net_cash_used_in_investing_activities, $currency_symbol); ?> </td>
            </tr>
            <?php $row_index += 1; ?>
            <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> parent-node expanded">
              <td class="parent"><?php echo app_lang('cash_flows_from_financing_activities'); ?></td>
              <td></td>
            </tr>
            <?php $parent_index = $row_index; 
              $net_cash_used_in_financing_activities = 0;
            ?>
            <?php 
            $data = $Accounting_model->get_html_statement_of_cash_flows($data_report['data']['non_current_liabilities_2'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $net_cash_used_in_financing_activities += $data['total_amount'];

            $data = $Accounting_model->get_html_statement_of_cash_flows($data_report['data']['owner_equity'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $net_cash_used_in_financing_activities += $data['total_amount'];

            $data = $Accounting_model->get_html_statement_of_cash_flows($data_report['data']['cash_flows_from_financing_activities'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $net_cash_used_in_financing_activities += $data['total_amount'];

            $row_index += 1; ?>
            <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> treegrid-parent-<?php echo html_entity_decode($parent_index); ?> parent-node expanded tr_total">
              <td class="parent"><?php echo app_lang('net_cash_used_in_financing_activities'); ?></td>
              <td class="total_amount"><?php echo to_currency($net_cash_used_in_financing_activities, $currency_symbol); ?> </td>
            </tr>
            <?php $row_index += 1; ?>
            <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> parent-node expanded tr_total">
              <td class="parent"><?php echo app_lang('net_increase_decrease_in_cash_and_cash_equivalents_uppercase'); ?></td>
              <td class="total_amount"><?php echo to_currency($net_cash_used_in_financing_activities + $net_cash_used_in_investing_activities + $total_cash_flows_from_operating_activities, $currency_symbol); ?> </td>
            </tr>
            <?php $row_index += 1; ?>
            <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> parent-node expanded hide">
              <td class="parent"><?php echo app_lang('cash_and_cash_equivalents_at_beginning_of_year'); ?></td>
              <td></td>
            </tr>
            <?php $parent_index = $row_index; 
            $total = 0;

            $data = $Accounting_model->get_html_statement_of_cash_flows($data_report['data']['cash_and_cash_equivalents'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];

            $data = $Accounting_model->get_html_statement_of_cash_flows($data_report['data']['current_assets_3'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];

            $data = $Accounting_model->get_html_statement_of_cash_flows($data_report['data']['cash_and_cash_equivalents_at_beginning_of_year'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];

            ?>
          <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> treegrid-parent-<?php echo html_entity_decode($parent_index); ?> parent-node expanded tr_total hide">
              <td class="parent"><?php echo app_lang('total_cash_and_cash_equivalents_at_beginning_of_year'); ?></td>
              <td class="total_amount"><?php echo to_currency($total, $currency_symbol); ?> </td>
            </tr>
          <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> parent-node expanded tr_total">
            <td class="parent"><?php echo app_lang('cash_and_cash_equivalents_at_end_of_year_uppercase'); ?></td>
            <td class="total_amount"><?php echo to_currency($total + $net_cash_used_in_financing_activities + $net_cash_used_in_investing_activities + $total_cash_flows_from_operating_activities, $currency_symbol); ?> </td>
          </tr>
        </tbody>
    </table>
  </div>
</div>