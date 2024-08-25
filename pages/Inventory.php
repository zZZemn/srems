<?php

include("components/header.php");
$getInventory = $query->getAll('inventory');

?>
<h4 class="text-primary">Inventory</h4>
<table class="table table-striped" style="font-size: 12px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Inventory Code</th>
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
<?php include("components/footer.php") ?>
<script src="js/Inventory.js"></script>
</body>

</html>