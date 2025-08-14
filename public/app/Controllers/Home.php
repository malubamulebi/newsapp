<?php
namespace App\Controllers;

use App\Models\PostsModel;

class Home extends BaseController
{
    public function index(): string
    {
        $posts = (new PostsModel())->orderBy('created_at','DESC')->findAll(12);
        return view('news/home', [
            'title'     => 'News',
            'posts'     => $posts,
            'bodyClass' => 'site-bg'
        ]);
    }
}
