<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class RoleRepository
{
    public function getRole(){
        $role = Role::orderBy('created_at', 'desc')->get();
        return $role;
    }

    public function getRoleId($id){
        $role = Role::find($id);
        return $role;
    }

    public function save($data) {
        DB::beginTransaction();
        try {
            $role = Role::create([
                'name'          => $data['name'],
                'guard_name'    => 'web',
            ]);

            if ($role) {
                $role->permissions()->sync($data['permissions'], []);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function update($data) {
        $role = new Permission();

        if ($role->update([ 'name' => $data['name'] ])) {
            $role->permissions()->sync($data['permissions']);
        }
    }
}
