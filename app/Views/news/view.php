<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <h1><?= esc($post['header']) ?></h1>
    <?php if (!empty($post['picture'])): ?>
        <img src="<?= base_url('uploads/' . $post['picture']) ?>" class="img-fluid mb-4" alt="">
    <?php endif; ?>
    <p><?= nl2br(esc($post['body'])) ?></p>
</div>
<?= $this->endSection() ?>
