<main>
    <div class="container-fluid">

        <!-- Breadcrumb start -->
        <div class="row m-4">
            <div class="col-md-6">
                <h4 class="main-title">Manage Returns</h4>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">
                    Add Returns
                </button>
            </div>
        </div>
        <!-- Form Validation start -->
        <div class="row ">
            <!-- Tooltips start -->
            <div class="col-12">
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
                                        <form class="row g-3 app-form rounded-control" id="return"
                                            enctype="multipart/form-data">
                                            <div class="col-md-6">
                                              <label class="form-label">Employee</label>
    <select class="form-select" id="employee_id" name="employee_id" required>
        <option value="">-- Select Employee --</option>
        <?php foreach ($employees as $employee): ?>
            <option value="<?= $employee->id ?>"><?= htmlspecialchars($employee->first_name.'  '.$employee->last_name) ?></option>
        <?php endforeach; ?>
    </select>
</div>
<div class="col-md-6">
    <label class="form-label">Return Item</label>
    <select class="form-select" id="inventory_item_id" name="inventory_assignment_id" required>
        <option value="">-- Select Employee first --</option>
    </select>
</div>
                                            <div class="col-md-6">
                                                <label class="form-label">Return Condition</label>
                                                <input class="form-control" id='returned' name="returned_condition" type="text">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Comments</label>
                                                <input class="form-control" id='comment' name="comments" type="text">
                                            </div>
                                            <div class="col-12 text-end">
                                            <button type="submit" id="tuma" class="btn btn-primary">Save</button>
                                            </div>
                                        </form>
                                    </div>
                        </div>
                    </div>
                </div>
                <!-- Grid.js Table Placeholder -->
                        <div id="grid-wrapper"></div>
            </div>
        </div>
        <!-- Tooltips end -->
    </div>
    <!-- Form Validation end -->

    </div>
</main>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="/scripts/base/base.js"></script>
<script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>
<link href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
<script src="/scripts/return/return.js"></script>