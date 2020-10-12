<?php

namespace App\Http\Controllers;

use App\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\Pesan;

class RoleController extends Controller
{
    // midleware
    public function __construct()
    {
        //verifikasi semua fungsi harus memakai token kecuali method store
        $this->middleware('auth:api');
        $this->pesan = new Pesan();
    }

    // getAll
    public function index()
    {
        (array)$data = Role::all();
        return $this->pesan->getAll($data);
    }
    // getById
    public function show($id)
    {
        //get user
        (array)$data = Role::find($id);
        return $this->pesan->byId($data);
    }
    // tambah data
    public function store(Request $request)
    {

        //validasi semua inputan
        $validator = $this->pesan->valid($request, [
            'role'   => 'required|unique:roles,role',
        ]);

        if ($validator->fails()) {
            return $this->pesan->errorValid($validator);
        }

        // tambah data
        $roles = $request->only(["role"]);
        $role = Role::create([
            'role' => $roles['role']
        ]);
        return $this->pesan->tambah($role);
    }
    // update
    public function update($id, Request $request)
    {
        //cek semua inputan sudah terisi
        $validator = $this->pesan->valid($request, [
            'role'   => 'required',
        ]);

        if ($validator->fails()) {
            return $this->pesan->errorValid($validator);
        }

        //update data
        $roles = $request->only(["role"]);
        $role = Role::find($id)->update([
            'role' => $roles['role']
        ]);
        return $this->pesan->edit($role);
    }
    //delete
    public function destroy($id)
    {
        $role = Role::find($id)->delete();
        return $this->pesan->hapus($role);
    }
}
