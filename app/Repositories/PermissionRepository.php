<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;

class PermissionRepository
{
    public function getRole(){
        $role = Role::get();
        return $role;
    }

    public function getPermission(){
        $permision = Permission::orderBy('created_at', 'desc')->get();
        return $permision;
    }

    public function getPermisionId($id){
        $permision = Permission::find($id);
        return $permision;
    }

    public function save($data) {
        return Permission::create([ 'name' => $data['name'], 'guard_name' => 'web' ]);
    }

    public function update($data) {
        $permision = new Permission();

        return $permision->update(['name' => $data['name']]);
    }

    public function delete($id) {

        $permision = Permission::find($id);
        $permision->delete();

        return $permision;

    }
}
