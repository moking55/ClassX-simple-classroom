<div class="container mt-5">
    <form id="lession_form">
        <div class="row">
            <div class="col-md-9 mx-auto pt-3">
                <h3>เพิ่มบทเรียนใหม่</h3>
                <div class="form-outline mb-3">
                    <input type="text" id="less_title" name="less_title" class="form-control" required />
                    <label class="form-label" for="less_title">หัวข้อ</label>
                </div>
                <textarea id="less_content" name="less_content"></textarea>
                <div class="text-center mt-3">
                    <button class="btn btn-primary" type="submit">เพิ่มข้อมูล</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.tiny.cloud/1/1j1l8ym1ch9hinyojfpjkxiq596oi1iyrxin9kklsqrgk8v3/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });

    $('#lession_form').on('submit', function(e) {
        e.preventDefault();
        const form_data = {
            less_title: $('#less_title').val(),
            less_content: tinymce.get('less_content').getContent(),
            class_id: <?= $class_id ?>
        }
        $.post("/be/create_less", form_data,
            function(data, textStatus, jqXHR) {
                if (data['status'] == true) {
                    Swal.fire({
                        icon: 'success',
                        text: 'เพิ่มบทเรียนสำเร็จแล้ว'
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        text: 'มีบางอย่างผิดปกติโปรดลองอีกครั้ง'
                    });
                }
            },
            "json"
        );

    });
</script>