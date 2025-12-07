<?php

namespace Vendors\Models;

use App\Models\Crud_model;

class Vendor_model extends Crud_model
{
    protected $table = null;

    function __construct()
    {
        $this->table = 'vendor'; // table name without prefix
        parent::__construct($this->table);
    }

    function get_details($options = array())
    {
        $vendor_table   = $this->db->prefixTable('vendor');
        // Branch functionality removed - not needed in vendors plugin
        // $branch_table   = $this->db->prefixTable('branch');
        // Country and Region removed - not needed in vendors plugin
        // $country_table  = $this->db->prefixTable('countries');
        // $region_table   = $this->db->prefixTable('regions');

        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $vendor_table.id=$id";
        }

        // Branch functionality removed - not needed in vendors plugin
        // $can_view_own_company_vendor = $this->_get_clean_value($options, "can_view_own_company_vendor");
        // if ($can_view_own_company_vendor) {
        //     $where .= " AND $vendor_table.branch_id=$can_view_own_company_vendor";
        // }

        // $company_id = $this->_get_clean_value($options, "company_id");
        // if ($company_id) {
        //     $where .= " AND $vendor_table.branch_id=$company_id";
        // }

        // $branch_id = $this->_get_clean_value($options, "branch_id");
        // if ($branch_id) {
        //     if (is_array($branch_id)) {
        //         $escaped_ids = array_map(function ($id) {
        //             return db_connect()->escape($id);
        //         }, $branch_id);
        //         $where .= " AND $vendor_table.branch_id IN (" . implode(",", $escaped_ids) . ")";
        //     } elseif (is_numeric($branch_id)) {
        //         $where .= " AND $vendor_table.branch_id=" . db_connect()->escape($branch_id);
        //     }
        // }

        // $branch_ids = $this->_get_clean_value($options, "branch_ids");
        // if ($branch_ids) {
        //     if (is_array($branch_ids)) {
        //         $escaped_ids = array_map(function ($id) {
        //             return db_connect()->escape($id);
        //         }, $branch_ids);
        //         $where .= " AND $vendor_table.branch_id IN (" . implode(",", $escaped_ids) . ")";
        //     } elseif (is_numeric($branch_ids)) {
        //         $where .= " AND $vendor_table.branch_id=" . db_connect()->escape($branch_ids);
        //     }
        // }

        // Branch, Country and Region functionality removed - not needed in vendors plugin
        $sql = "SELECT 
                    $vendor_table.*
                FROM $vendor_table
                WHERE $vendor_table.deleted=0 $where";

