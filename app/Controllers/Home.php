<?php

namespace App\Controllers;
use App\Models\PostsModel;

class Home extends BaseController
{
    public function index(): string
    {
        $posts = (new PostsModel())->where('deleted_at', null)
          ->orderBy('updated_at DESC, created_at DESC')
          ->findAll(6);
        return view('news/home', [
            'title'     => 'News',
            'posts'     => $posts,
            'bodyClass' => 'site-bg'
        ]);
    }
}
