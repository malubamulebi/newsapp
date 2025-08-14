<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">

  <?php if (!empty($posts)): ?>
    <?php $featured = $posts[0]; ?>
    <div class="row g-4 align-items-stretch">
      <div class="col-lg-8">
        <div class="card h-100 shadow-sm">
          <?php if (!empty($featured['picture'])): ?>
            <img src="<?= esc($featured['picture']) ?>" class="card-img-top" alt="">
          <?php endif; ?>
          <div class="card-body">
            <h2 class="card-title"><?= esc($featured['header']) ?></h2>
            <p class="text-muted"><?= esc(mb_strimwidth(strip_tags($featured['body']),0,220,'…')) ?></p>
            <a href="#" class="btn btn-brand">Read</a>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="vstack gap-3">
          <?php foreach (array_slice($posts,1,3) as $p): ?>
            <div class="card shadow-sm">
              <div class="card-body">
                <h6 class="card-title mb-1"><?= esc($p['header']) ?></h6>
                <small class="text-muted"><?= esc(mb_strimwidth(strip_tags($p['body']),0,90,'…')) ?></small>
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
            <img src="<?= esc($p['picture']) ?>" class="card-img-top" alt="">
          <?php endif; ?>
          <div class="card-body">
            <h6 class="card-title"><?= esc($p['header']) ?></h6>
            <small class="text-muted">
              <?= esc(($featured['updated_at'] ?? $featured['created_at'])) ?>
            </small>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</div>
<?= $this->endSection() ?>
