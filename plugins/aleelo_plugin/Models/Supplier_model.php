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
       

        $sql = "SELECT $supplier_table.*,$company_table.name AS company_name
        FROM $supplier_table
        LEFT JOIN $company_table ON $company_table.id= $supplier_table.company

        WHERE $supplier_table.deleted=0 $where";
        return $this->db->query($sql);
    }

 

}
