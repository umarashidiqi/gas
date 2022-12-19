<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\PermissionService;

class PermissionController extends Controller
{
    private $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService    = $permissionService;
        $this->view                 = 'settings.permission.';
    }

    public function index()
    {
        return view($this->view.'index')->with([
            'permissions'   => Permission::orderBy('created_at', 'desc')->get()
        ]);
    }

    public function store(Request $request)
    {
        $data   = $request->only(
            [
                'name'
            ]
        );

        $result = ['status' => 200];
        try {
            $result['data'] = $this->permissionService->savePermissionData($data);
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
            'permission'   => $this->permissionService->getPermisionId(decrypt($id))
        ]);
    }

    public function update(Request $request)
    {
        dd($request->all());
        $data   = $request->only(
            [
                'name',
                'id'
            ]
        );

        $result = ['status' => 200];
        try {
            $result['data'] = $this->permissionService->updatePermissionData($data);
        } catch (\Throwable $e) {
            $result         = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function destroy(Request $req)
    {
        $result = ['status' => 200];
        try {
            $result['data'] = $this->permissionService->deletePermission($req);
        } catch (\Throwable $e) {
            $result         = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }
}
