<?php

include("components/header.php");

?>
<div class="d-flex justify-content-between align-items-center">
    <h4 class="text-primary">Settings</h4>
</div>

<hr>


<h6>Account</h6>

<p>Change Password</p>

<hr>

<h6>Selection Data</h6>

<div class="card p-3">

    <h6 style="font-style: italic;">Teachers</h6>

    <form class="d-flex" id="formAddTeacher">
        <input type="hidden" name="REQUEST_TYPE" value="ADDTEACHER">
        <div class="input-group input-group-sm">
            <label for="t-name" class="input-group-text">Name</label>
            <input type="text" name="name" id="t-name" class="form-control">
        </div>
        <div class="input-group input-group-sm ms-1">
            <label for="t-contac-no" class="input-group-text">Contact No</label>
            <input type="number" name="contactNo" id="t-contact-no" class="form-control">
        </div>
        <button type="submit" class="btn btn-sm btn-primary ms-1">Add</button>
    </form>

    <div class="" style="height: 100px; overflow-y:auto">
        <table class="table table-sm">
            <thead style="position: sticky; top: 0;">
                <tr>
                    <th>ID</th>
                    <th>Teachers Name</th>
                    <th>Contact No.</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="teachersTableBody">
            </tbody>
        </table>
    </div>

</div>

<?php include("components/footer.php") ?>
<script src="js/Settings.js"></script>
</body>

</html>