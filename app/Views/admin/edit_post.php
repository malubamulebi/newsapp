<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-xl-6">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white"><h4 class="m-0">Edit Post</h4></div>
        <div class="card-body p-4 p-md-5">

          <form method="post" action="<?= site_url('update_post/'.$post['postId']) ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="mb-3">
              <label class="form-label">Status</label>
              <select name="status" class="form-select">
                <option value="active"   <?= ($post['status'] ?? 'active')==='active'?'selected':''; ?>>Active</option>
                <option value="archived" <?= ($post['status'] ?? '')==='archived'?'selected':''; ?>>Archived</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Headline</label>
              <input type="text" name="header" class="form-control form-control-lg" required
                     value="<?= esc($post['header']) ?>">
            </div>

            <div class="mb-3">
              <label class="form-label">Body</label>
              <textarea name="body" rows="6" class="form-control" required><?= esc($post['body']) ?></textarea>
            </div>

            <div class="mb-3 form-check">
              <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1"
                     <?= !empty($post['is_featured'])?'checked':''; ?>>
              <label class="form-check-label" for="is_featured">Make this the featured headline</label>
            </div>

            <div class="mb-4">
              <label class="form-label">Picture</label>
              <?php if (!empty($post['picture'])): ?>
                <div class="mb-2">
                  <img src="<?= base_url('uploads/'.$post['picture']) ?>" class="img-fluid rounded" style="max-height:160px">
                </div>
              <?php endif; ?>
              <input type="file" name="picture" class="form-control" accept="image/*">
              <small class="text-muted">Leave empty to keep current image.</small>
            </div>

            <div class="d-flex gap-2">
              <button class="btn btn-brand btn-lg">Save Changes</button>
              <a href="<?= site_url('admin/posts') ?>" class="btn btn-outline-secondary btn-lg">Cancel</a>
            </div>
          </form>

          <div class="text-muted small mt-3">
            Created: <?= esc($post['created_at'] ?? '-') ?> |
            Last modified: <?= esc($post['updated_at'] ?? '-') ?>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
