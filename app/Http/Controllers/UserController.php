<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\SendsApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use SendsApiResponse;
    /**
     * Get all users
     */
    public function getUsers(BaseRequest $request)
    {
        try {
            $users = User::orderBy('firstname')->get();
            $users = UserResource::collection($users);

            return $this->with($users)->success();
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->failure();
        }
    }
}
