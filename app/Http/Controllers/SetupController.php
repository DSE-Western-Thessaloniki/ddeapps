<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Level;
use App\Option;
use Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SetupController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Setup Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of the admin user.
    |
    */

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'min:6', 'max:255', 'unique:users'],
            'userlevel' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function saveSetup(Request $request)
    {
        $this->validator($request->all())->validate();

        $data = $request->all();

        event(new Registered($user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'userlevel' => 100,
            'password' => Hash::make($data['password']),
        ])));

        $first_run = Option::where('name', 'first_run')->first();
        $first_run->value = 0;
        $first_run->save();

        if ($request->wantsJson()) {
            return new Response('', 201);
        }
        else {
            Auth::login($user);
            return redirect(RouteServiceProvider::HOME);
        }
    }

    /**
     * Setup page
     *
     * @return view
     */
    public function setupPage()
    {
        return view('pages.setup');
    }
}
