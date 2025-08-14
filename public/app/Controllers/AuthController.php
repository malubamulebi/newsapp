<?php
namespace App\Controllers;

class AuthController extends BaseController
{
    public function login()
    {
        return view('auth/login', [
            'title'     => 'Sign In',
            'bodyClass' => 'site-bg'
        ]);
    }

    public function doLogin()
    {
        // TODO: plug real auth later
        return redirect()->to(site_url('/'));
    }
}
