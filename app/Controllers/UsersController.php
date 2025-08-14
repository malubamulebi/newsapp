<?php

namespace App\Controllers;

use App\Models\UsersModel;

class UsersController extends BaseController
{
    protected $modelName = UsersModel::class;
    protected $format    = 'json';

    public function index()
    {
        $userModel = new \App\Models\UsersModel();
        return $this->response->setJSON($userModel->findAll());
    }

    public function show($userId = null)
    {
        $userModel = new \App\Models\UsersModel();
        $user = $userModel->find($userId);
        if (!$user) return $this->response->setJSON(["error" => "User not found"]);
        return $this->response->setJSON($user);
    }

    public function new()
    {
        //
    }

    public function create()
    {
        $user = new UsersModel();
        $data = [
            'email'    => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'comment'  => $this->request->getPost('comment')
        ];
        $user->insert($data);
        return "User created successfully!";
    }

    public function update($userId = null)
    {
        if ($userId === null) {
            return $this->response->setJSON(["error" => "No User ID provided!"]);
        }

        $userModel = new UsersModel();

        if (!$userModel->find($userId)) {
            return $this->response->setJSON(["error" => "User not found"]);
        }

        $data = [
            'email'      => $this->request->getPost('email'),
            'username'   => $this->request->getPost('username'),
            'comment'    => $this->request->getPost('comment'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $data = array_filter($data, fn($v) => $v !== null);

        if (empty($data)) {
            return $this->response->setJSON(["error" => "No data provided to update User"]);
        }

        $userModel->update($userId, $data);

        return $this->response->setJSON(["message" => "User updated successfully"]);
    }

    public function delete($userId = null)
    {
        $userModel = new UsersModel();
        if (!$userModel->find($userId)) {
            return "User not found!";
        }
        $userModel->delete($userId);
        return "User deleted successfully!";
    }
}
