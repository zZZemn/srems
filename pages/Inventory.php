<?php

include("components/header.php");
$getInventory = $query->getAll('inventory');
$getCategories = $query->getAll('categories');

?>
<div class="d-flex justify-content-between align-items-center">
    <h4 class="text-primary">Inventory</h4>

    <div>
        <button class="btn btn-sm btn-dark" id="btnExportInventory"><i class="bi bi-download"></i> Export</button>
        <a href="Barcodes.php" class="btn btn-sm btn-dark"><i class="bi bi-eye"></i> Show Barcodes</a>
        <button class="btn btn-sm btn-primary" id="btnAddInventory"><i class="bi bi-plus-lg"></i> Add</button>
    </div>
</div>

<div class="d-flex justify-content-end mt-2">
    <select name="category" id="selectCategory" class="form-control" style="width: 100px;">
        <option value="ALL">All</option>
        <?php
        foreach ($getCategories as $category) {
        ?>
            <option value="<?= $category['NAME'] ?>"><?= $category['NAME'] ?></option>
        <?php
        }
        ?>
        <option value="Deleted">Deactivated</option>
    </select>
    <input type="search" class="form-control ms-1" id="inputSearch" placeholder="Search..." style="width: 300px">
</div>

<table class="table table-striped" style="font-size: 12px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Inventory Code</th>
            <th></th>
            <th>Item</th>
            <th>Total Qty</th>
            <th>Qty Left</th>
            <th>Category</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="inventoryTableBody">

    </tbody>
</table>

<div class="modal fade" tabindex="-1" role="dialog" id="ModalAddInventory">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus-fill"></i> Add Inventory</h5>
            </div>
            <form id="formAddInventory">
                <input type="hidden" name="REQUEST_TYPE" value="ADDINVENTORY">
                <div class="modal-body">
                    <div class="">
                        <label for="inventoryCode">Inventory Code:</label>
                        <input type="text" class="form-control mt-1" name="inventoryCode" id="inventoryCode" required>
                    </div>
                    <div class="mt-3">
                        <label for="inventoryItem">Item:</label>
                        <input type="text" class="form-control mt-1" name="inventoryItem" id="inventoryItem" required>
                    </div>
                    <div class="mt-3">
                        <label for="inventoryQty">Qty:</label>
                        <input type="number" class="form-control mt-1" name="inventoryQty" id="inventoryQty" required>
                    </div>
                    <div class="mt-3">
                        <label for="inventoryCategory">Category:</label>
                        <select class="form-control" name="inventoryCategory" id="inventoryCategory" required>
                            <option value=""></option>
                            <?php
                            foreach ($getCategories as $category) {
                            ?>
                                <option value="<?= $category['NAME'] ?>"><?= $category['NAME'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mt-3">
                        <label for="inventoryImage">Item Image:</label>
                        <input type="file" class="form-control mt-1" name="inventoryImage" id="inventoryImage" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="reset" class="btn btn-secondary btnCloseModal" id="btnCloseModal" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="ModalEditInventory">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus-fill"></i> Edit Inventory</h5>
            </div>
            <form id="formEditInventory">
                <input type="hidden" name="REQUEST_TYPE" value="EDITINVENTORY">
                <input type="hidden" name="ID" id="eInventoryId">
                <div class="modal-body">
                    <div class="">
                        <label for="inventoryCode">Inventory Code:</label>
                        <input type="text" class="form-control mt-1" name="inventoryCode" id="eInventoryCode" required>
                    </div>
                    <div class="mt-3">
                        <label for="inventoryItem">Item:</label>
                        <input type="text" class="form-control mt-1" name="inventoryItem" id="eInventoryItem" required>
                    </div>
                    <div class="mt-3">
                        <label for="inventoryQty">Qty:</label>
                        <input type="number" class="form-control mt-1" name="inventoryQty" id="eInventoryQty" required>
                    </div>
                    <div class="mt-3">
                        <label for="inventoryCategory">Category:</label>
                        <select class="form-control" name="inventoryCategory" id="eInventoryCategory" required>
                            <option value=""></option>
                            <?php
                            foreach ($getCategories as $category) {
                            ?>
                                <option value="<?= $category['NAME'] ?>"><?= $category['NAME'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mt-3">
                        <label for="inventoryImage">Change Image:</label>
                        <input type="file" class="form-control mt-1" name="inventoryImage" id="inventoryImage" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="reset" class="btn btn-secondary btnCloseModal" id="btnCloseModal" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="ModalViewItemImage">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <img id="ModalItemImageImg" src="../items-photos/default.jpg" alt="Item">
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary btnCloseModal" id="btnCloseModal" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include("components/footer.php") ?>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script src="js/Inventory.js"></script>
</body>

</html>