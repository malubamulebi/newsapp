<?= $this->extend('layouts/main') ?>

<?php
  // create vs edit
  $isEdit = !empty($post) && !empty($post['postId']);
  $action = $isEdit ? site_url('update_post/'.$post['postId']) : site_url('create_post');
  $title  = $isEdit ? 'Edit Post' : 'Create a Post';
  $backTo = site_url('admin/posts');
?>

<?= $this->section('styles') ?>
<style>
  .form-card{max-width:820px;margin:32px auto;}
  .form-card .card-body{padding:1.5rem;}
  @media(min-width:768px){.form-card .card-body{padding:2rem 2.25rem}}
  .thumb{max-width:220px;border-radius:.5rem}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4 py-md-5">
  <div class="card shadow-sm border-0 rounded-4 form-card">
    <div class="card-header bg-white border-0 py-3 py-md-4 d-flex justify-content-between align-items-center">
      <h4 class="m-0"><?= esc($title) ?></h4>
      <a href="<?= $backTo ?>" class="btn btn-outline-secondary btn-sm">All Posts</a>
    </div>

    <div class="card-body">
      <form method="post" action="<?= $action ?>" enctype="multipart/form-data">
        <?= function_exists('csrf_field') ? csrf_field() : '' ?>

        <div class="row g-3">
          <div class="col-12 col-sm-6">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <?php
                $curStatus = $isEdit ? ($post['status'] ?? 'active') : 'active';
              ?>
              <option value="active"   <?= $curStatus==='active'?'selected':''; ?>>Active</option>
              <option value="archived" <?= $curStatus==='archived'?'selected':''; ?>>Archived</option>
            </select>
          </div>

          <div class="col-12 col-sm-6 d-flex align-items-end">
            <div class="form-check ms-sm-3">
              <?php $isFeat = $isEdit ? (int)($post['is_featured'] ?? 0) : 0; ?>
              <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1" <?= $isFeat ? 'checked' : '' ?>>
              <label class="form-check-label" for="featured">Featured headline</label>
            </div>
          </div>

          <div class="col-12">
            <label class="form-label">Headline</label>
            <input type="text" name="header" class="form-control" placeholder="Enter headline"
                   value="<?= $isEdit ? esc($post['header']) : '' ?>" required>
          </div>

          <div class="col-12">
            <label class="form-label">Body</label>
            <textarea name="body" class="form-control" rows="8" placeholder="Write your story..." required><?= $isEdit ? esc($post['body']) : '' ?></textarea>
          </div>

          <div class="col-12">
            <label class="form-label">Picture</label>
            <input type="file" name="picture" class="form-control">
            <div class="form-text">JPG, PNG, or WEBP. Optional. Uploading a new one replaces the old one.</div>

            <?php if ($isEdit && !empty($post['picture'])): ?>
              <div class="mt-2">
                <img class="thumb" src="<?= base_url('uploads/'.$post['picture']) ?>" alt="current image">
              </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
          <a href="<?= $backTo ?>" class="btn btn-outline-secondary">Cancel</a>
          <button type="submit" class="btn btn-brand"><?= $isEdit ? 'Update' : 'Publish' ?></button>
        </div>
      </form>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
