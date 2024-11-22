<?php

include("components/header.php");

if (isset($_GET['tId'])) {
    $tCode = $_GET['tId'];

    $getTransaction = $query->getTransactionUsingTransactionCode($tCode);

    if ($getTransaction->num_rows > 0) {
        $transcation = $getTransaction->fetch_assoc();

        $custodianId = $transcation['CUSTODIAN_ID'];
        $studId = $transcation['STUDENT_ID'];
        $dueDate = $transcation['DUEDATE'];
        $status = $transcation['STATUS'];
        $venue = $transcation['VENUE'];
        $teacher = $transcation['TEACHER'];


        $getStudent = $query->getById('students', $studId);
        $student = $getStudent->fetch_assoc();


        $getTransctionD = $query->getTransactionDetailsUsingTransactionCode($tCode);
    } else {
        header("Location: Transaction.php");
        exit;
    }
} else {
    header("Location: Transaction.php");
    exit;
}
?>
<div class="d-flex justify-content-between align-items-center">
    <h4 class="text-primary">Transaction Details</h4>
    <div>
        <a href="Transaction.php" class="btn btn-sm btn-dark">Back to List</a>
        <?php
        if ($status != "RETURNED") {
            echo '<button class="btn btn-sm btn-primary" id="btnReturnTransaction">Mark as Return</button>';
        }
        ?>
    </div>
</div>

<div class="container p-3" id="">
    <div class="container card p-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6><i class="bi bi-info-circle"></i> Transction Information:</h6>
        </div>

        <ul class="list-group">
            <li class="list-group-item">Date of Transaction: <span id="tdDOT"><?= $transcation['DATE'] ?></span></li>
            <li class="list-group-item">Due Date: <?= $dueDate ?></li>
            <li class="list-group-item">STATUS: <?= $status ?></li>
            <li class="list-group-item">VENUE: <?= $venue ?></li>
            <li class="list-group-item">TEACHER: <?= $teacher ?></li>
        </ul>

    </div>

    <div class="container card mt-2 p-3">
        <div class="d-flex justify-content-between">
            <h6><i class="bi bi-person-vcard"></i> Student Details:</h6>
            <img src="../student-photos/<?= $student['IMG'] ?>" alt="student photo" style="height: 60px; width: 60px; border: 1px solid gray; border-radius: 5%" class="mb-2 p-1">
        </div>
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
                        <th>Available Qty</th>
                        <th>Qty</th>
                    </tr>
                </thead>
                <tbody id="transaction-item-tbody">
                    <?php
                    while ($td = $getTransctionD->fetch_assoc()) {
                        $getInventory = $query->getById('inventory', $td['INV_ID']);
                        $inv = $getInventory->fetch_assoc();
                    ?>
                        <tr>
                            <td><?= $td['ID'] ?></td>
                            <td><?= $inv['ITEM_NAME'] ?></td>
                            <td><?= $inv['QTY'] ?></td>
                            <td><?= $td['QTY'] ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </ul>
    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="ModalReturnTransaction">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Return Items</h5>
            </div>
            <form id="formReturnTransaction">
                <input type="hidden" name="REQUEST_TYPE" value="RETURNTRANSCTION">
                <input type="hidden" name="id" id="frmInputTId" value="<?= $transcation['ID'] ?>">
                <div class="modal-body">
                    Are you sure that you want change this transaction status to returned?

                    <hr>

                    <div>
                        <label for="rtnItemImg">Upload a Picture</label>
                        <input type="file" class="form-control mt-1" name="rtnItemImg" id="rtnItemImg" accept="image/*" required>
                    </div>
                    <div class="mt-2">
                        <label for="rtnRemarks">Remarks</label>
                        <input type="text" class="form-control mt-1" name="rtnRemarks" id="rtnRemarks" placeholder="Input Remarks" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Yes</button>
                    <button type="reset" class="btn btn-secondary btnCloseModal" id="btnCloseModal" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<input type="hidden" id="txtHiddenTCode" value="<?= $tCode ?>">



<?php include("components/footer.php") ?>
<script src="js/TransactionDetails.js"></script>
</body>

</html>