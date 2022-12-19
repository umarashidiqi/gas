<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\DataTableRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Services\PermissionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\RoleService;

class RoleController extends Controller
{
    private $roleService;
    private $permissionService;

    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->roleService          = $roleService;
        $this->permissionService    = $permissionService;
        $this->view                 = 'settings.role.';
    }

    public function index()
    {
        return view($this->view.'index')->with([
            'roles'         => $this->roleService->getRole(),
            'permissions'   => $this->permissionService->getPermission()
        ]);
    }

    public function store(Request $request)
    {
        $data   = $request->only(
            [
                'name',
                'permissions'
            ]
        );

        $result = ['status' => 200];
        try {
            $result['data'] = $this->roleService->saveRoleData($data);
        } catch (\Throwable $e) {
            $result         = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function edit($id)
    {
        return view($this->view.'edit')->with([
            'role'   => $this->roleService->getRoleId(decrypt($id))
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $data   = $request->only(
            [
                'name',
                'id',
                'permissions'
            ]
        );

        $result = ['status' => 200];
        try {
            $result['data'] = $this->roleService->updateRoleData($data);
        } catch (\Throwable $e) {
            $result         = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function destroy(Role $role)
    {
        if ($role->delete()) {
            return redirect()->back()->with('success', __(
                'role `:name` has been deleted', [
                    'name' => $role->name,
                ]
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t delete role',
        ));
    }

    public function detach(Role $role, Permission $permission)
    {
        if ($role->permissions()->detach([$permission->id])) {
            return redirect()->back()->with('success', __(
                'permission `:permission` has been detached from role `:role`', [
                    'permission' => $permission->name,
                    'role' => $role->name,
                ]
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t detach permission',
        ));
    }
}
