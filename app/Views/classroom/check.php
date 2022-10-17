<div class="container mt-4">
    <table class="table">
        <thead>
            <tr>
                <td>ลำดับ</td>
                <td>ชื่อ</td>
                <td>รหัสนักศึกษา</td>
                <td>ส่งเมื่อ</td>
                <td>คะแนน</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($works as $index => $work) : ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $work->name ?>
                        <ul class="list-group list-group-light list-group-small">
                            <?php foreach ($files as $file) : ?>
                                <?php if (!empty($file->ua_owner)) : ?>
                                    <?php if ($file->ua_owner == $work->user_id) : ?>
                                        <li class="list-group-item"><a href="<?= $file->ua_path ?>" target="_blank"><?= $file->ua_name ?></a></li>
                                    <?php endif ?>
                                <?php endif ?>
                            <?php endforeach ?>
                        </ul>
                    </td>
                    <td><?= $work->std_code ?></td>
                    <td><?= $work->submitted_at ?></td>
                    <td>
                        <div class="input-group mb-3">
                            <input type="number" class="form-control" value="<?= $work->score ?>" id="score_of_<?= $index+1 ?>" placeholder="กรอกคะแนน" aria-label="score" aria-describedby="button-addon2" />
                            <button class="btn btn-primary" onclick="javascript: submitScore(<?= $work->user_id ?>, $('#score_of_<?= $index+1 ?>').val())" type="button" id="button-addon2" data-mdb-ripple-color="dark">
                                ให้คะแนน
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    function submitScore(userID, score) {
        const form_data = {
            user_id: userID,
            score: score,
            assignment_id: <?= $assignmentID ?>
        }
        $.post("/be/givescore", form_data,
            function(data, textStatus, jqXHR) {
                if (data['status'] == true) {
                    Swal.fire({
                        icon: 'success',
                        text: 'บันทึกคะแนนแล้ว',
                        backdrop: false,
                        timer: 2000,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        text: 'มีบางอย่างผิดปกติโปรดลองใหม่อีกครั้ง'
                    });
                }
            },
            "json"
        );
    }
</script>