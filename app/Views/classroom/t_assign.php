<div class="container mt-5">
    <h3 class="my-5"><i class="fas fa-file-import fa-lg me-2"></i> เพิ่มงานไปยังชั้นเรียน</h3>
    <form id="createAssignment">
        <div class="row">
            <div class="col-md-8 rounded border p-4" style="background: #EEEEEE;">
                <div class="form-outline my-2">
                    <input type="text" id="assignment_name" class="form-control" required />
                    <label class="form-label" for="assignment_name">ชื่อ</label>
                </div>
                <div style="background: white" class="my-2">
                    <textarea id="instruction" rows="10"></textarea>
                </div>

                <input type="file" class="d-none" name="attach" id="attach">

                <div class="border rounded bg-white p-3">
                    <button class="btn btn-primary" id="uploadbtn" onclick="javascript: $('#attach').click()" type="button"><i class="fas fa-file-upload fa-fw me-1"></i> เพิ่มไฟล์แนบ</button>
                    <ul id="attach_list" class="list-group mt-3 list-group-light list-group-small">

                    </ul>
                </div>

            </div>
            <div class="col-md-4 px-4">
                <small class="mb-0">คะแนนของชิ้นงาน</small>
                <div class="form-outline my-2">
                    <input type="number" id="a_score" min="0" class="form-control" required />
                    <label class="form-label" for="a_score">คะแนน</label>
                </div>
                <small class="mb-0">ครบกำหนด</small>
                <div class="form-outline datetimepicker my-2" data-mdb-disabled="false">
                    <input type="text" class="form-control" id="dueDate" />
                    <label for="dueDate" class="form-label">เลือกวันครบกำหนด</label>
                </div>

                <!-- Default checkbox -->
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="isNoDueDate" />
                    <label class="form-check-label" for="isNoDueDate">ไม่มีวันครบกำหนด</label>
                </div>
                <div class="mt-4">
                    <button class="btn btn-primary w-100" type="submit" id="assignbtn">มอบหมาย</button>
                </div>
            </div>
        </div>
    </form>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.25.2/trumbowyg.min.js" integrity="sha512-mBsoM2hTemSjQ1ETLDLBYvw6WP9QV8giiD33UeL2Fzk/baq/AibWjI75B36emDB6Td6AAHlysP4S/XbMdN+kSA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    let class_attachments = [];
    $('#createAssignment').on('submit', function(e) {
        e.preventDefault();
        const form_data = {
            a_name: $('#assignment_name').val(),
            a_instruction: $('#instruction').val(),
            a_score: $('#a_score').val(),
            a_classid: <?= $class_id ?>,
            due_date: ($('#dueDate').val() != null) ? $('#dueDate').val() : ''
        }
        $.post("/be/assignwork", form_data,
            function(response, textStatus, jqXHR) {
                if (response['status'] == true) {
                    const form_data = {
                        data: class_attachments,
                        assign_id: response['lastInsertID'],
                    }
                    $.post("/be/save_attach", form_data,
                        function(data, textStatus, jqXHR) {
                            if (data['status'] == true) {
                                Swal.fire({
                                    icon: 'success',
                                    text: 'มอบหมายงานสำเร็จ'
                                }).then(function() {
                                    location.href = '/c/<?= $class_id ?>'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    text: 'มีบางอย่างผิดปกติ โปรดลองอีกครั้ง'
                                })
                            }
                        },
                        "json"
                    );
                }
            },
            "json"
        );
    });
    $('#isNoDueDate').on('change', function() {
        if (this.checked) {
            $('#dueDate').val('01/01/9999, 00:00');
            $('.datetimepicker').hide();
        } else {
            $('#dueDate').val('').show();
            $('.datetimepicker').show();
        }
    })
    $('#instruction').trumbowyg({
        btns: [
            ['undo', 'redo'], // Only supported in Blink browsers
            ['formatting'],
            ['strong', 'em', 'del'],
            ['superscript', 'subscript'],
            ['link'],
            ['insertImage'],
            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
            ['unorderedList', 'orderedList'],
            ['horizontalRule'],
            ['fullscreen']
        ]
    });
    $('#attach').on('change', function() {
        let fd = new FormData();
        const file = $('#attach')[0].files[0];
        fd.append('attach', file);
        $('#uploadbtn').attr('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden">กำลังโหลดข้อมูล...</span>');
        $.ajax({
            url: '/be/upload_attachment',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
                if (response['status'] == true) {
                    $('#attach_list').append(`<li class="list-group-item"><a href="${response['filePath']}" target="_blank">${response['fileName']}</a></li>`);
                    class_attachments.push({
                        response
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        text: 'มีบางอย่างผิดปกติโปรดลองอีกครั้ง'
                    })
                }
                $('#uploadbtn').attr('disabled', false).html('<i class="fas fa-file-upload fa-fw me-1"></i> เพิ่มไฟล์แนบ');
            },
        });
    });
</script>