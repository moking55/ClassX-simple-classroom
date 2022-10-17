<?php

namespace App\Controllers;

use App\Controllers\SqlCommands;

class UserCenter extends SqlCommands
{
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

    private function getJoinedClassName($user_id){
        return $this->db->query("SELECT
        classroom.class_name, 
        classroom.class_id
    FROM
        class_attendance
        INNER JOIN
        classroom
        ON 
            class_attendance.class_id = classroom.class_id
    WHERE
        class_attendance.user_id = ".$user_id."")->getResult();
    }

    public function assignments()
    {
        echo view("templates/header", ['title' => 'งานที่มอบหมาย']);
        echo view("classroom/assignments",['classes' => $this->getJoinedClassName($this->session->get('id'))]);
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
        echo view("templates/header", ['title' => '']);
        echo view("user_settings", [
            'profile' => $this->db->query("SELECT prefix.prefix AS prefix_name ,prefix.prefix_id, users.* , universities.university, universities.id FROM users INNER JOIN prefix ON users.prefix = prefix.prefix_id INNER JOIN universities ON users.university_id = universities.id WHERE users.id = " . $userID . "")->getRow(),
            'university' => $this->db->table('universities')->select(['id', 'university'])->get()->getResult()]);
        echo view("templates/footer");
    }
     public function getUserAssignments() {
        $user_id = $this->session->get('id');
        $class_id = $this->request->getGet('class_id');
        return view('/classroom/panelitems/all_assignments', ['assignmentList' => $this->getAssignmentList($class_id, $user_id)]);
    }

    private function getAssignmentFile($assignment_id) {
        $file = $this->db->query("SELECT
        assignment_attachments.attach_name, 
        assignment_attachments.attach_link
    FROM
        assignment_attachments
    WHERE
        assignment_attachments.assignment_id = ".$assignment_id."")->getResult();
        return $file;
    }

    public function viewUserAssignment()
    {
        $assignment_id = $this->request->getGet('a_id');

        $classInfo = $this->db->query("SELECT
        assignments.due_date, 
        assignments.a_score, 
        assignments.a_instruction, 
        assignments.a_name, 
        assignments.a_classid, 
        assignments.a_id
        FROM
            assignments
        WHERE
            assignments.`status` = 1 AND
            assignments.deleted_at IS NULL AND
            assignments.a_id = ".$assignment_id."")->getRow();
            return view('/classroom/panelitems/view_assignment', [
                "userAssignment" => $classInfo,
                "files" => $this->getAssignmentFile($assignment_id)]);

    }
}
