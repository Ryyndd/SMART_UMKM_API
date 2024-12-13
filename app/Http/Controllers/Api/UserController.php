<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResources;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


        // // Save the user instance
        /** @var \App\Models\User $user **/

class UserController extends Controller
{
    /**
     * Handle user login.
     */

    // public function login(Request $request)
    //     {
    //         $request->validate([
    //             'username' => 'required|string',
    //             'password' => 'required|string',
    //         ]);
        
    //         if (Auth::attempt($request->only('username', 'password'))) {
    //             $user = Auth::user(); // Mengambil data user yang sedang login

    //             // Save the user instance
    //             /** @var \App\Models\User $user **/
    //             if ($user->save()) {
    //                 return response()->json([
    //                     'response_code' => 200,
    //                     'message' => 'Login Berhasil',
    //                     'content' => $user
    //                 ]);
    //             }
    //         }
        
    //         return response()->json([   
    //             'response_code' => 404,
    //             'message' => 'Username atau Password Tidak Ditemukan!'
    //         ]);
    //     }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        
        
        // Mencari pengguna berdasarkan username
        $user = User::where('username', $request->username)->first();
        
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Username atau Password Tidak Ditemukan!',
                'data' => null
            ]);
        }

        // Memeriksa apakah pengguna ditemukan dan passwordnya cocok
        if ($user->password == $request->password) {
            // Jika login berhasil, Anda dapat mengembalikan data pengguna
            return response()->json([
                'status' => true,
                'message' => 'Login Berhasil',
                'data' => $user
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Username atau Password Tidak Ditemukan!',
            'data'=> null
        ]);
    }

    
    public function index(Request $request)
    {
        $query = $request->input('query');
    
        $users = User::when($query, function ($queryBuilder) use ($query) {
            return $queryBuilder->where('username', 'like', "%{$query}%")
                                ->orWhere('name', 'like', "%{$query}%");
        })->latest()->get();
    
        $message = $query ? "Berikut Data Hasil pencarian dari $query" : 'List data user';
    
        return new UserResources(true, $message, $users);
    }
    
    /**
     * Create a new user.
     */
    public function store(Request $request)
    {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg',
                'name' => 'required|string',
                'phone' => 'required',
                'email'=> 'required|string|email',
                'username' => 'required|string|max:255|unique:users,username',
                'password' => 'required|string|min:6',
                'role' => 'required|in:ADMIN,USER', // Sesuaikan roleVYUF
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // Handle image upload
            $image = $request->file('image');
            $namaImage = Str::slug($request->input('username')) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('user', $namaImage, 'public');


            // Hash password
            // $hashedPassword = Hash::make($request->password);

            // Buat user baru
            $user = User::create([
                'image'=> $namaImage,
                'name'=> $request->name,
                'email'=> $request->email,
                'phone' => $request->phone,
                'username' => $request->username,
                'password' => $request->password,
                // 'api_token' => Str::random(60),
                'role' => $request->role,
            ]);

            return new UserResources(true,'Data Product Berhasil Ditambahkan!', $user);

    }

    public function show($id)
    {

        $user = User::find($id);


        if (!$user) {
            return response()->json(['error' => 'User not found'],404);
        }

        return new UserResources(true, 'Detail Data User!', $user);
    }
    
    public function getUserByUsername($username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return new UserResources(true, 'Username Data User!', $user);
    }
    
    public function update( Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required',
            'email'=> 'required|string|email',
            'username' => 'required|string|',
            'password' => 'required|string|min:6',
            'role' => 'required|in:ADMIN,USER',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find($id);

        if(! $user) {
            return response()->json(['error'=> 'User not found'],404);
        }

        if($request->hasFile('image')){

            if ($user->image){
                $imagePath = parse_url($user->image, PHP_URL_PATH); // Get the path from the URL
                
                $relativePath = str_replace('public/storage/user/', '/user/', $imagePath); // Adjust the path

                // Check if the file exists and delete it
                if (Storage::disk('public')->exists($relativePath)) {
                    Storage::disk('public')->delete($relativePath);
                } else {
                    return response()->json(['error' => 'Image not found in storage'], 404);
                }
            }
            $image = $request->file('image');
            $namaImage = Str::slug($request->input('username')) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('user', $namaImage, 'public');
            
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

        if ($user->image) {
            // Extract the relative path from the full URL
            $imagePath = parse_url($user->image, PHP_URL_PATH); // Get the path from the URL
            
            // Remove the extra 'product/' segment if it exists
            $relativePath = str_replace('public/storage/user/', '/user/', $imagePath); // Adjust the path

            // Check if the file exists and delete it
            if (Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);
            } else {
                return response()->json(['error' => 'Image not found in storage'], 404);
            }
        }

        // Delete the product from the database
        $user->delete();

        
        return new UserResources(true, 'Data User Berhasil Dihapus!', null);
    }
    
}
