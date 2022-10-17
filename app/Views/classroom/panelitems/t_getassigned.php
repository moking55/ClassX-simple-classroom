<div class="datatable"></div>
<table class="table table-striped table-sm table-responsive">
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>ชื่องาน</th>
                <th>สร้างเมื่อ</th>
                <th>ส่งแล้ว</th>
                <th>คะแนน</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($assignments as $key => $assigned) : ?>
                <tr>
                    <td><?= $key ?></td>
                    <td><?= $assigned->a_name ?></td>
                    <td><?= $assigned->created_at ?></td>
                    <td><?= $assigned->submitted ?></td>
                    <td><?= $assigned->a_score ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>