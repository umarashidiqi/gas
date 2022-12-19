<?php

namespace App\Services;

use App\Repositories\PermissionRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionService
{
    private $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository ){
            $this->permissionRepository = $permissionRepository;
    }

    public function getPermission()
    {
        return $this->permissionRepository->getPermission();
    }

    public function getPermisionId($id) {
        return $this->permissionRepository->getPermisionId($id);
    }

    public function savePermissionData($data) {

        $validator = Validator::make($data, [
            'name' => 'required|string|unique:permissions',
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        } else {
            return $this->permissionRepository->save($data);
        }
    }

    public function updatePermissionData($data) {

        $validator = Validator::make($data, [
            'name' => ['required', 'string', Rule::unique('permissions')->ignore($data['id'])]
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        } else {
            return $this->permissionRepository->update($data);
        }
    }

    public function deletePermission($req){
        DB::beginTransaction();
        try {

            $id     = decrypt($req->id);
            $post   = $this->permissionRepository->delete($id);

        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());

            throw new InvalidArgumentException("Unable to delete permission");
        }

        DB::commit();

        return $post;
    }

}
