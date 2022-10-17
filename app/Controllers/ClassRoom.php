<?php

namespace App\Controllers;

use App\Models\ClassRoomModel;
use App\Models\AnnouncementModel;
use App\Models\AssignmentModel;
use App\Models\LessionModel;
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
        $this->lession = new LessionModel();
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
        universities.id AS `university_id`,
        universities.university,
        classroom.class_id,
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
        	meeting.class_id = " . $class_id . "")->getResult();
        return $result;
    }
    private function getAssignedAssignments($class_id)
    {
        $result = $this->db->query("SELECT
        assignments.a_name,
        assignments.a_id,
        assignments.a_score,
        assignments.created_at,
        (
        SELECT COUNT(CASE WHEN user_assignment.`status` = 1 THEN 1 END) FROM user_assignment WHERE user_assignment.a_id = assignments.a_id
        ) AS `submitted`
        FROM
            assignments
        WHERE
            assignments.a_classid = " . $class_id . " AND assignments.deleted_at IS NULL
        ORDER BY
            assignments.created_at DESC
            ")->getResult();
        return $result;
    }
    private function getAssignmentList($class_id, $user_id)
    {
        if (!empty($user_id)) {
            $classSearch = (!empty($class_id)) ? "assignments.a_classid =" . $class_id . " AND " : null;
            $result = $this->db->query("SELECT
                classroom.class_id,
                classroom.class_name,
                assignments.a_name,
                assignments.a_id,
                assignments.a_instruction,
                assignments.a_score `assign_score`,
                IFNULL( user_assignment.score, 0 ) AS `user_score`,
                IFNULL( user_assignment.`status`, 0 ) AS `status`,
                assignments.due_date 
            FROM
                assignments
                LEFT JOIN user_assignment ON assignments.a_id = user_assignment.a_id 
                AND user_assignment.user_id = " . $user_id . " 
                INNER JOIN
                classroom
                ON
                    assignments.a_classid = classroom.class_id
            WHERE
                " . $classSearch . "
                assignments.due_date > NOW() 
            HAVING `status` = 0")->getResult();
            return $result;
        }
    }
    private function getFiles($class_id)
    {
        $result = $this->db->query("SELECT
        assignment_attachments.attach_name, 
        assignment_attachments.attach_size, 
        assignment_attachments.uploaded_at, 
        assignment_attachments.attach_link,
        CONCAT(users.fname, ' ' ,users.lname) AS `name`
        FROM
	assignment_attachments
	INNER JOIN
	assignments
	ON 
		assignment_attachments.assignment_id = assignments.a_id
	INNER JOIN
	users
	ON 
		assignment_attachments.attach_owner = users.id
WHERE
	assignments.a_classid = " . $class_id . "")->getResult();

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
            'meeting' => $this->getMeeting($class_id),
            'assignments' => ($this->session->get('id') != $classInfo->class_owner) ? $this->getAssignedAssignments($class_id) : $this->getAssignmentList($classInfo->class_id, $this->session->get('id')),
            'files' => $this->getFiles($class_id),
            'lessions' => $this->lession->where('class_id', $class_id)->select(['less_title', 'les_id'])->findAll()
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
                echo view('classroom/t_assign', ['class_id' => $class_id]);
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
        $form_data['status'] = 1;
        $form_data['due_date'] = (empty($form_data['due_date'])) ? "9999-01-01 00:00" : $form_data['due_date'];
        $form_data['due_date'] = str_replace("/", "-", $form_data['due_date']);
        $form_data['due_date'] = date("Y-m-d H:i:s", strtotime($form_data['due_date']));
        if ($insertedID = $this->assign->insert($form_data)) {
            return $this->response->setJson(['status' => true, 'lastInsertID' => $insertedID]);
        } else {
            return $this->response->setJson(['status' => false]);
        }
    }

    public function editAssignment($class_id)
    {
        echo view('templates/header', ['title' => 'แก้ไขชั้นเรียน']);
        echo view('classroom/class_settings', ['classInfo' => $this->getClassInfo($class_id), 'university' => $this->db->table('universities')->select(['id', 'university'])->get()->getResult()]);
        echo view('templates/footer');
    }
    public function saveEditedClass()
    {
        $form_data = $this->request->getPost();
        $class_id = $this->request->getGet('class_id');

        $result = $this->db->table('classroom')->where('class_id', $class_id)->update($form_data);

        if ($result) {
            return $this->response->setJson(['status' => true]);
        } else {
            return $this->response->setJson(['status' => false]);
        }
    }

    public function viewUserScore()
    {
        $user_id = $this->session->get('id');
        $score = $this->db->query("SELECT
            assignments.a_name, 
            user_assignment.score AS userscore, 
            assignments.a_score AS score, 
            classroom.class_name,
            user_assignment.submitted_at
        FROM
            user_assignment
            INNER JOIN
            assignments
            ON 
                user_assignment.a_id = assignments.a_id
            INNER JOIN
            classroom
            ON 
                assignments.a_classid = classroom.class_id
        WHERE
            user_assignment.user_id = " . $user_id . "
            AND user_assignment.status = 1
        ORDER BY
            user_assignment.submitted_at DESC
            ")->getResult();
        echo view('templates/header', ['title' => 'คะแนนของฉัน']);
        echo view('classroom/myscore', ['myscores' => $score]);
        echo view('templates/footer');
    }

    public function createLession($class_id)
    {
        echo view('templates/header', ['title' => 'เพิ่มบทเรียน']);
        echo view('classroom/create_lession', ['class_id' => $class_id]);
        echo view('templates/footer');
    }
    public function viewLession($class_id)
    {
        $less_id = $this->request->getGet('id');
        $lession_data = $this->lession->select(['class_id', 'less_title', 'less_content'])->find($less_id);
        $classInfo = $this->getClassInfo($class_id);
        if ($lession_data && $lession_data["class_id"] == $class_id) {
            echo view('templates/header', ['title' => $lession_data['less_title']]);
            echo view('classroom/view_lession', ['lessInfo' => $lession_data, 'classInfo' => $classInfo]);
            echo view('templates/footer');
        } else {
            return "ERROR: NO PERMISSION";
        }
    }
    public function checkAssignment($class_id, $a_id)
    {
        $user_id = $this->session->get('id');
        $works = $this->db->query("SELECT   
            CONCAT(prefix.prefix, ' ' , users.fname, ' ' , users.lname) AS `name`, 
            users.std_code, 
            users.email, 
            user_assignment.score, 
            user_assignment.user_id, 
            assignment_attachments.attach_name, 
            assignment_attachments.attach_link, 
            user_assignment.submitted_at
            FROM
                user_assignment
                INNER JOIN
                users
                ON 
                    user_assignment.user_id = users.id
                LEFT JOIN
                assignment_attachments
                ON 
                    users.id = assignment_attachments.attach_owner AND
                    user_assignment.a_id = assignment_attachments.assignment_id
                INNER JOIN
                prefix
                ON prefix.prefix_id = users.prefix
            WHERE
                user_assignment.`status` = 1 AND
                user_assignment.a_id = " . $a_id . "
            ORDER BY user_assignment.submitted_at DESC
    ")->getResult();

        $files = $this->db->query("SELECT
        *
    FROM
        user_attachments
        INNER JOIN
        assignments
        INNER JOIN
        user_assignment
        ON 
            assignments.a_id = user_assignment.a_id AND
            user_attachments.ua_owner = user_assignment.user_id
    WHERE
        assignments.a_id = " . $a_id . "")->getResult();
        echo view('templates/header', ['title' => 'ตรวจงาน']);
        echo view('classroom/check', ['works' => $works, 'files' => $files, 'assignmentID' => $a_id]);
        echo view('templates/footer');
    }
}
