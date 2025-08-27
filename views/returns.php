<main>
  <div class="container-fluid">

    <!-- Page Heading -->
    <div class="row m-4 align-items-center">
      
      <!-- Left: Title -->
      <div class="col-md-4">
        <h4 class="main-title">Manage Returns</h4>
      </div>

      <!-- Middle: Filters + Generate Report -->
      <div class="col-md-5">
        <form id="reportFilterForm" class="d-flex align-items-center">
          <!-- Employee filter -->
          <select name="employee_id" id="employeeFilter" class="form-select me-2" style="min-width: 150px;">
            <option value="">All Employees</option>
            <?php foreach ($employees as $emp): ?>
              <option value="<?= $emp->id ?>"><?= htmlspecialchars($emp->first_name . ' ' . $emp->last_name) ?></option>
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
              <li><a class="dropdown-item" href="#" data-type="pdf">PDF Report</a></li>
              <li><a class="dropdown-item" href="#" data-type="excel">Excel Report</a></li>
            </ul>
          </div>
           <!-- Right: Add Returns Button -->
        <button type="button" class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
          Add Returns
        </button>
      </div>
        </form>

    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title">Add Return</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          
          <div class="modal-body">
            <form class="row g-3 app-form rounded-control" id="return" enctype="multipart/form-data">
              
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
                <label class="form-label">Return Item</label>
                <select class="form-select" id="inventory_item_id" name="inventory_assignment_id" required>
                  <option value="">-- Select Employee first --</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Return Condition</label>
                <input class="form-control" id="returned" name="returned_condition" type="text">
              </div>

              <div class="col-md-6">
                <label class="form-label">Comments</label>
                <input class="form-control" id="comment" name="comments" type="text">
              </div>

              <div class="col-12 text-end">
                <button type="submit" id="tuma" class="btn btn-primary">Save</button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Grid.js Table -->
    <div class="row">
      <div class="col-12">
        <div id="grid-wrapper"></div>
      </div>
    </div>

  </div>
</main>

<!-- Dependencies -->
<script src="/scripts/openjs/openjs.js"></script>
<script src="/scripts/base/base.js"></script>
<script src="/scripts/gridjs/gridjs.js"></script>
<link href="/scripts/gridjs/theme.css" rel="stylesheet" />
<script src="/scripts/return/return.js"></script>
<script>
document.querySelectorAll('.dropdown-item').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        const type = this.getAttribute('data-type');
        const employee = document.getElementById('employeeFilter').value;
        const date = document.getElementById('dateFilter').value;

        // Build URL with filters
        let url = `/return/report?type=${type}`;
        if (employee) url += `&employee_id=${employee}`;
        if (date) url += `&created_at=${date}`;

        // Open report in new tab
        window.open(url, '_blank');
    });
});
</script>
