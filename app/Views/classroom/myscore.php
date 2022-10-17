<div class="container mt-4">
    <h4 class="py-5">รายงานคะแนนของฉัน</h4>
    <div class="datatable">
        <table>
            <thead>
                <tr>
                    <th class="th-sm">ชื่องาน</th>
                    <th class="th-sm">ชื่อห้องเรียน</th>
                    <th class="th-sm">ส่งเมื่อ</th>
                    <th class="th-sm">คะแนนที่ได้</th>
                    <th class="th-sm">คะแนนเต็ม</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($myscores as $mywork) : ?>
                    <tr>
                        <td><?= $mywork->a_name ?></td>
                        <td><?= $mywork->class_name ?></td>
                        <td><?= $mywork->submitted_at ?></td>
                        <td><?= (is_null($mywork->userscore))? 'รอการตรวจ' : $mywork->userscore ?></td>
                        <td><?= $mywork->score ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>