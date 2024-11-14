<?php

include("components/header.php");
$getStudents = $query->getAll('students');

?>
<div class="d-flex justify-content-between align-items-center">
    <h4 class="text-primary">Students</h4>
    <button class="btn btn-sm btn-primary" id="btnAddStudent"><i class="bi bi-plus-lg"></i> Add</button>
</div>

<div class="d-flex justify-content-end mt-2">
    <select name="status" id="selectStatus" class="form-control" style="width: 100px;">
        <option value="ALL">All</option>
        <option value="ACTIVE">Active</option>
        <option value="INACTIVE">Deactivated</option>
    </select>
    <input type="search" class="form-control ms-1" id="inputSearch" placeholder="Search..." style="width: 300px">
</div>

<table class="table table-sm table-striped" style="font-size: 12px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Student Code</th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact No.</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="studentTableBody">

    </tbody>
</table>

<div class="modal fade" tabindex="-1" role="dialog" id="ModalAddStudent">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus-fill"></i> Add Student</h5>
            </div>
            <form id="formAddStudent">
                <input type="hidden" name="REQUEST_TYPE" value="ADDSTUDENT">
                <div class="modal-body">
                    <div class="">
                        <label for="studentCode">Student Code:</label>
                        <input type="text" class="form-control mt-1" name="studentCode" id="studentCode" required>
                    </div>
                    <div class="mt-3">
                        <label for="studentName">Name:</label>
                        <input type="text" class="form-control mt-1" name="studentName" id="studentName" required>
                    </div>
                    <div class="mt-3">
                        <label for="studentEmail">Email:</label>
                        <input type="email" class="form-control mt-1" name="studentEmail" id="studentEmail" required>
                    </div>
                    <div class="mt-3">
                        <label for="studentContactNo">Contact No:</label>
                        <input type="text" class="form-control mt-1" name="studentContactNo" id="studentContactNo" required>
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

<div class="modal fade" tabindex="-1" role="dialog" id="ModalEditStudent">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus-fill"></i> Edit Student</h5>
            </div>
            <form id="formEditStudent">
                <input type="hidden" name="REQUEST_TYPE" value="EDITSTUDENT">
                <input type="hidden" name="ID" id="eStudentId">
                <div class="modal-body">
                    <div class="">
                        <label for="studentCode">Student Code:</label>
                        <input type="text" class="form-control mt-1" name="studentCode" id="eStudentCode" required>
                    </div>
                    <div class="mt-3">
                        <label for="studentName">Name:</label>
                        <input type="text" class="form-control mt-1" name="studentName" id="eStudentName" required>
                    </div>
                    <div class="mt-3">
                        <label for="studentEmail">Email:</label>
                        <input type="email" class="form-control mt-1" name="studentEmail" id="eStudentEmail" required>
                    </div>
                    <div class="mt-3">
                        <label for="studentContactNo">Contact No:</label>
                        <input type="text" class="form-control mt-1" name="studentContactNo" id="eStudentContactNo" required>
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

<?php include("components/footer.php") ?>
<script src="js/Students.js"></script>
</body>

</html>