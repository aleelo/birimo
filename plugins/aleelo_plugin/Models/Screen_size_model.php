<?php

namespace aleelo_plugin\Models;
use App\Models\Crud_model;

class Screen_size_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'screen_size';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $screen_size_table = $this->db->prefixTable('screen_size');

        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where = " AND $screen_size_table.id=$id";
        }

        $sql = "SELECT $screen_size_table.*
        FROM $screen_size_table
        WHERE $screen_size_table.deleted=0 $where";
        return $this->db->query($sql);
    }
}
