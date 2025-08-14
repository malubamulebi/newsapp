<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-xl-6">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white">
          <h4 class="m-0">Create a Post</h4>
        </div>
        <div class="card-body p-4 p-md-5">
        <form method="post" action="<?= site_url('create_post') ?>" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Headline</label>
            <input type="text" name="header" class="form-control form-control-lg" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Body</label>
            <textarea name="body" rows="6" class="form-control" required></textarea>
          </div>

          <div class="mb-4">
            <label class="form-label">Picture</label>
            <input type="file" name="picture" class="form-control">
          </div>

          <div class="d-flex gap-2">
            <button class="btn btn-brand btn-lg">Publish</button>
            <a href="<?= site_url('/') ?>" class="btn btn-outline-secondary btn-lg">Cancel</a>
          </div>
        </form>

        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
