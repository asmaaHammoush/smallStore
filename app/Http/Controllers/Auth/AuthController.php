<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\login\LoginUserRequest;
use App\Http\Requests\users\StoreUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use App\Traits\processImageTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    use HttpResponses,processImageTrait;

    public function register(StoreUserRequest $request){
        $imageName = $this->uploadPhoto($request, 'users');
        $user=new User();
        DB::transaction(function () use ($request, $imageName,&$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);
            $user->image()->create([
                'photo' => $imageName[0]
            ]);
        });
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of '. $user->name)->plainTextToken
        ]);

    }

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());
        $credentials = request(['email', 'password']);

        if (!$token = auth()->guard('web')->attempt($credentials)) {
            return $this->error('', 'Credentials do not match', 401);
        }
        $user= auth()->guard('api')->user();
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of '. $user->name)->plainTextToken
        ]);
    }


    public function logout(){
        Auth::user()->currentAccessToken()->delete();
        return $this->success([
            'message' => 'you have successfully been logged out and your token has been deleted'
        ]);
    }
}
