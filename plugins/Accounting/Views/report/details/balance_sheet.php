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
            <h4 class="text-center no-margin-top-20 no-margin-left-24"><?php echo app_lang('balance_sheet'); ?></h4>
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
        <tr class="treegrid-1000 parent-node expanded">
          <td class="parent"><?php echo app_lang('acc_assets'); ?></td>
          <td></td>
        </tr>
        <?php
          $row_index = 0;
          $parent_index = 0;
          $row_index += 1;
          $parent_index = $row_index;
          $total_current_assets = 0;
          ?>
          <tr class="treegrid-<?php echo html_entity_decode($parent_index); ?> treegrid-parent-1000 parent-node expanded">
            <td class="parent"><?php echo app_lang('acc_current_assets'); ?></td>
            <td></td>
          </tr>
          <?php
          $row_index += 1;
          ?>
          <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-<?php echo html_entity_decode($parent_index); ?> parent-node expanded">
            <td class="parent"><?php echo app_lang('acc_accounts_receivable'); ?></td>
            <td></td>
          </tr>
          <?php 
            $_index = $row_index;
            $data = $Accounting_model->get_html_balance_sheet($data_report['data']['accounts_receivable'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total_current_assets += $data['total_amount'];
            ?>
          <?php 
            $row_index += 1;
           ?>
          <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> treegrid-parent-<?php echo html_entity_decode($parent_index); ?> parent-node expanded tr_total">
            <td class="parent"><?php echo app_lang('total_accounts_receivable'); ?></td>
            <td class="total_amount"><?php echo to_currency($data['total_amount'], $currency_symbol); ?> </td>
          </tr>
          <?php 

            $data = $Accounting_model->get_html_balance_sheet($data_report['data']['cash_and_cash_equivalents'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total_current_assets += $data['total_amount'];

            $data = $Accounting_model->get_html_balance_sheet($data_report['data']['current_assets'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total_current_assets += $data['total_amount'];
          
            $row_index += 1;
          ?>
          <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> treegrid-parent-1000 parent-node expanded tr_total">
            <td class="parent"><?php echo app_lang('total_current_assets'); ?></td>
            <td class="total_amount"><?php echo to_currency($total_current_assets, $currency_symbol); ?> </td>
          </tr>
          <?php 
            $row_index += 1;
            $parent_index = $row_index;
            $total_long_term_assets = 0;
          ?>
          <tr class="treegrid-<?php echo html_entity_decode($parent_index); ?> treegrid-parent-1000 parent-node expanded">
            <td class="parent"><?php echo app_lang('long_term_assets'); ?></td>
            <td></td>
          </tr>
          <?php 
            $data = $Accounting_model->get_html_balance_sheet($data_report['data']['fixed_assets'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total_long_term_assets += $data['total_amount'];

            $data = $Accounting_model->get_html_balance_sheet($data_report['data']['non_current_assets'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total_long_term_assets += $data['total_amount'];

            $row_index += 1;
            ?>
          <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> treegrid-parent-1000 parent-node expanded tr_total">
            <td class="parent"><?php echo app_lang('total_long_term_assets'); ?></td>
            <td class="total_amount"><?php echo to_currency($total_long_term_assets, $currency_symbol); ?> </td>
          </tr>
          <?php 
            $row_index += 1;
            ?>
          <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> tr_total">
            <td class="parent"><?php echo app_lang('total_assets'); ?></td>
            <td class="total_amount"><?php echo to_currency($total_current_assets + $total_long_term_assets, $currency_symbol); ?> </td>
          </tr>
          <?php 
            $row_index += 1;
            ?>
            <tr class="treegrid-1001 parent-node expanded">
              <td class="parent"><?php echo app_lang('liabilities_and_shareholders_equity'); ?></td>
              <td></td>
            </tr>
            <?php
            $row_index += 1;
            $parent_index = $row_index;
            $total_current_liabilities = 0;
            ?>
            <tr class="treegrid-<?php echo html_entity_decode($parent_index); ?> treegrid-parent-1001 parent-node expanded">
              <td class="parent"><?php echo app_lang('acc_current_liabilities'); ?></td>
              <td></td>
            </tr>
            <?php $row_index += 1; ?>
            <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-<?php echo html_entity_decode($parent_index); ?> parent-node expanded">
              <td class="parent"><?php echo app_lang('accounts_payable'); ?></td>
              <td></td>
            </tr>
            <?php 
              $_index = $row_index;
              $data = $Accounting_model->get_html_balance_sheet($data_report['data']['accounts_payable'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $_index, $currency_symbol);
              $row_index = $data['row_index'];
              echo html_entity_decode($data['html']);
              $total_current_liabilities += $data['total_amount'];
              ?>
            <?php $row_index += 1; ?>
            <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> treegrid-parent-<?php echo html_entity_decode($parent_index); ?> tr_total">
              <td class="parent"><?php echo app_lang('total_accounts_payable'); ?></td>
              <td class="total_amount"><?php echo to_currency($data['total_amount'], $currency_symbol); ?> </td>
            </tr>
            <?php 
            $data = $Accounting_model->get_html_balance_sheet($data_report['data']['credit_card'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total_current_liabilities += $data['total_amount'];

            $data = $Accounting_model->get_html_balance_sheet($data_report['data']['current_liabilities'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total_current_liabilities += $data['total_amount'];
            ?>
           
            <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> treegrid-parent-1001 tr_total">
              <td class="parent"><?php echo app_lang('total_current_liabilities'); ?></td>
              <td class="total_amount"><?php echo to_currency($total_current_liabilities, $currency_symbol); ?> </td>
            </tr>
            <?php $row_index += 1; ?>
            <?php
            $row_index += 1;
            $parent_index = $row_index;
            $total_non_current_liabilities = 0;
            ?>
            <tr class="treegrid-<?php echo html_entity_decode($parent_index); ?> treegrid-parent-1001 parent-node expanded">
              <td class="parent"><?php echo app_lang('acc_non_current_liabilities'); ?></td>
              <td></td>
            </tr>
            <?php 
            $data = $Accounting_model->get_html_balance_sheet($data_report['data']['non_current_liabilities'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total_non_current_liabilities += $data['total_amount'];
            ?>

            <?php $row_index += 1; ?>
            <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> treegrid-parent-1001 tr_total">
              <td class="parent"><?php echo app_lang('total_non_current_liabilities'); ?></td>
              <td class="total_amount"><?php echo to_currency($total_non_current_liabilities, $currency_symbol); ?> </td>
            </tr>
            <?php $row_index += 1; ?>
            <?php
            $row_index += 1;
            $parent_index = $row_index;
            $total_shareholders_equity = $data_report['net_income'];
            ?>
            <tr class="treegrid-<?php echo html_entity_decode($parent_index); ?> treegrid-parent-1001 parent-node expanded">
              <td class="parent"><?php echo app_lang('shareholders_equity'); ?></td>
              <td></td>
            </tr>
            <?php $row_index += 1; ?>
            <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-<?php echo html_entity_decode($parent_index); ?>">
              <td >
                <?php echo app_lang('acc_net_income'); ?> 
              </td>
              <td class="total_amount">
                <?php echo to_currency($data_report['net_income'], $currency_symbol); ?> 
              </td>
            </tr>
            <?php $row_index += 1; ?>
            <?php 
            $data = $Accounting_model->get_html_balance_sheet($data_report['data']['owner_equity'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total_shareholders_equity += $data['total_amount'];
            ?>

            <?php $row_index += 1; ?>
            <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> treegrid-parent-1001 tr_total">
              <td class="parent"><?php echo app_lang('total_shareholders_equity'); ?></td>
              <td class="total_amount"><?php echo to_currency($total_shareholders_equity, $currency_symbol); ?> </td>
            </tr>
            <?php $row_index += 1; ?>
            <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> tr_total">
              <td class="parent"><?php echo app_lang('total_liabilities_and_equity'); ?></td>
              <td class="total_amount"><?php echo to_currency($total_shareholders_equity + $total_non_current_liabilities + $total_current_liabilities, $currency_symbol); ?> </td>
            </tr>
            <?php $row_index += 1; ?>
        </tbody>
    </table>
  </div>
</div>
