<main>
    <div class="container-fluid">

        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">Manage Location</h4>
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
                            Add Location
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
                                        <form class="row g-3 app-form rounded-control" id="location"
                                            enctype="multipart/form-data">
                                            <div class="col-md-6">
                                                <label class="form-label">County</label>
                                                <input class="form-control" id='county' name="county" type="text">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Office</label>
                                                <input class="form-control" id='office' name="office" type="text">
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
                                    <th>County</th>
                                    <th>Office</th>
                                    <th>Created On</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($locations)) : ?>
                                    <?php foreach ($locations as $location) : ?>
                                        <tr data-id="<?= $location->id ?>">
                                            <td><?= $location->id ?></td>
                                            <td><?= htmlspecialchars($location->county) ?></td>
                                            <td><?= htmlspecialchars($location->office) ?></td>
                                            <td><?= $location->created_at ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="openModal(<?= $location->id ?>)">Edit</button>
                                                <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No locations found.</td>
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
    var locations = <?php echo json_encode($locations) ?>;
</script>
<script src="/scripts/location/location.js"></script>