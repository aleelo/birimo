<?php

namespace aleelo_plugin\Controllers;
use aleelo_plugin\Controllers\App_Controller_Plugin;
use App\Controllers;
use App\Controllers\Security_Controller;
class Security_Controller_Plugin extends Security_Controller {

   public $Assigning_items_model;

    public $Screen_size_model;
    public $Items_model;
    public $Items_list_model;
    public $db;
    public $Expenses_model;
    public $Company_model;
    public $Projects_model;
    public $Project_status_model;
    public $Clients_model;
    public $Country_model;
    public $Regions_model;

    public$University_names_model;
    // use App_Controller;
    public$Field_of_study_model;
    public$Users_models;
    public$Users_model;

    public$Tasks_model;

    public$Estimates_model;
    public$Supplier_model;

    public function __construct($redirect = true) {
        parent::__construct();
        $this->db = \Config\Database::connect(); // Initialize the database connection

         $this->Assigning_items_model = new \aleelo_plugin\Models\Assigning_items_model();
        $this->Screen_size_model = new \aleelo_plugin\Models\Screen_size_model();
        $this->Items_list_model = new \aleelo_plugin\Models\Items_list_model();
        $this->Expenses_model = new \aleelo_plugin\Models\Expense_model();
        $this->Company_model = new \App\Models\Company_model();
        $this->Project_status_model = new \aleelo_plugin\Models\Project_status_model();
        $this->Projects_model = new \aleelo_plugin\Models\Projects_model();
         $this->Clients_model= new \aleelo_plugin\Models\Clients_model();
$this->University_names_model = new \aleelo_plugin\Models\University_names_model();
$this->Field_of_study_model = new \aleelo_plugin\Models\Field_of_study_model();
$this->Users_models = new \aleelo_plugin\Models\Users_models();
$this->Tasks_model = new \aleelo_plugin\Models\Tasks_model();
$this->Estimates_model = new \aleelo_plugin\Models\Estimates_model();
$this->Users_model = new \aleelo_plugin\Models\Users_models();
$this->Country_model = new \aleelo_plugin\Models\Country_model();
$this->Regions_model = new \aleelo_plugin\Models\Regions_model();
$this->Items_model = new \aleelo_plugin\Models\Items_model();
        $this->Supplier_model = new \aleelo_plugin\Models\Supplier_model();

        // if (!$login_user_id && $redirect) {
        //     $uri_string = uri_string();

        //     if (!$uri_string || $uri_string === "signin" || $uri_string === "/signin" || $uri_string === "/") {
        //         app_redirect('signin');
        //     } else {
        //         app_redirect('signin?redirect=' . get_uri($uri_string));
        //     }
        // }

        // app_hooks()->do_action('app_hook_before_app_access', array(
        //     "login_user_id" => $login_user_id,
        //     "redirect" => $redirect
        // ));

        // //initialize login users required information
        // $this->login_user = $this->Users_models->get_access_info($login_user_id);

        // //initialize login users access permissions
        // if ($this->login_user && $this->login_user->permissions) {
        //     $permissions = unserialize($this->login_user->permissions);
        //     $this->login_user->permissions = is_array($permissions) ? $permissions : array();
        // } else {
        //     if (!$this->login_user) {
        //         $this->login_user = new \stdClass();
        //     }
        //     $this->login_user->permissions = array();
        // }
       
    }

