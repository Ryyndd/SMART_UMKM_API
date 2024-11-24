<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResources;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

 
                // // Save the user instance
                // /** @var \App\Models\User $user **/


class UserController extends Controller
{
    /**
     * Handle user login.
     */

    public function index(Request $request)
        {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);
        
            if (Auth::attempt($request->only('username', 'password'))) {
                $user = Auth::user(); // Mengambil data user yang sedang login
                
                $user->api_token = Str::random(100); // Membuat API token baru
                
                // Save the user instance
                /** @var \App\Models\User $user **/
                if ($user->save()) {
                    return response()->json([
                        'response_code' => 200,
                        'message' => 'Login Berhasil',
                        'content' => $user
                    ]);
                }
            }
        
            return response()->json([
                'response_code' => 404,
                'message' => 'Username atau Password Tidak Ditemukan!'
            ]);
        }

    /**
     * Create a new user.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255|unique:users,username',
                'password' => 'required|string|min:6',
                'role' => 'required|in:admin,user', // Sesuaikan role
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal!',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Hash password
            $hashedPassword = Hash::make($request->password);

            // Buat user baru
            $user = User::create([
                'username' => $request->username,
                'password' => $hashedPassword,
                'api_token' => Str::random(60),
                'role' => $request->role,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User berhasil ditambahkan!',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all users with pagination.
     */
    public function allUser()
    {
        try {
            // Ambil semua data user dengan paginasi
            $users = User::latest()->paginate(5);

            return new UserResources(true, 'List data user', $users);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
