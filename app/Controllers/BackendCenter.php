<?php

namespace App\Controllers;

use App\Models\RegisterModel;
use App\Models\ClassRoomModel;
use App\Models\LessionModel;
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
        $this->classroom = new ClassRoomModel();
        $this->lession = new LessionModel();
    }

    public function doRegister()
    {
        $form_data = $this->request->getPost();
        $form_data['password'] = password_hash($form_data['password'], PASSWORD_BCRYPT);
        $form_data['profile_img'] = null;
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

    private function uploadFile($directoryName = null, $file)
    {
        $directoryName = '/' . $directoryName;
        $newName = $file->getRandomName();
        $file->move(ROOTPATH . 'public/uploads' . $directoryName, $newName);
        if ($file->hasMoved()) {
            $fileName = $file->getName();
            return $this->response->setJson(
                [
                    'status' => true,
                    'fileName' => $fileName,
                    'filePath' => '/uploads' . $directoryName . '/' . $fileName,
                    'fileExtension' => strtolower($file->getClientExtension()),
                    'fileSize' => $file->getSizeByUnit('kb')
                ]
            );
        } else {
            return $this->response->setJson(['status' => false]);
        }
    }

    public function uploadAttachment()
    {
        $userID = $this->session->get('id');
        $file = $this->request->getFile('attach');
        return $this->uploadFile('class_attach/' . $userID, $file);
    }

    public function saveUserAttachment()
    {
        if (empty($form_data['data'])) {
            return $this->response->setJson(['status' => true]);
        }
    }

    public function saveAttachment()
    {

        $form_data = $this->request->getPost();
        $builder = $this->db->table('assignment_attachments');
        if (empty($form_data['data'])) {
            return $this->response->setJson(['status' => true]);
        }
        foreach ($form_data['data'] as $data) {
            foreach ($data as $value) {
                $fileInfo = [
                    'assignment_id' => $form_data['assign_id'],
                    'attach_owner' => session()->get('id'),
                    'attach_name' => $value['fileName'],
                    'attach_link' => $value['filePath'],
                    'attach_ext' => $value['fileExtension'],
                    'attach_size' => $value['fileSize'],
                    'uploaded_at' => date("Y-m-d H:i:s")
                ];

                try {
                    $builder->insert($fileInfo);
                    continue;
                } catch (\Exception $th) {
                    return $this->response->setJson(['status' => false, 'msg' => $th->getMessage()]);
                }
            }
        }
        return $this->response->setJson(['status' => true]);
    }

    public function uploadAvatar()
    {
        $file = $this->request->getFile('avatar_upload');
        return $this->uploadFile('avatar', $file);
    }

    public function uploadCover()
    {
        $file = $this->request->getFile('class_img_cover');
        return $this->uploadFile('cover', $file);
    }

    public function updateCover()
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
        $form_data['meet_invite'] = $form_data['meet_invite'] . time();
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
    public function submitAttachment()
    {
        $form_data =  $this->request->getPost();
        if (empty($form_data['uploaded'])) {
            return $this->response->setJson(['status' => true]);
        }
        foreach ($form_data['uploaded'] as $files) {
            foreach ($files as $file) {
                $single_file =  [
                    'au_id' => $form_data['au_id'],
                    'ua_classid' => $form_data['ua_classid'],
                    'ua_owner' => $this->session->get('id'),
                    'ua_name' => $file['fileName'],
                    'ua_path' => $file['filePath'],
                    'ua_ext' => $file['fileExtension'],
                    'ua_size' => $file['fileSize'],
                    'uploaded_at' => date("Y-m-d H:i:s"),
                ];
                $result = $this->db->table('user_attachments')->insert($single_file);
                if ($result) {
                    return $this->response->setJson(['status' => true, 'lastInsertID' => $this->db->insertID()]);
                } else {
                    return $this->response->setJson(['status' => false]);
                }
            }
        }
    }

    private function isSubmitted($uesr_id, $assignment_id)
    {
        $result = $this->db->query("SELECT
        COUNT(user_assignment.a_id) as `submitted_count`
    FROM
        user_assignment
        INNER JOIN
        assignments
        ON 
            user_assignment.a_id = assignments.a_id
    WHERE
        user_assignment.user_id = " . $uesr_id . " AND
        user_assignment.a_id = " . $assignment_id . "")->getResult();
        return $result;
    }

    public function submitAssignment()
    {
        $form_data =  $this->request->getPost();
        if (!empty($form_data)) {
            $form_data['score'] = 0;
            $form_data['submitted_at'] = date("Y-m-d H:i:s");
            $result = $this->db->table('user_assignment')->insert($form_data);

            if ($result) {
                return $this->response->setJson(['status' => true, 'lastInsertID' => $this->db->insertID()]);
            } else {
                return $this->response->setJson(['status' => false]);
            }
        }
    }

    public function doLeaveClass()
    {
        $class_id = $this->request->getGet('id');
        $user_id = $this->session->get('id');
        if ($this->db->table('user_assignment')->where('class_id', $class_id)->where('user_id', $user_id)->update('leave_at', date("Y-m-d H:i:s"))) {
            return $this->response->setJson(['status' => true]);
        } else {
            return $this->response->setJson(['status' => false]);
        }
    }
    public function doRemoveClass()
    {
        $class_id = $this->request->getGet('class_id');
        if ($this->classroom->delete($class_id)) {
            return $this->response->setJson(['status' => true]);
        } else {
            return $this->response->setJson(['status' => false]);
        }
    }
    public function doCreateLession()
    {
        $form_data = $this->request->getPost();
        if ($lastInsertID = $this->lession->insert($form_data)) {
            return $this->response->setJson(['status' => true, 'less_id' => $lastInsertID]);
        } else {
            return $this->response->setJson(['status' => false]);
        }
    }
    public function givescore()
    {
        $data = $this->request->getPost();
        $result = $this->db->table('user_assignment')
            ->set('score', $data['score'])
            ->where('a_id', $data['assignment_id'])
            ->where('user_id', $data['user_id'])
            ->update();
        if ($result) {
            return $this->response->setJson(['status' => true]);
        } else {
            return $this->response->setJson(['status' => false]);
        }
    }
}
