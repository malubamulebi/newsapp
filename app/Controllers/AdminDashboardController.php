<?php
namespace App\Controllers;

use App\Models\PostsModel;
use App\Models\UsersModel;
use App\Models\AdminModel;

class AdminDashboardController extends BaseController
{

    public function archivePost($id)
    {
        $pm = new \App\Models\PostsModel();
        if (! $pm->find($id)) {
            return redirect()->to(site_url('admin/posts'))->with('error', 'Post not found');
        }
        $pm->update($id, ['status' => 'archived', 'is_featured' => 0]);
        return redirect()->to(site_url('admin/posts'))->with('success', 'Post archived');
    }

    public function restorePost($id)
    {
        $pm = new \App\Models\PostsModel();
        if (! $pm->find($id)) {
            return redirect()->to(site_url('admin/posts'))->with('error', 'Post not found');
        }
        $pm->update($id, ['status' => 'active']);
        return redirect()->to(site_url('admin/posts'))->with('success', 'Post restored');
    }


    public function index()
    {
        $pm = new \App\Models\PostsModel();

        $total    = $pm->where('deleted_at', null)->countAllResults(false);

        // group-by status
        $rows = $pm->select('status, COUNT(*) as c')
                ->where('deleted_at', null)
                ->groupBy('status')
                ->findAll();

        $byStatus = ['active' => 0, 'archived' => 0, 'featured' => 0];
        foreach ($rows as $r) {
            $key = strtolower(trim($r['status']));
            if (isset($byStatus[$key])) {
                $byStatus[$key] = (int)$r['c'];
            }
        }

        // featured count (independent of archived/active if you want; usually only active)
        $featured = (int)$pm->where('deleted_at', null)->where('is_featured', 1)->countAllResults();

        // last 7 days for line chart
        $labels = [];
        $values = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('M d', strtotime($d));
            $values[] = (int)$pm->where('deleted_at', null)
                                ->where("DATE(created_at)", $d)
                                ->countAllResults();
        }

        return view('admin/dashboard', [
            'title'     => 'Admin Dashboard',
            'total'     => (int)$total,
            'active'    => (int)$byStatus['active'],
            'archived'  => (int)$byStatus['archived'],
            'featured'  => (int)$featured,
            'byStatus'  => $byStatus,
            'labels'    => $labels,
            'values'    => $values,
            'generatedBy' => session('user')['name'] ?? session('user')['email'] ?? 'Admin',
            'bodyClass' => 'site-bg',
        ]);
    }



    public function posts()
    {
        $q     = trim((string)$this->request->getGet('q'));
        $from  = $this->request->getGet('from');  // YYYY-MM-DD
        $to    = $this->request->getGet('to');    // YYYY-MM-DD

        $model   = new \App\Models\PostsModel();
        $builder = $model->where('deleted_at', null);

        if ($q !== '') {
            $builder->groupStart()
                    ->like('header', $q)
                    ->orLike('body', $q)
                    ->groupEnd();
        }
        if (!empty($from)) {
            $builder->where('DATE(created_at) >=', $from);
        }
        if (!empty($to)) {
            $builder->where('DATE(created_at) <=', $to);
        }

        $posts = $builder->orderBy('created_at', 'DESC')->paginate(10);
        $pager = $model->pager;

        return view('admin/posts_list', [
            'title'     => 'All Posts',
            'bodyClass' => 'site-bg',
            'posts'     => $posts,
            'pager'     => $pager,
            'q'         => $q,
            'from'      => $from,
            'to'        => $to,
        ]);
    }



}