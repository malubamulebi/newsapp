<?php
namespace App\Controllers;

class AuthController extends BaseController
{
    public function login()
    {
        return view('auth/login', [
            'title'     => 'Sign In',
            'bodyClass' => 'site-bg',
        ]);
    }

    public function doLogin()
    {
        // TODO: add real auth. For now, mark logged in and go home.
        session()->set(['isLoggedIn' => true]);
        return redirect()->to(site_url('/'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'));
    }
}
