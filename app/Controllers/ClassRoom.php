<?php

namespace App\Controllers;

use App\Models\ClassRoomModel;
use App\Models\AnnouncementModel;
use App\Models\AssignmentModel;
use Config\Database;
use Config\Services;

class ClassRoom extends BaseController
{
    public function __construct()
    {
        $this->request = Services::request();
        $this->session = Services::session();
        $this->assign = new AssignmentModel();
        $this->annoce = new AnnouncementModel();
        $this->classroom = new ClassRoomModel();
        $this->db = Database::connect();
    }
    private function getClassMember($class_id)
    {
        return $this->db->query("SELECT CONCAT(prefix.prefix,' ', users.fname,' ', users.lname) AS `name`, users.nickname, users.std_code, users.profile_img, class_attendance.joined_at FROM class_attendance INNER JOIN users ON class_attendance.user_id = users.id INNER JOIN prefix ON prefix.prefix_id = users.prefix WHERE class_attendance.class_id = " . $class_id . "")->getResult();
    }
    private function getClassInfo($class_id)
    {
        return $this->db->query("SELECT
        (
        SELECT
            CONCAT( users.fname, ' ', users.lname ) 
        FROM
            classroom AS b
            INNER JOIN users ON b.class_owner = users.id 
        WHERE
            b.class_id = " . $class_id . " 
        ) AS 'owner_name',
        universities.university,
        classroom.class_owner,
        classroom.class_name,
        classroom.class_description,
        classroom.class_img_cover,
        classroom.class_code,
        classroom.class_invite,
        classroom.created_at 
        FROM
            classroom
            INNER JOIN universities ON classroom.university_id = universities.id 
        WHERE
            classroom.`status` = 1 
            AND classroom.deleted_at IS NULL 
            AND classroom.class_id = " . $class_id . "")->getRow();
    }
    private function getMeeting($class_id)
    {
        $result = $this->db->query("SELECT
        	meeting.meet_name, 
        	meeting.meet_invite, 
        	meeting.meet_owner
        FROM
        	meeting
        WHERE
        	meeting.end_at IS NULL AND
        	meeting.class_id = ".$class_id."")->getResult();
        return $result;
    }
    public function index($class_id)
    {
        $classInfo = $this->getClassInfo($class_id);
        echo view('templates/header', ['title' => $classInfo->class_name]);
        echo view('classroom/home', [
            'class_id' => $class_id,
            'classInfo' => $classInfo,
            'classMembers' => $this->getClassMember($class_id),
            'meeting' => $this->getMeeting($class_id)
        ]);
        echo view('templates/footer');
    }
    public function meetingRoom()
    {
        echo view('templates/header', ['title' => 'Instant Conference']);
        if ($meetID = $this->request->getGet('meetID')) {
            $meetInfo = $this->db->table('meeting')
                ->select('*')
                ->where('meet_invite', $meetID)
                ->get()
                ->getRow();
            echo view('classroom/meet', [
                'meetInfo' => $meetInfo,
            ]);
        } else {
            return 'ERROR: PARAMETER INCORRECT';
        }
        echo view('templates/footer');
    }
    public function createClass()
    {
        $form_data = $this->request->getPost();
        $form_data['class_invite'] = substr(uniqid(), 5, 6);
        $form_data['class_owner'] = $this->session->get('id');

        if ($insertedID = $this->classroom->insert($form_data)) {
            return $this->response->setJson(['status' => true, 'lastInsertID' => $insertedID]);
        } else {
            return $this->response->setJson(['status' => false]);
        };
    }

    public function createAnnouce()
    {
        $form_data = $this->request->getPost();
        if ($result = $this->annoce->insert($form_data)) {
            return $this->response->setJson(['status' => true, 'lastInsertID' => $result]);
        } else {
            return $this->response->setJson(['status' => false]);
        }
    }
    public function getAnnouce()
    {
        $class_id = $this->request->getGet('class_id');
        $streamsContents = $this->db->query("SELECT 
        CONCAT(users.fname, ' ' ,users.lname) AS `name`,
        users.profile_img,
        annoucements.title, 
        annoucements.content, 
        annoucements.created_at
        FROM
            annoucements
            INNER JOIN
            classroom
            ON 
                annoucements.class_id = classroom.class_id
            INNER JOIN
            users
            ON 
                classroom.class_owner = users.id
        WHERE
            annoucements.class_id = " . $class_id . "
        ORDER BY
            annoucements.created_at DESC
            ")->getResult();
        return view('classroom/panelitems/posts', ['streams' => $streamsContents]);
    }

    public function addAssignment($class_id)
    {
        echo view('templates/header', ['title' => 'เพิ่มงานไปยังชั้นเรียน']);
        switch ($this->request->getGet('type')) {
            case '1':
                echo view('classroom/t_assign');
                break;

            default:
                # code...
                break;
        }
        echo view('templates/footer');
    }

    public function createAssignment()
    {
        $form_data = $this->request->getPost();
        $user_id = $this->session->get('id');

        if ($this->assign->insert($form_data)) {
        } else {
        }
    }
}
