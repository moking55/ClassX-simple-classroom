<link rel="stylesheet" href="/css/jquery.gridstrap.min.css">
<div class="container p-4">
    <div class="row g-0 gap-2 p-3 border rounded" style="background: url('/img/profile-card-bg.jpg');background-size: cover;">
        <div class="col-md-4 col-lg-3 text-center"><img src="<?= (empty($userInfo->profile_img)) ? '/img/placeholder-avatar.jpg' : $userInfo->profile_img ?>" class="img-user-profile rounded-circle" alt="user profile image"></div>
        <div class="col-md-5 my-auto px-3 position-relative">
            <small style="position: absolute;top: -18px;color: #FF7700"><?= $userInfo->university ?></small>
            <h4><?= $userInfo->name ?> (<?= $userInfo->nickname ?>) <a class="text-warning" href=""><i class="fas fa-pen fa-sm"></i></a></h4>
            <p class="m-0">ชั้นเรียนทั้งหมด <?= $userInfo->joined_class ?></p>
            <p class="m-0">ชั้นเรียนที่สร้าง <?= $userInfo->own_class ?></p>
        </div>
    </div>
    <div class="w-100 position-relatived">
        <div class="dropdown">
            <button type="button" id="action-btn" data-mdb-toggle="dropdown" aria-expanded="true" class="btn btn-lg btn-primary btn-actions btn-rounded" data-mdb-ripple-color="dark"><i class="fas fa-plus fa-sm"></i>&nbsp;&nbsp; สร้างใหม่</button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="javascript: void(0)" data-mdb-toggle="modal" data-mdb-target="#joinModal">เข้าร่วมวิชาเรียน</a></li>
                <li><a class="dropdown-item" href="javascript: void(0)" data-mdb-toggle="modal" data-mdb-target="#createModal">สร้างวิชาใหม่</a></li>
            </ul>
        </div>
    </div>
    <h4 class="mt-5">ลงทะเบียนแล้ว</h4>
    <!-- <div class="d-flex justify-content-center" style="height: 200px">
        <div class="my-auto" id="loadClass">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div> -->
    <section id="joinedClassRoom">
        <?php if (empty($joinedClass)) : ?>
            <div class="d-flex justify-content-center">
                <div class="my-auto text-center">
                    <img src="/img/sleeping.png" style="width: 100px" class="img-responsive mb-3" alt="nap">
                    <h5 class="text-muted">ยังไม่มีวิชาที่ลงทะเบียน</h5>
                </div>
            </div>
        <?php else : ?>
            <div class="row" id="course-list">
                <?php foreach ($joinedClass as $class) : ?>
                    <div class="col-md-4 p-2 course-grid">
                        <div class="card shadow-0 border h-100">
                            <img class="card-img-top course-cover" src="<?= (empty($class->class_img_cover)) ? '/img/classroom-cover.jpg' : $class->class_img_cover ?>" alt="Course Cover">
                            <div class="card-img-overlay" style="background: rgba(0,0,0,0.3)">
                                <a href="/c/<?= $class->class_id ?>" class="text-light">
                                    <h5 class="card-title" style="line-height: 10px"><?= $class->class_name ?></h5>
                                    <small class="card-text d-block"><?= $class->class_code ?></small>
                                    <small class="card-text d-block"><?= $class->teacher_name ?></small>
                                </a>
                            </div>
                            <img src="<?= (empty($class->profile_img)) ? '/img/placeholder-avatar.jpg' : $class->profile_img ?>" class="rounded-circle course-owner-img" style="z-index: 1;" alt="">
                            <div class="card-body p-4 position-relative" style="background: white">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif ?>
    </section>
    <h4 class="mt-5">วิชาที่ฉันสอน</h4>
    <section id="">
        <?php if (empty($ownedClass)) : ?>
            <div class="d-flex justify-content-center">
                <div class="my-auto text-center">
                    <img src="/img/sleeping.png" style="width: 100px" class="img-responsive mb-3" alt="nap">
                    <h5 class="text-muted">ยังไม่มีวิชาที่ฉันสอน</h5>
                </div>
            </div>
        <?php else : ?>
            <div class="row" id="ownedClassRoom">
                <?php foreach ($ownedClass as $class) : ?>
                    <div class="col-md-4 p-2 course-grid">
                        <div class="card shadow-0 border h-100">
                            <img class="card-img-top course-cover" src="<?= (empty($class->class_img_cover)) ? '/img/classroom-cover.jpg' : $class->class_img_cover ?>" alt="Course Cover">
                            <div class="card-img-overlay" style="background: rgba(0,0,0,0.3)">
                                <a href="/c/<?= $class->class_id ?>" class=" text-light">
                                    <h5 class="card-title" style="line-height: 10px"><?= $class->class_name ?></h5>
                                    <small class="card-text d-block"><?= $class->class_code ?></small>
                                    <small class="card-text d-block"><?= $class->class_owner_name ?></small>
                                </a>
                            </div>
                            <img src="<?= (empty($class->class_owner)) ? '/img/placeholder-avatar.jpg' : $class->class_owner ?>" class="rounded-circle course-owner-img" style="z-index: 1;" alt="">
                            <div class="card-body p-4 position-relative" style="background: white">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif ?>
    </section>