    protected function can_edit_profile() {
        if ($this->login_user->user_type === "staff" && !$this->login_user->is_admin && get_array_value($this->login_user->permissions, "cant_edit_profile") == "1") {
            return true;
        }
    }
    protected function can_edit_team_member() {
        if ($this->login_user->user_type === "staff" && !$this->login_user->is_admin && get_array_value($this->login_user->permissions, "can_edit_team_member") == "1") {
            return true;
        }
    }
    public function get_bank_name_dropdown() {
        
        $bane_names = $this->db->query("SELECT id, bank_name FROM rise_bank_names WHERE deleted=0")->getResult();
        $temp_array = array('' => '---Choose Bank Name---');

        if(!$bane_names){
            return null;
        }
  
        foreach($bane_names as $b){
            $temp_array[$b->id] = $b->bank_name;
        }

        return $temp_array;
    }
    protected function get_clients_and_leads_dropdown($return_json = false) {
        $clients_dropdown = array("" => "-");
        $clients_json_dropdown = array(array("id" => "", "text" => "-"));
       if($this->login_user->company_access == "all"){
       if( $company_id = $this->login_user->department==0){
        $clients = $this->Clients_model->get_all_where(array("deleted" => 0), 0, 0, "is_lead")->getResult();
       }
       else{
        $company_id = $this->login_user->department;
        $clients = $this->Clients_model->get_all_where(array("deleted" => 0,"company_id"=>$company_id), 0, 0, "is_lead")->getResult();
       }
    }
       else if(get_array_value($this->login_user->permissions, "client") === "own_company" || get_array_value($this->login_user->permissions, "invoice") === "own_invoice"){
        $company_id = $this->login_user->department;
        $clients = $this->Clients_model->get_all_where(array("deleted" => 0,"company_id"=>$company_id), 0, 0, "is_lead")->getResult();
         }
         else{
            $clients = $this->Clients_model->get_all_where(array("deleted" => 0), 0, 0, "is_lead")->getResult();
         }
        
      
        foreach ($clients as $client) {
            $company_name = $client->is_lead ? app_lang("lead") . ": " . $client->company_name : $client->company_name;

            $clients_dropdown[$client->id] = $company_name;
            $clients_json_dropdown[] = array("id" => $client->id, "text" => $company_name);
        }

        return $return_json ? $clients_json_dropdown : $clients_dropdown;
    }

    protected function can_view_own_project() {
        if (
            
            $this->login_user->company_access==="all" 
        ) {
            return $this->login_user->department;
        }
        return $this->login_user->department;
    }
    protected function can_view_own_company_project() {
        if ($this->login_user->user_type == "staff" && get_array_value($this->login_user->permissions, "can_view_own_company_project") === "1") {
            return $this->login_user->department; 
        }
        else if($this->login_user->user_type == "staff" && $this->login_user->company_access==="all"){
            return $this->login_user->department;
        }
        return null; 
    }
// ----------------------------------------------------invoice-----------------------------------------------------
    protected function can_view_invoice() {
        if ($this->login_user->user_type == "staff" && get_array_value($this->login_user->permissions, "hide_invoice") !== "1") {
            return true; 
        }
        return false; 
    }


    protected function can_edit_invoice() {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_update_invoice") == "1")) {
            return true;
        }
        return null; 
    }


    protected function can_add_invoice() {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_add_invoice") == "1")) {
            return true;
        }
        return false;
    }

    protected function can_delete_invoice() {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_invoice") == "1")) {
            return true;
        }
        return null; 
    }
    protected function can_view_own_company_invoice() {
        if ($this->login_user->user_type == "staff" && get_array_value($this->login_user->permissions, "invoice") === "own_invoice") {
            return $this->login_user->department; 
        }
        else if($this->login_user->user_type == "staff" && $this->login_user->company_access==="all"){
            return $this->login_user->department;
        }
        return null; 
    }

    // ----------------------------------------------------estimate-----------------------------------------------------
    protected function can_view_estimate() {
        if ($this->login_user->user_type == "staff" && get_array_value($this->login_user->permissions, "hide_estimate") !== "1") {
            return true; 
        }
        
        return app_redirect("forbidden"); 
    }


    protected function can_edit_estimate() {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_update_estimate") == "1")) {
            return true;
        }
        return null; 
    }


    protected function can_add_estimate() {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_add_estimate") == "1")) {
            return true;
        }
        return false;
    }

    protected function can_delete_estimate() {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_estimate") == "1")) {
            return true;
        }
        return null; 
    }
    protected function can_view_own_company_estimate() {
        if ($this->login_user->user_type == "staff" && get_array_value($this->login_user->permissions, "estimate") === "own_company") {
            return $this->login_user->department; 
        }
        else if($this->login_user->user_type == "staff" && $this->login_user->company_access==="all"){
            return $this->login_user->department;
        }
        return null; 
    }
   // ----------------------------------------------------payment-----------------------------------------------------
   protected function can_view_payment() {
    if ($this->login_user->user_type == "staff" && get_array_value($this->login_user->permissions, "hide_payment") !== "1") {
        return true; 
    }
    return false;
}


