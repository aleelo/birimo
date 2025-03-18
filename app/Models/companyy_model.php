<?php
// app/Models/CompanyDepartmentModel.php

namespace App\Models;

use CodeIgniter\Model;

class Companyy_model extends Crud_model
{
    protected $table      = 'company_departments';
    protected $primaryKey = 'id';
    
    protected $allowedFields = ['user_id', 'department_name'];
    
    protected $useTimestamps = true;
    function get_access_info($user_id = 0) {
        $company_departments = $this->db->prefixTable('rise_company_departments');
        
        $user_id = $this->_get_clean_value($user_id);
    
        if (!$user_id) {
            $user_id = 0;
        }
    
        $sql = "SELECT rise_company_departments.department_name
                FROM rise_company_departments
                WHERE rise_company_departments.user_id = $user_id";
        
        return $this->db->query($sql)->getRow();
    }
    
}
