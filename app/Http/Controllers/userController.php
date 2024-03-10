<?php

namespace App\Http\Controllers;

use App\Http\Requests\users\StoreUserRequest;
use App\Http\Requests\users\UpdateUserRequest;
use App\Traits\HttpResponses;
use App\Traits\processImageTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class userController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use processImageTrait,HttpResponses;

    public function index()
    {
        $users = User::with('image:imageable_id,photo')->get();
        if (!$users) {
            return $this->responseError('users not found',404);
        }
        return $this->success($users,'ok');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(StoreUserRequest $request)
    {
        $imageName = $this->uploadPhoto($request, 'users');
        $success = false;
        $user = new User();

        DB::transaction(function () use ($request, $imageName, &$success, &$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);
                $user->image()->create([
                'photo' => $imageName[0]
            ]);

            if (!$user) {
                $success = false;
                return;
            }
            $success = true;
        });

        if ($success) {
            return $this->success($user, "ok");
        }
            return $this->responseError('user failed stored',400);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with('image:imageable_id,photo')->find($id);
        if (!$user) {
            return $this->responseError('user not found',404);
        }
         return $this->success($user,"ok");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // عرض نموذج تعديل المستخدم
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user=User::find($id);
        if (!$user) {
            return $this->responseError('user not found',404);
        }

        DB::transaction(function () use ($request,&$user) {
            $imageName = $this->updatePhoto($request,$user->image->photo,'users');
            $user->images()->delete();
            $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);
            $user->image()->update([
            'photo' => $imageName[0]
        ]);

        });
        $updatedUser = User::with('image:imageable_id,photo')->find($id);
        return $this->success($updatedUser,"ok");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $success = false;
        if (!$user) {
            return $this->responseError('user not found',404);
        }
        DB::transaction(function () use (&$user,$id,&$success) {
            $this->deletePhoto($user->image->photo);
            $user->image()->delete();
            $user->delete();
            $user = User::find($id);
            if (!$user) {
                $success = true;
                return;
            }
            $success = false;
     });
        if (!$success) {
          return $this->responseError('deleted failed',400);
        }
           return $this->responseSuccess('user deleted successfully');
    }
}
