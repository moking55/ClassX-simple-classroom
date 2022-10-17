<div class="container">
    <div class="row mt-4">
        <div class="col-md-4">
            <select class="select" id="select_class" data-mdb-filter="true">
                <?php foreach ($classes as $class) : ?>
                    <option value="<?= $class->class_id ?>"><?= $class->class_name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-8"></div>
    </div>
</div>

<div class="container mt-4">
    <div id="myassignments"></div>
</div>

<script>
    $(document).ready(function() {
        if ($("#select_class").val() != '') {
            $('#myassignments').load("/be/getassignments?class_id=" + $("#select_class").val());
        } else {
            $('#myassignments').text("/be/getassignments");
        }
    });
    $('#select_class').on('change', function() {
        if ($("#select_class").val() != '') {
            $('#myassignments').load("/be/getassignments?class_id=" + $("#select_class").val());
        } else {
            $('#myassignments').text("/be/getassignments");
        }
    });
</script>