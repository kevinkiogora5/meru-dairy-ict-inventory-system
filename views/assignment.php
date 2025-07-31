<main>
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Manage Inventory_Assignment</h4>
            </div>
        </div>

        <!-- Main Card -->
        <div class="row">
            <div class="col-12">
                <div class="card p-3">

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Assign Inventory
                    </button>

                    <!-- Modal Form -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Assign Inventory</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="row g-3 app-form rounded-control" id="assignment" enctype="multipart/form-data">
                                        <div class="col-md-6">
                                            <label class="form-label">Employee</label>
                                            <select class="form-select" id="employee_id" name="employee_id" required>
                                                <option value="">-- Select Employee --</option>
                                                <?php foreach ($employees as $employee): ?>
                                                    <option value="<?= $employee->id ?>"><?= htmlspecialchars($employee->first_name . ' ' . $employee->last_name) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Inventory Item</label>
                                            <select class="form-select" id="inventory_item_id" name="inventory_item_id" required>
                                                <option value="">-- Select Inventory Item --</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Location</label>
                                            <select class="form-select" id="location_id" name="location_id" required>
                                                <option value="">-- Select Location --</option>
                                                <?php foreach ($locations as $location): ?>
                                                    <option value="<?= $location->id ?>"><?= htmlspecialchars($location->office) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Notes</label>
                                            <input class="form-control" id="notes" name="notes" type="text">
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
                    <div class="card-body">
                        <div id="grid-wrapper"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

<!-- Dependencies -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="/scripts/base/base.js"></script>
<script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>
<link href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />

<!-- Assignment data from PHP -->
<script>
    var assignment = <?= json_encode($assignments) ?>;
</script>

<!-- Assignment JS -->
<script src="/scripts/inventory/assignment.js"></script>
