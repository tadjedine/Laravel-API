<?php

namespace App\Repositories;

use App\Models\User;

 class UserRepository{

    public function getActiveUsers() {
        return User::where('active', true)
                   ->whereNotNull('email_verified_at')
                   ->get();
    }

    public function create(array $data) {
        return User::create($data);
    }

 }

