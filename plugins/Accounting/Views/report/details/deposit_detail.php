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
          </tr>
          <tr>
            <td colspan="5">
              <h4 class="text-center no-margin-top-20 no-margin-left-24"><?php echo app_lang('deposit_detail'); ?></h4>
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
            <td class="total_amount text-bold"><?php echo app_lang('acc_amount'); ?></td>
          </tr>
          <?php
           $row_index = 0;
           $parent_index = 0;

           $data = $Accounting_model->get_html_deposit_detail($data_report['data']['accounts_receivable'], ['html' => '', 'row_index' => $row_index + 1, 'total_credit' => 0, 'total_debit' => 0], $parent_index, $currency_symbol);
          $row_index = $data['row_index'];
          echo html_entity_decode($data['html']);

          $data = $Accounting_model->get_html_deposit_detail($data_report['data']['cash_and_cash_equivalents'], ['html' => '', 'row_index' => $row_index + 1, 'total_credit' => 0, 'total_debit' => 0], $parent_index, $currency_symbol);
          $row_index = $data['row_index'];
          echo html_entity_decode($data['html']);

          $data = $Accounting_model->get_html_deposit_detail($data_report['data']['current_assets'], ['html' => '', 'row_index' => $row_index + 1, 'total_credit' => 0, 'total_debit' => 0], $parent_index, $currency_symbol);
          $row_index = $data['row_index'];
          echo html_entity_decode($data['html']);

          $data = $Accounting_model->get_html_deposit_detail($data_report['data']['fixed_assets'], ['html' => '', 'row_index' => $row_index + 1, 'total_credit' => 0, 'total_debit' => 0], $parent_index, $currency_symbol);
          $row_index = $data['row_index'];
          echo html_entity_decode($data['html']);

          $data = $Accounting_model->get_html_deposit_detail($data_report['data']['non_current_assets'], ['html' => '', 'row_index' => $row_index + 1, 'total_credit' => 0, 'total_debit' => 0], $parent_index, $currency_symbol);
          $row_index = $data['row_index'];
          echo html_entity_decode($data['html']);

          $data = $Accounting_model->get_html_deposit_detail($data_report['data']['accounts_payable'], ['html' => '', 'row_index' => $row_index + 1, 'total_credit' => 0, 'total_debit' => 0], $parent_index, $currency_symbol);
          $row_index = $data['row_index'];
          echo html_entity_decode($data['html']);

          $data = $Accounting_model->get_html_deposit_detail($data_report['data']['credit_card'], ['html' => '', 'row_index' => $row_index + 1, 'total_credit' => 0, 'total_debit' => 0], $parent_index, $currency_symbol);
          $row_index = $data['row_index'];
          echo html_entity_decode($data['html']);

          $data = $Accounting_model->get_html_deposit_detail($data_report['data']['current_liabilities'], ['html' => '', 'row_index' => $row_index + 1, 'total_credit' => 0, 'total_debit' => 0], $parent_index, $currency_symbol);
          $row_index = $data['row_index'];
          echo html_entity_decode($data['html']);

          $data = $Accounting_model->get_html_deposit_detail($data_report['data']['non_current_liabilities'], ['html' => '', 'row_index' => $row_index + 1, 'total_credit' => 0, 'total_debit' => 0], $parent_index, $currency_symbol);
          $row_index = $data['row_index'];
          echo html_entity_decode($data['html']);

          $data = $Accounting_model->get_html_deposit_detail($data_report['data']['owner_equity'], ['html' => '', 'row_index' => $row_index + 1, 'total_credit' => 0, 'total_debit' => 0], $parent_index, $currency_symbol);
          $row_index = $data['row_index'];
          echo html_entity_decode($data['html']);
         
          ?>
        </tbody>
      </table>
    </div>
</div>