<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository ){
            $this->userRepository = $userRepository;
    }

    public function getRole() {
        return $this->userRepository->getRole();
    }

    public function getPermision() {
        return $this->userRepository->getPermision();
    }

    public function getUserPaginate($request) {
        return $this->userRepository->getUserPaginate($request);
    }

    public function saveUserData($data) {

        $validator = Validator::make($data, [
            'name'                  => 'required|string',
            'username'              => 'required|string|unique:users',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|string|min:8',
            'password_confirmation' => 'required|same:password',
            'is_actived'            => 'integer',
            'profile_photo_path.*'  => 'images|mimes:png,jpg,jpeg,bmp',
            'roles.*'               => 'nullable|integer|exists:roles,id'
        ]);

        $data['password']           = Hash::make($data['password']);
        $avatar                     = time().'.'.$data['profile_photo_path']->extension();
        $data['profile_photo_path']->move(public_path('images/avatar'), $avatar);
        $data['profile_photo_path'] = $avatar;

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        } else {
            return $this->userRepository->save($data);
        }
    }

}
