<?php
include("components/header.php");

$noOfStudents = $query->countStudent();
$noOfItems = $query->countInventory();

?>


<div class="d-flex justify-content-between align-items-center">
    <h4 class="text-primary">Dashboard</h4>
</div>

<div class="d-flex">
    <div class="card p-3 m-2 w-50" style="border-left: 5px solid #007bff;">
        <h6 class="m-0">Total number of students</h6>
        <hr>
        <div class="text-secondary">
            <span style="font-size: 50px">
                <i class="bi bi-person-badge"></i>
            </span>
            <span class="ms-5 " style="font-size: 50px">
                <?= $noOfStudents ?>
            </span>
        </div>
    </div>

    <div class="card p-3 m-2 w-50" style="border-left: 5px solid #007bff;">
        <h6 class="m-0">Total number of items</h6>
        <hr>
        <div class="text-secondary">
            <span style="font-size: 50px">
                <i class="bi bi-hourglass"></i>
            </span>
            <span class="ms-5 " style="font-size: 50px">
                <?= $noOfItems ?>
            </span>
        </div>
    </div>
</div>

<canvas id="biChart" width="400" height="200">

</canvas>

<?php include("components/footer.php") ?>
<script src="js/Dashboard.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>