<?php

namespace App\Controllers;

use App\Models\AdminModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class AdminController extends BaseController
{
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $adminModel = new \App\Models\AdminModel();
        return $this->response->setJSON($adminModel->findAll());
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $adminId
     *
     * @return ResponseInterface
     */
    public function show($adminId = null)
    {
        $adminModel = new \App\Models\AdminModel();
        $admin = $adminModel->find($adminId);
        if (!$admin) return $this->response->setJSON(["error" => "Admin not found"]);
        return $this->response->setJSON($admin);
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
        $newAdmin = new AdminModel();
        $newAdminData = [
            'email'=> $this->request->getPost('email'), 
            'password'=> $this->request->getPost('password'), 
        ];
        $newAdmin -> insert($newAdminData);
        return $this->response->setJSON(['message' => 'Admin created successfully!']);
        //
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $adminId
     *
     * @return ResponseInterface
     */
    public function edit($adminId = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $adminId
     *
     * @return ResponseInterface
     */
   public function update($adminId = null)
{
    if ($adminId === null) {
        return $this->response->setJSON(["error" => "No Admin ID provided!"]);
    }

    $adminModel = new AdminModel();

    // Check if admin exists
    if (!$adminModel->find($adminId)) {
        return $this->response->setJSON(["error" => "Admin not found"]);
    }

    $data = [
        'email'      => $this->request->getPost('email'),
        'password'   => $this->request->getPost('password') 
            ? password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
            : null,
        'updated_at' => date('Y-m-d H:i:s')
    ];

    // Remove null values so only provided fields get updated
    $data = array_filter($data, fn($v) => $v !== null);

    if (empty($data)) {
        return $this->response->setJSON(["error" => "No data provided to update Admin"]);
    }

    $adminModel->update($adminId, $data);

    return $this->response->setJSON(["message" => "Admin updated successfully"]);
}


    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $adminId
     *
     * @return ResponseInterface
     */
    public function delete($adminId = null)
    {
        $adminModel = new AdminModel();

        if (!$adminModel->find($adminId)) {
            return $this->response->setJSON(["error" => "Admin not found"]);
        }

        $adminModel->delete($adminId); // Soft delete if model has soft deletes enabled

        return $this->response->setJSON(["message" => "Admin deleted successfully"]);
    }

}
