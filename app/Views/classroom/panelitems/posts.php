<?php if (!empty($streams)) : ?>
    <?php foreach ($streams as $stream) : ?>
        <div class="card shadow-0 border my-2">
            <div class="card-header d-flex">
                <div class="my-auto">
                    <img src="<?= (empty($stream->profile_img))? '/img/placeholder-avatar.jpg' : $stream->profile_img ?>" style="width: 42px;height: 42px; object-fit: cover" class="me-2 rounded-circle" alt="" />
                    <p class="text-black d-inline mb-0"><?= $stream->name ?></p> <span><small class="text-muted d-inline">เมื่อ <?= $stream->created_at ?></small></span>
                </div>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?= $stream->title ?></h5>
                <div class="card-content">
                <?= $stream->content ?>
                </div>
            </div>
        </div>
    <?php endforeach ?>
<?php else : ?>
    <div class="rounded border text-center mt-2 py-4">
        <h4 class="m-0">นี่คือส่วนที่คุณสามารถพูดคุยกับชั้นเรียนได้</h4>
        <small class="text-muted">เพื่อแชร์ประกาศ โพสต์งาน และตอบคำถามของนักเรียน!</small>
    </div>
<?php endif ?>