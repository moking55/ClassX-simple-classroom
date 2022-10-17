<form id="createClass" method="post">
    <div class="container mt-5" style="max-width: 900px">
        <input type="file" accept="image/*" id="class_img_cover" class="d-none">
        <a href="javascript: $('#class_img_cover').click()">
            <img id="classCoverPreview" src="<?= (!empty($classInfo->class_img_cover)) ? $classInfo->class_img_cover : '/img/classroom-cover.jpg' ?>" class="card-img" style="height: 240px;object-fit:cover;object-position: center;" alt="Class cover" />
        </a>
        <h3 class="my-3">แก้ไขชั้นเรียน</h3>

        <form id="editClassForm">
            <div class="mb-2">
                <div class="form-outline">
                    <input type="text" id="class_name" value="<?= $classInfo->class_name ?>" class="form-control" />
                    <label class="form-label" for="class_name">ชื่อวิชา</label>
                </div>
            </div>
            <div class="mb-2">
                <div class="form-outline">
                    <textarea class="form-control" id="class_desc" name="class_desc" rows="4"><?= $classInfo->class_description ?></textarea>
                    <label class="form-label" for="class_desc">คำอธิบายรายวิชา</label>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="mb-2">
                        <div class="form-outline">
                            <input type="text" id="class_code" value="<?= $classInfo->class_code ?>" class="form-control" />
                            <label class="form-label" for="class_code">รหัสวิชา (ถ้ามี)</label>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <select class="select" data-mdb-filter="true" id="university">
                        <option value="<?= $classInfo->university_id ?>" selected hidden><?= $classInfo->university ?></option>
                        <?php foreach ($university as $uniName) : ?>
                            <option value="<?= $uniName->id ?>"><?= $uniName->university ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" id="saveClassBtn" class="btn btn-primary btn-rounded">บันทึกการแก้ไข</button>
                <button type="button" id="removeClassBtn" class="btn btn-danger btn-rounded">ลบห้องเรียน</button>
            </div>
        </form>
    </div>
</form>

<script>
    $('#saveClassBtn').on('click', function(e) {
        e.preventDefault();
        const form_data = {
            class_name: $('#class_name').val(),
            class_description: $('#class_desc').val(),
            class_code: $('#class_code').val(),
            university_id: $('#university').val()
        }
        $.post("/be/saveeditclass?class_id=<?= $classInfo->class_id ?>", form_data,
            function(data, textStatus, jqXHR) {
                if (data['status'] == true) {
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกข้อมูลแล้ว'
                    }).then(function() {
                        location.reload()
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'มีบางอย่างผิดปกติ'
                    });
                }
            },
            "json"
        );
    });
    $('#class_img_cover').on('change', function() {
        let fd = new FormData();
        const file = $('#class_img_cover')[0].files[0];
        fd.append('class_img_cover', file);
        $('#classCoverPreview').css('opacity', '0.5');
        $.ajax({
            url: '/be/upload_cover',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#classCoverPreview').attr('src', response['filePath']);
                const coverURL = {
                    class_img_cover: response['filePath']
                };
                $.post("/be/updatecover?class_id=<?= $classInfo->class_id ?>", coverURL,
                    function(data, textStatus, jqXHR) {
                        console.log(coverURL);
                        console.log(data);
                        if (data['status'] != true) {
                            Swal.fire({
                                'icon': 'error',
                                'title': 'มีบางอย่างผิดปกติโปรดลองอีกครั้ง'
                            })
                        }
                        $('#classCoverPreview').css('opacity', '1');
                    },
                    "json"
                );

            },
        });
    });
    $('#removeClassBtn').on('click', function() {
        Swal.fire({
            title: 'แน่ใจหรือไม่',
            text: 'หากคุณลบห้องเรียนนี้ไฟล์ทั้งหมดรวมถึงข้อมูลบันทึกต่างๆจะหายไป',
            showCancelButton: true,
            confirmButtonText: 'ใช้ฉันแน่ใจ',
            cancelButtonText: 'ยกเลิก',
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.get("/be/removeClass?class_id=<?= $classInfo->class_id ?>",
                    function(data, textStatus, jqXHR) {
                        if (data['status'] == true) {
                            Swal.fire({
                                title: 'success',
                                title: 'ลบชั้นเรียนแล้ว'
                            }).then(function() {
                                location.href = '/';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'มีบางอย่างผิดปกติ'
                            });
                        }
                    },
                    "json"
                );
            }
        });
    });
</script>