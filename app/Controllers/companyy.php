<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Companyy_model;
use CodeIgniter\Database\Exceptions\DataException;

class Companyy extends Security_Controller
{
    public function some_method() {
        $data['login_user'] = $this->session->get('login_user'); // Assuming the user data is stored in the session
        return view('todo/company_topbar_icon', $data);
    }
    public function save()
    {
        $request = service('request');
        $department = $request->getPost('department');

        if ($department !== null) {
            $db = db_connect();
            $user_id = $this->login_user->id;

            $builder = $db->table('rise_users');
            $existingUser = $builder->where('id', $user_id)->get()->getRow();

            $company_id = ($department === "0") ? null : $department;

            if ($existingUser) {
                $builder->where('id', $user_id)->update(['department' => $company_id]);

                return $this->response->setJSON([
                    "success" => true,
                    "message" => "User updated successfully",
                    "user" => ['id' => $user_id, 'department' => $company_id]
                ]);
            } else {
                return $this->response->setJSON([
                    "success" => false,
                    "message" => "User not found"
                ]);
            }
        }

        return $this->response->setJSON([
            "success" => false,
            "message" => "Department not provided"
        ]);
    }
}
