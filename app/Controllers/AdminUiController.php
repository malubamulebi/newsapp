<?php
namespace App\Controllers;

class AdminUiController extends BaseController
{
    public function newPost()
    {
        return view('admin/add_post', [
            'title'     => 'Add Post',
            'bodyClass' => 'site-bg'
        ]);
    }
}
