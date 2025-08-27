<main>
    <div class="container-fluid">

        <!-- Breadcrumb start -->
        <div class="row m-3">
            <div class="col-md-6">
                <h4 class="main-title">Manage Inventory Items</h4>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
         <div class="d-flex align-items-center mx-2">
    <!-- Filters -->
    <form id="reportFilterForm" class="d-flex align-items-center">
        <!-- Category filter -->
        <select name="category_id" id="categoryFilter" class="form-select me-2 w-auto" style="min-width: 200px;">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat->id ?>"><?= htmlspecialchars($cat->type) ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Date filter -->
        <input type="date" name="created_at" id="dateFilter" class="form-control me-2">

        <!-- Dropdown for report type -->
        <div class="dropdown">
            <button class="btn btn-success dropdown-toggle" type="button" id="reportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Generate Report
            </button>
            <ul class="dropdown-menu" aria-labelledby="reportDropdown">
                <li>
                    <a class="dropdown-item" href="#" data-type="pdf">PDF Report</a>
                </li>
                <li>
                    <a class="dropdown-item" href="#" data-type="excel">Excel Report</a>
                </li>
            </ul>
        </div>
    </form>
</div>

<script>
document.querySelectorAll('.dropdown-item').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        const type = this.getAttribute('data-type');
        const category = document.getElementById('categoryFilter').value;
        const date = document.getElementById('dateFilter').value;

        // Build URL with filters
        let url = `/inventory/report?type=${type}`;
        if (category) url += `&category_id=${category}`;
        if (date) url += `&created_at=${date}`;

        // Open report in new tab
        window.open(url, '_blank');
    });
});
</script>
 <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">
                    Add Items
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
                 <!-- Grid.js Table Placeholder -->
                    <div class="card-body">
                        <div id="grid-wrapper"></div>
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
<script>
    var goods = <?php echo json_encode($items) ?>;
</script>
<script src="/scripts/inventory/item.js"></script>