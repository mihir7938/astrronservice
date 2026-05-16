<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Services\UploadImageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private $role_id = Role::USER_ROLE_ID;
    private $imageService;

    public function __construct(
        UploadImageService $imageService
    )
    {
        $this->imageService = $imageService;
    }

    public function create($request)
    {
        return DB::transaction(function () use ($request) {
            $user = new User();
            if($request->type) {
                $user->role_id = $request->type;
            } else {
                $user->role_id = $this->role_id;
            }
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->phone = $request->phone;
            $user->save();
            return $user;
        });
    }
    public function getUserById($id)
    {
        return User::find($id);
    }
    public function update($user, $data)
    {
        return $user->update($data);
    }
    public function delete($user)
    {
        return $user->delete($user);
    }
    public function getAllUsers($per_page = -1)
    {
        if($per_page == -1){
            return User::orderBy('created_at', 'desc')->get();    
        }
        return User::orderBy('created_at', 'desc')->paginate($per_page);
    }
    public function getUsersByFilter($request)
    {
        $filter_query = User::orderBy('created_at','desc');
        if($request->has('status') && $request->status != ''){
            $filter_query = $filter_query->where('status', $request->status);
        }
        return $filter_query->select('*')->get();
    }
}
