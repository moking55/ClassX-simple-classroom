<link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<div class="container p-4">
    <div class="card bg-dark text-white">
        <img src="/img/classroom-cover.jpg" class="card-img" style="height: 240px;object-fit:cover;object-position: center;" alt="Class cover" />
        <div class="card-img-overlay d-flex flex-row justify-content-between">
            <div class="mt-auto rounded px-3 py-1" style="background: rgba(38,38,38,0.5);">
                <h3 class="card-title m-0"><?= $classInfo->class_name ?></h3>
                <p class="card-text m-0"><?= $classInfo->class_code ?></p>
            </div>
            <a href="#" class="text-muted"><i class="fas fa-cog fa-lg"></i></a>
        </div>
    </div>
    <section>
        <!-- Tabs navs -->
        <ul class="nav nav-tabs my-3 nav-fill" id="ex-with-icons" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="ex-with-icons-tab-1" data-mdb-toggle="tab" href="#postsTab" role="tab" aria-controls="postsTab" aria-selected="true"><i class="fas fa-stream fa-fw me-2"></i>กิจกรรมล่าสุด</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="ex-with-icons-tab-2" data-mdb-toggle="tab" href="#assignments" role="tab" aria-controls="assignments" aria-selected="false"><i class="fas fa-briefcase fa-fw me-2"></i>งานที่มอบหมาย</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="ex-with-icons-tab-3" data-mdb-toggle="tab" href="#documents" role="tab" aria-controls="documents" aria-selected="false"><i class="fas fa-paperclip fa-fw me-2"></i>สื่อการสอน</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="ex-with-icons-tab-3" data-mdb-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false"><i class="fas fa-folder fa-fw me-2"></i>ไฟล์ทั้งหมด</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="ex-with-icons-tab-3" data-mdb-toggle="tab" href="#attendance" role="tab" aria-controls="attendance" aria-selected="false"><i class="fas fa-users fa-fw me-2"></i>บุคคล</a>
            </li>
        </ul>
        <!-- Tabs navs -->

        <!-- Tabs content -->
        <div class="tab-content" id="classStreams">
            <div class="tab-pane fade show active" id="postsTab" role="tabpanel" aria-labelledby="ex-with-icons-tab-1">
                <section>
                    <div class="row gy-2">
                        <div class="col-md-3">
                            <div class="border rounded p-3">
                                <p class="my-1">รหัสเข้าร่วมชั้นเรียน</p>
                                <h4 class="text-primary"><?= $classInfo->class_invite ?></h4>
                                <div class="d-flex justify-content-end">
                                    <a href="javascript: Swal.fire({html:`<h1 style='font-size: 80pt'><?= $classInfo->class_invite ?></h1>`,showConfirmButton:false})"><i class="fas fa-expand"></i></a>
                                </div>
                            </div>
                            <?php foreach ($meeting as $meet): ?>
                                <div class="border rounded p-3 bg-primary text-light mt-2">
                                <p class="my-1">การประชุมในห้องเรียน</p>
                                <h4><?= $meet->meet_name ?></h4>
                                <div class="d-flex justify-content-end">
                                    <a class="text-light" href="/meeting?meetID=<?= $meet->meet_invite ?>"><small>เข้าร่วม <i class="fas fa-arrow-right fa-sm"></i></small></a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="col-md-9">
                            <?php if (session()->get('id') == $classInfo->class_owner) : ?>
                                <div id="preCreatePost">
                                    <div class="rounded border d-flex justify-content-between">
                                        <small class=" p-3"><a class="text-muted" href="javascript: $('#preCreatePost').hide();$('#createPost').show();"><i class="fas fa-bullhorn fa-fw me-1"></i> ประกาศบางสิ่งในชั้นเรียน</a></small>
                                        <small class="border-start p-3"><a href="javascript: void(0)" data-mdb-target="#createMeetRoom" data-mdb-toggle="modal" class="text-primary"><i class="fas fa-chalkboard-teacher fa-fw me-1"></i></i> เริ่มการประชุมใหม่</a></small>
                                    </div>
                                </div>

                                <form id="createPost" class="mb-3">
                                    <div class="form-outline mb-3">
                                        <input type="text" id="title" name="title" class="form-control" required />
                                        <label class="form-label" for="title">หัวข้อประกาศ</label>
                                    </div>
                                    <textarea name="postactivity" id="postactivity" cols="10" rows="5" style="max-height:200px"></textarea>
                                    <div class="p-1">
                                        <button class="btn btn-primary" type="submit">โพสต์</button>
                                        <button class="btn" onclick="$('#createPost').hide();$('#preCreatePost').show();" type="button">ยกเลิก</button>
                                    </div>
                                </form>
                            <?php endif ?>
                            <div class="text-center py-5" id="annouceLoad">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div id="annouce">

                            </div>
                        </div>
                    </div>
                </section>

                <!-- CreateMeeting -->
                <div class="modal fade" id="createMeetRoom" tabindex="-1" aria-labelledby="CreateMeeting" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title" id="CreateMeeting">สร้างการประชุมใหม่</h5>
                                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form id="createMeetForm">
                                <div class="modal-body">
                                    <div class="form-outline">
                                        <input type="text" id="meet_name" class="form-control" required />
                                        <label class="form-label" for="meet_name">หัวข้อการประชุม</label>
                                    </div>
                                    <div class="text-center">
                                        <button class="mt-2 btn btn-success" type="submit">เริ่มการประชุม</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="assignments" role="tabpanel" aria-labelledby="ex-with-icons-tab-2">
                <?php if (session()->get('id') == $classInfo->class_owner) : ?>
                    <section>
                        <div class="d-flex justify-content-between my-5">
                            <h4>งานที่ฉันมอบหมาย</h4>
                            <div class="dropdown">
                                <button class="btn btn-success btn-rounded" type="button" id="assignmentType" data-mdb-toggle="dropdown" aria-expanded="false"><i class="fas fa-plus fa-fw me-1"></i> สร้าง</button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="assignmentType">
                                    <li><a class="dropdown-item" href="/c/<?= $class_id ?>/assign?type=1"><i class="far fa-sticky-note me-2"></i>งาน</a></li>
                                    <li><a class="dropdown-item disabled" href="/c/<?= $class_id ?>/assign?type=2"><i class="far fa-file-alt me-2"></i>งานแบบทดสอบ</a></li>
                                    <li><a class="dropdown-item disabled" href="/c/<?= $class_id ?>/assign?type=3"><i class="far fa-question-circle me-2"></i>คำถาม</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="datatable">
                            <table class="table table-striped table-sm table-responsive">
                                <thead>
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>ชื่องาน</th>
                                        <th>ส่งแล้ว</th>
                                        <th>ยังไม่ได้ส่ง</th>
                                        <th>คะแนน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><a href="">awd</a></td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>10</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>
                <?php else : ?>
                    <section>
                        <h5 class="mt-3">ยังไม่ได้ส่ง</h5>
                        <ul class="list-group list-group-light my-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="/img/school-bag.png" class="rounded-circle" alt="" style="width: 45px; height: 45px" />
                                    <div class="ms-3">
                                        <a class="fw-bold my-auto" data-mdb-toggle="modal" style="cursor: pointer" data-mdb-target="#assignmentModal">Kate sadas</a>
                                    </div>
                                </div>
                                <span class="badge rounded-pill badge-danger"><i class="fas fa-times fa-fw me-1"></i>เลยกำหนด</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="/img/school-bag.png" class="rounded-circle" alt="" style="width: 45px; height: 45px" />
                                    <div class="ms-3">
                                        <p class="fw-bold mb-1">Kate Hunington</p>
                                    </div>
                                </div>
                                <span class="badge rounded-pill badge-warning"><i class="fas fa-clock fa-fw me-1"></i>กำหนดส่ง 9 / 10 / 2022</span>
                            </li>
                        </ul>
                        <h5>ส่งแล้ว</h5>
                        <ul class="list-group list-group-light my-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="/img/school-bag.png" class="rounded-circle" alt="" style="width: 45px; height: 45px" />
                                    <div class="ms-3">
                                        <p class="fw-bold mb-1">Kate Hunington</p>
                                    </div>
                                </div>
                                <span class="badge rounded-pill badge-success"><i class="fas fa-check fa-fw me-1"></i>ส่งแล้ว</span>
                            </li>
                        </ul>
                    </section>
                <?php endif; ?>

            </div>
            <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="ex-with-icons-tab-3">
                <h5>เนื้อหาภายในบทเรียน</h5>
                <ul class="list-group list-group-light my-3">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="/img/school-bag.png" class="rounded-circle" alt="" style="width: 45px; height: 45px" />
                            <div class="ms-3">
                                <p class="fw-bold mb-1">Kate Hunington</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right"></i></a>
                    </li>
                </ul>

            </div>
            <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="ex-with-icons-tab-3">
                <h5>ไฟล์ทั้งหมด</h5>
                <div class="datatable">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th>ชื่อ</th>
                                <th>ขนาด</th>
                                <th>วันที่สร้าง</th>
                                <th>ตัวเลือก</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>test</td>
                                <td>1 KB</td>
                                <td>s</td>
                                <td>s</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="attendance" role="tabpanel" aria-labelledby="ex-with-icons-tab-4">
                <h6 class="bg-light p-2 border-top border-bottom">ครู</h6>

                <ul class="list-group list-group-light mb-4">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="https://mdbootstrap.com/img/new/avatars/8.jpg" alt="" style="width: 45px; height: 45px" class="rounded-circle" />
                            <div class="ms-3">
                                <p class="fw-bold mb-1"><?= $classInfo->owner_name ?></p>
                            </div>
                        </div>
                    </li>
                </ul>

                <h6 class="bg-light p-2 border-top border-bottom">นักเรียน</h6>

                <ul class="list-group list-group-light">
                    <?php foreach ($classMembers as $classMember) : ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="https://mdbootstrap.com/img/new/avatars/9.jpg" alt="" style="width: 45px; height: 45px" class="rounded-circle" />
                                <div class="ms-3">
                                    <p class="fw-bold mb-1"><?= $classMember->name ?> (<?= $classMember->nickname ?>)</p>
                                    <?php if (!empty($classMember->std_code)) : ?>
                                        <p class="text-muted mb-0"><?= $classMember->std_code ?></p>
                                    <?php endif ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>

            </div>
        </div>
        <!-- Tabs content -->
    </section>
