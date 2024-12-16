<?php

include("components/header.php");
$getStudents = $query->getAll('students_list');

?>
<div class="d-flex justify-content-between align-items-center">
    <h4 class="text-primary">Students List Database</h4>
    <div>
        <a href="Students.php" class="btn btn-sm btn-dark">Back To System Students</a>
        <button class="btn btn-sm btn-primary" id="btnAddStudent"><i class="bi bi-plus-lg"></i> Add</button>
    </div>
</div>

<table class="table table-sm table-striped" style="font-size: 12px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Student Code</th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact No.</th>
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
                    <div class="mt-3">
                        <label for="studentYear">Year:</label>
                        <select class="form-control mt-1" name="studentYear" id="studentYear" required>
                            <option value=""></option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                    <div class="mt-3">
                        <label for="studentSection">Section:</label>
                        <select class="form-control mt-1" name="studentSection" id="studentSection" required>
                            <option value=""></option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                            <option value="F">F</option>
                        </select>
                    </div>
                    <!-- <div class="mt-3">
                        <label for="studentImage">Student Image:</label>
                        <input type="file" class="form-control mt-1" name="studentImage" id="studentImage" accept="image/*">
                    </div> -->
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
                    <div class="mt-3">
                        <label for="eStudentYear">Year:</label>
                        <select class="form-control mt-1" name="studentYear" id="eStudentYear" required>
                            <option value=""></option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                    <div class="mt-3">
                        <label for="eStudentSection">Section:</label>
                        <select class="form-control mt-1" name="studentSection" id="eStudentSection" required>
                            <option value=""></option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                            <option value="F">F</option>
                        </select>
                    </div>
                    <!-- <div class="mt-3">
                        <label for="studentImage">Student Image:</label>
                        <input type="file" class="form-control mt-1" name="studentImage" id="studentImage" accept="image/*">
                    </div> -->
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
            <img id="ModalItemImageImg" src="" alt="Item">
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary btnCloseModal" id="btnCloseModal" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include("components/footer.php") ?>
<script src="js/StudentList.js"></script>
</body>

</html>