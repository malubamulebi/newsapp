<?php
namespace App\Controllers;

use App\Models\PostsModel;
use App\Models\UsersModel;
use App\Models\AdminModel;

class AdminDashboardController extends BaseController
{
    public function index()
    {
        // simple gate – later replace with real auth
        if (!session('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }

        $posts = new PostsModel();
        $users = new UsersModel();
        $admins = new AdminModel();

        // stats
        $stats = [
            'totalPosts'     => $posts->where('deleted_at', null)->countAllResults(),
            'archivedPosts'  => $posts->onlyDeleted()->countAllResults(),
            'totalUsers'     => $users->countAllResults(),
            'totalAdmins'    => $admins->countAllResults(),
        ];

        // latest 10 active + archived
        $activePosts   = (new PostsModel())->where('deleted_at', null)->orderBy('updated_at DESC, created_at DESC')->findAll(10);
        $archivedPosts = (new PostsModel())->onlyDeleted()->orderBy('deleted_at DESC')->findAll(10);

        // latest 10 users (email, username, comment preview)
        $latestUsers = (new UsersModel())->orderBy('userId DESC')->findAll(10);

        return view('admin/dashboard', [
            'title'        => 'Dashboard',
            'bodyClass'    => 'site-bg',
            'stats'        => $stats,
            'activePosts'  => $activePosts,
            'archivedPosts'=> $archivedPosts,
            'latestUsers'  => $latestUsers,
        ]);
    }

    public function archivePost($id)
    {
        if (!session('isLoggedIn')) return redirect()->to(site_url('login'));
        $posts = new PostsModel();
        $posts->delete($id); // soft-delete (archive)
        return redirect()->to(site_url('admin'))->with('msg', 'Post archived.');
    }

    public function restorePost($id)
    {
        if (!session('isLoggedIn')) return redirect()->to(site_url('login'));
        $posts = new PostsModel();
        // restore by clearing deleted_at
        $posts->update($id, ['deleted_at' => null]);
        return redirect()->to(site_url('admin'))->with('msg', 'Post restored.');
    }
}
