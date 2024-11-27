<?php

include("components/header.php");
$getInv = $query->getAll("inventory");

?>
<div class="d-flex justify-content-between align-items-center">
    <h4 class="text-primary">Barcodes</h4>
</div>

<div class="container">
    <div class="row">
        <?php
        $getInv = $query->getAll("inventory");
        while ($inv = $getInv->fetch_assoc()) {
            $barcode = $inv['BARCODE'];
            $barcodeUrl = "https://bwipjs-api.metafloor.com/?bcid=code128&text={$barcode}&scale=3&height=10";
        ?>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 card p-2 m-2" style="max-width: 250px;">
                <div>
                    <img src="<?= $barcodeUrl ?>" style="width: 100%; height: 100%" alt="Barcode for <?= $barcode ?>" />
                </div>
                <small class="text-primary fw-bold"><?= $inv['ITEM_NAME'] ?></small>
            </div>
        <?php
        }
        ?>
    </div>
</div>



<?php include("components/footer.php") ?>
</body>

</html>