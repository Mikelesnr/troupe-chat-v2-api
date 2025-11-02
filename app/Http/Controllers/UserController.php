<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Return paginated users using UserResource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);

        $users = User::orderBy('name')->paginate($perPage);

        return UserResource::collection($users);
    }
}
