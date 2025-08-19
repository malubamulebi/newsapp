<?php
namespace App\Controllers;

use App\Models\PostsModel;

class AdminUiController extends BaseController
{
    public function newPost()
    {
        return view('admin/post_form', [
            'title'     => 'New Post',
            'post'      => null,
            'bodyClass' => 'site-bg',
        ]);    }

    public function editPost($id)
    {
        $post = (new PostsModel())->find($id);
        if (!$post) {
            return redirect()->to(site_url('admin/posts'))->with('error','Post not found');
        }

        // same view -> edit mode
        return view('admin/post_form', [
            'title'     => 'Edit Post',
            'post'      => $post,
            'bodyClass' => 'site-bg',
        ]);
    }
}
