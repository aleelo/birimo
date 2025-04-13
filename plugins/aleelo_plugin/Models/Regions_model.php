<?php

namespace aleelo_plugin\Models;
use App\Models\Crud_model;

class Regions_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'regions';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $regions_table = $this->db->prefixTable('regions');

        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where = " AND $regions_table.id=$id";
        }

        $sql = "SELECT $regions_table.*
        FROM $regions_table
        WHERE $regions_table.deleted=0 $where";
        return $this->db->query($sql);
    }
}
