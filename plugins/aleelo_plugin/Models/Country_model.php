<?php

namespace aleelo_plugin\Models;
use App\Models\Crud_model;

class Country_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'countries';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $countries_table = $this->db->prefixTable('countries');

        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where = " AND $countries_table.id=$id";
        }

        $sql = "SELECT $countries_table.*
        FROM $countries_table
        WHERE $countries_table.deleted=0 $where";
        return $this->db->query($sql);
    }
}
