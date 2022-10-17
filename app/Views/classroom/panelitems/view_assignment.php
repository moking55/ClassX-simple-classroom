<div class="container mt-4">
    <div class="row gy-3">
        <div class="col-md-6 d-flex order-md-1 justify-content-between">
            <div>
                <h5 class="mb-1">คะแนน</h5>
                <small><?= $userAssignment->a_score ?> คะแนนที่เป็นไปได้</small>
            </div>
            <div><button class="btn btn-primary d-inline js-confetti" style="z-index: 10000" type="button">ส่งงาน</button></div>
        </div>
        <div class="col-md-6 order-md-0">
            <h3 class="mb-1"><?= $userAssignment->a_name ?></h3>
            <?php if ($userAssignment->due_date == "9999-01-01 00:00:00") : ?>
                ไม่มีกำหนดส่ง
            <?php else : ?>
                <small>กำหนดส่ง <?= $userAssignment->due_date ?></small>
            <?php endif ?>
            <br><br>
            <h6 class="mb-1">คำแนะนำ</h6>
            <small><?= (empty($userAssignment->a_instruction) ? '<i>ไม่มีคำแนะนำ</i>' : $userAssignment->a_instruction) ?></small>
            <br><br>
            <h6 class="mb-1">ไฟล์แนบ</h6>
            <?php if (!empty($files)) : ?>
                <?php foreach ($files as $file) : ?>
                    <div class="rounded border p-2">
                        <a href="<?= $file->attach_link ?>" target="_blank"><?= $file->attach_name ?><a>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <small><i>ไม่มีไฟล์</i></small>
            <?php endif ?>
            <br><br>
            <h6 class="mb-2">งานของฉัน</h6>
            <input type="file" id="assign-file-upload" style="display: none;">
            <div class="dropdown">
                <button class="btn btn-outline-info btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-mdb-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-paperclip fa-fw me-1"></i>แนบ
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item text-muted" href="#"><i class="fas fa-link fa-fw me-1"></i>ลิ้ง</a></li>
                    <li><a class="dropdown-item text-muted" onclick="javascript: $('#assign-file-upload').click()" type="button"><i class="fas fa-upload fa-fw me-1"></i>ไฟล์</a></li>
                </ul>
            </div>

            <ul id="attach_list" class="list-group mt-3 list-group-light list-group-small">

            </ul>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>

<script>
    var triggers = $(".js-confetti");
    var defaults = {
        disableForReducedMotion: true,
        zIndex: 10000
    };
    var uploaded = [];

    function fire(particleRatio, opts) {
        confetti(
            Object.assign({}, defaults, opts, {
                particleCount: Math.floor(200 * particleRatio)
            })
        );
    }

    function confettiExplosion(origin) {
        fire(0.25, {
            spread: 26,
            startVelocity: 55,
            origin
        });
        fire(0.2, {
            spread: 60,
            origin
        });
        fire(0.35, {
            spread: 100,
            decay: 0.91,
            origin
        });
        fire(0.1, {
            spread: 120,
            startVelocity: 25,
            decay: 0.92,
            origin
        });
        fire(0.1, {
            spread: 120,
            startVelocity: 45,
            origin
        });
    }


    $('#assign-file-upload').on('change', function() {
        var fd = new FormData();
        var file = $('#assign-file-upload')[0].files[0];
        fd.append('attach', file);
        $.ajax({
            url: '/be/upload_attachment',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response) {
                uploaded.push({
                    response
                });
                console.log(uploaded);
                $('#attach_list').append(`<li class="list-group-item"><a href="${response['filePath']}" target="_blank">${response['fileName']}</a></li>`);
            },
        });
    });
    triggers.on('click', function() {
        $(triggers).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden">กำลังโหลดข้อมูล...</span>').attr('disabled', true);
        var data = {
            a_id: <?= $userAssignment->a_id ?>,
            user_id: <?= session()->get('id') ?>,
            status: 1
        }

        $.post("/be/submit_assignment", data,
            function(res, textStatus, jqXHR) {
                if (res['status'] == true) {
                    form_data = {
                        uploaded: uploaded,
                        ua_classid: <?= $userAssignment->a_classid ?>,
                        au_id: res['lastInsertID'],

                    };
                    $.post("/be/submit_attachment", form_data,
                        function(response, textStatus, jqXHR) {
                            if (response['status'] == true) {
                                Array.from(triggers).forEach((trigger) => {
                                    const rect = trigger.getBoundingClientRect();
                                    const center = {
                                        x: rect.left + rect.width / 2,
                                        y: rect.top + rect.height / 2
                                    };
                                    const origin = {
                                        x: center.x / window.innerWidth,
                                        y: center.y / window.innerHeight
                                    };
                                    confettiExplosion(origin);
                                });
                                $(triggers).removeClass('btn-primary').addClass('btn-success').html('<i class="fas fa-check fa-fw me-1"></i> ส่งงานแล้ว');
                                var delayclose = setInterval(() => {
                                    $('#assignmentModal').modal('hide');
                                    clearInterval(delayclose);
                                }, 1300);
                                var delayReload = setInterval(() => {
                                    $('#myassignments').load("/be/getassignments?class_id=" + $("#select_class").val());
                                    clearInterval(delayReload);
                                }, 1700);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'มีบางอย่างผิดปกติโปรดลองใหม่อีกครั้ง'
                                })
                                $(triggers).attr('disabled', false).text('ส่งงาน')
                            }
                        },
                        "json"
                    );
                }
            },
            "json"
        );
    });
</script>