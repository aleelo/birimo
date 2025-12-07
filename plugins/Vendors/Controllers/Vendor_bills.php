<?php

namespace Vendors\Controllers;

use App\Libraries\Paytm;
use Config\Services;
use App\Libraries\E_invoice;
use App\Libraries\Dropdown_list;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Days;

class Vendor_bills extends Security_Controller_Plugin_vendor
{

    function __construct()
    {
        parent::__construct();
        // Load models (see models below)
        $this->Vendor_bills_model        = new \Vendors\Models\Vendor_bills_model();
        $this->Vendor_bill_items_model   = new \Vendors\Models\Vendor_bill_items_model();
        $this->Vendor_items_model        = new \Vendors\Models\Vendor_items_model(); // optional (for item lookup)
        $this->db                        = \Config\Database::connect();

        // Gate 1: Must have CRM permission
        if (!$this->can_manage_vendors()) {
            app_redirect("forbidden");
        }

        // Gate 2: (only after CRM is OK) must also have items permission
        if (!$this->can_hide_vendor_bills()) {
            app_redirect("forbidden");
        }
    }

    /* load invoice list view */

    function index($tab = "", $status = "", $selected_currency = "")
    {
        // custom fields (use the vendor_bills entity, not invoices)
        $view_data["custom_field_headers"] = $this->Custom_fields_model
            ->get_custom_field_headers_for_table("vendor_bills", $this->login_user->is_admin, $this->login_user->user_type);
        $view_data["custom_field_filters"] = $this->Custom_fields_model
            ->get_custom_field_filters("vendor_bills", $this->login_user->is_admin, $this->login_user->user_type);

        // permissions: expose the flag your view actually checks
        $view_data["can_add_vendor_bills"] = $this->can_add_new_vendor_bill();
        $view_data["can_add_new_vendor_bill_payment"] = $this->can_add_new_vendor_bill_payment();

        // (optional) if you show payments/actions like invoices
        $view_data["can_update_vendor_bill"]    = $this->can_update_vendor_bill();

        if ($this->login_user->user_type === "staff") {
            // if (!$this->can_hide_invoices()) {
            //     app_redirect("forbidden");
            // }

            $view_data['tab']               = clean_data($tab);
            $view_data['status']            = clean_data($status);
            $view_data['selected_currency'] = clean_data($selected_currency);

            // currency dropdown / conversion (reuse your helpers)
            $view_data["currencies_dropdown"] = $this->_get_currencies_dropdown(true, $selected_currency);
            $view_data["conversion_rate"]     = $this->get_conversion_rate_with_currency_symbol();
            $view_data['vendors_dropdown'] = $this->_get_vendors_dropdown_json();


            // Branch functionality removed - not needed in vendors plugin
            // $branch_id = $this->get_user_branch_access_view();
            // if (is_array($branch_id) || !$branch_id) {
            //     $hide_branch_dropdown = true;
            // } else {
            //     $hide_branch_dropdown = false;
            // }

            // Branch functionality removed - not needed in vendors plugin
            $view_data['hide_branch_dropdown'] = true;
            // $view_data['branch_dropdown']      = $this->_get_branch();
            $view_data['branch_dropdown']      = json_encode(array(array("id" => "", "text" => "-")));

            return $this->template->rander("Vendors\Views/vendor_bills/index", $view_data);
        } else {
            // client view – reuse client invoices page or create a vendor bills client page later
            if (!$this->can_client_access("invoice")) {
                app_redirect("forbidden");
            }
            $view_data["client_info"] = $this->Clients_model->get_one($this->login_user->client_id);
            $view_data['client_id']   = $this->login_user->client_id;
            $view_data['page_type']   = "full";
            return $this->template->rander("Vendors\Views/clients/invoices/index", $view_data);
        }
    }



    /* load new invoice modal */

