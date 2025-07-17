<main>
    <div class="container-fluid">

        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">Manage Returns</h4>
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
                            Add Returns
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
    <select class="form-select" id="inventory_item_id" name="inventory_item_id" required>
        <option value="">-- Select Return Item --</option>
        <?php foreach ($items as $item): ?>
            <option value="<?= $item->id ?>"><?= htmlspecialchars($item->name) ?></option>
        <?php endforeach; ?>
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
                                    <th>Employee Names</th>
                                    <th>Returned Item</th>
                                    <th>Return Condition</th>
                                    <th>Return Date</th>
                                    <th>Comments</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($returns)): ?>                                    
                                <?php foreach ($returns as $return): ?>
                                    <tr data-id="<?= $return->id ?>">
                                        <td><?= $return->id ?></td>
                                        <td><?= htmlspecialchars($return->employee_email) ?></td>
                                        <td><?= htmlspecialchars($return->inventory_item_name) ?></td>
                                        <td><?= htmlspecialchars($return->returned_condition) ?></td>
                                        <td><?= htmlspecialchars($return->return_date) ?></td>
                                        <td><?= htmlspecialchars($return->comments) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning " onclick="openModal(<?= $return->id ?>)">Edit</button>
                                            <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                        <td colspan="5" class="text-center">No returns found.</td>
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
    var returns = <?php echo json_encode($returns) ?>;
</script>
<script src="/scripts/return/return.js"></script>