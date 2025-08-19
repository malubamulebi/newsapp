<?php
namespace App\Controllers;

use App\Models\PostsModel;
use App\Models\UsersModel;
use App\Models\AdminModel;

class AdminDashboardController extends BaseController
{
    public function index()
    {
        $pm = new PostsModel();

        // Counts
        $total      = $pm->withDeleted()->countAllResults(); // includes archived (soft-deleted)
        $active     = (new PostsModel())->where('deleted_at', null)->where('status', 'active')->countAllResults();
        $archived   = (new PostsModel())->onlyDeleted()->countAllResults();
        $featured   = (new PostsModel())->where('deleted_at', null)->where('is_featured', 1)->countAllResults();

        // Posts by status (for Pie/Bar)
        $byStatus = [
            'active'   => $active,
            'archived' => $archived,
            'featured' => $featured,
        ];

        // Posts per day (last 7 days) for Line chart
        $db = db_connect();
        $rows = $db->query("
            SELECT DATE(created_at) d, COUNT(*) c
            FROM posts
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
            GROUP BY DATE(created_at)
            ORDER BY d
        ")->getResultArray();

        // Fill missing days with 0
        $labels = [];
        $values = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = date('Y-m-d', strtotime("-$i day"));
            $labels[] = date('M j', strtotime($day));
            $found = array_values(array_filter($rows, fn($r) => $r['d'] === $day));
            $values[] = $found ? (int)$found[0]['c'] : 0;
        }

        return view('admin/dashboard', [
            'title'     => 'Admin Dashboard',
            'bodyClass' => 'site-bg',

            // widgets
            'total'     => $total,
            'active'    => $active,
            'archived'  => $archived,
            'featured'  => $featured,

            // charts
            'byStatus'  => $byStatus,
            'labels'    => $labels,
            'values'    => $values,
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