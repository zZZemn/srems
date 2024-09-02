<?php

include("components/header.php");
$getTransaction = $query->getAll('transaction');

?>
<div class="d-flex justify-content-between align-items-center">
    <h4 class="text-primary">Transaction</h4>
    <a href="TransactionAdd.php" class="btn btn-sm btn-primary" id="btnAddTransaction"><i class="bi bi-plus-lg"></i> Add</a>
</div>
<table class="table table-striped" style="font-size: 12px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Transaction Code</th>
            <th>Custodian</th>
            <th>Student</th>
            <th>Date</th>
            <th>Due Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($getTransaction->num_rows > 0) {
            while ($transaction = $getTransaction->fetch_assoc()) {
        ?>
                <tr>
                    <td><?= $transaction['ID'] ?></th>
                    <td><a href="TransactionDetails.php?tId=<?= $transaction['TRANSACTION_CODE'] ?>"><?= $transaction['TRANSACTION_CODE'] ?></a></th>
                    <td><?= $transaction['CUSTODIAN_ID'] ?></th>
                    <td><?= $transaction['STUDENT_ID'] ?></th>
                    <td><?= (new DateTime($transaction['DATE']))->format('F j, Y') ?></td>
                    <td><?= (new DateTime($transaction['DUEDATE']))->format('F j, Y') ?></td>
                    <td><?= $transaction['STATUS'] ?></th>
                </tr>
            <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="7" class="text-center">
                    No Data Found!
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<?php include("components/footer.php") ?>
</body>

</html>