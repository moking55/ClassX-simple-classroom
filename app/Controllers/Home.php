<?php

namespace App\Controllers;

class Home extends SqlCommands
{
    public function index()
    {
        echo view('templates/header', ['title' => 'ชั้นเรียนของฉัน']);
        if ($this->session->has('id')) {
            $userID = $this->session->get('id');
            echo view('userhome', [
                'university' => $this->db->table('universities')->select(['id', 'university'])->get()->getResult(),
                'userInfo' => $this->getUserInfo($userID)[0],
                'ownedClass' => $this->getOwnedClasses($userID),
                'joinedClass' => $this->getJoinedClass($userID)

            ]);
        } else {
            echo view('guesthome');
        }
        echo view('templates/footer');
    }
    public function info()
    {
        echo view('templates/header', ['title' => 'ClassX ระบบห้องเรียนออนไลน์']);
        echo view('info');
        echo view('templates/footer');
    }

}
