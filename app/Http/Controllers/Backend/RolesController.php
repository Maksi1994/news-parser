<?php

namespace App\Http\Controllers\Backend;

use App\Http\Resources\Roles\RoleResource;
use App\Http\Resources\Roles\RolesCollection;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{

    public function save(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'exists:users',
            'name' => 'required|unique:roles'
        ]);
        $success = false;

        if (!$validation->fails()) {
            Role::updateOrCreate(
                ['id' => $request->id],
                [
                    'name' => $request->name
                ]);

            $success = true;
        }

        return $this->success($success);
    }


    public function getList(Request $request)
    {
        $roles = Role::get();

        return new RolesCollection($roles);
    }

    public function getOne(Request $request) {
        $role = Role::withCount('users')->find($request->id);

        return new RoleResource($role);
    }


}
