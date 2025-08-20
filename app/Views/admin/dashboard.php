<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">

  <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
    <h3 class="m-0">Admin Dashboard</h3>
    <div class="d-flex gap-2">
      <a class="btn btn-primary" href="<?= site_url('admin/posts') ?>">All Posts</a>
      <button id="btnExportPdf" class="btn btn-outline-dark">Export PDF</button>
      <a class="btn btn-brand" href="<?= site_url('new-post') ?>">New Post</a>
    </div>
  </div>

  <div id="dashboardArea">
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
              <label class="form-check"><input class="form-check-input colorPick" type="checkbox" value="#d32f2f" checked><span class="form-check-label">Red</span></label>
              <label class="form-check"><input class="form-check-input colorPick" type="checkbox" value="#388e3c" checked><span class="form-check-label">Green</span></label>
              <label class="form-check"><input class="form-check-input colorPick" type="checkbox" value="#f57c00" checked><span class="form-check-label">Orange</span></label>
              <label class="form-check"><input class="form-check-input colorPick" type="checkbox" value="#1976d2" checked><span class="form-check-label">Blue</span></label>
              <label class="form-check"><input class="form-check-input colorPick" type="checkbox" value="#fbc02d" checked><span class="form-check-label">Yellow</span></label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body">
        <h6 class="mb-3">Posts Overview</h6>
        <canvas id="dashboardChart" height="120"></canvas>
        <small class="text-muted d-block mt-2">Tip: switch type to see the same data as Bar/Pie/Line.</small>
      </div>
    </div>
  </div>
</div>

<?php
  $statusLabels = ['Active','Archived','Featured'];
  $statusCounts = [ (int)$byStatus['active'], (int)$byStatus['archived'], (int)$byStatus['featured'] ];
?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const statusLabels = ['Active','Archived','Featured'];
  const statusCounts = [<?= (int)$byStatus['active'] ?>, <?= (int)$byStatus['archived'] ?>, <?= (int)$byStatus['featured'] ?>];
  const lineLabels = <?= json_encode($labels) ?>;
  const lineCounts = <?= json_encode($values) ?>;

  const ctx = document.getElementById('dashboardChart').getContext('2d');
  const generatedBy = <?= json_encode($generatedBy ?? 'Admin') ?>;
  function currentColors() {
    const picks = [...document.querySelectorAll('.colorPick:checked')].map(i => i.value);
    return picks.length ? picks : ['#1976d2'];
  }

  function makeConfig(type, xAxis) {
    const colors = currentColors();
    if (xAxis === 'day') {
      if (type === 'pie') {
        return { type:'pie', data:{ labels:lineLabels, datasets:[{ data:lineCounts, backgroundColor: colors.slice(0, lineLabels.length) }] } };
      } else if (type === 'line') {
        return { type:'line', data:{ labels:lineLabels, datasets:[{ label:'Posts per Day', data:lineCounts, borderColor:colors[0], backgroundColor:colors[0], fill:false, tension:0.2 }] }, options:{ scales:{ y:{ beginAtZero:true, precision:0 } } } };
      } else {
        return { type:'bar', data:{ labels:lineLabels, datasets:[{ label:'Posts per Day', data:lineCounts, backgroundColor:colors[0] }] }, options:{ scales:{ y:{ beginAtZero:true, precision:0 } } } };
      }
    } else {
      if (type === 'pie') {
        return { type:'pie', data:{ labels:statusLabels, datasets:[{ data:statusCounts, backgroundColor: colors.slice(0, statusLabels.length) }] } };
      } else if (type === 'line') {
        return { type:'line', data:{ labels:statusLabels, datasets:[{ label:'Posts by Status', data:statusCounts, borderColor:colors[0], backgroundColor:colors[0], fill:false, tension:0.2 }] }, options:{ scales:{ y:{ beginAtZero:true, precision:0 } } } };
      } else {
        return { type:'bar', data:{ labels:statusLabels, datasets:[{ label:'Posts by Status', data:statusCounts, backgroundColor: colors.slice(0, statusLabels.length) }] }, options:{ scales:{ y:{ beginAtZero:true, precision:0 } } } };
      }
    }
  }

  let chart = new Chart(ctx, makeConfig('bar','status'));
  function rebuildChart(){ const t = chartType.value, x = xAxis.value; chart.destroy(); chart = new Chart(ctx, makeConfig(t,x)); }
  const chartType = document.getElementById('chartType');
  const xAxis = document.getElementById('xAxis');
  chartType.addEventListener('change', rebuildChart);
  xAxis.addEventListener('change', rebuildChart);
  document.querySelectorAll('.colorPick').forEach(el => el.addEventListener('change', rebuildChart));

  function ts() {
    const d = new Date(), p=n=>String(n).padStart(2,'0');
    return `${d.getFullYear()}-${p(d.getMonth()+1)}-${p(d.getDate())} ${p(d.getHours())}:${p(d.getMinutes())}`;
  }
  document.getElementById('btnExportPdf').addEventListener('click', async () => {
  const area = document.getElementById('dashboardArea');
  const canvas = await html2canvas(area, { scale: 2, useCORS: true });
  const img = canvas.toDataURL('image/png');

  const { jsPDF } = window.jspdf;
  const pdf = new jsPDF('p', 'mm', 'a4');

  const pageW = pdf.internal.pageSize.getWidth();
  const pageH = pdf.internal.pageSize.getHeight();

  pdf.setFont('helvetica', 'bold');
  pdf.setFontSize(16);
  pdf.text('NewsApp Dashboard', 14, 14);

  pdf.setFont('helvetica', 'normal');
  pdf.setFontSize(10);

  const metaLeft  = 'Generated: ' + ts();
  const metaRight = 'By: ' + (typeof generatedBy !== 'undefined' ? generatedBy : 'Admin');

  // left meta
  pdf.text(metaLeft, 14, 22);

  // right meta (right-aligned)
  const rightWidth = pdf.getTextWidth(metaRight);
  pdf.text(metaRight, pageW - 14 - rightWidth, 22);

  const margin = 10, top = 28;
  const maxW = pageW - margin * 2;
  const maxH = pageH - top - margin;
  const ratio = canvas.height / canvas.width;

  let drawW = maxW;
  let drawH = maxW * ratio;
  if (drawH > maxH) { drawH = maxH; drawW = maxH / ratio; }

  pdf.addImage(img, 'PNG', (pageW - drawW) / 2, top, drawW, drawH);

  const stamp = ts().replace(/[: ]/g, '').replace(/-/g, '');
  pdf.save(`NewsDashboard_${stamp}.pdf`);
  });
</script>
<?= $this->endSection() ?>
