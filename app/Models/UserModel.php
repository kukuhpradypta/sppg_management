<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'username', 'email', 'password', 'nama_lengkap',
        'role', 'sppg_id', 'is_active', 'last_login',
    ];

    protected $validationRules = [
        'username'     => 'required|min_length[3]|max_length[50]',
        'email'        => 'required|valid_email|max_length[100]',
        'password'     => 'required|min_length[6]',
        'nama_lengkap' => 'required|max_length[100]',
        'role'         => 'required|in_list[admin,sppg]',
    ];

    public function verifyLogin($username, $password)
    {
        $user = $this->where('username', $username)
                     ->where('is_active', 1)
                     ->first();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
}
