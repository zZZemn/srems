<?php include("components/header.php") ?>

<h4 class="text-primary">Database Backup</h4>
<p class="alert alert-secondary">Create a backup of your database to ensure data is preserved in case of emergencies or system failures.</p>
<a href="../backend/controller/backup/BackupController.php" target="_blank" class="btn btn-primary">Export Database</a>


<hr>

<h5 class="text-primary">Restore Database</h5>

<form action="../backend/controller/backup/restore.php" method="POST" enctype="multipart/form-data" class="d-flex mt-3">
    <input type="file" class="form-control" name="db" required>
    <button type="submit" class="btn btn-primary ms-2">Upload</button>
</form>


<?php include("components/footer.php") ?>
</body>

</html>