protected function can_edit_payment() {
    if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_update_payment") == "1")) {
        return true;
    }
    return false; 
}


protected function can_add_payment() {
    if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_add_payment") == "1")) {
        return true;
    }
    return false;
}

protected function can_delete_payment() {
    if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_payment") == "1")) {
        return true;
    }
    return false; 
}
protected function can_view_own_company_payment() {
    if ($this->login_user->user_type == "staff" && get_array_value($this->login_user->permissions, "payment") === "own_company") {
        return $this->login_user->department; 
    }
    else if($this->login_user->user_type == "staff" && $this->login_user->company_access==="all"){
        return $this->login_user->department;
    }
    return false; 
}

 // ----------------------------------------------------client-----------------------------------------------------
 protected function can_view_client() {
    if ($this->login_user->user_type == "staff" && get_array_value($this->login_user->permissions, "hide_client") !== "1") {
        return true; 
    }
    return false;
}


protected function can_edit_client() {
    if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_update_client") == "1")) {
        return true;
    }
    return false; 
}


protected function can_add_client() {
    if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_add_client") == "1")) {
        return true;
    }
    return false;
}

protected function can_delete_client() {
    if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_client") == "1")) {
        return true;
    }
    return false; 
}
protected function can_view_own_company_client() {
    if ($this->login_user->user_type == "staff" && get_array_value($this->login_user->permissions, "client") === "own_company") {
        return $this->login_user->department; 
    }
    else if($this->login_user->user_type == "staff" && $this->login_user->company_access==="all"){
        return $this->login_user->department;
    }
    return false; 
}
 // ----------------------------------------------------supplier-----------------------------------------------------
 protected function can_view_supplier() {
    if ($this->login_user->user_type == "staff" && get_array_value($this->login_user->permissions, "hide_supplier") !== "1") {
        return true; 
    }
    return false;
}


protected function can_edit_supplier() {
    if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_update_supplier") == "1")) {
        return true;
    }
    return false; 
}


protected function can_add_supplier() {
    if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_add_supplier") == "1")) {
        return true;
    }
    return false;
}

protected function can_delete_supplier() {
    if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_supplier") == "1")) {
        return true;
    }
    return false; 
}
protected function can_view_own_company_supplier() {
    if ($this->login_user->user_type == "staff" && get_array_value($this->login_user->permissions, "supplier") === "own_company") {
        return $this->login_user->department; 
    }
    else if($this->login_user->user_type == "staff" && $this->login_user->company_access==="all"){
        return $this->login_user->department;
    }
    return false; 
}

// ----------------------------------------------------expense-----------------------------------------------------
protected function can_view_expense() {
    if ($this->login_user->user_type == "staff" && get_array_value($this->login_user->permissions, "hide_expense") !== "1") {
        return true; 
    }
    
    return false;
}


protected function can_edit_expense() {
    if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_update_expense") == "1")) {
        return true;
    }
    return false; 
}


protected function can_add_expense() {
    if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_add_expense") == "1")) {
        return true;
    }
    return false;
}

protected function can_delete_expense() {
    if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_expense") == "1")) {
        return true;
    }
    return false; 
}
protected function can_view_own_company_expense() {
    if ($this->login_user->user_type == "staff" && get_array_value($this->login_user->permissions, "expense") === "own_company") {
        return $this->login_user->department; 
    }
    else if($this->login_user->user_type == "staff" && $this->login_user->company_access==="all"){
        return $this->login_user->department;
    }
    return false; 
}
// ----------------------------------------------------task-----------------------------------------------------
protected function can_view_task() {
    if ($this->login_user->user_type == "staff" && get_array_value($this->login_user->permissions, "hide_task") !== "1") {
        return true; 
    }
    return false;
}


