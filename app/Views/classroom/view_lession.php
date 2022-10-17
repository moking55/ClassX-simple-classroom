<div class="container pt-4">
    <div class="card bg-dark text-white">
        <img src="<?= (!empty($classInfo->class_img_cover)) ? $classInfo->class_img_cover : '/img/classroom-cover.jpg' ?>" class="card-img" style="height: 240px;object-fit:cover;object-position: center;" alt="Class cover" />
        <div class="card-img-overlay d-flex flex-row justify-content-between">
            <div class="mt-auto rounded px-3 py-1" style="background: rgba(38,38,38,0.5);">
                <h3 class="card-title m-0"><?= $classInfo->class_name ?></h3>
                <p class="card-text m-0"><?= $classInfo->class_code ?></p>
            </div>
            <?php if (session()->get('id') == $classInfo->class_owner) : ?>
                <a href="/c/<?= $classInfo->class_id ?>/settings" class="text-muted"><i class="fas fa-cog fa-lg"></i></a>
            <?php endif ?>
        </div>
    </div>
</div>