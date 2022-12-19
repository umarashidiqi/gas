<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserRepository
{
    public function getRole(){
        $role = Role::get();
        return $role;
    }

    public function getPermision(){
        $permision = Permission::get();
        return $permision;
    }

    public function save($data) {
        if ($user = User::create($data)) {
            $user->roles()->sync($data['roles'], []);
            $user->email_verified_at = now();
            $user->save();
        }
    }
}
