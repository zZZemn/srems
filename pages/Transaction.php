<?php

include("components/header.php");
$getTransaction = $query->getAll('transaction');

?>
<div class="d-flex justify-content-between align-items-center">
    <h4 class="text-primary">Transaction</h4>
    <a href="TransactionAdd.php" class="btn btn-sm btn-primary" id="btnAddTransaction"><i class="bi bi-plus-lg"></i> Add</a>
</div>
<div class="d-flex justify-content-end mt-2">
    <select name="status" id="selectStatus" class="form-control" style="width: 100px;">
        <option value="ALL">All</option>
        <option value="OVERDUE">Overdue</option>
        <option value="BARROWED">Barrowed</option>
        <option value="RETURNED">Returned</option>
    </select>
    <input type="search" class="form-control ms-1" id="inputSearch" placeholder="Search..." style="width: 300px">
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
    <tbody id="transactionTableBody">

    </tbody>
</table>

<button class="btn btn-sm btn-dark" id="btnSendEmail">Send Email For Overdue Transaction</button>

<?php include("components/footer.php") ?>
<script src="js/Transaction.js"></script>
</body>

</html>