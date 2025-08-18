<h2>Create New Post</h2>

<form action="<?= base_url('admin/savePost') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <label for="title">Title:</label>
    <input type="text" name="title" id="title" required>

    <label for="body">Content:</label>
    <textarea name="body" id="body" rows="5" required></textarea>

    <label for="image">Upload Image:</label>
    <input type="file" name="image" id="image">

    <button type="submit">Save Post</button>

    <div class="mb-3">
    <label for="status" class="form-label">Status</label>
    <select name="status" id="status" class="form-control">
        <option value="draft">Draft</option>
        <option value="published">Published</option>
    </select>
    </div>

</form>
