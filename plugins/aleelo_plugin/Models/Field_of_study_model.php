<?php

namespace aleelo_plugin\Models;
use App\Models\Crud_model;

class Field_of_study_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'education_industry';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $field_of_study_table = $this->db->prefixTable('education_industry');
        // $field_of_study_table = 'education_industry';

        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where = " AND $field_of_study_table.id=$id";
        }

        $sql = "SELECT $field_of_study_table.*
        FROM $field_of_study_table
        WHERE $field_of_study_table.deleted=0 $where";
        return $this->db->query($sql);
    }
}
