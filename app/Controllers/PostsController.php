<?php

namespace App\Controllers;

use App\Models\PostsModel;

class PostsController extends BaseController
{
    protected $modelName = PostsModel::class;
    protected $format    = 'json';

    public function index()
    {
        $m = new PostsModel();

        $posts = $m->orderBy('updated_at DESC, created_at DESC')->findAll();

        return view('admin/posts_list', ['posts' => $posts]);
    }

    public function show($id = null)
    {
        $m = new PostsModel();
        $post = $m->find($id);
        if (!$post) {
            return $this->response->setJSON(['error' => 'Post not found']);
        }
        return $this->response->setJSON($post);
    }

    public function new()
    {
        return view('admin/add_post', [
            'title'     => 'Create Post',
            'bodyClass' => 'site-bg',
        ]);
    }

    public function create()
    {
        $m = new PostsModel();

        // picture (optional)
        $filename = null;
        $file = $this->request->getFile('picture');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $filename = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $filename);
        }

        // checkbox name can be 'is_featured' or 'featured'
        $isFeatured = $this->request->getPost('is_featured');
        if ($isFeatured === null) {
            $isFeatured = $this->request->getPost('featured');
        }
        $isFeatured = $isFeatured ? 1 : 0;

        if ($isFeatured) {
            // only one featured post at a time
            $m->where('is_featured', 1)->set('is_featured', 0)->update();
        }

        $data = [
            'header'      => trim((string)$this->request->getPost('header')),
            'body'        => trim((string)$this->request->getPost('body')),
            'picture'     => $filename,
            'status'      => $this->request->getPost('status') ?: 'active',
            'is_featured' => $isFeatured,
        ];

        if ($data['header'] === '' || $data['body'] === '') {
            return redirect()->back()->with('error', 'Headline and Body are required');
        }

        $m->insert($data);

        return redirect()->to(site_url('admin/posts'))->with('success', 'Post created!');
    }

    public function view($id)
    {
        $m = new PostsModel();
        $post = $m->find($id);
        if (!$post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Post not found');
        }

        return view('news/view', [
            'title' => $post['header'],
            'post'  => $post,
        ]);
    }

    public function update($id = null)
    {
        if ($id === null) {
            return redirect()->back()->with('error', 'No Post ID provided.');
        }

        $m = new PostsModel();
        $post = $m->find($id);
        if (!$post) {
            return redirect()->back()->with('error', 'Post not found.');
        }

        // picture (optional)
        $filename = $post['picture'] ?? null;
        $file = $this->request->getFile('picture');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $filename = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $filename);
        }

        // checkbox name can be 'is_featured' or 'featured'
        $isFeatured = $this->request->getPost('is_featured');
        if ($isFeatured === null) {
            $isFeatured = $this->request->getPost('featured');
        }
        $isFeatured = $isFeatured ? 1 : 0;

        $data = [
            'header'      => trim((string)$this->request->getPost('header')),
            'body'        => trim((string)$this->request->getPost('body')),
            'status'      => $this->request->getPost('status') ?: $post['status'],
            'is_featured' => $isFeatured,
            'picture'     => $filename,
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        if ($isFeatured) {
            $m->where('postId !=', $id)->set('is_featured', 0)->update();
        }

        $m->update($id, $data);

        return redirect()->to(site_url('admin/posts'))->with('success', 'Post updated successfully');
    }

    public function delete($id = null)
    {
        $m = new PostsModel();
        if (!$m->find($id)) {
            return 'Post not found!';
        }
        $m->delete($id); // SoftDeletes if enabled on the model
        return 'Post deleted successfully!';
    }
}
