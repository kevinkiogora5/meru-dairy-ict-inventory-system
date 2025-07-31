<main>
    <div class="container-fluid">

        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">Manage Inventory categories</h4>
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
                            Add Categories
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
                                        <form class="row g-3 app-form rounded-control" id="category"
                                            enctype="multipart/form-data">
                                            <div class="col-md-6">
                                                <label class="form-label">Type</label>
                                                <input class="form-control" id='type' name="type" type="text">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Description</label>
                                                <input class="form-control" id='description' name="description" type="text">
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
    <!-- Form Validation end -->

    </div>
</main>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="/scripts/base/base.js"></script>
<script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>
<link href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
<script>
    var categories = <?php echo json_encode($categories) ?>;
</script>
<script src="/scripts/inventory/category.js"></script>