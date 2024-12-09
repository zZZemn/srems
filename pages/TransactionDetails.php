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

        $img = $transcation['IMG'];
        $remarks = $transcation['REMARKS'];

        $signature = $transcation['SIGNATURE'];


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
                        <th>Qty</th>
                        <th>Damage Qty</th>
                    </tr>
                </thead>
                <tbody id="transaction-item-tbody">
                    <?php
                    while ($td = $getTransctionD->fetch_assoc()) {
                        $getInventory = $query->getById('inventory', $td['INV_ID']);
                        $inv = $getInventory->fetch_assoc();

                        $replacedItemQty = 0;
                        $getReplacedItemQty = $query->getReplacedItemQty($td['ID']);
                        if ($getReplacedItemQty->num_rows > 0) {
                            $ReplacedItemQty = $getReplacedItemQty->fetch_assoc();

                            $replacedItemQty += $ReplacedItemQty['replaced_qty'];
                        }

                    ?>
                        <tr>
                            <td><?= $td['ID'] ?></td>
                            <td><?= $inv['ITEM_NAME'] ?></td>
                            <td><?= $td['QTY'] ?></td>
                            <td class="d-flex">
                                <input
                                    class="form-control input-damage-qty"
                                    data-id="<?= $td['ID'] ?>"
                                    data-itemname="<?= $inv['ITEM_NAME'] ?>"
                                    data-curqty="<?= $td['QTY'] ?>"
                                    style="width: 100px;"
                                    type="number"
                                    max="<?= $td['QTY'] ?>"
                                    min="0"
                                    value="<?= $td['DAMAGED_QTY'] ?>"
                                    <?php
                                    echo ($status == "RETURNED") ? 'disabled' : ''
                                    ?> />
                                <?php
                                if ($status == "RETURNED" && $td['DAMAGED_QTY'] > 0 && $td['DAMAGED_QTY'] > $replacedItemQty) {
                                ?>
                                    <button class="btn btn-sm btn-primary ms-2 btn-replace"
                                        data-tdid="<?= $td['ID'] ?>"
                                        data-dmgqty="<?= $td['DAMAGED_QTY'] ?>"
                                        data-replacedqty="<?= $replacedItemQty ?>">Replace</button>
                                <?php
                                }

                                if ($replacedItemQty > 0) {
                                ?>
                                    <span class="text-success ms-2" style="font-size: 12px;"><?= $replacedItemQty ?> Replaced Item/s</span>
                                <?php
                                }
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </ul>
    </div>

    <?php
    if ($status == "RETURNED") {
    ?>
        <div class="container card mt-2 p-3">
            <div class="d-flex justify-content-between">
                <h6><i class="bi bi-hourglass"></i> Return Details:</h6>
            </div>

            <?php
            if ($img != null) {
            ?>
                <div class="mt-2">
                    <span style="font-style: italic;">Image:</span>
                    <div>
                        <img src="../returned-item-photos/<?= $img ?>" alt="IMG">
                        <!-- <img src="../items-photos/default.jpg" alt="IMG"> -->
                    </div>
                </div>
            <?php
            }
            ?>

            <div class="mt-2">
                <span style="font-style: italic;">Remarks:</span>
                <div class="card p-2" style="font-size: 12px;">
                    <?= $remarks ?>
                </div>
            </div>
        </div>
    <?php
    }
    ?>


    <div class="container card mt-2 p-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6>Signature:</h6>
        </div>

        <img />
        <img id="SignatureImage" alt="Signature Image Preview" src="<?= $signature ?>" style="height: 300px; width: 300px;" />
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


<div class="modal fade" tabindex="-1" role="dialog" id="ModalReplaceItems">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Replace Items</h5>
            </div>
            <form id="formReplaceItems">
                <input type="hidden" name="REQUEST_TYPE" value="REPLACEITEMS">
                <input type="hidden" id="replaceTD_ID" name="td_id">
                <input type="hidden" id="replace_dmg_qty" name="dmg_qty">
                <div class="modal-body">
                    <div class="mt-2">
                        <label for="rtnRemarks">Quantity</label>
                        <input type="number" class="form-control mt-1" name="qty" id="replace_qty" placeholder="Quantity" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
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