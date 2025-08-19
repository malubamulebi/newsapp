<?php

namespace App\Controllers;
use App\Models\PostsModel;

class Home extends BaseController
{
public function index(): string
{
    $pm = new PostsModel();

    // 1) Try to get the featured (headline) post
    $featured = $pm->where('deleted_at', null)
                   ->where('status', 'active')
                   ->where('is_featured', 1)
                   ->orderBy('updated_at DESC, created_at DESC')
                   ->first();

    // 2) Get the next 5 latest active, excluding featured if it exists
    $builder = $pm->where('deleted_at', null)
                  ->where('status', 'active')
                  ->orderBy('updated_at DESC, created_at DESC');

    if ($featured) {
        $builder->where('postId !=', $featured['postId']);
    }

    $others = $builder->findAll(5);

    // 3) Build the list the view expects: first item is the big headline
    $posts = $featured ? array_merge([$featured], $others) : $others;

    return view('news/home', [
        'title'     => 'News',
        'posts'     => $posts,
        'bodyClass' => 'site-bg'
    ]);
}
}