</div>

<!-- Modal -->
<div class="modal top fade" id="assignmentModal" tabindex="-1" aria-labelledby="assignmentModal" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="false">
    <div class="modal-dialog modal-fullscreen ">
        <div class="modal-content">
            <div class="modal-header border-0 px-4 py-4">
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mt-4">
                    <div class="row gy-3">
                        <div class="col-md-6 d-flex order-md-1 justify-content-between">
                            <div>
                                <h5 class="mb-1">คะแนน</h5>
                                <small>10 คะแนนที่เป็นไปได้</small>
                            </div>
                            <div><button class="btn btn-primary d-inline js-confetti" style="z-index: 10000" type="button">ส่งงาน</button></div>
                        </div>
                        <div class="col-md-6 order-md-0">
                            <h3 class="mb-1">การบ้าน 2</h3>
                            <small>กำหนดส่ง asd</small>
                            <br><br>
                            <h6 class="mb-1">คำแนะนำ</h6>
                            <small><i>ไม่มีคำแนะนำ</i></small>
                            <br><br>
                            <h6 class="mb-1">ไฟล์แนบ</h6>
                            <small><i>ไม่มีไฟล์</i></small>
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
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.25.2/trumbowyg.min.js" integrity="sha512-mBsoM2hTemSjQ1ETLDLBYvw6WP9QV8giiD33UeL2Fzk/baq/AibWjI75B36emDB6Td6AAHlysP4S/XbMdN+kSA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
<script>
    $(document).ready(function() {
        $('#createPost').hide();
        $('#postactivity').trumbowyg();
        const delayShow = setInterval(function() {
            $('#annouce').load("/be/getannounces?class_id=<?= $class_id ?>").show();
            $('#annouceLoad').hide();
            clearInterval(delayShow);
        }, 1400);
    });

    /* Create Class Posts */
    $("#createPost").on('submit', function(e) {
        e.preventDefault();
        const form_data = {
            title: $("#title").val(),
            content: $("#postactivity").val(),
            class_id: <?= $class_id ?>
        };
        $.post("/be/createannounces", form_data,
            function(data, textStatus, jqXHR) {
                if (data['status'] === true) {
                    $('#preCreatePost, #annouceLoad').show();
                    $('#createPost, #annouce').hide();
                    const delayShow = setInterval(function() {
                        $('#annouce').load("/be/getannounces?class_id=<?= $class_id ?>").show();
                        $('#annouceLoad').hide();
                        clearInterval(delayShow);
                    }, 1400);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'มีบางอย่างผิดปกติ',
                        text: 'โปรดลองใหม่อีกครั้ง'
                    });
                }
            },
            "json"
        );
    });

    /* Create Class Meeting */
    $("#createMeetForm").on('submit', function(e) {
        e.preventDefault();
        const form_data = {
            meet_name: $('#meet_name').val(),
            meet_invite: '<?= $classInfo->class_invite ?>',
            class_id: <?= $class_id ?>
        }
        $.post("/be/createmeet", form_data,
            function(data, textStatus, jqXHR) {
                if (data['status'] === true) {
                    return location.href = "/meeting?meetID=" + data['meetID'];
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'มีบางอย่างผิดปกติ',
                        text: 'โปรดลองใหม่อีกครั้ง'
                    });
                }
            },
            "json"
        );
    });
</script>
<script src="/js/classroom.js"></script>