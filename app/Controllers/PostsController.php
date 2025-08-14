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
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $file = $this->request->getFile('picture');
        $picturePath = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $newName);
            $picturePath = 'uploads/' . $newName; // relative path
        }

        $newPost = new PostsModel();
        $newPostData = [
            'header'=> $this->request->getPost('header'), 
            'body'=> $this->request->getPost('body'), 
            'picture'=> $this->request->getPost('picture')
        ];
        $newPost -> insert($newPostData);
        return "Post created sucessfully!";
        //
        // Enforce max 6 active posts on the homepage (auto-archive oldest)
        $MAX = 6;
        $active = (new \App\Models\PostsModel())
            ->where('deleted_at', null)
            ->orderBy('updated_at DESC, created_at DESC')
            ->findAll();

        if (count($active) > $MAX) {
            // find anything beyond the top $MAX and archive them
            $toArchive = array_slice($active, $MAX);
            $pm = new \App\Models\PostsModel();
            foreach ($toArchive as $row) {
                $pm->delete($row['postId']); // soft delete
            }
        }

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
