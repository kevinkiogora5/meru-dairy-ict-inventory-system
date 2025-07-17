<div class="row mt-5">
  <?php foreach ($assignments as $a): ?>
    <div class="col-md-6 col-lg-4 mt-3">
      <div class="card <?= $a->deleted_at ? 'border-danger text-muted' : '' ?>">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
          <span>
            <strong>Item Name: <?= htmlspecialchars($a->item_name ?? '-') ?></strong><br>
            <small>Serial Number: <?= htmlspecialchars($a->serial_number ?? '-') ?></small>
          </span>
          <?php if (!empty($a->deleted_at)): ?>
            <span class="badge bg-danger">Deleted</span>
          <?php endif; ?>
        </div>
        <div class="card-body">
          <p><i class="ti ti-user"></i> <strong>EmployeeId:</strong> <?= htmlspecialchars($a->employee_email ?? '-') ?></p>
          <p><i class="ti ti-map-pin"></i> <strong>Location Office:</strong> <?= htmlspecialchars($a->location_office ?? '-') ?></p>
          <p><i class="ti ti-map-pin"></i> <strong>Issued By:</strong> <?= htmlspecialchars($a->user_Id ?? '-') ?></p>
          <p><i class="ti ti-calendar"></i> <strong>Issued Date:</strong> <?= htmlspecialchars($a->issue_date ?? '-') ?></p>
          <p><i class="ti ti-message-dots"></i> <strong>Notes:</strong> <?= htmlspecialchars($a->notes ?? '-') ?></p>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
