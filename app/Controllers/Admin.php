<?php

namespace App\Controllers;
use App\Models\PostModel;

class Admin extends BaseController
{
    public function index()
    {
        return view('admin/dashboard');
    }

    public function createPost()
    {
        return view('admin/post_form');
    }

    public function savePost()
    {
        $postModel = new PostModel();

        $data = [
            'title' => $this->request->getPost('title'),
            'body'  => $this->request->getPost('body')
        ];

        // Handle image upload
        $img = $this->request->getFile('image');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $newName = $img->getRandomName();
            $img->move('uploads', $newName);
            $data['image'] = $newName;
        }

        $postModel->save($data);

        return redirect()->to(base_url('admin'));
    }
}
