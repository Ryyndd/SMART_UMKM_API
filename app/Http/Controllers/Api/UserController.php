<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResources;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


                // // Save the user instance
                /** @var \App\Models\User $user **/


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
                
                $user->api_token = Str::random(60); // Membuat API token baru
                
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
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'name' => 'required|string',
                'phone' => 'required|numeric',
                'email'=> 'required|string|email',
                'username' => 'required|string|max:255|unique:users,username',
                'password' => 'required|string|min:6',
                'role' => 'required|in:admin,user', // Sesuaikan roleVYUF
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal!',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle image upload
            $image = $request->file('image');
            $namaImage = Str::slug($request->input('username')) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/user', $namaImage, 'public');


            // Hash password
            $hashedPassword = Hash::make($request->password);

            // Buat user baru
            $user = User::create([
                'image'=> $namaImage,
                'name'=> $request->name,
                'email'=> $request->email,
                'phone' => $request->phone,
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

    public function show($id)
    {

        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'],404);
        }

        return new UserResources(true, 'Detail Data User!', $user);
    }


    public function update( Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'email'=> 'required',
            'phone' => 'required',
            'username' => 'required',
            'password'=> 'required',
            'role'=> 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find($id);

        if(! $user) {
            return response()->json(['error'=> 'User not found'],404);
        }

        if($request->hasFile('image')){
            $image = $request->file('image');
            $namaImage = Str::slug($request->input('username')) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/user', $namaImage, 'public');
            
            $user->update([
                'image' => $namaImage,
                'name'=> $request->name,
                'email'=> $request->email,
                'phone'=> $request->phone,
                'username'=> $request->username,
                'password'=> $request->password,
                'role'=> $request->role
            ]);

        } else {
            $user->update([
                'name'=> $request->name,
                'email'=> $request->email,
                'phone'=> $request->phone,
                'username'=> $request->username,
                'password'=> $request->password,
                'role'=> $request->role
            ]);
        }

        return new UserResources(true,'Data User Berhasil Diubah!' ,$user);

    }


    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'],404);
        }
        
        if ($user->image){
            Storage::delete('public/user/'. $user->image);
        }

        $user->delete();

        return new UserResources(true, 'Data User Berhasil Dihapus!', null);
    }
    /**
     * Get all users with pagination.
     */
    public function allUser()
    {
        try {
            // Ambil semua data user dengan paginasi
            $users = User::latest()->get();

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
