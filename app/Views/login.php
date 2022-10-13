<img src="/img/corner-bg.png" class="d-none d-sm-none d-md-block corner-bg">
<div style="height: 92vh;background: url('/img/test.jpg');background-size: cover">
    <div class="container">
        <div class="row position-relative" style="height: 85vh;z-index: 5">
            <div class="col-md-6 my-auto text-center">
                <div class="spinner-border mt-auto mb-auto" role="status" id="loadingpage">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <form method="POST" class="py-2" id="loginForm">
                    <h4>เข้าสู่ระบบ</h4>
                    <div class="p-3">
                        <div class="row gy-3 gx-2">
                            <div class="col-12 px-5">
                                <div class="form-outline">
                                    <input type="text" id="username" name="username" class="form-control" required />
                                    <label class="form-label" for="username">ชื่อผู้ใช้</label>
                                </div>
                            </div>
                            <div class="col-12 px-5">
                                <div class="form-outline">
                                    <input type="password" id="password" name="password" class="form-control" required />
                                    <label class="form-label" for="password">รหัสผ่าน</label>
                                </div>
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

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
<script>
    $("#loginForm").hide();
    const loadingForm = setInterval(function() {
        $("#loadingpage").hide();
        $("#loginForm").show();
        clearInterval(loadingForm);
    }, 800);
    $('#loginForm').on('submit', function(event) {
        event.preventDefault();
        const form_data = {
            username: $('#username').val(),
            password: $('#password').val()
        }

        $.post("/login", form_data,
            function(data, textStatus, jqXHR) {
                $("#loadingpage").show();
                $("#loginForm").hide();
                if (data['status']) {
                    Swal.fire({
                        icon: 'success',
                        title: 'เข้าสู่ระบบสำเร็จ',
                        text: 'เรากำลังจัดเตรียมสิ่งต่างๆให้คุณ....',
                        timer: Math.floor(Math.random() * (3000 - 1500 + 1) + 1500),
                        allowEnterKey: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    }).then(function() {
                        location.href = '/';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'มีบางอย่างไม่ถูกต้อง',
                        text: 'โปรดตรวจสอบชื่อผู้ใช้หรือรหัสผ่าน'
                    }).then(function() {
                        $("#loadingpage").hide();
                        $("#loginForm").show();
                    })
                }
            },
            "json"
        );
    });
</script>