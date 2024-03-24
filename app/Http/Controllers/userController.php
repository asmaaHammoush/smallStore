<?php

namespace App\Http\Controllers;

use App\Filters\UserFilter;
use App\Http\Requests\filters\FilterRequest;
use App\Http\Requests\users\StoreUserRequest;
use App\Http\Requests\users\UpdateUserRequest;
use App\Traits\HttpResponses;
use App\Traits\processImageTrait;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;


class userController extends Controller
{
    private $userFilter;

    public function __construct(UserFilter $userFilter)
    {
        $this->userFilter = $userFilter;
    }

    use processImageTrait,HttpResponses;

    public function index(FilterRequest $request)
    {
        throw_if(!$this->authorize('viewAny',User::class),new AuthorizationException);
        $users = User::with('image:imageable_id,photo')->get();
        $users=$this->userFilter->applyFiltersUser($users, $request->all());
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
        throw_if(!$this->authorize('create',User::class),new AuthorizationException);
        $imageName = $this->uploadPhoto($request, 'users');
        $user = new User();
        DB::transaction(function () use ($request, $imageName, &$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ])->image()->create([
                'photo' => $imageName[0]
            ]);
            if (!$user) {
                return $this->responseError('user failed stored',400);
            }
        });
        return $this->success($user, "ok");

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        throw_if(!$this->authorize('view',$user),new AuthorizationException);
        $user->load('image:imageable_id,photo');
        return $this->success($user, "ok");
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
    public function update(UpdateUserRequest $request, User $user)
    {
        throw_if(!$this->authorize('update',$user),new AuthorizationException);

        DB::transaction(function () use ($request,$user) {
            $imageName=null;
            if ($user->image()->exists()) {
                $imageName = $this->updatePhoto($request, $user->image->photo, 'users');
                $user->image()->delete();
            }
            $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);
            $user->image()->update([
            'photo' => $imageName[0]
        ]);

        });
        return $this->success($user,"ok");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        throw_if(!$this->authorize('delete',$user),new AuthorizationException);
        DB::transaction(function () use ($user) {
            if ($user->image()->exists()) {
                $this->deletePhoto($user->image->photo);
                $user->image()->delete();
            }
            $user->delete();
        });
        if (!$user) {
          return $this->responseError('deleted failed',400);
        }
           return $this->responseSuccess('user deleted successfully');
    }
}
