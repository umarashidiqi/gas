<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->view = 'user.';
    }

    public function index()
    {
        $users  = User::orderBy('name', 'asc')
                        ->with(['permissions', 'roles'])->get();

        return view($this->view.'index')->with([
            'roles'         => Role::get(),
            'permissions'   => Permission::get(),
            'users'         => $users
        ]);
    }

    public function store(Request $request)
    {
        $data   = $request->only(
            [
                'name',
                'username',
                'email',
                'password',
                'password_confirmation',
                'roles',
                'is_actived',
                'profile_photo_path'
            ]
        );

        $result = ['status' => 200];
        try {
            $result['data'] = $this->userService->saveUserData($data);
        } catch (\Throwable $e) {
            $result         = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'username' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'password_confirmation' => ['nullable', 'same:password'],
            'roles.*' => ['nullable', 'integer', 'exists:roles,id'],
            'permissions.*' => ['nullable', 'integer', 'exists:permissions,id'],
        ]);

        if ($user->update($request->only(['name', 'username', 'email']))) {
            $user->permissions()->sync($request->input('permissions', []));
            $user->roles()->sync($request->input('roles', []));

            if ($password = $request->input('password')) {
                $user->update([
                    'password' => Hash::make($password),
                ]);
            }

            return redirect()->back()->with('success', __(
                'user `:name` has been updated', [
                    'name' => $user->name,
                ],
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t update user'
        ));
    }

    public function destroy(User $user)
    {
        if ($user->delete()) {
            return redirect()->back()->with('success', __(
                'user `:name` has been deleted', [
                    'name' => $user->name,
                ],
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t delete user'
        ));
    }

    /**
     * @param \App\Models\User $user
     * @param \App\Models\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function detachPermission(User $user, Permission $permission)
    {
        if ($user->permissions()->detach([$permission->id])) {
            return redirect()->back()->with('success', __(
                'permission `:permission from user `:user` has been detached`', [
                    'permission' => $permission->name,
                    'user' => $user->name,
                ]
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t detach permission `:permission` from user `:user`', [
                'permission' => $permission->name,
                'user' => $user->name,
            ]
        ));
    }

    /**
     * @param \App\Models\User $user
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function detachRole(User $user, Role $role)
    {
        if ($user->roles()->detach([$role->id])) {
            return redirect()->back()->with('success', __(
                'role `:role from user `:user` has been detached`', [
                    'role' => $role->name,
                    'user' => $user->name,
                ]
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t detach role `:role` from user `:user`', [
                'role' => $role->name,
                'user' => $user->name,
            ]
        ));
    }
}
