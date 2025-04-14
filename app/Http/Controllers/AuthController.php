<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Cache\RateLimiting\Limit;

class AuthController extends Controller
{
    public function register()
    {
        $user = User::orderBy('created_at', 'DESC')->get();
        return view('SystemControls.access-security', compact('user'));
    }

    // registration logic
    public function registerSave(Request $request)
    {
        Log::info('Register Save Called', $request->all());

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|min:11',
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('SystemControls.access-security')->with('success', 'Account created successfully!');
    }

    public function login()
    {
        return view('auth.login');
    }

    // login logic
    public function loginAction(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = Str::lower($request->input('email'));
        $key = 'login:attempts:' . $email;
        $maxAttempts = 2; 
        $decaySeconds = 60;

        // Check if too many attempts
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->with('cooldown', $seconds)->withInput();
        }

        // check credentials
        if (!Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            RateLimiter::hit($key, $decaySeconds / 2);

            return back()->with('error', "The credentials don't match our records.")->onlyInput('email');
        }

        // successful login, clear attempts
        RateLimiter::clear($key);

        $user = Auth::user();

        if ($user->status === 'Inactive') {
            Auth::logout();
            $request->session()->invalidate();
            return back()->with('error', 'Your account is inactive. Please contact the administrator.')->onlyInput('email');
        }

        $request->session()->regenerate();

        return match ($user->role) {
            'SuperAdmin' => redirect()->route('superadmin.dashboard'),
            'Accountant' => redirect()->route('accountant.dashboard'),
            'Teacher' => redirect()->route('teacher.dashboard'),
            default => redirect()->route('login'),
        };
    }

    // logout logic
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        return redirect('/');
    }

    public function toggleStatus(Request $request, $id)
    {
        try {
            $user = User::find($id);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $user->update([
                'status' => $request->input('status')
            ]);

            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}