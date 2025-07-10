<main>
    <div class="container-fluid">

        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">Manage Employees</h4>
            </div>
        </div>
        <!-- Breadcrumb end -->

        <!-- Form Validation start -->
        <div class="row ">
            <!-- Tooltips start -->
            <div class="col-12">
                <div class="card">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            Add Employees
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form class="row g-3 app-form rounded-control" id="employee"
                                            enctype="multipart/form-data">
                                            <div class="col-md-6">
                                                <label class="form-label">FirstName</label>
                                                <input class="form-control" id='first_name' name="first_name" type="text">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">LastName</label>
                                                <input class="form-control" id='last_name' name="last_name" type="text">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Email</label>
                                                <input class="form-control" id='email' name="email" type="email">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Phone</label>
                                                <input class="form-control" id='phone' name="phone" type="text">
                                            </div>
                                            <div class="col-md-6">
    <label class="form-label">Department</label>
    <select class="form-select" id="department_id" name="department_id" required>
        <option value="">-- Select Department --</option>
        <?php foreach ($departments as $dept): ?>
            <option value="<?= $dept->id ?>"><?= htmlspecialchars($dept->name) ?></option>
        <?php endforeach; ?>
    </select>
</div>

                                            <button type="submit" id="tuma" class="btn btn-primary">Save</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>FirstName</th>
                                    <th>LastName</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Department</th>
                                    <th>Created On</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($employees)) : ?>
                                    <?php foreach ($employees as $employee) : ?>
                                        <tr data-id="<?= $employee->id ?>">
                                            <td><?= $employee->id ?></td>
                                            <td><?= htmlspecialchars($employee->first_name) ?></td>
                                            <td><?= htmlspecialchars($employee->last_name) ?></td>
                                            <td><?= htmlspecialchars($employee->email) ?></td>
                                            <td><?= htmlspecialchars($employee->phone) ?></td>
                                            <td><?= htmlspecialchars($employee->department_name) ?></td>
                                            <td><?= htmlspecialchars($employee->created_at) ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-warning edit-btn">Edit</button>
                                                <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No employees found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tooltips end -->
    </div>
    <!-- Form Validation end -->

    </div>
</main>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="/scripts/base/base.js"></script>
<script src="/scripts/employee/employee.js"></script>