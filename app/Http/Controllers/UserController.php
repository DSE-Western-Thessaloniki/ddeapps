<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = User::all();

        return view('admin.user.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'min:6', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = new User([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'username' => $request->get('username'),
            'password' => Hash::make($request->get('password')),
            'active' => 1,
            'updated_by' => Auth::user()->id,
        ]);

        $user->save();

        $userRole = Role::all()->where('name', 'User');
        $user->roles()->attach($userRole);

        return redirect(route('admin.user.show', [$user]))
            ->with('status', 'User saved!');
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show(User $user)
    {
        return view('admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'username' => ['required', 'string', 'min:6', 'max:255', Rule::unique('users')->ignore($user)],
        ]);

        $user->username = $request->get('username');
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->active = $request->get('active') == 1 ? 1 : 0;

        // Update roles
        $roles = DB::table('roles')->get();
        $new_roles = [];
        foreach ($roles as $role) {
            $check = $request->get($role->name);
            if ($check == 1) {
                array_push($new_roles, $role->id);
            }
        }
        $user->roles()->sync($new_roles);

        $user->save();

        return redirect(route('admin.user.index'))->with('status', 'User updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect(route('admin.user.index'))->with('status', 'User deleted!');
    }

    public function password(User $user)
    {
        if (Auth::user()->isAdministrator()) {
            return view('admin.user.password')->with('user', $user);
        }
        abort(403);
    }

    public function changePassword(Request $request, User $user)
    {
        if (Auth::user()->isAdministrator()) {
            $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $user->password = Hash::make($request->get('password'));
            $user->save();

            return redirect()->route('admin.user.index')->with('status', __('Password changed!'));
        }
        abort(403);
    }
}
