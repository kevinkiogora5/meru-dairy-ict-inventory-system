<main>
    <div class="container-fluid">

        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">Manage Inventory Items</h4>
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
                            Add Items
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
                                        <form class="row g-3 app-form rounded-control" id="item"
                                            enctype="multipart/form-data">
                                            <div class="col-md-6">
                                                <label class="form-label">Name</label>
                                                <input class="form-control" id='name' name="name" type="text">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Model</label>
                                                <input class="form-control" id='model' name="model" type="text">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Brand</label>
                                                <input class="form-control" id='brand' name="brand" type="text">
                                            </div>
                                           <div class="col-md-6">
    <label class="form-label">Condition</label>
    <input class="form-control" id='item_condition' name="item_condition" type="text">
</div> 

                                            <div class="col-md-6">
                                                <label class="form-label">Serial Number</label>
                                                <input class="form-control" id='serial_number' name="serial_number" type="text">
                                            </div>
                                            <div class="col-md-6">
    <label class="form-label">Category</label>
    <select class="form-select" id="category_id" name="category_id" required>
        <option value="">-- Select Category --</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat->id ?>"><?= htmlspecialchars($cat->type) ?></option>
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
                                    <th>Name</th>
                                    <th>Model</th>
                                    <th>Brand</th>
                                    <th>Status</th>
                                    <th>Condtion</th>
                                    <th>SerialNumber</th>
                                    <th>Type</th>
                                    <th>Posted On</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($items)) : ?>
                                    <?php foreach ($items as $item) : ?>
                                        <tr data-id="<?= $item->id ?>">
                                            <td><?= $item->id ?></td>
                                            <td><?= htmlspecialchars($item->name) ?></td>
                                            <td><?= htmlspecialchars($item->model) ?></td>
                                            <td><?= htmlspecialchars($item->brand) ?></td>
                                            <td><?= htmlspecialchars($item->status) ?></td>
                                            <td><?= htmlspecialchars($item->item_condition) ?></td>
                                            <td><?= htmlspecialchars($item->serial_number) ?></td>
                                            <td><?= htmlspecialchars($item->category_type) ?></td>
                                            <td><?= htmlspecialchars($item->created_at) ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-warning"onclick="openModal(<?= $item->id ?>)">Edit</button>
                                                <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="12" class="text-center">No items found.</td>
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
    var goods = <?php echo json_encode($items) ?>;
</script>
<script src="/scripts/inventory/item.js"></script>