<main>
    <div class="container-fluid">

        <!-- Breadcrumb start -->
        <div class="row m-4">
            <div class="col-md-6">
                <h4 class="main-title">Manage Inventory categories</h4>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
             <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            Add Categories
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
                                        <form class="row g-3 app-form rounded-control" id="category"
                                            enctype="multipart/form-data">
                                            <div class="col-md-6">
                                                <label class="form-label">Type</label>
                                                <input class="form-control" id='type' name="type" type="text">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Description</label>
                                                <input class="form-control" id='description' name="description" type="text">
                                            <div class="col-12 text-end mt-2">
                                            <button type="submit" id="tuma" class="btn btn-primary">Save</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 <!-- Grid.js Table Placeholder -->
                        <div id="grid-wrapper"></div>
                </div>
            </div>
        </div>
        <!-- Form Validation end -->

    </div>
</main>
<script src="/scripts/openjs/openjs.js"></script>
<script src="/scripts/base/base.js"></script>
<script src="/scripts/gridjs/gridjs.js"></script>
<link href="scripts/gridjs/theme.css" rel="stylesheet" />
<script>
    var categories = <?php echo json_encode($categories) ?>;
</script>
<script src="/scripts/inventory/category.js"></script>