    function modal_form()
    {
        $invoice_id = $this->request->getPost('id');
        $is_clone = $this->request->getPost('is_clone');

        // if (!$this->can_update_invoices()) {
        //     app_redirect("forbidden");
        // }

        // if (!$this->is_invoice_editable($invoice_id, $is_clone)) {
        //     app_redirect("forbidden");
        // }

        $this->validate_submitted_data(array(
            "id" => "numeric",
            "client_id" => "numeric",
            "project_id" => "numeric"
        ));

        $client_id = $this->request->getPost('client_id');
        $project_id = $this->request->getPost('project_id');
        $model_info = $this->Vendor_bills_model->get_one($invoice_id);

        //check if estimate_id/order_id/proposal_id/contract_id posted. if found, generate related information
        $estimate_id = $this->request->getPost('estimate_id');
        $contract_id = $this->request->getPost('contract_id');
        $proposal_id = $this->request->getPost('proposal_id');
        $order_id = $this->request->getPost('order_id');
        $view_data['estimate_id'] = $estimate_id;
        $view_data['contract_id'] = $contract_id;
        $view_data['proposal_id'] = $proposal_id;
        $view_data['order_id'] = $order_id;

        if ($estimate_id || $order_id || $proposal_id || $contract_id) {
            $info = null;
            if ($estimate_id) {
                $info = $this->Estimates_model->get_one($estimate_id);
            } else if ($order_id) {
                $info = $this->Orders_model->get_one($order_id);
            } else if ($contract_id) {
                $info = $this->Contracts_model->get_one($contract_id);
            } else if ($proposal_id) {
                $info = $this->Proposals_model->get_one($proposal_id);
            }

            if ($info) {
                $now = get_my_local_time("Y-m-d");
                $model_info->bill_date = $now;
                $model_info->due_date = $now;
                $model_info->client_id = $info->client_id;
                $model_info->tax_id = $info->tax_id;
                $model_info->tax_id2 = $info->tax_id2;
                $model_info->discount_amount = $info->discount_amount;
                $model_info->discount_amount_type = $info->discount_amount_type;
                $model_info->discount_type = $info->discount_type;
                $model_info->note = $info->note;
            }
        }

        //here has a project id. now set the client from the project
        // Projects_model removed - not needed in vendors plugin
        if ($project_id) {
            // $client_id = $this->Projects_model->get_one($project_id)->client_id;
            // $model_info->client_id = $client_id;
            // Note: client_id should be set from another source if needed
        }


        $project_client_id = $client_id;
        if ($model_info->client_id) {
            $project_client_id = $model_info->client_id;
        }

        $view_data['model_info'] = $model_info;

        //make the drodown lists
        $view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));

        $client_options = array("is_lead" => 0);
        $owner_id = $this->show_own_client_invoice_user_id();
        if ($owner_id) {
            $client_options['owner_id'] = $owner_id;
        }

        // Projects_model removed - not needed in vendors plugin
        // $projects = $this->Projects_model->get_dropdown_list(array("title"), "id", array("client_id" => $project_client_id, "project_type" => "client_project"));
        $suggestion = array(array("id" => "", "text" => "-"));
        // foreach ($projects as $key => $value) {
        //     $suggestion[] = array("id" => $key, "text" => $value);
        // }
        $view_data['projects_suggestion'] = $suggestion;

        $view_data['client_id'] = $client_id;
        $view_data['project_id'] = $project_id;




        $dropdown_list = new Dropdown_list($this);
        // $view_data['clients_dropdown'] = $dropdown_list->get_clients_id_and_text_dropdown();
        // $view_data['clients_dropdown'] = $this->get_invoice_clients_and_leads_dropdown();


        //prepare label suggestions
        $view_data['label_suggestions'] = $this->make_labels_dropdown("invoice", $model_info->labels);

        //clone invoice
        $view_data['is_clone'] = $is_clone;

        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("invoices", $model_info->id, $this->login_user->is_admin, $this->login_user->user_type)->getResult();

        // Company functionality removed - not needed in vendors plugin
        // $view_data['companies_dropdown'] = $this->_get_companies_dropdown();
        // if (!$model_info->company_id) {
        //     $view_data['model_info']->company_id = $this->get_default_company_id();
        //     $view_data['model_info']->company_id = 1;
        // }

        return $this->template->view('Vendors\Views/vendor_bills/modal_form', $view_data);
    }


    // Vendor Bill

    /* ========= CREATE ========= */
    public function create()
    {
        $view_data = [];
        // Branch functionality removed - not needed in vendors plugin
        // Branch functionality removed - not needed in vendors plugin
        // $view_data['branch_dropdown']   = $this->_get_branch_map();
        $view_data['branch_dropdown']   = array();
        $view_data['vendors_dropdown']  = $this->_vendors_map(null);
        // Projects and Phases removed - replaced with Invoice dropdown
        $view_data['invoices_dropdown'] = $this->_invoices_map();
        $view_data['items_dropdown']    = $this->_vendor_items_map();
        $view_data['accounts_dropdown'] = $this->_accounts_map();

        $view_data['model_info'] = (object)[
            "id"              => "",
            "vendor_id"       => "",
            "accounting_date" => date('Y-m-d'),
            "bill_date"       => date('Y-m-d'),
            "due_date"        => null,
            "status"          => "draft",
            "total"           => "0.00",
            "invoice_id"      => null,
        ];
        $view_data['existing_lines'] = [];

        return $this->template->rander('Vendors\Views/vendor_bills/vendor_bill_form', $view_data);
    }

    /* ========= EDIT ========= */
    public function edit($id = null)
    {
        $id = (int)$id;
        if (!$id) return redirect()->to(get_uri('vendor_bills'));

        $bill = $this->db->table(get_db_prefix() . 'vendor_bills')
            ->where('id', $id)
            ->get()->getRow();

        if (!$bill) return redirect()->to(get_uri('vendor_bills'));

        // FK is vendor_bill_id; price column is 'rate'
        $lines = $this->db->table(get_db_prefix() . 'vendor_bill_items')
            ->where('vendor_bill_id', $id)
            ->where('deleted', 0)
            ->orderBy('sort', 'asc')
            ->orderBy('id', 'asc')
            ->get()->getResult();

        // dropdowns
        // Branch functionality removed - not needed in vendors plugin
        // Branch functionality removed - not needed in vendors plugin
        // $view_data['branch_dropdown']   = $this->_get_branch_map();
        $view_data['branch_dropdown']   = array();
        $view_data['vendors_dropdown']  = $this->_vendors_map(null);
        // Projects and Phases removed - replaced with Invoice dropdown
        $view_data['invoices_dropdown'] = $this->_invoices_map();
        $view_data['items_dropdown']    = $this->_vendor_items_map();
        $view_data['accounts_dropdown'] = $this->_accounts_map();

        // header model
        $view_data['model_info'] = (object)[
            "id"              => $bill->id,
            "vendor_id"       => $bill->vendor_id,
            "bill_date"       => $bill->bill_date,
            "due_date"        => $bill->due_date,
            "status"          => $bill->status,
            "total"           => isset($bill->invoice_total) ? $bill->invoice_total : null,
            "accounting_date" => $bill->accounting_date,
            "invoice_id"      => isset($bill->invoice_id) ? $bill->invoice_id : null,
        ];

        // map DB -> UI; IMPORTANT: include 'id' so we can update in-place
        $existing = [];
        foreach ($lines as $ln) {
            $existing[] = [
                'id'          => (int)$ln->id,                  // keep primary key
                'item_id'     => $ln->item_id ? (string)$ln->item_id : null,
                'account_id'  => $ln->account_id ? (string)$ln->account_id : null,
                'description' => (string)$ln->description,
                'quantity'    => (float)$ln->quantity,          // preserves quantity
                'unit_price'  => (float)$ln->rate,              // UI field name
                // Invoice removed from line items - now at bill level
                'days'        => isset($ln->days) ? (float)$ln->days : 1,
                'sort'        => isset($ln->sort) ? (int)$ln->sort : 0,
            ];
        }
        $view_data['existing_lines'] = $existing;

        return $this->template->rander('Vendors\Views/vendor_bills/vendor_bill_form', $view_data);
    }

    /* ========= SAVE (create/update with real UPSERT) ========= */
    // public function save()
    // {
    //     $this->access_only_team_members();

    //     // 1) Validate header
    //     $this->validate_submitted_data([
    //         "vendor_id"       => "required|numeric",
    //         "bill_date"       => "required",
    //         "accounting_date" => "required" // your form requires it
    //     ]);

    //     $id              = (int) $this->request->getPost('id'); // 0=create, >0=edit
    //     $vendor_id       = (int) $this->request->getPost('vendor_id');
    //     $accounting_date = $this->request->getPost('accounting_date') ?: date('Y-m-d'); // UI-only
    //     $bill_date       = $this->request->getPost('bill_date');
    //     $due_date        = $this->request->getPost('due_date') ?: null;
    //     $status          = $this->request->getPost('status') ?: 'draft';

    //     // 2) Parse & normalize posted lines
    //     $items_json = (string) $this->request->getPost('items');
    //     $rows = $items_json ? json_decode($items_json, true) : [];
    //     if (!is_array($rows)) $rows = [];

    //     $normRows = [];
    //     foreach ($rows as $r) {
    //         $item  = trim((string)($r['item_id']     ?? ""));
    //         $acc   = trim((string)($r['account_id']  ?? ""));
    //         $desc  = trim((string)($r['description'] ?? ""));
    //         $qty   = (float)($r['quantity']   ?? 0);
    //         $price = (float)($r['unit_price'] ?? 0);
    //         $isBlank = ($item === "" && $acc === "" && $desc === "" && $qty == 0 && $price == 0);
    //         if (!$isBlank) {
    //             // carry id if present for update
    //             $r['id'] = isset($r['id']) ? (int)$r['id'] : 0;
    //             $normRows[] = $r;
    //         }
    //     }

    //     if (empty($normRows)) {
    //         return $this->response->setJSON([
    //             "success" => false,
    //             "message" => "<small class='text-danger'>Please add at least one line item.</small>"
    //         ]);
    //     }

    //     // 3) Validate lines
    //     $lineErrors = [];
    //     foreach ($normRows as $i => $r) {
    //         $rowNo = $i + 1;
    //         $itemId = (int)($r['item_id']    ?? 0);
    //         $accId  = (int)($r['account_id'] ?? 0);
    //         $qty    = (float)($r['quantity'] ?? 0);
    //         $price  = (float)($r['unit_price'] ?? 0);

    //         if (!$itemId)   $lineErrors[] = "Row {$rowNo}: item is required.";
    //         if (!$accId)    $lineErrors[] = "Row {$rowNo}: account is required.";
    //         if ($qty <= 0)  $lineErrors[] = "Row {$rowNo}: quantity must be greater than 0.";
    //         if ($price < 0) $lineErrors[] = "Row {$rowNo}: unit price can't be negative.";
    //     }

    //     if ($lineErrors) {
    //         return $this->response->setJSON([
    //             "success"     => false,
    //             "message"     => implode("<br>", $lineErrors),
    //             "line_errors" => $lineErrors
    //         ]);
    //     }

    //     // 4) Prefetch item meta
    //     $itemIds = [];
    //     foreach ($normRows as $r) {
    //         if (!empty($r['item_id'])) $itemIds[] = (int)$r['item_id'];
    //     }
    //     $itemIds   = array_values(array_unique(array_filter($itemIds)));
    //     $itemsMeta = [];
    //     if ($itemIds) {
    //         $itRows = $this->db->table($this->db->prefixTable('vendor_items'))
    //             ->select('id, title, unit_type, taxable, days')
    //             ->whereIn('id', $itemIds)
    //             ->get()->getResult();
    //         foreach ($itRows as $it) {
    //             $itemsMeta[(int)$it->id] = $it;
    //         }
    //     }

    //     // 5) Compute totals (also assemble payloads later)
    //     $subtotal = 0.00;
    //     foreach ($normRows as $r) {
    //         $qty  = max(0, (float)($r['quantity']   ?? 0));
    //         $rate = max(0, (float)($r['unit_price'] ?? 0));
    //         $days = max(1, (float)($r['days']       ?? 1));
    //         $subtotal += round($qty * $rate * $days, 2);
    //     }

    //     // 6) Header payload
    //     $bill_data = [
    //         "type"             => "vendor_bill",
    //         "vendor_id"        => $vendor_id,
    //         "bill_date"        => $bill_date,
    //         "accounting_date"  => $accounting_date,
    //         "due_date"         => $due_date,
    //         "status"           => $status,
    //         "invoice_subtotal" => $subtotal,
    //         "invoice_total"    => $subtotal,
    //         "deleted"          => 0,
    //     ];
    //     if (!$id) {
    //         $bill_data["created_by"] = (int) ($this->login_user->id ?? 0);
    //     }

    //     // 7) Transaction: upsert header + upsert lines
    //     $this->db->transStart();

    //     // header upsert
    //     $bill_id = $this->Vendor_bills_model->ci_save($bill_data, $id);
    //     if (!$bill_id) {
    //         $this->db->transRollback();
    //         return $this->response->setJSON([
    //             "success" => false,
    //             "message" => app_lang("error_occurred") . "<br/><small class='text-danger'>Save bill header failed.</small>"
    //         ]);
    //     }

    //     // current existing (not deleted) rows in DB
    //     $existingIds = [];
    //     $rowsDb = $this->db->table($this->db->prefixTable('vendor_bill_items'))
    //         ->select('id')
    //         ->where('vendor_bill_id', $bill_id)
    //         ->where('deleted', 0)
    //         ->get()->getResult();
    //     foreach ($rowsDb as $rdb) $existingIds[] = (int)$rdb->id;

    //     $toUpdate = [];
    //     $toInsert = [];
    //     $keepIds  = [];
    //     $sort     = 1;

    //     foreach ($normRows as $r) {
    //         $postedId = isset($r['id']) ? (int)$r['id'] : 0;

    //         $item_id = empty($r['item_id']) ? null : (int)$r['item_id'];
    //         $qty     = max(0, (float)($r['quantity']   ?? 0));
    //         $rate    = max(0, (float)($r['unit_price'] ?? 0));   // UI -> DB
    //         $days    = max(1, (float)($r['days']       ?? 1));
    //         $desc    = $r['description'] ?? null;
    //         $meta    = $item_id && isset($itemsMeta[$item_id]) ? $itemsMeta[$item_id] : null;

    //         $payload = [
    //             "vendor_bill_id" => $bill_id,
    //             "title"          => $meta->title      ?? null,
    //             "description"    => $desc,
    //             "quantity"       => $qty,
    //             "unit_type"      => $meta->unit_type  ?? null,
    //             "rate"           => $rate,
    //             "account_id"     => empty($r['account_id']) ? null : (int)$r['account_id'],
    //             "project_id"     => empty($r['project_id']) ? null : (int)$r['project_id'],
    //             "phase_id"       => empty($r['phase_id'])   ? null : (int)$r['phase_id'],
    //             "total"          => round($qty * $rate * $days, 2),
    //             "sort"           => $sort++,
    //             "item_id"        => $item_id,
    //             "days"           => $days,
    //             "taxable"        => (int)($meta->taxable ?? 0),
    //             "is_section"     => 0,
    //             "deleted"        => 0,
    //         ];

    //         if ($postedId > 0 && in_array($postedId, $existingIds, true)) {
    //             $payload['id'] = $postedId;
    //             $toUpdate[] = $payload;
    //             $keepIds[]  = $postedId;
    //         } else {
    //             $toInsert[] = $payload;
    //         }
    //     }

    //     // soft-delete rows removed in UI
    //     $deleteIds = array_diff($existingIds, $keepIds);
    //     if (!empty($deleteIds)) {
    //         $this->db->table($this->db->prefixTable('vendor_bill_items'))
    //             ->where('vendor_bill_id', $bill_id)
    //             ->whereIn('id', $deleteIds)
    //             ->update(['deleted' => 1]);
    //     }

    //     // updates
    //     if (!empty($toUpdate)) {
    //         // CI4 Query Builder has updateBatch on the table builder:
    //         $this->db->table($this->db->prefixTable('vendor_bill_items'))
    //             ->updateBatch($toUpdate, 'id');
    //     }

    //     // inserts
    //     if (!empty($toInsert)) {
    //         $this->db->table($this->db->prefixTable('vendor_bill_items'))
    //             ->insertBatch($toInsert);
    //     }

    //     $this->db->transComplete();
    //     if ($this->db->transStatus() === false) {
    //         return $this->response->setJSON([
    //             "success" => false,
    //             "message" => app_lang("error_occurred") . "<br/><small class='text-danger'>DB commit failed.</small>"
    //         ]);
    //     }

    //     // 8) Success
    //     return $this->response->setJSON([
    //         "success"     => true,
    //         "id"          => $bill_id,
    //         "message"     => app_lang("record_saved"),
    //         "redirect_to" => get_uri("vendor_bills")
    //     ]);
    // }

    public function save()
    {
        $this->access_only_team_members();

        // 1) Validate header
        $this->validate_submitted_data([
            "vendor_id"       => "required|numeric",
            "bill_date"       => "required",
            "accounting_date" => "required"
        ]);

        $id              = (int) $this->request->getPost('id'); // 0=create, >0=edit
        $vendor_id       = (int) $this->request->getPost('vendor_id');
        $accounting_date = $this->request->getPost('accounting_date') ?: date('Y-m-d');
        $bill_date       = $this->request->getPost('bill_date');
        $due_date        = $this->request->getPost('due_date') ?: null;
        $status          = $this->request->getPost('status') ?: 'draft';
        $invoice_id      = $this->request->getPost('invoice_id') ? (int)$this->request->getPost('invoice_id') : null;

        // 2) Parse & normalize posted lines
        $items_json = (string) $this->request->getPost('items');
        $rows = $items_json ? json_decode($items_json, true) : [];
        if (!is_array($rows)) $rows = [];

        $normRows = [];
        foreach ($rows as $r) {
            $item  = trim((string)($r['item_id']     ?? ""));
            $acc   = trim((string)($r['account_id']  ?? ""));
            $desc  = trim((string)($r['description'] ?? ""));
            $qty   = (float)($r['quantity']   ?? 0);
            $price = (float)($r['unit_price'] ?? 0);
            $isBlank = ($item === "" && $acc === "" && $desc === "" && $qty == 0 && $price == 0);
            if (!$isBlank) {
                $r['id'] = isset($r['id']) ? (int)$r['id'] : 0; // keep PK if present
                $normRows[] = $r;
            }
        }

        if (empty($normRows)) {
            return $this->response->setJSON([
                "success" => false,
                "message" => "<small class='text-danger'>Please add at least one line item.</small>"
            ]);
        }

        // 3) Validate lines
        $lineErrors = [];
        foreach ($normRows as $i => $r) {
            $rowNo = $i + 1;
            $itemId = (int)($r['item_id']    ?? 0);
            $accId  = (int)($r['account_id'] ?? 0);
            $qty    = (float)($r['quantity'] ?? 0);
            $price  = (float)($r['unit_price'] ?? 0);

            if (!$itemId)   $lineErrors[] = "Row {$rowNo}: item is required.";
            if (!$accId)    $lineErrors[] = "Row {$rowNo}: account is required.";
            if ($qty <= 0)  $lineErrors[] = "Row {$rowNo}: quantity must be greater than 0.";
            if ($price < 0) $lineErrors[] = "Row {$rowNo}: unit price can't be negative.";
        }

        if ($lineErrors) {
            return $this->response->setJSON([
                "success"     => false,
                "message"     => implode("<br>", $lineErrors),
                "line_errors" => $lineErrors
            ]);
        }

        // 4) Prefetch item meta (optional, for title/unit_type/taxable/days)
        $itemIds = [];
        foreach ($normRows as $r) {
            if (!empty($r['item_id'])) $itemIds[] = (int)$r['item_id'];
        }
        $itemIds   = array_values(array_unique(array_filter($itemIds)));
        $itemsMeta = [];
        if ($itemIds) {
            $it = $this->db->table($this->db->prefixTable('vendor_items'))
                ->select('id, title, unit_type, taxable, days')
                ->whereIn('id', $itemIds)
                ->get()->getResult();
            foreach ($it as $row) {
                $itemsMeta[(int)$row->id] = $row;
            }
        }

        // 5) Compute totals
        $subtotal = 0.00;
        foreach ($normRows as $r) {
            $qty  = max(0, (float)($r['quantity']   ?? 0));
            $rate = max(0, (float)($r['unit_price'] ?? 0));
            $days = max(1, (float)($r['days']       ?? 1));
            $subtotal += round($qty * $rate * $days, 2);
        }

        // 6) Header payload
        $bill_data = [
            "type"             => "vendor_bill",
            "vendor_id"        => $vendor_id,
            "bill_date"        => $bill_date,
            "accounting_date"  => $accounting_date,
            "due_date"         => $due_date,
            "status"           => $status,
            "invoice_subtotal" => $subtotal,
            "invoice_total"    => $subtotal,
            "deleted"          => 0,
        ];

        // Add invoice_id if column exists in vendor_bills table
        $bills_table = $this->db->prefixTable('vendor_bills');
        if ($this->db->fieldExists('invoice_id', $bills_table)) {
            $bill_data["invoice_id"] = $invoice_id;
        }
        if (!$id) {
            $bill_data["created_by"] = (int) ($this->login_user->id ?? 0);
        }

        // 7) Transaction: upsert header + upsert lines via ci_save + soft-delete removed lines
        $this->db->transStart();

        // header upsert
        $bill_id = $this->Vendor_bills_model->ci_save($bill_data, $id);
        if (!$bill_id) {
            $this->db->transRollback();
            return $this->response->setJSON([
                "success" => false,
                "message" => app_lang("error_occurred") . "<br/><small class='text-danger'>Save bill header failed.</small>"
            ]);
        }

        // find existing (not deleted) line ids
        $existingIds = [];
        $rowsDb = $this->db->table($this->db->prefixTable('vendor_bill_items'))
            ->select('id')
            ->where('vendor_bill_id', $bill_id)
            ->where('deleted', 0)
            ->get()->getResult();
        foreach ($rowsDb as $rdb) $existingIds[] = (int)$rdb->id;

        $keepIds = [];
        $sort = 1;

        foreach ($normRows as $r) {
            $postedId = isset($r['id']) ? (int)$r['id'] : 0;

            $item_id = empty($r['item_id']) ? null : (int)$r['item_id'];
            $qty     = max(0, (float)($r['quantity']   ?? 0));
            $rate    = max(0, (float)($r['unit_price'] ?? 0));  // UI -> DB (rate)
            $days    = max(1, (float)($r['days']       ?? 1));
            $desc    = $r['description'] ?? null;

            $meta = $item_id && isset($itemsMeta[$item_id]) ? $itemsMeta[$item_id] : null;

            $payload = [
                "vendor_bill_id" => $bill_id,
                "title"          => $meta->title      ?? null,
                "description"    => $desc,
                "quantity"       => $qty,
                "unit_type"      => $meta->unit_type  ?? null,
                "rate"           => $rate,                                // DB column = rate
                "account_id"     => empty($r['account_id']) ? null : (int)$r['account_id'],
                // Invoice removed from line items - now at bill level
                "total"          => round($qty * $rate * $days, 2),
                "sort"           => $sort++,
                "item_id"        => $item_id,
                "days"           => $days,
                "taxable"        => (int)($meta->taxable ?? 0),
                "is_section"     => 0,
                "deleted"        => 0,
            ];

            // ci_save handles insert/update depending on $postedId
            $savedId = $this->Vendor_bill_items_model->ci_save($payload, $postedId);
            if (!$savedId) {
                $this->db->transRollback();
                return $this->response->setJSON([
                    "success" => false,
                    "message" => app_lang("error_occurred") . "<br/><small class='text-danger'>Saving a line item failed.</small>"
                ]);
            }
            $keepIds[] = (int)$savedId;
        }

        // soft-delete lines that were removed in UI
        foreach ($existingIds as $eid) {
            if (!in_array($eid, $keepIds, true)) {
                // RISE-style: delete() usually soft-deletes (sets deleted=1)
                $this->Vendor_bill_items_model->delete($eid);
            }
        }

        $this->db->transComplete();
        if ($this->db->transStatus() === false) {
            return $this->response->setJSON([
                "success" => false,
                "message" => app_lang("error_occurred") . "<br/><small class='text-danger'>DB commit failed.</small>"
            ]);
        }

        // 8) Success
        return $this->response->setJSON([
            "success"     => true,
            "id"          => $bill_id,
            "message"     => app_lang("record_saved"),
            "redirect_to" => get_uri("vendor_bills")
        ]);
    }



    // Return phases (milestones) for a project as {id: "Title", ...}


    // Projects and Phases removed - not needed in vendors plugin
    // public function get_phases_by_project()
    // {
    //     $this->access_only_team_members();
    //     $project_id = (int) $this->request->getPost('project_id');
    //     if ($project_id <= 0) {
    //         echo json_encode([]);
    //         return;
    //     }
    //     // ... removed phase functionality
    // }

    // Return vendor_item meta {description, unit_type, rate, days, taxable}
    // public function get_item_info()
    // {
    //     $this->access_only_team_members();

    //     $item_id = (int) $this->request->getPost('item_id');
    //     if ($item_id <= 0) {
    //         echo json_encode(['success' => false, 'message' => 'Invalid item']);
    //         return;
    //     }

    //     $t  = $this->db->prefixTable('vendor_items');
    //     $qb = $this->db->table($t)
    //         ->select('id, title, description, unit_type, rate, days, taxable')
    //         ->where('id', $item_id);

    //     if ($this->db->fieldExists('deleted', $t)) {
    //         $qb->where('deleted', 0);
    //     }

    //     $row = $qb->get()->getRowArray();
    //     if (!$row) {
    //         echo json_encode(['success' => false, 'message' => 'Item not found']);
    //         return;
    //     }

    //     echo json_encode([
    //         'success' => true,
    //         'data' => [
    //             'description' => (string)($row['description'] ?? ''),
    //             'unit_type'   => (string)($row['unit_type'] ?? ''),
    //             'rate'        => (float)($row['rate'] ?? 0),
    //             'days'        => (float)($row['days'] ?? 1),
    //             'taxable'     => (int)  ($row['taxable'] ?? 0),
    //         ]
    //     ]);
    // }

    public function get_item_info()
    {
        $this->access_only_team_members();

        $item_id = (int) $this->request->getPost('item_id');
        if ($item_id <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid item']);
        }

        $t  = $this->db->prefixTable('vendor_items');
        $qb = $this->db->table($t)
            ->select('id, title, description, unit_type, rate, days, taxable')
            ->where('id', $item_id);

        if ($this->db->fieldExists('deleted', $t)) {
            $qb->where('deleted', 0);
        }

        $row = $qb->get()->getRowArray();
        if (!$row) {
            return $this->response->setJSON(['success' => false, 'message' => 'Item not found']);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'description'  => (string)($row['description'] ?? ''),
                'unit_type'    => (string)($row['unit_type'] ?? ''),
                'rate'         => (float)($row['rate'] ?? 0),
                'days'         => (float)($row['days'] ?? 1),
                'default_qty'  => (float)($row['days'] ?? 1), // 👈 JS will pick this up
                'taxable'      => (int)  ($row['taxable'] ?? 0),
            ]
        ]);
    }



    // Projects and Phases removed - not needed in vendors plugin
    public function get_phases_dropdown($project_id = 21, $return_as_list_data = false)
    {
        // Projects and Phases removed - not needed in vendors plugin
        $list = [];
        if ($return_as_list_data) return $list;
        return $this->response->setJSON($list);
    }

    // Get invoice total by invoice ID
    public function get_invoice_total()
    {
        $this->access_only_team_members();

        $invoice_id = (int) $this->request->getPost('invoice_id');
        if ($invoice_id <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid invoice ID'
            ]);
        }

        $invoice = $this->db->table($this->db->prefixTable('invoices'))
            ->select('id, invoice_total, invoice_subtotal, display_id')
            ->where('id', $invoice_id)
            ->where('deleted', 0)
            ->get()
            ->getRow();

        if (!$invoice) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invoice not found'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'invoice_total' => (float)($invoice->invoice_total ?? 0),
                'invoice_subtotal' => (float)($invoice->invoice_subtotal ?? 0),
                'display_id' => $invoice->display_id ?? 'INV-' . $invoice->id
            ]
        ]);
    }
    /* add or edit an invoice */



    //delete vendor bills
    public function delete()
    {
        $this->validate_submitted_data([
            "id" => "required|numeric"
        ]);

        $id   = (int) $this->request->getPost('id');
        $bill = $this->Vendor_bills_model->get_one($id);

        if (!$bill || ($bill->deleted ?? 0)) {
            return $this->response->setJSON([
                "success" => false,
                "message" => app_lang('record_not_found') ?: "Record not found"
            ]);
        }

        // Non-null title for activity logs
        $title = "Vendor bill #{$bill->id}";
        if (!empty($bill->bill_date)) {
            $title .= " • {$bill->bill_date}";
        }

        $this->db->transStart();

        $pfx = get_db_prefix();

        // 0) Delete accounting transactions FIRST (before deleting records)
        // This prevents foreign key constraint issues
        $Accounting_model = model("Accounting\Models\Accounting_model");

        // Delete transactions for vendor bill payments
        $payTbl   = $this->db->table($pfx . 'vendor_bill_payments');
        $payments = $payTbl->where('vendor_bill_id', $id)->get()->getResultArray();

        foreach ($payments as $pr) {
            $payId = (int) $pr['id'];
            // Delete GL transactions for this payment
            $Accounting_model->delete_convert($payId, 'vendor_bill_payment');
            $Accounting_model->delete_convert($payId, 'supplier_payment'); // fallback for older records
        }

        // Delete transactions for the vendor bill itself
        $Accounting_model->delete_convert($id, 'vendor_bills');

        // 1) Remove vendor bill payments linked to this bill
        foreach ($payments as $pr) {
            $payId = (int) $pr['id'];
            // soft delete if column exists, else hard delete
            if (array_key_exists('deleted', $pr)) {
                $payTbl->where('id', $payId)->update(['deleted' => 1]);
            } else {
                $payTbl->where('id', $payId)->delete();
            }
        }

        // 2) Delete bill items (permanent)
        if (method_exists($this->Vendor_bill_items_model, 'delete_by_bill')) {
            // pass hard-delete flag if your model supports it
            $this->Vendor_bill_items_model->delete_by_bill($id, true);
        } else {
            $this->db->table($this->db->prefixTable('vendor_bill_items'))
                ->where('vendor_bill_id', $id)
                ->delete();
        }

        // 3) Delete the bill (permanent)
        // Note: delete_permanently() doesn't return a value, it just deletes
        $this->Vendor_bills_model->delete_permanently($id);

        // Get affected rows immediately after deletion to verify it worked
        $billDeleted = ($this->db->affectedRows() > 0);

        // 4) Activity log (ensure title is not null)
        $this->Activity_logs_model->ci_save([
            "log_for"        => "vendor_bill",
            "log_type"       => "vendor_bill",
            "log_for_id"     => $bill->id,
            "log_type_title" => $title,
            "log_type_id"    => $bill->id,
            "action"         => "deleted",
            "created_by"     => (int)($this->login_user->id ?? 0),
            "created_at"     => get_current_utc_time(),
            "changes"        => ""
        ]);

        $this->db->transComplete();

        // Check if deletion was successful
        // Note: delete_convert may return false if no transactions exist, which is OK
        // We check transaction status and if the bill was actually deleted
        if ($this->db->transStatus() === false || !$billDeleted) {
            return $this->response->setJSON([
                "success" => false,
                "message" => app_lang('record_cannot_be_deleted') ?: "Could not delete the record"
            ]);
        }

        // 5) Remove attached files (if any)
        if (!empty($bill->files)) {
            $file_path = get_setting("timeline_file_path");
            $files     = @unserialize($bill->files);
            if (is_array($files)) {
                foreach ($files as $f) {
                    delete_app_files($file_path, [$f]);
                }
            }
        }

        return $this->response->setJSON([
            "success" => true,
            "message" => app_lang('record_deleted') ?: "Deleted"
        ]);
    }





    /* list of invoices, prepared for datatable  */

    public function list_data()
    {
        // Custom fields (use your own entity key; “vendor_bills” recommended)
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table(
            "vendor_bills",
            $this->login_user->is_admin,
            $this->login_user->user_type
        );

        $options = array(
            "status"               => $this->request->getPost("status"),
            "vendor_id"            => $this->request->getPost("vendor_id"),
            "project_id"           => $this->request->getPost("project_id"),
            "start_date"           => $this->request->getPost("start_date"),
            "end_date"             => $this->request->getPost("end_date"),
            "currency"             => $this->request->getPost("currency"), // optional if you support multi-currency
            "custom_fields"        => $custom_fields,
            "custom_field_filter"  => $this->prepare_custom_field_filter_values("vendor_bills", $this->login_user->is_admin, $this->login_user->user_type),
            // NEW: pipe the keyword into the model (covers appTable 'search' & a few variants)
            "search"              => $this->request->getPost("search")
                ?? $this->request->getPost("searchText")
                ?? $this->request->getPost("general_search")
        );

        $list_data = $this->Vendor_bills_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    /**
     * One row for appTable (matches your columns in index.php)
     */
    private function _make_row($data, $custom_fields, $is_mobile = 0)
    {
        // Bill link (uses display_id if present)
        $bill_label = $data->display_id ? $data->display_id : ("BILL-" . $data->id);
        $bill_url   = anchor(get_uri("vendor_bills/edit/" . $data->id), $bill_label);

        // Vendor link
        $vendor_label = $data->vendor_name ?: "-";
        // $vendor_url   = $data->vendor_id ? anchor(get_uri("vendors/view/" . $data->vendor_id), $vendor_label) : $vendor_label;
        $vendor_url   = $vendor_label;

        // Projects removed - not needed in vendors plugin
        // $project_cell = $data->project_title
        //     ? anchor(get_uri("projects/view/" . $data->project_id), $data->project_title)
        //     : "-";


        // Branch removed - not needed in vendors plugin
        // $branch_cell = $data->branch_name ?: "-";

        // Dates: raw (hidden for sort) + formatted
        $bill_date_raw = $data->bill_date;
        $bill_date_sh  = format_to_date($data->bill_date, false);

        $due_date_raw  = $data->due_date;
        $due_date_sh   = format_to_date($data->due_date, false);

        // Money
        $total_cell    = to_currency($data->bill_value);
        $paid_cell     = to_currency($data->payment_received);
        $due_cell      = to_currency($data->due_amount);

        // Status + labels
        $status_badge = $this->_get_vendor_bill_status_label($data);
        $labels_html  = " " . make_labels_view_data($data->labels_list ?? "", true);

        $row = array(
            $data->id,           // hidden id (sort)
            $bill_url,           // bill number
            $vendor_url,         // vendor
            // $branch_cell,       // project
            $bill_date_raw,      // hidden sort
            $bill_date_sh,       // bill date shown
            // $due_date_raw,       // hidden sort
            // $due_date_sh,        // due date shown
            $total_cell,         // total invoiced
            $paid_cell,          // payment received
            $due_cell,           // due
            $status_badge . $labels_html // status
        );

        // Custom fields
        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row[] = $this->template->view(
                "custom_fields/output_" . $field->field_type,
                array("value" => $data->$cf_id)
            );
        }

        // Prepare action buttons
        $edit   = "";
        $delete = "";

        // Option A: Edit the BILL (recommended on a bills table)
        if ($this->can_update_vendor_bill()) {
            $edit = anchor(
                get_uri("vendor_bills/edit/" . $data->id),
                "<i data-feather='edit' class='icon-16'></i>",
                [
                    "class" => "edit action-icon",
                    "title" => app_lang('edit'),
                    "data-bs-toggle" => "tooltip",
                    "aria-label" => app_lang('edit'),
                ]
            );
        }

        if ($this->can_delete_vendor_bill()) {
            $delete = js_anchor(
                "<i data-feather='trash' class='icon-16'></i>",
                [
                    "class" => "delete action-icon",
                    "title" => app_lang('delete'),
                    "data-bs-toggle" => "tooltip",
                    "aria-label" => app_lang('delete'),
                    "data-action-url" => get_uri("vendor_bills/delete"),
                    "data-id" => $data->id,
                    "data-action" => "delete-confirmation"
                ]
            );
        }

        $actions = '<div class="actions-inline">' . $edit . $delete . '</div>';
        $row[]   = $actions;




        return $row;
    }



    private function _get_vendor_bill_status_label($data, $return_html = true, $extra_classes = "")
    {
        return get_vendor_bill_status_label($data, $return_html, $extra_classes);
    }



    /**
     * Single row fetcher used after save() to append/refresh table row (like invoices/_row_data)
     */
    private function _row_data($id)
    {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table(
            "vendor_bills",
            $this->login_user->is_admin,
            $this->login_user->user_type
        );

        $options = array(
            "id"             => $id,
            "custom_fields"  => $custom_fields
        );

        $data = $this->Vendor_bills_model->get_details($options)->getRow();
        return $this->_make_row($data, $custom_fields);
    }


    private function _make_vendor_bill_options_dropdown($d)
    {
        $links = [];

        // Edit (icon only with tooltip)
        $links[] = anchor(
            get_uri("vendor_bills/edit/" . $d->id),
            "<i data-feather='edit' class='icon-16' title='" . app_lang('edit') . "' data-bs-toggle='tooltip'></i>",
            ["class" => "dropdown-item text-center", "data-bs-toggle" => "tooltip", "title" => app_lang('edit')]
        );

        // Delete (icon only with tooltip)
        $links[] = js_anchor(
            "<i data-feather='trash' class='icon-16' title='" . app_lang('delete') . "' data-bs-toggle='tooltip'></i>",
            [
                "class" => "dropdown-item text-center delete",
                "data-action-url" => get_uri("vendor_bills/delete"),
                "data-id" => $d->id,
                "data-action" => "delete-confirmation",
                "data-bs-toggle" => "tooltip",
                "title" => app_lang('delete')
            ]
        );

        // Build HTML items
        $items = implode('', array_map(fn($l) => "<li>{$l}</li>", $links));

        return '<span class="dropdown inline-block">'
            . '  <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'
            . '    <i data-feather="menu" class="icon-16"></i>'
            . '  </button>'
            . '  <ul class="dropdown-menu dropdown-menu-end p-1 text-center" role="menu" style="min-width:80px;">'
            .        $items
            . '  </ul>'
            . '</span>';
    }



    public function list()
    {
        $result = $this->Vendor_bills_model->get_list_for_table($this->request->getGet());
        return $this->response->setJSON($result);
    }
    public function bulk_action()
    {
        // use $_POST['ids'], mass_convert, mass_delete_convert
        return $this->response->setJSON(['success' => true]);
    }






    /**
     * Numbering logic (RISE-style) for vendor bills.
     * Produces display_id, number_year, number_sequence.
     * Example: BILL/2025/000123
     */
    private function _prepare_vendor_bill_display_id_data($due_date, $bill_date)
    {
        $bills_table = $this->db->prefixTable('vendor_bills');
        $year = date('Y', strtotime($bill_date ?: date('Y-m-d')));

        $row = $this->db->table($bills_table)
            ->select('MAX(number_sequence) AS max_seq', false)
            ->where('number_year', $year)
            ->get()->getRow();

        $next = (int)($row->max_seq ?? 0) + 1;

        return array(
            "display_id"      => sprintf('BILL/%s/%06d', $year, $next),
            "number_year"     => $year,
            "number_sequence" => $next
        );
    }
}
