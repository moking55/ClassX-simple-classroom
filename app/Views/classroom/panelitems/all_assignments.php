<?php if (!empty($assignmentList)) : ?>
    <ul class="list-group list-group-light">
        <?php foreach ($assignmentList as $work) : ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="/img/school-bag.png" class="rounded-circle" alt="" style="width: 45px; height: 45px" />
                    <div class="ms-3 my-3">
                        <a class="fw-bold mb-0" href="javascript: showModal(<?= $work->a_id ?>)"><?= $work->a_name ?></a>
                    </div>
                </div>
                <span class="badge rounded-pill badge-success"> <?= ($work->due_date == "9999-01-01 00:00:00") ? 'ไม่มีกำหนดส่ง' : 'กำหนดส่ง ' . $work->due_date ?></span>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="modal top fade" id="assignmentModal" tabindex="-1" aria-labelledby="assignmentModal" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="false">
        <div class="modal-dialog modal-fullscreen ">
            <div class="modal-content">
                <div class="modal-header border-0 px-4 py-4">
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="assignmentInfo">

                </div>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="text-center mt-5">
        <img src="/img/chilling.png" style="height: 360px" alt="">
        <h3>ไม่มีงานที่ต้องส่งในตอนนี้!</h3>
    </div>
<?php endif ?>


<div class="modal top fade" id="assignmentModal" tabindex="-1" aria-labelledby="assignmentModal" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="false">
    <div class="modal-dialog modal-fullscreen ">
        <div class="modal-content">
            <div class="modal-header border-0 px-4 py-4">
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="assignmentInfo">
                
            </div>
        </div>
    </div>
</div>
<script>
    function showModal(a_id) {
        $('#assignmentInfo').load('/be/viewassignment?a_id='+a_id);
        $('#assignmentModal').modal('show');
    }
</script>