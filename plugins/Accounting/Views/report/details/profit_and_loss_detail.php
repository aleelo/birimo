<?php $Accounting_model = model("Accounting\Models\Accounting_model"); ?>
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
          <td></td>
        </tr>
        <tr>
          <td colspan="5">
            <h4 class="text-center no-margin-top-20 no-margin-left-24"><?php echo app_lang('profit_and_loss_detail'); ?></h4>
          </td>
          <td></td>
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
          <td></td>
        </tr>
        <tr>
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
          <td class="text-bold"><?php echo app_lang('split'); ?></td>
          <td class="total_amount text-bold"><?php echo app_lang('acc_amount'); ?></td>
          <td class="total_amount text-bold"><?php echo app_lang('balance'); ?></td>
        </tr>
        <tr class="treegrid-1000 parent-node expanded">
          <td class="parent"><?php echo app_lang('acc_ordinary_income_expenses'); ?></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <?php
         $row_index = 1;
         $parent_index = 1; ?>

        <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-1000 parent-node expanded">
          <td class="parent"><?php echo app_lang('acc_income'); ?></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <?php
        $total_income = 0;
        $data = $Accounting_model->get_html_profit_and_loss_detail($data_report['data']['income'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total_income = $data['total_amount'];

         ?>
        <?php $row_index += 1; ?>
          <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> treegrid-parent-<?php echo html_entity_decode($parent_index); ?> parent-node expanded tr_total">
            <td class="parent"><?php echo sprintf(app_lang('total_for'), app_lang('acc_income')); ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount"><?php echo to_currency($total_income, $currency_symbol); ?> </td>
            <td></td>
          </tr>

        <?php
         $row_index += 1;
         $parent_index = $row_index; ?>
        <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-1000 parent-node expanded">
          <td class="parent"><?php echo app_lang('acc_cost_of_sales'); ?></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <?php 
          $data = $Accounting_model->get_html_profit_and_loss_detail($data_report['data']['cost_of_sales'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total_cost_of_sales = $data['total_amount'];
         ?>
        <?php $row_index += 1; ?>
          <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> treegrid-parent-<?php echo html_entity_decode($parent_index); ?> parent-node expanded tr_total">
            <td class="parent"><?php echo sprintf(app_lang('total_for'), app_lang('acc_cost_of_sales')); ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount"><?php echo to_currency($total_cost_of_sales, $currency_symbol); ?> </td>
            <td></td>
          </tr>
        <?php $row_index += 1; ?>
        <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-1000 parent-node expanded tr_total">
          <td class="parent"><?php echo app_lang('gross_profit'); ?></td>
          <td></td>
          <td></td>
          <td></td>
          <td class="total_amount"><?php echo to_currency($total_income - $total_cost_of_sales, $currency_symbol); ?></td>
          <td></td>
        </tr>
        <?php
         $row_index += 1;
         $parent_index = $row_index; ?>
        <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-1000 parent-node expanded">
          <td class="parent"><?php echo app_lang('acc_other_income'); ?></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <?php 
        $data = $Accounting_model->get_html_profit_and_loss_detail($data_report['data']['other_income'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total_other_income = $data['total_amount'];
         ?>
        <?php $row_index += 1; ?>
          <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> treegrid-parent-<?php echo html_entity_decode($parent_index); ?> parent-node expanded tr_total">
            <td class="parent"><?php echo sprintf(app_lang('total_for'), app_lang('acc_other_income')); ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount"><?php echo to_currency($total_other_income, $currency_symbol); ?> </td>
            <td></td>
          </tr>
        <?php
         $row_index += 1;
         $parent_index = $row_index; ?>
        <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-1000 parent-node expanded">
          <td class="parent"><?php echo app_lang('acc_expenses'); ?></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <?php 
        $data = $Accounting_model->get_html_profit_and_loss_detail($data_report['data']['expenses'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total_expenses = $data['total_amount'];
         ?>
        <?php $row_index += 1; ?>
          <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> treegrid-parent-<?php echo html_entity_decode($parent_index); ?> parent-node expanded tr_total">
            <td class="parent"><?php echo sprintf(app_lang('total_for'), app_lang('acc_expenses')); ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount"><?php echo to_currency($total_expenses, $currency_symbol); ?> </td>
            <td></td>
          </tr>
        <?php
         $row_index += 1;
         $parent_index = $row_index; ?>
        <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-1000 parent-node expanded">
          <td class="parent"><?php echo app_lang('acc_other_expenses'); ?></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <?php 
        $data = $Accounting_model->get_html_profit_and_loss_detail($data_report['data']['other_expenses'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency_symbol);
            $row_index = $data['row_index'];
            echo html_entity_decode($data['html']);
            $total_other_expenses = $data['total_amount'];
         ?>
          <?php $row_index += 1; ?>
          <tr class="treegrid-total-<?php echo html_entity_decode($row_index); ?> treegrid-parent-<?php echo html_entity_decode($parent_index); ?> parent-node expanded tr_total">
            <td class="parent"><?php echo sprintf(app_lang('total_for'), app_lang('acc_other_expenses')); ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount"><?php echo to_currency($total_other_expenses, $currency_symbol); ?> </td>
            <td></td>
          </tr>
          <?php $row_index += 1; ?>
        <tr class="treegrid-<?php echo html_entity_decode($row_index); ?> treegrid-parent-1000 parent-node expanded tr_total">
          <td class="parent"><?php echo app_lang('acc_net_income'); ?></td>
          <td></td>
          <td></td>
          <td></td>
          <td class="total_amount"><?php echo to_currency(($total_income + $total_other_income) - ($total_cost_of_sales + $total_expenses + $total_other_expenses), $currency_symbol); ?></td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>