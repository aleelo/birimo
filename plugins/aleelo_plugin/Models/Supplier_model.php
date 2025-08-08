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
        $country_table = $this->db->prefixTable('countries');
        $region_table = $this->db->prefixTable('regions');
        
        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where = " AND $supplier_table.id=$id";
        }

        $can_view_own_company_client = $this->_get_clean_value($options, "can_view_own_company_client");
        if ($can_view_own_company_client) {
            $where .= " AND $supplier_table.company=$can_view_own_company_client";
        }
        $can_view_own_department_client = $this->_get_clean_value($options, "can_view_own_department_client");
        if ($can_view_own_department_client) {
            $where .= " AND $supplier_table.company=$can_view_own_department_client";
        }
        $company_id = $this->_get_clean_value($options, "company_id");
        if ($company_id) {
            $where .= " AND $supplier_table.company=$company_id";
        }

        $sql = "SELECT $supplier_table.*,$company_table.name AS company_name,$region_table.region AS region_name,$country_table.country_name AS country_name
        FROM $supplier_table
        LEFT JOIN $company_table ON $company_table.id= $supplier_table.company
        LEFT JOIN $region_table ON $region_table.id= $supplier_table.region
        LEFT JOIN $country_table ON $country_table.id= $supplier_table.country


        WHERE $supplier_table.deleted=0 $where";
        return $this->db->query($sql);
    }

 

}
