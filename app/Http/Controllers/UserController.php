<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\HandlesImages;
use App\Traits\SendsApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use SendsApiResponse, HandlesImages;
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

    public function updateUser(UpdateUserRequest $request)
    {
        DB::beginTransaction(); // Start the transaction
        try {
            $user = $request->user();

            if ($request->has('firstname')) {
                $user->firstname = $request->firstname;
            }

            if ($request->has('lastname')) {
                $user->lastname = $request->lastname;
            }

            if ($request->has('password')) {
                if ($request->has('old_password')) {
                    if (Hash::check($request->old_password, $user->password)) {
                        $user->password = bcrypt($request->password);
                    } else {
                        return $this->failure(
                            ['old_password' => ['The old password does not match.']],
                            403
                        );
                    }
                } else {
                    return $this->failure('Old password and confirmation are required if password is provided.', 403);
                }
            }

            if ($request->has('image')) {
                if ($user->image) {
                    $this->deleteImage($user->image, '');
                }
                $image_name = $this->uploadImage($request->image, '/users');
                $user->image = $image_name;
            }

            $user->save();

            DB::commit();
            return $this->with($user)->success();
        } catch (\Throwable $th) {
            DB::rollBack();

            // If an image was uploaded but the operation failed, delete it
            if ($request->has('image') && isset($image_name)) {
                $this->deleteImage($image_name, '');
            }

            Log::error($th);

            return $this->failure();
        }
    }
}
