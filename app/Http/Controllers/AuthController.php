<?php

namespace App\Http\Controllers;
use Illuminate\Auth\Events\Registered;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(){
        return Inertia::render('Auth/Register');
    }


    public function store(Request $request) { 

        
        

        $fields = $request->validate([
            'avatar'      => ['nullable', 'image'], 
            'name'        => ['required', 'string', 'max:255'],
            'lastName'    => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],

            'birthdate'   => ['required', 'date'],
            'country'     => ['required', 'string', 'max:100'],
            'city'        => ['required', 'string', 'max:100'],
            'role'        => ['required', 'in:school,job'], 
            'schoolType'  => ['nullable', 'string', 'max:100'],
            'jobType'     => ['nullable', 'string', 'max:100'],
        ]);


        if($request->hasFile('avatar')) {
            $fields['avatar'] = $request->file('avatar')->store('avatars', 'public');
            
        }

        $user = User::create($fields);
        event(new Registered($user));
        Auth::login($user);

        return Inertia::location(route('dashboard'));

    }

    public function authenticate(Request $request) {
        
        $fields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if(Auth::attempt($fields, $request->remember)) {
            $request->session()->regenerate();
            return Inertia::location(route('dashboard'));
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records',
        ])->onlyInput('email');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Inertia::location(route('home'));
    }

    public function delete_user(Request $request){
        
        $user = Auth::user();
        if ($user) {
            $user->delete();
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Inertia::location(route('home'));
    }
}
