<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\RoleRequest;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.list',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = new Role;
        $permissions = Permission::pluck('name','id');
        return view('admin.roles.form',compact('role','permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {

        // if(Role::where('name',$request->name)->first())
        // {
        //     return redirect()->back()->withErrors(['error' => __('roles.messages.role_exists_error',['role' => $request->name])]);
        // }


        $role = Role::create($request->except('permissions'));


        if(!$role){
            return redirect()->back()->withErrors(['error' => __('roles.messages.role_saved_success')]);
        }
        $role->syncPermissions($request->permissions);

        return redirect()->route('admin.roles.index')->with(['success' => __('roles.messages.role_saved_success')]);


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
    public function edit(Role $role)
    {
        $permissions = Permission::pluck('name','id');
        return view('admin.roles.form',compact('role','permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, Role $role)
    {

        if(!$role->update($request->except('permissions'))){
            return redirect()->back()->withErrors(['error' => __('roles.messages.role_updated_error')]);
        }


        $role->syncPermissions($request->permissions);
        return redirect()->route('admin.roles.index')->with(['success' => __('roles.messages.role_updated_success')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if(!$role->delete()){
            return redirect()->back()->withErrors(['error' => __('roles.messages.role_deleted_error')]);
        }
        return redirect()->route('admin.roles.index')->with(['success' => __('roles.messages.role_deleted_success')]);
    }

    public function createpermission(Request $request) {
        if(!$request->ajax()) {
            return abort(404);
        }
        $permission = Permission::create($request->all());

        return response()->json(['data' => ['id' => $permission->id,'name' => $permission->name]], 200);
    }
}
