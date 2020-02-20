<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;

use App\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('roles')->get();
        return view('admin.users.list',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User;
        $roles = Role::pluck('name','id');
        return view('admin.users.form',compact('user','roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        if(!User::create($request->all())){
            return redirect()->back()->withErrors(['error' => __('users.messages.user_saved_success')]);
        }
        return redirect()->route('admin.users.index')->with(['success' => __('users.messages.user_saved_success')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::pluck('name','id');
        return view('admin.users.form',compact('user','roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        if(!$user->update($request->all())){
            return redirect()->back()->withErrors(['error' => __('users.messages.user_updated_error')]);
        }

        if($user->id != 1) {
            $user->syncRoles($request->role);
        }

        return redirect()->route('admin.users.index')->with(['success' => __('users.messages.user_updated_success')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(!$user->delete()){
            return redirect()->back()->withErrors(['error' => __('users.messages.user_deleted_error')]);
        }
        return redirect()->route('admin.users.index')->with(['success' => __('users.messages.user_deleted_success')]);
    }
}
