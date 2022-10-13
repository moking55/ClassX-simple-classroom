<?php

namespace App\Controllers;

use Config\Services;
use Config\Database;
use App\Models\RegisterModel;

class UserCenter extends BaseController
{
    public function __construct()
    {
        $this->db = Database::connect();
        $this->session = Services::session();
        $this->userModel = new RegisterModel();
    }
    public function userRegister()
    {
        echo view("templates/header", ['title' => 'ระบบสมัครสมาชิก', 'university' => $this->db->table('universities')->select(['id', 'university'])->get()->getResult()]);
        echo view("register");
        echo view("templates/footer");
    }
    public function userLogin()
    {
        echo view("templates/header", ['title' => 'เข้าสู่ระบบ']);
        echo view("login");
        echo view("templates/footer");
    }
    public function assignments()
    {
        echo view("templates/header", ['title' => 'ระบบสมัครสมาชิก']);
        echo view("classroom/assignments");
        echo view("templates/footer");
    }
    public function userLogout()
    {
        $this->session->destroy();
        return redirect()->to('/');
    }
    public function userSettings()
    {
        $userID = $this->session->get('id');
        //dd($this->userModel->find($userID));
        //dd($this->db->query("SELECT prefix.prefix, users.profile_img, users.telephone, users.university_id, users.std_code, users.nickname, users.lname, users.fname, users.prefix, users.email, users.username, users.id, universities.university, universities.id FROM users INNER JOIN prefix ON users.prefix = prefix.prefix_id INNER JOIN universities ON users.university_id = universities.id WHERE users.id = " . $userID . "")->getRow());
        echo view("templates/header", ['title' => '']);
        echo view("user_settings", [
            'profile' => $this->db->query("SELECT prefix.prefix AS prefix_name ,prefix.prefix_id, users.* , universities.university, universities.id FROM users INNER JOIN prefix ON users.prefix = prefix.prefix_id INNER JOIN universities ON users.university_id = universities.id WHERE users.id = " . $userID . "")->getRow(),
            'university' => $this->db->table('universities')->select(['id', 'university'])->get()->getResult()]);
        echo view("templates/footer");
    }
}
