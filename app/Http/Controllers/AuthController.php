<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\VerifyEmail;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => null,
        ]);

        if (Role::where('name', 'user')->exists()) {
            $user->assignRole('user');
        } else {
            return response()->json(['message' => 'Foydalanuvchi uchun rol mavjud emas'], 422);
        }

        $verificationToken = base64_encode(Str::random(60));

        $user->email_verification_token = $verificationToken;
        $user->save();

        $verificationUrl = route('api.email.verify', ['token' => $verificationToken]);

        Mail::to($user->email)->send(new VerifyEmail($verificationUrl));

        return response()->json(['message' => 'Foydalanuvchi muvaffaqiyatli ro`yxatdan o`tdi. Kiritilgan emailingizga tasdiqlash linki yuborildi'], 201);
    }

    public function verifyEmail($token)
    {

        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid verification token'], 400);
        }
        $user->email_verified_at = now();
        $user->email_verification_token = null;
        $user->save();

        return response()->json(['message' => 'Email successfully verified']);
    }

    public function login(Request $request)
    {
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
            return response()->json(['message' => 'Authorization required'], 401, [
                'WWW-Authenticate' => 'Basic realm="Restricted Area"'
            ]);
        }
    
        $credentials = [
            'email' => $_SERVER['PHP_AUTH_USER'],
            'password' => $_SERVER['PHP_AUTH_PW']
        ];
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
    
            if (is_null($user->email_verified_at)) {
                return response()->json(['message' => 'Email not verified. Please verify your email.'], 403);
            }
    
            return response()->json(['message' => 'Login successful', 'user' => $user, 'redirect_url' => 'bbc.index'], 200);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
    
    

    public function logout(Request $request)
    {
        return response()->json([
            'message' => 'Logged out successfully'
        ])->header('WWW-Authenticate', 'Basic realm="Logout"');
    }    
}
