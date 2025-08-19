<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="m-0">Admin Dashboard</h3>
    <a class="btn btn-brand" href="<?= site_url('new-post') ?>">New Post</a>
  </div>

  <!-- 4 number widgets -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
      <div class="card shadow-sm border-0 rounded-4 p-3 h-100">
        <div class="text-muted small">Total Posts</div>
        <div class="display-6 fw-bold"><?= esc($total) ?></div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="card shadow-sm border-0 rounded-4 p-3 h-100">
        <div class="text-muted small">Active</div>
        <div class="display-6 fw-bold"><?= esc($active) ?></div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="card shadow-sm border-0 rounded-4 p-3 h-100">
        <div class="text-muted small">Archived</div>
        <div class="display-6 fw-bold"><?= esc($archived) ?></div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="card shadow-sm border-0 rounded-4 p-3 h-100">
        <div class="text-muted small">Featured</div>
        <div class="display-6 fw-bold"><?= esc($featured) ?></div>
      </div>
    </div>
  </div>

  <!-- Controls -->
  <div class="card border-0 shadow-sm rounded-4 mb-3">
    <div class="card-body">
      <div class="row g-3 align-items-end">
        <div class="col-sm-6 col-lg-3">
          <label class="form-label">Chart Type</label>
          <select id="chartType" class="form-select">
            <option value="bar" selected>Bar</option>
            <option value="pie">Pie</option>
            <option value="line">Line</option>
          </select>
        </div>
        <div class="col-sm-6 col-lg-3">
          <label class="form-label">X Axis</label>
          <select id="xAxis" class="form-select">
            <option value="status" selected>Status</option>
            <option value="day">Day (last 7)</option>
          </select>
        </div>
        <div class="col-sm-6 col-lg-3">
          <label class="form-label">Y Axis</label>
          <select id="yAxis" class="form-select">
            <option value="count" selected>Count</option>
          </select>
        </div>
        <div class="col-sm-6 col-lg-3">
          <label class="form-label">Colors</label>
          <div class="d-flex flex-wrap gap-2">
            <label class="form-check">
              <input class="form-check-input colorPick" type="checkbox" value="#d32f2f" checked>
              <span class="form-check-label">Red</span>
            </label>
            <label class="form-check">
              <input class="form-check-input colorPick" type="checkbox" value="#388e3c" checked>
              <span class="form-check-label">Green</span>
            </label>
            <label class="form-check">
              <input class="form-check-input colorPick" type="checkbox" value="#f57c00" checked>
              <span class="form-check-label">Orange</span>
            </label>
            <label class="form-check">
              <input class="form-check-input colorPick" type="checkbox" value="#1976d2" checked>
              <span class="form-check-label">Blue</span>
            </label>
            <label class="form-check">
              <input class="form-check-input colorPick" type="checkbox" value="#fbc02d" checked>
              <span class="form-check-label">Yellow</span>
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Chart -->
  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
      <h6 class="mb-3">Posts Overview</h6>
      <canvas id="dashboardChart" height="120"></canvas>
      <small class="text-muted d-block mt-2">Tip: switch type to see the same data as Bar/Pie/Line.</small>
      <li class="nav-item"><a class="nav-link" href="<?= site_url('admin/posts') ?>">All Posts</a></li>

    </div>
  </div>

</div>

<?php
  // Prepare datasets from PHP -> JS
  $statusLabels = ['Active','Archived','Featured'];
  $statusCounts = [ (int)$byStatus['active'], (int)$byStatus['archived'], (int)$byStatus['featured'] ];

  // For the line chart we use $labels (last 7 days) and $values (counts)
?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const statusLabels = ['Active','Archived','Featured'];
  const statusCounts = [<?= (int)$byStatus['active'] ?>, <?= (int)$byStatus['archived'] ?>, <?= (int)$byStatus['featured'] ?>];

  const lineLabels = <?= json_encode($labels) ?>;  // last 7 days, e.g., ["Aug 10","Aug 11",...]
  const lineCounts = <?= json_encode($values) ?>;  // counts per day

  const ctx = document.getElementById('dashboardChart').getContext('2d');

  function currentColors() {
    const picks = Array.from(document.querySelectorAll('.colorPick:checked')).map(i => i.value);
    return picks.length ? picks : ['#1976d2']; // default blue if none selected
  }

  function makeConfig(type, xAxis) {
    const colors = currentColors();

    if (xAxis === 'day') {
      // X = Day (last 7)
      if (type === 'pie') {
        // Pie with 7 slices
        return { type: 'pie',
          data: { labels: lineLabels, datasets: [{ data: lineCounts, backgroundColor: colors.slice(0, lineLabels.length) }] }
        };
      } else if (type === 'line') {
        return { type: 'line',
          data: { labels: lineLabels, datasets: [{ label: 'Posts per Day', data: lineCounts, borderColor: colors[0], backgroundColor: colors[0], fill: false, tension: 0.2 }] },
          options: { scales: { y: { beginAtZero: true, precision: 0 } } }
        };
      } else { // bar
        return { type: 'bar',
          data: { labels: lineLabels, datasets: [{ label: 'Posts per Day', data: lineCounts, backgroundColor: colors[0] }] },
          options: { scales: { y: { beginAtZero: true, precision: 0 } } }
        };
      }
    } else {
      // X = Status (Active/Archived/Featured)
      if (type === 'pie') {
        return { type: 'pie',
          data: { labels: statusLabels, datasets: [{ data: statusCounts, backgroundColor: colors.slice(0, statusLabels.length) }] }
        };
      } else if (type === 'line') {
        return { type: 'line',
          data: { labels: statusLabels, datasets: [{ label: 'Posts by Status', data: statusCounts, borderColor: colors[0], backgroundColor: colors[0], fill: false, tension: 0.2 }] },
          options: { scales: { y: { beginAtZero: true, precision: 0 } } }
        };
      } else { // bar
        return { type: 'bar',
          data: { labels: statusLabels, datasets: [{ label: 'Posts by Status', data: statusCounts, backgroundColor: colors.slice(0, statusLabels.length) }] },
          options: { scales: { y: { beginAtZero: true, precision: 0 } } }
        };
      }
    }
  }

  let chart = new Chart(ctx, makeConfig('bar', 'status'));

  function rebuildChart() {
    const type  = document.getElementById('chartType').value;
    const xAxis = document.getElementById('xAxis').value;
    chart.destroy();
    chart = new Chart(ctx, makeConfig(type, xAxis));
  }

  document.getElementById('chartType').addEventListener('change', rebuildChart);
  document.getElementById('xAxis').addEventListener('change', rebuildChart);
  document.querySelectorAll('.colorPick').forEach(el => el.addEventListener('change', rebuildChart));
</script>

<?= $this->endSection() ?>
