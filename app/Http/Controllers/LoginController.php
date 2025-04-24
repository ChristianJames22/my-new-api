<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{

    public function store(Request $request)
    {

        if ($request->has('firstname')) {
            $request->validate([
                'firstname' => 'required|string',
                'lastname' => 'required|string',
                'age' => 'required|string',
                'address' => 'required|string',
                'sex' => 'required|string',
                'Mi' => 'required|string|min:1',
                'course' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
            ]);

            $user = User::create([
                'studentid' => (string) Str::uuid(),
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'Mi' => $request->Mi,
                'email' => $request->email,
                'age' => $request->age,
                'sex' => $request->age,
                'address' => $request->address,
                'course' => $request->course,
                'password' => bcrypt($request->password),
            ]);

            Log::info('User registered:', ['email' => $user->email]);

            return response()->json(['message' => 'User registered successfully'], 201);
        }


        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            Log::warning(' Login failed: user not found', ['email' => $request->email]);
        } elseif (! Hash::check($request->password, $user->password)) {
            Log::warning(' Login failed: password mismatch', ['email' => $request->email]);
        } else {
            Log::info(' Login successful', ['email' => $request->email]);
        }

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        Log::info('Token issued', ['email' => $user->email]);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }


    public function index(Request $request)
    {
        \Log::info('User info requested', ['user_id' => $request->user()->id ?? 'none']);

        return response()->json($request->user());
    }



    public function destroy(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        Log::info('User logged out', ['email' => $request->user()->email]);

        return response()->json(['message' => 'Logged out']);
    }
}
