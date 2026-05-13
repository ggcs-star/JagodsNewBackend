<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Enums\UserRole;
use App\Enums\UserApplied;
use Yajra\Datatables\Datatables;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\BackendController;

class UserController extends BackendController
{
    public function __construct()
    {
        $this->data['siteTitle'] = 'User';

        $this->middleware(['permission:user_create'])->only('create', 'store');
        $this->middleware(['permission:user_edit'])->only('edit', 'update');
        $this->middleware(['permission:user_delete'])->only('destroy');
        $this->middleware(['permission:user_show'])->only('show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.user.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['roles'] = Role::whereNotIn('id', [UserRole::CUSTOMER, UserRole::RESTAURANTOWNER, UserRole::WAITER, UserRole::DELIVERYBOY])->get();
        return view('admin.user.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user             = new User;
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;
        $user->username   = $request->username ?? generateUsername($request->email);
        $user->password   = Hash::make(request('password'));
        $user->phone      = $request->phone;
        $user->address    = $request->address;
        $user->status     = $request->status;
        $user->applied    = UserApplied::ADMIN;
        $user->save();

        if (request()->file('image')) {
            $user->addMedia(request()->file('image'))->toMediaCollection('user');
        }

        $role = Role::find($request->role);
        if (!blank($role)) {
            $user->assignRole($role->name);
        }

        return redirect(route('admin.user.index'))->withSuccess('The Data Inserted Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->data['user'] = User::with('media', 'roles')->findOrFail($id);
        return view('admin.user.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->data['roles'] = Role::whereNotIn('id', [UserRole::CUSTOMER, UserRole::RESTAURANTOWNER, UserRole::WAITER, UserRole::DELIVERYBOY])->get();
        $this->data['user']  = User::with('media', 'roles')->findOrFail($id);
        return view('admin.user.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::find($id);
        if (!blank($user)) {
            $user->first_name = $request->first_name;
            $user->last_name  = $request->last_name;
            $user->email      = $request->email;
            $user->username   = $request->username ?? generateUsername($request->email);
            if ($request->password) {
                $user->password = Hash::make(request('password'));
            }

            $user->phone   = $request->phone;
            $user->address = $request->address;
            $user->status  = $request->status;
            $user->save();

            if (request()->file('image')) {
                $user->media()->delete();
                $user->addMedia(request()->file('image'))->toMediaCollection('user');
            }
            $role = Role::find($request->role);
            if (!blank($role)) {
                $oldRole = $user->getrole;
                if($oldRole) {
                    $user->removeRole($oldRole->id);
                }
                $user->assignRole($role->name);
            }
            return redirect(route('admin.user.index'))->withSuccess('The Data Updated Successfully');
        } else {
            return redirect(route('admin.user.index'))->withError('The User Not Found');
        }
    }

    public function getUsers()
    {
        $users = User::with('roles')->whereHas('roles',function ($query) {
            $query->whereNotIn('id',[UserRole::CUSTOMER, UserRole::RESTAURANTOWNER, UserRole::WAITER, UserRole::DELIVERYBOY]);
        })->latest()->get();
        $i = 0;
        return Datatables::of($users)

            ->addColumn('action', function ($user) {
                return action_button([
                    'view'   => ['route' => route('admin.user.show', $user),'permission' => 'user_show'],
                    'edit'   => ['route' => route('admin.user.edit', $user),'permission' => 'user_edit'],
                ]);
            })
            ->addColumn('name', function ($user) {
                return $user->name;
            })
            ->addColumn('email', function ($user) {
                return $user->email;
            })
            ->addColumn('phone', function ($user) {
                return $user->phone;
            })

            ->addColumn('role', function ($user) {
              return  $user->getrole->name;
            })
            ->editColumn('id', function ($user) use (&$i) {
                return ++$i;
            })
            ->escapeColumns([])
            ->make(true);
    }


}
