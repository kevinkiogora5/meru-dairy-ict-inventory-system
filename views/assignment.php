<main>
    <div class="container-fluid">

        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">Manage Inventory_Assignment</h4>
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
                            Assign Inventory
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
                                        <form class="row g-3 app-form rounded-control" id="assignment"
                                            enctype="multipart/form-data">
                                            <div class="col-md-6">
                                              <label class="form-label">Employee</label>
    <select class="form-select" id="employee_id" name="employee_id" required>
        <option value="">-- Select Employee --</option>
        <?php foreach ($employees as $employee): ?>
            <option value="<?= $employee->id ?>"><?= htmlspecialchars($employee->first_name. '  '.$employee->last_name) ?></option>
        <?php endforeach; ?>
    </select>
</div>
<div class="col-md-6">
    <label class="form-label">Inventory Item</label>
    <select class="form-select" id="inventory_item_id" name="inventory_item_id" required>
        <option value="">-- Select Inventory Item --</option>
        <?php foreach ($items as $item): ?>
            <option value="<?= $item->id ?>"><?= htmlspecialchars($item->name) ?></option>
        <?php endforeach; ?>
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
                                                   <input class="form-control" id='notes' name="notes" type="text">
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
                                    <th>Employee Id</th>
                                    <th>Inventory Item Name</th>
                                    <th>Location</th>
                                    <th>Issue Date</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($assignments)): ?>                                    
                                <?php foreach ($assignments as $assignment): ?>
                                    <tr data-id="<?= $assignment->id ?>">
                                        <td><?= $assignment->id ?></td>
                                        <td><?= htmlspecialchars($assignment->employees_email) ?></td>
                                        <td><?= htmlspecialchars($assignment->items_name) ?></td>
                                        <td><?= htmlspecialchars($assignment->location_name) ?></td>
                                        <td><?= htmlspecialchars($assignment->issue_date) ?></td>
                                        <td><?= htmlspecialchars($assignment->notes) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning"onclick="openModal(<?= $assignment->id ?>)">Edit</button>
                                            <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                        <td colspan="5" class="text-center">No assignments found.</td>
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
<script>
    var assignment = <?php echo json_encode($assignments) ?>;
</script>
<script src="/scripts/inventory/assignment.js"></script>