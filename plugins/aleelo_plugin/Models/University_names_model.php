<?php

namespace aleelo_plugin\Models;
use App\Models\Crud_model;

class University_names_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'university_names';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $university_names_table = $this->db->prefixTable('university_names');

        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where = " AND $university_names_table.id=$id";
        }

        $sql = "SELECT $university_names_table.*
        FROM $university_names_table
        WHERE $university_names_table.deleted=0 $where";
        return $this->db->query($sql);
    }
}
