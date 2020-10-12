<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Libraries\Pesan;

class UserController extends Controller
{
    // midleware
    public function __construct()
    {
        //verifikasi semua fungsi harus memakai token kecuali method store
        $this->middleware('auth:api', ['except' => ['index']]);
        $this->pesan = new Pesan();
    }

    // getAll
    public function index()
    {
        (array)$user = User::all();
        return $this->pesan->getAll($user);
    }
    // getById
    public function show($id)
    {
        //get user
        (array)$user = User::find($id);
        return $this->pesan->byId($user);
    }
    // active deactive user
    public function active($id)
    {
        //get user
        (array)$user = User::find($id);
        if ($user) {
            (int)$active = 0;
            (string)$pesan = "";
            if ($user->active == 1) {
                $active = 0;
                $pesan = 'akun di non aktifkan';
            } else {
                $active = 1;
                $pesan = 'akun di aktifkan';
            }
            User::whereId($id)->update([
                'active' => $active
            ]);
            return $this->pesan->data(true, $pesan, 200);
        }
        //jika user tidak ditemukan
        return $this->pesan->data(false, "User tidak ditemukan", 404);
    }
    // tambah data
    public function store(Request $request)
    {

        //validasi semua inputan
        $validator = $this->pesan->valid($request, [
            'username'   => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->pesan->errorValid($validator);
        }

        // tambah data
        $users = $request->only(["role_id", "username", "email", "password"]);
        $user = new User();
        $user->username = $users['username'];
        $user->password = Hash::make($users['password']);
        $user->email = $users['email'];
        $user->active = 1;
        $user->role_id = $users['role_id'];
        $user->email_verified_at = date('Y-m-d H:i:s');
        $user->save();

        return $this->pesan->tambah($user);
    }
    // update
    public function update($id, Request $request)
    {
        //cek semua inputan sudah terisi
        $validator = $this->pesan->valid($request, [
            'username'   => 'required',
            'email' => 'required',
            'role_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->pesan->errorValid($validator);
        }

        //update data
        $users = $request->only(["role_id", "username", "email"]);
        $user = User::find($id);
        if (!$user) {
            return $this->pesan->edit();
        }
        $user->username = $users['username'];
        $user->email = $users['email'];
        $user->role_id = $users['role_id'];
        $user->save();
        return $this->pesan->edit($user);
    }
    // reset password
    public function reset($id, Request $request)
    {
        //cek semua inputan sudah terisi
        $validator = $this->pesan->valid($request, [
            'password'   => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return $this->pesan->errorValid($validator);
        }

        // reset password
        $users = $request->only(['password']);
        $user = User::find($id);
        if (!$user) {
            return $this->pesan->edit();
        }
        $user->password = Hash::make($users['password']);
        $user->save();
        return $this->pesan->edit($user);
    }
    //delete
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->pesan->hapus();
        }
        $user->delete();

        return $this->pesan->hapus($user);
    }
}
