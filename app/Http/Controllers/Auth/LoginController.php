<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Traits\SendsApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use SendsApiResponse;
    /**
     * User login
     */
    public function login(LoginRequest $request) {
    
        try {
            $email = $request->validated('email');
            $password = $request->validated('password');


            $attempt = auth()->once(['email' => $email, 'password' => $password]);

            
            // Invalid credentials
            if (!$attempt) {
                return $this->failure('Invalid Credentials', 401);
            }

            $user = $request->user();

            // Deactivated account
            if ($user->status == 0) {
                return $this->failure('Account is inactive. Contact your administrator.', 400);
            }

            DB::beginTransaction();

            // Generate token for user
            $token = $user->createToken('User Token - ' . time(), [], now()->addMonth())->plainTextToken;

            
            $user->save();

            DB::commit();

            return $this->with(['user' => new UserResource($user), 'token' => $token])->success();
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);

            return $this->failure();
        }
    }


    /**
     * User logout
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return $this->success();
        } catch (\Throwable $th) {
            Log::error($th);

            return $this->failure();
        }
    }
}
