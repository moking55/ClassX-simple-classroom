<?php

namespace App\Controllers;

use Config\Services;
use Config\Database;
use App\Models\RegisterModel;

class SqlCommands extends BaseController
{
    public function __construct()
    {
        $this->session = Services::session();
        $this->user = new RegisterModel();
        $this->db = Database::connect();
    }
    protected function getUserInfo(int $user_id)
    {
        return $this->user->query("SELECT
        CONCAT(prefix.prefix, ' ',users.fname, ' ',users.lname) AS `name`, 
        users.nickname, 
        users.std_code, 
        universities.university, 
        users.profile_img, 
        COUNT(class_attendance.class_id) AS joined_class, 
        COUNT(classroom.class_id) AS own_class
        FROM
            users
            INNER JOIN
            prefix
            ON 
                users.prefix = prefix.prefix_id
            INNER JOIN
            universities
            ON 
                users.university_id = universities.id
            INNER JOIN
            class_attendance
            ON 
                users.id = class_attendance.user_id
            INNER JOIN
            classroom
            ON 
                users.id = classroom.class_owner
        WHERE
            users.id = " . $user_id . "")->getResult();
    }

    protected function getOwnedClasses(int $user_id)
    {
        return $this->user->query("SELECT
            classroom.class_id,
        	classroom.class_name,
        	classroom.class_description,
        	classroom.class_code,
            classroom.class_img_cover,
        	CONCAT(users.fname, ' ' ,users.lname) `class_owner_name`, 
        	users.profile_img as `class_owner`
        FROM
        	classroom
        	INNER JOIN
        	users
        	ON 
        		classroom.class_owner = users.id
        WHERE
        	classroom.class_owner = " . $user_id . " AND
        	classroom.deleted_at IS NULL AND
        	classroom.`status` = 1
        ")->getResult();
    }

    protected function getJoinedClass(int $user_id)
    {
        return $this->db->query("SELECT
        	CONCAT(users.fname,' ', users.lname) AS `teacher_name`, 
            users.profile_img,
        	classroom.class_id, 
        	classroom.class_name, 
        	classroom.class_img_cover, 
        	classroom.class_code
        FROM
        	class_attendance
        	INNER JOIN
        	classroom
        	ON 
        		class_attendance.class_id = classroom.class_id
        	INNER JOIN
        	users
        	ON 
        		classroom.class_owner = users.id
        WHERE
        	class_attendance.user_id = " . $user_id . " AND
            classroom.deleted_at IS NULL AND
        	classroom.`status` = 1 AND
            class_attendance.leave_at IS NULL

        ")->getResult();
    }

    protected function getAssignmentList($class_id, $user_id)
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
    
}
