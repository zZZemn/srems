<?php

include("components/header.php");
$getInv = $query->getAll("inventory");

?>

<style>
    .print-container {}

    @media print {
        #main-container {
            left: 0 !important;
            top: 0 !important;
            padding: 10px !important;
            width: 100% !important;
        }

        .print-container {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            margin: 0 !important;
            padding: 20px !important;
            width: 100% !important;
        }
    }
</style>

<div class="d-flex justify-content-between align-items-center d-print-none">
    <h4 class="text-primary">Barcodes</h4>
    <button class="btn btn-sm btn-dark" id="btn-print">Print Barcodes</button>
</div>

<div class="print-container">
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
<script>
    $("#btn-print").click(function(e) {
        e.preventDefault();

        window.print();
    });
</script>
</body>

</html>