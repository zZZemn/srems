<?php

include("components/header.php");
$getInventory = $query->getAll('inventory');

?>
<h4 class="text-primary">Inventory</h4>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Inventory Code</th>
            <th>Item</th>
            <th>Total Qty</th>
            <th>Qty Left</th>
            <th>Category</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($getInventory->num_rows > 0) {
            while ($item = $getInventory->fetch_assoc()) {
        ?>
                <tr>
                    <td><?= $item['ID'] ?></td>
                    <td><?= $item['INV_CODE'] ?></td>
                    <td><?= $item['ITEM_NAME'] ?></td>
                    <td><?= $item['QTY'] ?></td>
                    <td><?= $item['QTY'] ?></td>
                    <td><?= $item['CATEGORY'] ?></td>
                    <td><?= $item['STATUS'] ?></td>
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