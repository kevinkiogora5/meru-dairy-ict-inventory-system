<main>
    <div class="container-fluid">

        <div class="row m-4">
    <div class="col-md-6">
        <h4 class="main-title">Manage Department</h4>
    </div>
    <div class="col-md-6 d-flex justify-content-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Add Departments
        </button>
    </div>
</div>
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
                                        <form class="row g-3 app-form rounded-control" id="department"
                                            enctype="multipart/form-data">
                                            <div class="col-md-6">
                                                <label class="form-label">Name</label>
                                                <input class="form-control" id='name' name="name" type="text">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Description</label>
                                                <input class="form-control" id='description' name="description" type="text">
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
<script src="/scripts/openjs/openjs.js"></script>
<script src="/scripts/base/base.js"></script>
<script src="/scripts/gridjs/gridjs.js"></script>
<link href="/scripts/gridjs/theme.css" rel="stylesheet" />
<script>
    var departments = <?php echo json_encode($departments) ?>;
</script>

<script src="/scripts/department/department.js"></script>