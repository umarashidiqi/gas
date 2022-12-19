<?php

namespace App\Services;

use App\Repositories\RoleRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Validation\Rule;

class RoleService
{
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository ){
            $this->roleRepository = $roleRepository;
    }

    public function getRole(){
        return $this->roleRepository->getRole();
    }

    public function getRoleId($id) {
        return $this->roleRepository->getRoleId($id);
    }

    public function saveRoleData($data) {

        $validator = Validator::make($data, [
            'name'          => 'required|string|unique:roles',
            'permissions.*' => 'nullable|integer|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        } else {
            return $this->roleRepository->save($data);
        }
    }

    public function updateRoleData($data) {

        $validator = Validator::make($data, [
            'name'          => ['required', 'string', Rule::unique('roles')->ignore($data['id'])],
            'permissions.*' => 'nullable|integer|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        } else {
            return $this->roleRepository->update($data);
        }
    }

}
