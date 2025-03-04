<?php

namespace aleelo_plugin\Controllers;
use aleelo_plugin\Controllers\App_Controller_Plugin;
use App\Controllers;
use App\Controllers\Security_Controller;
class Security_Controller_Plugin extends Security_Controller {

   public $Assigning_items_model;

    public $Screen_size_model;
    public $Items_list_model;
    public $db;
    public $Expenses_model;
    public $Company_model;
    public $Projects_model;
    public $Project_status_model;
    public $Clients_model;
    public $Users_models;

//     public$Collective_revenue_report_model;
    // use App_Controller;

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
         $this->Users_models= new \aleelo_plugin\Models\Users_models();


        // $this->Collective_revenue_report_model = new \emof_plugin\Models\Collective_revenue_report_model();
// $login_user_id = $this->Users_models->login_user_id();
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

    function get_departments_for_table_emp(){
        // $depts = $this->db->table('departments')->select('id,nameEn')->get();
        $dept_id = $this->get_user_department_id();
        $role = $this->get_user_role();

        if($role == 'admin' || $role == 'Administrator' || $role == 'HRM'){
            $dept_id = '%';
        }

        $depts = $this->db->query("select id,name from rise_company where id like '$dept_id'");
        $data[] = array('id' => '', 'text' => 'All Companies');

        if(!$depts){
            return [];
        }else{
            $depts = $depts->getResult();
            foreach($depts as $d){
                $data[] = array('id' => $d->id, 'text' => $d->name
            );
            }

            return json_encode($data);
        }


    }

    public function get_user_role() {
        $user = $this->login_user;

        if($user->is_admin){
            return 'admin';
        }
        
        $role = $this->Roles_model->get_one($user->role_id);
        return $role->title;
    }

    public function get_user_department_id(){
        $user_id = $this->login_user->id;
        $job_info = $this->db->query("SELECT t.company_id from rise_team_member_job_info t left join rise_users u on u.id=t.user_id where t.user_id = $user_id")->getRow();
        
        return $job_info?->company_id;
    }


}
