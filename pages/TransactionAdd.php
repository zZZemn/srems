<?php

include("components/header.php");

$teachers = $query->getAll('teachers');

?>
<div class="d-flex justify-content-between align-items-center">
    <h4 class="text-primary">Add Transaction</h4>
    <!-- <a href="Transaction.php" class="btn btn-sm btn-primary" id="btnAddTransaction">View Transaction</a> -->
</div>

<form class="container card mt-2 p-3" id="frmTransactionAdd">
    <div class="input-design1">
        <label for="studentCode">Student Code:</label>
        <div class="d-flex">
            <input type="text" class="form-control" name="studentCode" id="studentCode" placeholder="Input / Scan Student Code" required>
            <button class="btn btn-dark ms-1" id="btnClearStudCode">Clear</button>
        </div>
    </div>
    <div class="container card mt-2 p-3">
        <h6><i class="bi bi-person-vcard"></i> Student Details:</h6>
        <ul class="list-group">
            <li class="list-group-item">Name: <span id="sdName"></span></li>
            <li class="list-group-item">Email: <span id="sdEmail"></span></li>
            <li class="list-group-item">Contact no: <span id="sdContactNo"></span></li>
        </ul>
    </div>

    <div class="container card mt-2 p-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6><i class="bi bi-hourglass"></i> Items to Barrow:</h6>
            <button type="button" class="btn btn-sm btn-primary" id="btnAddItem"><i class="bi bi-plus-lg"></i> Add Item</button>
        </div>
        <ul class="list-group mt-2">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Available Qty</th>
                        <th>Qty</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="transaction-item-tbody">

                </tbody>
            </table>
        </ul>
    </div>

    <div class="container card mt-2 p-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6><i class="bi bi-calendar-day"></i> Due Date:</h6>
        </div>

        <input type="date" class="form-control" required id="dueDate" name="dueDate" value="<?= date('Y-m-d', strtotime('+4 days')) ?>" readonly>

    </div>

    <div class="container card mt-2 p-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6>Teacher:</h6>
        </div>

        <select class="form-control" required id="teacher" name="teacher">
            <option value=""></option>
            <?php
            foreach ($teachers as $teacher) {
            ?>
                <option value="<?= $teacher['NAME'] ?>"><?= $teacher['NAME'] ?></option>
            <?php
            }
            ?>
        </select>

    </div>

    <div class="container card mt-2 p-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6>Venue:</h6>
        </div>

        <input type="text" class="form-control" required id="venue" name="venue">

    </div>

    <div class="mt-5 d-flex justify-content-end">
        <a href="Transaction.php" class="btn btn-sm btn-dark me-1">Back to list</a>
        <button type="submit" class="btn btn-sm btn-primary" id="BtnSaveTransaction">Save</button>
    </div>
</form>


<div class="modal fade" tabindex="-1" role="dialog" id="ModalTransactionAddItem">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus-fill"></i> Add Item</h5>
            </div>
            <form id="formTransactionAddItem">
                <input type="hidden" name="REQUEST_TYPE" value="ADDSTUDENT">
                <div class="modal-body">
                    <div>
                        <label for="AddItemSelectCategory">Category</label>
                        <select name="category" id="AddItemSelectCategory" class="form-control">
                            <option value="ALL">All</option>
                            <option value="Glass">Glass</option>
                            <option value="Plates">Plates</option>
                        </select>
                    </div>

                    <div class="mt-2">
                        <label for="AddItemItemName">Item</label>
                        <select name="category" id="AddItemItemNameSelect" class="form-control">
                            <option value=""></option>
                        </select>
                    </div>

                    <div class="">
                        <!-- <label for="AddItemInputItem">Item:</label>
                        <input type="text" class="form-control mt-1" name="AddItemInputItem" id="AddItemInputItem" list="itemList" required>
                        <datalist id="itemList">

                        </datalist> -->

                        <input type="hidden" id="hiddenItemName" required>
                        <input type="hidden" id="hiddenItemId" required>
                        <input type="hidden" id="hiddenItemQty" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add</button>
                    <button type="reset" class="btn btn-secondary btnCloseModal" id="btnCloseModal" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>



<?php include("components/footer.php") ?>
<script src="js/TransactionAdd.js"></script>
</body>

</html>