        return $this->db->query($sql);
    }

    public function get_vendors_with_invoices_dropdown()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('rise_vendor');

        $builder->select('rise_vendor.id, rise_vendor.vendor_name');
        $builder->join('rise_invoice_items', 'rise_invoice_items.vendor_id = rise_vendor.id', 'inner');
        $builder->join('rise_invoices', 'rise_invoices.id = rise_invoice_items.invoice_id', 'inner');

        $builder->where('rise_vendor.deleted', 0);
        $builder->where('rise_invoices.deleted', 0);
        $builder->groupBy('rise_vendor.id');

        $query = $builder->get();

        $vendors = ['' => '-'];
        foreach ($query->getResult() as $vendor) {
            $vendors[$vendor->id] = $vendor->vendor_name;
        }

        return $vendors;
    }

    public function get_vendors_with_open_balance_dropdown()
    {
        $db = \Config\Database::connect();

        $sql = "
        SELECT 
            v.id,
            v.vendor_name,
            COALESCE(SUM(ii.vendor_quantity * ii.quantity * ii.days), 0) AS total_invoiced,
            COALESCE(p.total_payments, 0) AS total_payments
        FROM rise_vendor v
        INNER JOIN rise_invoice_items ii 
                ON ii.vendor_id = v.id 
               AND ii.deleted = 0
        INNER JOIN rise_invoices inv 
                ON inv.id = ii.invoice_id 
               AND inv.deleted = 0
        LEFT JOIN (
            SELECT vendor_id, SUM(COALESCE(amount,0)) AS total_payments
            FROM rise_invoice_vendor_payments
            WHERE deleted = 0
            GROUP BY vendor_id
        ) p ON p.vendor_id = v.id
        WHERE v.deleted = 0
        GROUP BY v.id, v.vendor_name, p.total_payments
        HAVING (COALESCE(SUM(ii.vendor_quantity * ii.quantity * ii.days), 0) - COALESCE(p.total_payments, 0)) > 0
        ORDER BY v.vendor_name ASC
        ";

        $query = $db->query($sql);

        $vendors = ['' => '-'];
        foreach ($query->getResult() as $row) {
            $vendors[$row->id] = $row->vendor_name;
        }

        return $vendors;
    }

    // public function dropdown(?int $branch_id = null, string $search = ''): array
    // {
    //     $t = $this->db->prefixTable($this->table);

    //     $b = $this->db->table($t)
    //         ->select('id, vendor_name')
    //         ->where('deleted', 0);

    //     if (!empty($branch_id)) {
    //         $b->where('branch_id', $branch_id);
    //     }

    //     if ($search !== '') {
    //         $b->like('vendor_name', $search);
    //     }

    //     $b->orderBy('vendor_name', 'ASC');

    //     $out = [];
    //     foreach ($b->get()->getResult() as $r) {
    //         $out[(string) $r->id] = (string) $r->vendor_name;
    //     }
    //     return $out;
    // }

    // Branch functionality removed - not needed in vendors plugin
    public function dropdown(?int $branch_id = null, string $search = ''): array
    {
        // Force known-good base name, prefix once.
        $t = $this->db->prefixTable('vendor');

        $b = $this->db->table($t)
            ->select('id, vendor_name')
            ->where('deleted', 0);

        // Branch functionality removed - not needed in vendors plugin
        // if (!empty($branch_id)) {
        //     $b->where('branch_id', $branch_id);
        // }

        if ($search !== '') {
            $b->like('vendor_name', $search);
        }

        $b->orderBy('vendor_name', 'ASC');

        $out = [];
        foreach ($b->get()->getResult() as $r) {
            $out[(string)$r->id] = (string)$r->vendor_name;
        }
        return $out;
    }


    /**
     * (Optional) Select2-friendly output: [{id:..., text:...}, ...]
     * Use when you need a list_data shape instead of a map.
     */
    public function dropdown_list(?int $branch_id = null, string $search = ''): array
    {
        $map = $this->dropdown($branch_id, $search);
        $list = [];
        foreach ($map as $id => $name) {
            $list[] = ['id' => $id, 'text' => $name];
        }
        return $list;
    }


    public function get_suppliers_with_open_balance_dropdown()
    {
        $db = \Config\Database::connect();

        // Using raw SQL keeps it simple and avoids join-duplication issues.
        $sql = "
        SELECT 
            s.id,
            s.vendor_name,
            -- total invoiced by supplier
            COALESCE(SUM(ii.supplier_quantity * ii.quantity * ii.days), 0) AS total_invoiced,
            -- pre-aggregated payments per supplier
            COALESCE(p.total_payments, 0) AS total_payments
        FROM rise_vendor s
        INNER JOIN rise_invoice_items ii 
                ON ii.supplier_id = s.id 
               AND ii.deleted = 0
        INNER JOIN rise_invoices inv 
                ON inv.id = ii.invoice_id 
               AND inv.deleted = 0
        LEFT JOIN (
            SELECT supplier_id, SUM(COALESCE(amount,0)) AS total_payments
            FROM rise_invoice_supplier_payments
            WHERE deleted = 0
            GROUP BY supplier_id
        ) p ON p.supplier_id = s.id
        WHERE s.deleted = 0
        GROUP BY s.id, s.vendor_name, p.total_payments
        HAVING (COALESCE(SUM(ii.supplier_quantity * ii.quantity * ii.days), 0) - COALESCE(p.total_payments, 0)) > 0
        ORDER BY s.vendor_name ASC
    ";

        $query = $db->query($sql);

        $suppliers = ['' => '-'];
        foreach ($query->getResult() as $row) {
            $suppliers[$row->id] = $row->vendor_name;
        }

        return $suppliers;
    }
}