</div>


<!-- Modal -->
<div class="modal top fade" id="joinModal" tabindex="-1" aria-labelledby="joinModal" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
    <div class="modal-dialog modal-sm  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">เข้าร่วมวิชาเรียน</h5>
            </div>
            <form id="joinClass" method="post">
                <div class="modal-body">
                    <div class="form-outline">
                        <input type="text" id="class_invite" class="form-control" required />
                        <label class="form-label" for="class_invite">รหัสเข้าร่วมห้องเรียน</label>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn" data-mdb-dismiss="modal">
                        ยกเลิก
                    </button>
                    <button type="submit" class="btn btn-primary" id="joinBtn">เข้าร่วม</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal top fade" id="createModal" tabindex="-1" aria-labelledby="createModal" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="false">
    <div class="modal-dialog modal-fullscreen ">
        <div class="modal-content">
            <div class="modal-header border-0 px-4 py-4">
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createClass" method="post">
                    <div class="container mt-5" style="max-width: 900px">
                        <h3 class="mb-3">สร้างวิชาใหม่</h3>
                        <div class="mb-2">
                            <div class="form-outline">
                                <input type="text" id="class_name" class="form-control" />
                                <label class="form-label" for="class_name">ชื่อวิชา</label>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="form-outline">
                                <textarea class="form-control" id="class_desc" name="class_desc" rows="4"></textarea>
                                <label class="form-label" for="class_desc">คำอธิบายรายวิชา</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-2">
                                    <div class="form-outline">
                                        <input type="text" id="class_code" class="form-control" />
                                        <label class="form-label" for="class_code">รหัสวิชา (ถ้ามี)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <select class="select" data-mdb-filter="true" data-mdb-container="#createModal" id="university">
                                    <option value="0" selected hidden>มหาวิทยาลัย</option>
                                    <?php foreach ($university as $uniName) : ?>
                                        <option value="<?= $uniName->id ?>"><?= $uniName->university ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" id="createClassBtn" class="btn btn-primary btn-rounded">สร้างวิชา</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="/js/jquery.gridstrap.min.js"></script>
<script>
    <?php
    $useragent = $_SERVER['HTTP_USER_AGENT'];

    if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) : ?>
        $('#course-list').gridstrap();
        $('#ownedClassRoom').gridstrap();
    <?php endif ?>
    $('#createClass').on('submit', function(e) {
        e.preventDefault();
        $('#createClassBtn').text('กำลังสร้างวิชา...').attr('disabled', true);

        const form_data = {
            university_id: $('#university').val(),
            class_name: $('#class_name').val(),
            class_description: $('#class_desc').val(),
            class_code: $('#class_code').val()
        }

        $.post("/be/createclass", form_data,
            function(data, textStatus, jqXHR) {
                if (data['status'] == true) {
                    location.href = '/c/' + data['lastInsertID'];
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาดบางอย่าง',
                        text: 'เรากำลังวินิฉัยปัญหาโปรดลองอีกครั้ง'
                    })
                    $('#createClassBtn').text('สร้างวิชา').attr('disabled', false);
                }
            },
            "json"
        );
    });

    $('#joinClass').on('submit', function(e) {
        $('#joinBtn').attr('disabled', true).text('กำลังประมวลผล...');
        e.preventDefault();
        const form_data = {
            class_invite: $('#class_invite').val()
        }
        $.post("/be/joinclass", form_data,
            function(data, textStatus, jqXHR) {
                console.log(data);
                if (data['status'] === true && data['message'] == 'USER_CAN_JOIN') {
                    location.href = '/c/' + data['classID'];
                } else if (data['message'] === 'USER_CANNOT_JOIN') {
                    Swal.fire({
                        icon: 'error',
                        title: 'เข้าร่วมไม่ได้',
                        text: 'ไม่สามารถเข้าร่วมชั้นเรียนที่เข้าร่วมแล้วหรือเป็นเจ้าของ'
                    });
                    $('#joinBtn').attr('disabled', false).text('เข้าร่วม');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'ไม่มีห้องเรียน',
                        text: 'โปรดติดต่อผู้สอนเพื่อรับรหัสเข้าร่วมใหม่'
                    });
                    $('#joinBtn').attr('disabled', false).text('เข้าร่วม');
                }
            },
            "json"
        );
    })
</script>