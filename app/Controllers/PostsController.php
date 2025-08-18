<?php

namespace App\Controllers;

// use CodeIgniter\HTTP\ResponseInterface;
// use CodeIgniter\RESTful\ResourceController;
use App\Models\PostsModel;
use CodeIgniter\RESTful\ResourceController;

class PostsController extends BaseController
{
    protected $modelName = PostsModel::class;
    protected $format    = 'json';
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
   
    public function index()
    {
        $postModel = new \App\Models\PostsModel();
        return $this->response->setJSON($postModel->findAll());
    }


    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */


    public function show($id = null)
    {
        $postModel = new \App\Models\PostsModel();
        $post = $postModel->find($id);
        if (!$post) return $this->response->setJSON(["error" => "Post not found"]);
        return $this->response->setJSON($post);
    }


    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        //
            return view('admin/add_post', [
            'title'     => 'Create Post',
            'bodyClass' => 'site-bg'
        ]);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
        public function create()
        {
            $postModel = new \App\Models\PostsModel();

            // handle file
            $file = $this->request->getFile('picture');
            $filename = null;
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $filename = $file->getRandomName();
                $file->move(FCPATH . 'uploads', $filename); // public/uploads
            }

            $data = [
                'header'   => $this->request->getPost('header'),
                'body'     => $this->request->getPost('body'),
                'picture'  => $filename,                             // null if none
                'status'   => $this->request->getPost('status') ?? 'active',
            ];

            // basic required check (avoid "header cannot be null")
            if (empty($data['header']) || empty($data['body'])) {
                return redirect()->back()->with('error', 'Headline and Body are required');
            }

            $postModel->insert($data);

            // success
            return redirect()->to(site_url('/'))->with('success', 'Post created!');
        }


        public function view($id)
        {
            $postModel = new \App\Models\PostsModel();
            $post = $postModel->find($id);

            if (!$post) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Post not found");
            }

            return view('news/view', [
                'title' => $post['header'],
                'post' => $post
            ]);
        }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    
    // public function update($id = null)
    // {
    //     $newPost = new PostsModel();
    //     $postId = $newPost->find($id);
    //     $newPostData = [
    //         'header'=> $this->request->getPost('header'), 
    //         'body'=> $this->request->getPost('body'), 
    //         'picture'=> $this->request->getPost('picture')
    //     ];
    //     //
    //     $newPost -> update($postID=null, $newPostData);
    //     return "Post Updated!";
    // }
    public function update($id = null)
    {
        if ($id === null) {
            return $this->response->setJSON(["error" => "No Post ID provided!"]);
        }

        $postModel = new PostsModel();

        if (!$postModel->find($id)) {
            return $this->response->setJSON(["error" => "Post not found"]);
        }

        $data = [
            'header' => $this->request->getPost('header'),
            'body'   => $this->request->getPost('body'),
            'picture'=> $this->request->getPost('picture'),
            'status' => $this->request->getPost('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $data = array_filter($data, fn($v) => $v !== null);

        if (empty($data)) {
            return $this->response->setJSON(["error" => "No data provided to update"]);
        }

        $postModel->update($id, $data);

        return $this->response->setJSON(["message" => "Post updated successfully"]);
    }


    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */

    public function delete($id = null)
    {
        $postModel = new PostsModel();
        // Check if post exists
        if (!$postModel->find($id)) {
            return "Post not found!";
        }
        // Soft delete
        $postModel->delete($id);

        return "Post deleted successfully!";
       
    }
    
    
}
