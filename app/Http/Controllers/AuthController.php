<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showRegister ()
    {
        return view('auth.register');
    }
    public function showLogin ()
    {
        return view('auth.login');
    }
    public function showAdmin ()
    {
        return view('auth.admin');
    }
    public function register (Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);
    
        // Create the user but do not log them in
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']), // Hash the password
        ]);
    
        // Redirect to the login page after successful registration
        return redirect()->route('show.login')->with('success', 'Registration successful. Please log in.');
    }
    public function login (Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();

            return redirect()->route('welcome');
        }

        throw ValidationException::withMessages([
            'credentials' => 'Sorry, incorrect credentials'
        ]);

    }
    public function admin (Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
    
        // Check if the user exists and is an admin
        $user = User::where('email', $validated['email'])->first();
    
        if ($user && $user->is_admin && Auth::attempt($validated)) {
            $request->session()->regenerate();
    
            return redirect()->route('sports.create'); // Redirect admin to sports.create
        }
    
        // If the user is not an admin or credentials are invalid
        throw ValidationException::withMessages([
            'credentials' => 'Sorry, you do not have admin access or your credentials are incorrect.'
        ]);

    }
    public function logout (Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('show.login');
    }
}
