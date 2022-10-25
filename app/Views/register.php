<img src="/img/corner-bg.png" class="d-none d-sm-none d-md-block corner-bg">
<div style="height: 92vh;background: url('/img/test.jpg');background-size: cover">
    <div class="container">
        <div class="row position-relative" style="height: 85vh;z-index: 5">
            <div class="col-md-6 my-auto text-center">
                <div class="spinner-border mt-auto mb-auto" role="status" id="loadingpage">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <form method="POST" class="py-2" id="regisForm">
                    <h4>ลงทะเบียนเข้าสู่ระบบ</h4>
                    <div class="p-3">
                        <div class="row gy-3 gx-2">
                            <div class="col-2">
                                <select id="prefix" class="select">
                                    <option value="1">นาย</option>
                                    <option value="2">นางสาว</option>
                                    <option value="3">นาง</option>
                                </select>
                            </div>
                            <div class="col-5">
                                <div class="form-outline">
                                    <input type="text" id="fname" name="fname" class="form-control" required />
                                    <label class="form-label" for="fname">ชื่อจริง</label>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="form-outline">
                                    <input type="text" id="lname" name="lname" class="form-control" required />
                                    <label class="form-label" for="lname">นามสกุล</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-outline">
                                    <input type="text" id="username" name="username" class="form-control" required />
                                    <label class="form-label" for="username">ชื่อผู้ใช้</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-outline">
                                    <input type="password" id="password" name="password" class="form-control" required />
                                    <label class="form-label" for="password">รหัสผ่าน</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-outline">
                                    <input type="text" id="nickname" name="nickname" class="form-control" />
                                    <label class="form-label" for="nickname">ชื่อเล่น</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-outline">
                                    <input type="email" id="email" name="email" class="form-control" required />
                                    <label class="form-label" for="email">อีเมล์</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-outline">
                                    <input type="text" id="stdcode" name="std_code" class="form-control" />
                                    <label class="form-label" for="std_code">รหัสนักศึกษา (ถ้ามี)</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-outline">
                                    <input type="text" id="telephone" name="telephone" class="form-control" />
                                    <label class="form-label" for="telephone">เบอร์โทรศัพท์</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <select class="select" data-mdb-filter="true" name="university" id="university">
                                    <option value="0" selected hidden>มหาวิทยาลัย</option>
                                    <?php foreach ($university as $uniName) : ?>
                                        <option value="<?= $uniName->id ?>"><?= $uniName->university ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                </form>
            </div>
            <div class="col-md-6"></div>
        </div>
    </div>
</div>
<script>
    $("#regisForm").hide();
    const loadingForm = setInterval(function() {
        $("#loadingpage").hide();
        $("#regisForm").show();
        clearInterval(loadingForm);
    }, 1000)

    $('#regisForm').on('submit', function(event) {
        event.preventDefault();
        const form_data = {
            username: $('#username').val(),
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
        
        $("#loadingpage").show();
        $("#regisForm").hide();
        $.post('/register', form_data,
        function(data, textStatus, jqXHR) {
                if (data['status']) {
                    Swal.fire({
                        icon: 'success',
                        title: 'สมัครสมาชิกสำเร็จ',
                        text: 'โปรดเข้าสู่ระบบเพื่อดำเนินการต่อ'
                    }).then(function() {
                        location.href = '/login';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'มีบางอย่างไม่ถูกต้อง',
                        text: 'โปรดตรวจสอบข้อมูลของคุณ'
                    }).then(function(){
                        $("#loadingpage").hide();
                        $("#regisForm").show();
                    })
                }
            },
            "json"
        );
    });
</script>