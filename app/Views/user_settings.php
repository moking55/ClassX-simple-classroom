<div class="container" style="max-width: 860px;">
    <div class="text-center py-4">
        <input type="file" name="avatar_upload" class="d-none" id="avatar_upload">
        <img src="/img/placeholder-avatar.jpg" onclick="javascript: $('#avatar_upload').click()" class="rounded-circle mb-2" style="height: 200px" alt="user avatar">
        <h4 class="py-3">ยินดีต้อนรับคุณ <?= $profile->fname ?> <?= $profile->lname ?></h4>
        <div class="border rounded p-3">
            <div class="p-3">
                <form id="profileEdit">
                    <div class="row gy-3 gx-2">
                        <div class="col-2">
                            <select id="prefix" class="select">
                                <option value="<?= $profile->prefix_id ?>" hidden><?= $profile->prefix_name ?></option>
                                <option value="1">นาย</option>
                                <option value="2">นาง</option>
                                <option value="3">นางสาว</option>
                            </select>
                        </div>
                        <div class="col-5">
                            <div class="form-outline">
                                <input type="text" id="fname" name="fname" value="<?= $profile->fname ?>" class="form-control"  />
                                <label class="form-label" for="fname">ชื่อจริง</label>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-outline">
                                <input type="text" id="lname" value="<?= $profile->lname ?>" name="lname" class="form-control"  />
                                <label class="form-label" for="lname">นามสกุล</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <select class="select" data-mdb-filter="true" name="university" id="university">
                                <option value="<?= $profile->university_id ?>" selected hidden><?= $profile->university ?></option>
                                <?php foreach ($university as $uniName) : ?>
                                    <option value="<?= $uniName->id ?>"><?= $uniName->university ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <div class="form-outline">
                                <input type="password" id="password" name="password" class="form-control"  />
                                <label class="form-label" for="password">รหัสผ่าน</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-outline">
                                <input type="text" id="nickname" value="<?= $profile->nickname ?>" name="nickname" class="form-control" />
                                <label class="form-label" for="nickname">ชื่อเล่น</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-outline">
                                <input type="email" id="email" name="email" value="<?= $profile->email ?>" class="form-control"  />
                                <label class="form-label" for="email">อีเมล์</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-outline">
                                <input type="text" id="stdcode" name="std_code" value="<?= $profile->std_code ?>" class="form-control" />
                                <label class="form-label" for="std_code">รหัสนักศึกษา (ถ้ามี)</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-outline">
                                <input type="text" id="telephone" name="telephone" <?= $profile->telephone ?> class="form-control" />
                                <label class="form-label" for="telephone">เบอร์โทรศัพท์</label>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary">ยืนยันการแก้ไข</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $('#profileEdit').on('submit', function(e) {
        e.preventDefault();
        const form_data = {
            password: $('#password').val(),
            email: $('#email').val(),
            prefix: $('#prefix').val(),
            fname: $('#fname').val(),
            lname: $('#lname').val(),
            nickname: $('#nickname').val(),
            std_code: $('#stdcode').val(),
            telephone: $('#telephone').val(),
            university_id: $('#university').val(),
        }
        if (form_data['password'] == '') {
            delete form_data['password'];
        }
        $.post("/be/updateprofilesetting", form_data,
            function (data, textStatus, jqXHR) { 
                if (data['status'] == true) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ดำเนินการสำเร็จ',
                        text: 'บันทึกข้อมูลสำเร็จแล้ว'
                    }).then(function (){
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'ดำเนินการไม่สำเร็จ',
                        text: 'มีบางอย่างผิดปกติโปรดลองใหม่อีกครั้ง'
                    });
                }
            },
            "json"
        );
    });
</script>