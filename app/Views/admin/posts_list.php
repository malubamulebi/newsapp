<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="m-0">All Posts</h3>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-secondary" href="<?= site_url('admin') ?>">Dashboard</a>
      <a class="btn btn-brand" href="<?= site_url('new-post') ?>">New Post</a>
    </div>
  </div>

  <form method="get" class="row g-2 align-items-end mb-3">
    <div class="col-12 col-md-4">
      <label class="form-label">Search</label>
      <input type="text" name="q" value="<?= esc($q ?? '') ?>" class="form-control" placeholder="Search title or body">
    </div>
    <div class="col-6 col-md-3">
      <label class="form-label">From</label>
      <input type="date" name="from" value="<?= esc($from ?? '') ?>" class="form-control">
    </div>
    <div class="col-6 col-md-3">
      <label class="form-label">To</label>
      <input type="date" name="to" value="<?= esc($to ?? '') ?>" class="form-control">
    </div>
    <div class="col-12 col-md-2 d-grid">
      <button class="btn btn-primary">Filter</button>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Header</th>
          <th>Status</th>
          <th>Date Posted</th>
          <th>Last Modified</th>
          <th>Featured</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($posts) && is_array($posts)): ?>
          <?php foreach ($posts as $p): ?>
            <?php $cid = 'c'.$p['postId']; ?>
            <tr>
              <td class="fw-semibold"><?= esc($p['header']) ?></td>
              <td><span class="badge bg-secondary"><?= esc($p['status'] ?? 'active') ?></span></td>
              <td><?= esc(date('M j, Y H:i', strtotime($p['created_at'] ?? date('Y-m-d H:i:s')))) ?></td>
              <td><?= esc(!empty($p['updated_at']) ? date('M j, Y H:i', strtotime($p['updated_at'])) : '-') ?></td>
              <td>
                <?php if (!empty($p['is_featured'])): ?>
                  <i class="bi bi-star-fill text-warning"></i>
                <?php else: ?>
                  <i class="bi bi-dash"></i>
                <?php endif; ?>
              </td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary" href="<?= site_url('posts/view/'.$p['postId']) ?>">Open</a>
                <a class="btn btn-sm btn-warning" href="<?= site_url('admin/edit-post/'.$p['postId']) ?>">Edit</a>
                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $cid ?>">See more</button>
              </td>
            </tr>
            <!-- collapse row stays as you had it -->
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="6">No posts found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="mt-3">
    <?= isset($pager) ? $pager->links() : '' ?>
  </div>
</div>
<?= $this->endSection() ?>
