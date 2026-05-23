<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function attemptLogin()
    {
        $userModel = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $userModel->verifyLogin($username, $password);

        if ($user) {
            $sessionData = [
                'user_id'    => $user['id'],
                'username'   => $user['username'],
                'nama'       => $user['nama_lengkap'],
                'role'       => $user['role'],
                'sppg_id'    => $user['sppg_id'],
                'isLoggedIn' => true,
            ];

            session()->set($sessionData);

            // Update last login
            $userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);

            return redirect()->to('/dashboard')->with('success', 'Login berhasil!');
        }

        return redirect()->back()->with('error', 'Username atau password salah.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah logout.');
    }
}
