<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container py-5">
  <h2 class="mb-3"><?= esc($post['header']) ?></h2>
  <?php if (!empty($post['picture'])): ?>
    <img class="img-fluid mb-3" src="<?= base_url('uploads/' . $post['picture']) ?>" alt="">
  <?php endif; ?>
  <p><?= nl2br(esc($post['body'])) ?></p>
  <a class="btn btn-outline-secondary mt-3" href="<?= site_url('/') ?>">Back</a>
</div>
<?= $this->endSection() ?>
