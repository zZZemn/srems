<?php

include("components/header.php");

if (isset($_GET['sId'])) {
    $sId = $_GET['sId'];

    $getStudent = $query->getById('students', $sId);

    if ($getStudent->num_rows > 0) {
        $student = $getStudent->fetch_assoc();

        $getTransactionD = $query->getTransactionDetailsUsingStudentId($sId);
    } else {
        header("Location: Students.php");
        exit;
    }
} else {
    header("Location: Students.php");
    exit;
}
?>
<div class="d-flex justify-content-between align-items-center">
    <h4 class="text-primary">Student Details</h4>
    <div>
        <a href="Students.php" class="btn btn-sm btn-dark">Back to List</a>
    </div>
</div>

<div class="container p-3" id="">

    <div class="container card mt-2 p-3">
        <h6><i class="bi bi-person-vcard"></i> Student Details:</h6>
        <ul class="list-group">
            <li class="list-group-item">Name: <span id="sdName"><?= $student['NAME'] ?></span></li>
            <li class="list-group-item">Email: <span id="sdEmail"><?= $student['EMAIL'] ?></span></li>
            <li class="list-group-item">Contact no: <span id="sdContactNo"><?= $student['CONTACT_NO'] ?></span></li>
        </ul>
    </div>

    <div class="container card mt-2 p-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6><i class="bi bi-hourglass"></i> Barrowed Items:</h6>
        </div>
        <ul class="list-group mt-2">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Qty</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="transaction-item-tbody">
                    <?php
                    while ($td = $getTransactionD->fetch_assoc()) {
                        $getInventory = $query->getById('inventory', $td['INV_ID']);
                        $inv = $getInventory->fetch_assoc();
                    ?>
                        <tr>
                            <td><?= $td['tId'] ?></td>
                            <td><?= $inv['ITEM_NAME'] ?></td>
                            <td><?= $td['QTY'] ?></td>
                            <td><?= $td['DATE'] ?> </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </ul>
    </div>
</div>


<?php include("components/footer.php") ?>
<!-- <script src="js/StudentDetails.js"></script> -->
</body>

</html>