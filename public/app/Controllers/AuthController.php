<?php
namespace App\Controllers;

class AuthController extends BaseController
{
    public function login()
    {
        return view('auth/login', [
            'title'     => 'Sign In',
            'bodyClass' => 'site-bg'
        ]);
    }

    public function doLogin()
    {
        // TODO: plug real auth later
        return redirect()->to(site_url('/'));
    }

    public function view($id)
    {
    $post = (new \App\Models\PostsModel())
        ->where('deleted_at', null)
        ->find($id);

    if (!$post) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Post not found');
    }

    return view('news/post', ['title' => $post['header'], 'post' => $post]);
    }

}
