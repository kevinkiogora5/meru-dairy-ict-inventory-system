<main>
    <div class="container-fluid">

        <!-- Breadcrumb start -->
        <div class="row m-4">
            <div class="col-md-6">
                <h4 class="main-title">Manage Employees</h4>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">
                    Add Employees
                </button>
            </div>
        </div>
        <!-- Breadcrumb end -->

        <!-- Form Validation start -->
        <div class="row ">
            <!-- Tooltips start -->
            <div class="col-12">
                <div class="card">
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
                                                <label class="form-label">Employee id</label>
                                                <input class="form-control" id='email' name="email" type="text">
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
<div class="col-12 text-end">

                                            <button type="submit" id="tuma" class="btn btn-primary">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 <!-- Grid.js Table Placeholder -->
                    <div class="card-body">
                        <div id="grid-wrapper"></div>
                    </div>
            </div>
        </div>
        <!-- Tooltips end -->
    </div>
    <!-- Form Validation end -->
</main>
<script src="/scripts/openjs/openjs.js"></script>
<script src="/scripts/base/base.js"></script>
<script src="/scripts/gridjs/gridjs.js"></script>
<link href="/scripts/gridjs/theme.css" rel="stylesheet" />
<script src="/scripts/employee/employee.js"></script>