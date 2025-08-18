<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">

  <?php if (!empty($posts)): ?>
    <?php $featured = $posts[0]; ?>
    <div class="row g-4 align-items-stretch">
      <div class="col-lg-8">
        <div class="card h-100 shadow-sm">
          <?php if (!empty($featured['picture'])): ?>
            <img src="<?= base_url('uploads/' . $featured['picture']) ?>" class="card-img-top" alt="<?= esc($featured['header']) ?>">
          <?php endif; ?>
          <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-2">
              <?php if (!empty($featured['status'])): ?>
                <span class="badge text-bg-secondary text-uppercase small"><?= esc($featured['status']) ?></span>
              <?php endif; ?>
              <?php $fd = $featured['updated_at'] ?? $featured['created_at'] ?? null; ?>
              <?php if ($fd): ?>
                <small class="text-muted"><?= esc(date('M j, Y • H:i', strtotime($fd))) ?></small>
              <?php endif; ?>
            </div>
            <h2 class="card-title"><?= esc($featured['header']) ?></h2>
            <p class="text-muted"><?= esc(mb_strimwidth(strip_tags($featured['body']), 0, 220, '…')) ?></p>
            <a href="<?= site_url('posts/view/' . $featured['postId']) ?>" class="btn btn-brand">Read</a>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="vstack gap-3">
          <?php foreach (array_slice($posts, 1, 3) as $p): ?>
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <h6 class="card-title mb-1">
                  <a href="<?= site_url('posts/view/' . $p['postId']) ?>">
                    <?= esc($p['header']) ?>
                  </a>
                </h6>
                <small class="text-muted d-block mb-1">
                  <?php $d = $p['updated_at'] ?? $p['created_at'] ?? null; ?>
                  <?= $d ? esc(date('M j, Y', strtotime($d))) : '' ?>
                </small>
                <small class="text-muted"><?= esc(mb_strimwidth(strip_tags($p['body']), 0, 90, '…')) ?></small>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-info mt-4">No posts yet. Add some from the admin.</div>
  <?php endif; ?>

  <h4 class="mt-5 mb-3">More stories</h4>
  <div class="row g-4">
    <?php foreach (array_slice($posts ?? [], 0, 8) as $p): ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="card h-100 shadow-sm">
          <?php if (!empty($p['picture'])): ?>
            <img src="<?= base_url('uploads/' . $p['picture']) ?>" class="card-img-top" alt="<?= esc($p['header']) ?>">
          <?php endif; ?>
          <div class="card-body">
            <h6 class="card-title mb-1">
              <a href="<?= site_url('posts/view/' . $p['postId']) ?>">
                <?= esc($p['header']) ?>
              </a>
            </h6>
            <div class="d-flex align-items-center gap-2">
              <?php if (!empty($p['status'])): ?>
                <span class="badge text-bg-light text-uppercase small"><?= esc($p['status']) ?></span>
              <?php endif; ?>
              <small class="text-muted">
                <?php $pd = $p['updated_at'] ?? $p['created_at'] ?? null; ?>
                <?= $pd ? esc(date('M j, Y', strtotime($pd))) : '' ?>
              </small>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</div>
<?= $this->endSection() ?>
