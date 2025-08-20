<?php

namespace App\Controllers;

use App\Models\PostsModel;

class Home extends BaseController
{
    public function index(): string
{
    $pm = new \App\Models\PostsModel();

    $featured = $pm->where('deleted_at', null)
                   ->where('status', 'active')
                   ->where('is_featured', 1)
                   ->orderBy('updated_at DESC, created_at DESC')
                   ->first();

    $builder = $pm->where('deleted_at', null)
                  ->where('status', 'active')
                  ->orderBy('updated_at DESC, created_at DESC');

    if ($featured) {
        $builder->where('postId !=', $featured['postId']);
    }

    $others = $builder->findAll(5);

    $posts = $featured ? array_merge([$featured], $others) : $others;

    return view('news/home', [
        'title'     => 'News',
        'posts'     => $posts,
        'bodyClass' => 'site-bg',
    ]);
}

}
