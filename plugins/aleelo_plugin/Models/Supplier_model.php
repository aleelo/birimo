<?php

namespace aleelo_plugin\Models;
use App\Models\Crud_model;

class Supplier_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'supplier';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $supplier_table = $this->db->prefixTable('supplier');
        $company_table =$this->db->prefixTable('company');
        
        $where = "";
        
        $can_view_own_company_client = $this->_get_clean_value($options, "can_view_own_company_client");
        if ($can_view_own_company_client) {
            $where .= " AND $supplier_table.company=$can_view_own_company_client";
        }
        $can_view_own_department_client = $this->_get_clean_value($options, "can_view_own_department_client");
        if ($can_view_own_department_client) {
            $where .= " AND $supplier_table.company=$can_view_own_department_client";
        }

        $sql = "SELECT $supplier_table.*,$company_table.name AS company_name
        FROM $supplier_table
        LEFT JOIN $company_table ON $company_table.id= $supplier_table.company

        WHERE $supplier_table.deleted=0 $where";
        return $this->db->query($sql);
    }

 

}