protected function can_edit_task() {
    if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_edit_tasks") == "1")) {
        return true;
    }
    return false; 
}


protected function can_add_task() {
    if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_create_tasks") == "1")) {
        return true;
    }
    return false;
}

protected function can_delete_task() {
    if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_tasks") == "1")) {
        return true;
    }
    return false; 
}
protected function can_view_own_company_task() {
    if ($this->login_user->user_type == "staff" && get_array_value($this->login_user->permissions, "task") === "own_company") {
        return $this->login_user->department; 
    }
    else if($this->login_user->user_type == "staff" && $this->login_user->company_access==="all"){
        return $this->login_user->department;
    }
    return false; 
}



    protected function can_view_own_department_client() {
        if ($this->login_user->company_access == "all" && ($this->login_user->user_type == "staff" || get_array_value($this->login_user->permissions,"invoice") ==="own_company")){
        return $this->login_user->department; 
    }
    return null; 
    }




    public function get_merchant_types_dropdown() {
        
        $merchant_types = $this->db->query("SELECT mt.id, mt.merchant_type FROM rise_merchant_types mt WHERE mt.deleted=0")->getResult();
        $temp_array = array('' => '---  Choose Merchant Type ---');

        if(!$merchant_types){
            return null;
        }
  
        foreach($merchant_types as $m){
            $temp_array[$m->id] = $m->merchant_type;
        }

        return $temp_array;
    }

    public function get_merchant_types_dropdown_js() {
        
        $merchant_types = $this->db->query("SELECT mt.id, mt.merchant_type FROM rise_merchant_types mt WHERE mt.deleted=0")->getResult();
        // $temp_array[] = array('id' =>'','text'=> '---  Choose Merchant Type ---');

        if(!$merchant_types){
            return [];
        }
  
        foreach($merchant_types as $m){
            $temp_array[] = array('id' => $m->id,'text' => $m->merchant_type);
        }

        return $temp_array;
    }


    protected function show_own_office_only_user_id() {
        if ($this->login_user->user_type === "staff") {
            // print_r(get_array_value($this->login_user->permissions, "somgas"));die;
            return get_array_value($this->login_user->permissions, "somgas") == "own_office" ? $this->login_user->id : false;
        }
    }


    public function can_view_team_members_contact_info()
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin) {
                return true;
            } else if (get_array_value($this->login_user->permissions, "can_view_team_members_contact_info") == "1") {
                return true;
            }
        }
    }
    public function get_departments_for_table_emp() {
        $dept_id = $this->get_user_department_id();
        $role = $this->get_user_role();
    
        if ($role == 'admin' || $role == 'Administrator' || $role == 'HRM') {
            $dept_id = '%';
        }
    
        if (!$dept_id) {
            return json_encode([['id' => '', 'text' => 'All Companies']]);
        }
    
        $query = "SELECT id, name FROM rise_company WHERE id LIKE ?";
        $depts = $this->db->query($query, [$dept_id]);
    
        if (!$depts) {
            return json_encode([['id' => '', 'text' => 'All Companies']]);
        }
    
        $data[] = ['id' => '', 'text' => 'All Companies'];
    
        foreach ($depts->getResult() as $d) {
            $data[] = ['id' => $d->id, 'text' => $d->name];
        }
    
        return json_encode($data);
    }
    

    public function get_user_role() {
        $user = $this->login_user;

        if($user->is_admin){
            return 'admin';
        }
        
        $role = $this->Roles_model->get_one($user->role_id);
        return $role->title;
    }

    public function get_user_department_id() {
        $user_id = $this->login_user->department ?? null; 
    
        if (!$user_id) {
            return null; 
        }
    
        $query = "SELECT t.company_id FROM rise_expenses t 
                  LEFT JOIN rise_users u ON u.id = t.user_id 
                  WHERE t.company_id = ?";
        
        $job_info = $this->db->query($query, [$user_id])->getRow();
    
        return $job_info?->company_id;
    }
    


}
