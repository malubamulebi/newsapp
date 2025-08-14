<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
  <?php if (session()->getFlashdata('msg')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('msg')) ?></div>
  <?php endif; ?>

  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <div class="small text-muted">Active Posts</div>
          <div class="display-6"><?= esc($stats['totalPosts']) ?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <div class="small text-muted">Archived</div>
          <div class="display-6"><?= esc($stats['archivedPosts']) ?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <div class="small text-muted">Users</div>
          <div class="display-6"><?= esc($stats['totalUsers']) ?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <div class="small text-muted">Admins</div>
          <div class="display-6"><?= esc($stats['totalAdmins']) ?></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-7">
      <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <strong>Recent Posts</strong>
          <a class="btn btn-sm btn-brand" href="<?= site_url('admin/new-post') ?>">New Post</a>
        </div>
        <div class="table-responsive">
          <table class="table table-sm align-middle mb-0">
            <thead><tr>
              <th>Title</th><th>Updated</th><th class="text-end">Actions</th>
            </tr></thead>
            <tbody>
            <?php foreach ($activePosts as $p): ?>
              <tr>
                <td><?= esc($p['header']) ?></td>
                <td><small class="text-muted"><?= esc($p['updated_at'] ?? $p['created_at']) ?></small></td>
                <td class="text-end">
                  <!-- for now, link to archive -->
                  <a class="btn btn-sm btn-outline-secondary" href="<?= site_url('admin/archive-post/'.$p['postId']) ?>">Archive</a>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card shadow-sm mt-4">
        <div class="card-header bg-white"><strong>Archived Posts</strong></div>
        <div class="table-responsive">
          <table class="table table-sm align-middle mb-0">
            <thead><tr>
              <th>Title</th><th>Archived</th><th class="text-end">Actions</th>
            </tr></thead>
            <tbody>
            <?php foreach ($archivedPosts as $p): ?>
              <tr>
                <td><?= esc($p['header']) ?></td>
                <td><small class="text-muted"><?= esc($p['deleted_at']) ?></small></td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-success" href="<?= site_url('admin/restore-post/'.$p['postId']) ?>">Restore</a>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="card shadow-sm">
        <div class="card-header bg-white"><strong>Latest Users</strong></div>
        <div class="table-responsive">
          <table class="table table-sm align-middle mb-0">
            <thead><tr>
              <th>Email</th><th>Username</th><th>Comment</th>
            </tr></thead>
            <tbody>
            <?php foreach ($latestUsers as $u): ?>
              <tr>
                <td><small><?= esc($u['email']) ?></small></td>
                <td><small><?= esc($u['username'] ?? '') ?></small></td>
                <td><small class="text-muted"><?= esc(mb_strimwidth($u['comment'] ?? '',0,40,'…')) ?></small></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
