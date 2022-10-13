<div class="container mt-5">
    <h3 class="my-5"><i class="fas fa-file-import fa-lg me-2"></i> เพิ่มงานไปยังชั้นเรียน</h3>
    <form id="createAssignment">
        <div class="row">
            <div class="col-md-8 rounded border p-4" style="background: #EEEEEE;">
                <div class="form-outline my-2">
                    <input type="text" id="assignment_name" class="form-control" />
                    <label class="form-label" for="assignment_name">ชื่อ</label>
                </div>
                <div style="background: white" class="my-2">
                    <textarea id="instruction" rows="10"></textarea>
                </div>

                <input type="file" class="d-none" name="attach" id="attach">

                <div class="border rounded bg-white p-3">
                    <button class="btn btn-primary" onclick="javascript: $('#attach').click()" type="button"><i class="fas fa-file-upload fa-fw me-1"></i> เพิ่มไฟล์แนบ</button>
                </div>
            </div>
            <div class="col-md-4 px-4">
                <small class="mb-0">คะแนนของชิ้นงาน</small>
                <div class="form-outline my-2">
                    <input type="number" id="assignment_name" min="0" class="form-control" />
                    <label class="form-label" for="assignment_name">คะแนน</label>
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
                    <button class="btn btn-primary w-100" type="submit">มอบหมาย</button>
                </div>
            </div>
        </div>
    </form>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.25.2/trumbowyg.min.js" integrity="sha512-mBsoM2hTemSjQ1ETLDLBYvw6WP9QV8giiD33UeL2Fzk/baq/AibWjI75B36emDB6Td6AAHlysP4S/XbMdN+kSA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $('#createAssignment').on('submit', function(e) {
        e.preventDefault();
        $.post("/be/assignwork", data,
            function(data, textStatus, jqXHR) {
                console.log();
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
</script>