<?php

namespace App\Http\Controllers;

use App\Http\Requests\users\StoreUserRequest;
use App\Http\Requests\users\UpdateUserRequest;
use App\Traits\processImageTrait;
use App\Models\User;


class userController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use processImageTrait;

    public function index()
    {
        $users = User::get();
        if (!$users) {
            return response()->json([
                'message' => 'users not found'
            ], 404);
        }

            foreach ($users as $user) {
                $user->load('image:imageable_id,photo');
            }
            return response()->json([
                'message' => 'ok',
                'data' => $users
            ], 200);

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
        $request->validate([
            'photo' => '
             max:1
            |array',
        ]);

        $imageName = $this->photo($request,'users');
        $user = User::create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'product_id' => $request->product_id,
            ]
        )->image()->create([
           'photo' => $imageName[0]
        ])->save();

        if (!$user) {
            return response()->json([
                'message' => 'user failed stored'
            ], 404);
        }

        return response()->json([
            "message" => "ok",
            'data' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'user not found'
            ], 404);
        }
         $user->load('image:imageable_id,photo');

        return response()->json([
            "message" => "ok",
            'data' => $user
        ], 201);
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
        $imageName = $this->photo($request,'users');
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'user not found'
            ], 404);
        }
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'product_id' => $request->product_id
        ]);
        $user->image()->update([
            'photo' => $imageName[0]
        ]);

            return response()->json([
                "message" => "ok",
                'data' => $user
            ], 201);

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
        if (!$user) {
            return response()->json([
                'message' => 'user not found'
            ], 404);
        }
        $user->image()->delete();
        $user->delete();
        $user = User::find($id);
        if ($user) {
            return response()->json([
                'message' => 'deleted failed'
            ], 400);
        }
        return response()->json([
            'message' => 'user deleted successfully'
        ], 201);
    }
}
