<?php

namespace App\Controllers;

use App\Models\RegisterModel;
use CodeIgniter\I18n\Time;
use Config\Services;
use Config\Database;

class BackendCenter extends BaseController
{
    public function __construct()
    {
        $this->registerModel = new RegisterModel();
        $this->request = Services::request();
        $this->session = Services::session();
        $this->db = Database::connect();
    }

    public function doRegister()
    {
        $form_data = $this->request->getPost();
        $form_data['password'] = password_hash($form_data['password'], PASSWORD_BCRYPT);

        try {
            $result = $this->registerModel->insert($form_data, true);
        } catch (\Exception $th) {
            $result = false;
        }
        return $this->response->setJson(['status' => boolval($result)]);
    }

    public function doLogin()
    {
        $form_data = $this->request->getPost();
        $userinfo = $this->registerModel->select(['id', 'username', 'password'])->where('username', $form_data['username'])->first();
        if (!empty($form_data['username']) && !empty($form_data['password']) && !is_null($userinfo)) {
            if (password_verify($form_data['password'], $userinfo['password'])) {
                $this->session->set('id', $userinfo['id']);
                return $this->response->setJson(['status' => true]);
            } else {
                return $this->response->setJson(['status' => false]);
            }
        } else {
            return $this->response->setJson(['status' => false]);
        }
    }

    public function doJoinClass()
    {
        $classCode = $this->request->getPost('class_invite');
        $userID = $this->session->get('id');
        $result = $this->db->query("SELECT class_id FROM classroom WHERE class_invite LIKE '" . $classCode . "'")->getRow();
        if (!empty($result)) {
            $classID = $result->class_id;
            /* ลองทำเป็น procedure แล้วทำไม่ได้ งงเหมือนกันว่าทำไม */
            /* โค้ดนี้แสดงผลถูกต้องแล้วใช้เวลา 3 ชั่วโมง+ ในการออกแบบยากชิบหาย */
            /* ฉะนั้นอะไรที่มันรันได้แล้วก็ไมจำเป็นต้องไปปรับมัน */
            $result = $this->db->query("SELECT
	            IFNULL(
	            	(
	            	SELECT
	            	IF
	            		( classroom.class_owner = " . $userID . ", 'OWNER',
                        IF ( class_attendance.user_id = " . $userID . "
                        AND class_attendance.class_id = " . $classID . ", 'JOINED', 'PASS' )) 
	            	FROM
	            		class_attendance
	            		INNER JOIN classroom ON classroom.class_invite = '" . $classCode . "'
	            		AND class_attendance.class_id = " . $classID . "
	            		LIMIT 1 
	            	),
	            'PASS') as `isJoinable`
            ")->getRow();
            if (!is_null($result) && $result->isJoinable == "PASS" && !empty($result)) {

                $data = [
                    'class_id' => $classID,
                    'user_id' => $userID,
                    'joined_at' => Time::now('Asia/Bangkok')
                ];
                $this->db->table('class_attendance')->insert($data);
                return $this->response->setJson(['status' => true, 'message' => 'USER_CAN_JOIN', 'classID' => $classID]);
            } else {
                return $this->response->setJson(['status' => false, 'message' => 'USER_CANNOT_JOIN']);
            }
        } else {
            return $this->response->setJson(['status' => false, 'message' => 'NO_CLASS_FOUND']);
        }
    }

    public function updateProfile()
    {
        $userID = $this->session->get('id');
        $form_data = $this->request->getPost();
        if (!empty($form_data['password'])) {
            $form_data['password'] = password_hash($form_data['password'], PASSWORD_BCRYPT);
        }
        if ($this->registerModel->update($userID, $form_data)) {
            return $this->response->setJson(['status' => true]);
        } else {
            return $this->response->setJson(['status' => false]);
        }
    }

    public function createMeeting()
    {
        $userID = $this->session->get('id');
        $form_data = $this->request->getPost();

        $form_data['meet_owner'] = $userID;
        $form_data['created_at'] = date('Y-m-d H:i:s');
        $form_data['meet_invite'] = $form_data['meet_invite']. time();
        if ($this->db->table('meeting')->insert($form_data)) {
            return $this->response->setJson(['status' => true, 'meetID' => $form_data['meet_invite']]);
        } else {
            return $this->response->setJson(['status' => false]);
        }
    }

    public function endMeeting()
    {
        $data = $this->request->getGet();
        $result = $this->db->table('meeting')
        ->set('end_at', date('Y-m-d H:i:s'))
        ->set('status', 0)
        ->where('id', $data['meetID'])
        ->update();
        return $result;
    }

    public function doLeaveClass()
    {
        # code...
    }
    public function doRemoveClass()
    {
        # code...
    }
}
