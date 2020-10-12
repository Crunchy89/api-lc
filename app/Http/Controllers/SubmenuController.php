<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\Pesan;
use App\Submenu;

class SubmenuController extends Controller
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
        (array)$data = Submenu::all();
        return $this->pesan->getAll($data);
    }
    // getAll
    public function getByMenuId($id)
    {
        (array)$data = Submenu::whereMenu_id($id)->whereActive(1)->orderBy('order', 'ASC')->get();
        return $this->pesan->getAll($data);
    }
    // getById
    public function show($id)
    {
        //get user
        (array)$data = Submenu::find($id);
        return $this->pesan->byId($data);
    }
    // active deactive user
    public function active($id)
    {
        //get user
        (array)$data = Submenu::find($id);
        if ($data) {
            (int)$active = 0;
            (string)$pesan = "";
            if ($data->active == 1) {
                $active = 0;
                $pesan = 'Submenu di non aktifkan';
            } else {
                $active = 1;
                $pesan = 'Submenu di aktifkan';
            }
            Submenu::whereId($id)->update([
                'active' => $active
            ]);
            return $this->pesan->data(true, $pesan, 200);
        }
        //jika user tidak ditemukan
        return $this->pesan->data(false, "Submenu tidak ditemukan", 404);
    }
    // tambah data
    public function store(Request $request)
    {

        //validasi semua inputan
        $validator = $this->pesan->valid($request, [
            'menu_id' => 'required',
            'title'   => 'required',
            'icon' => 'required',
            'link' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->pesan->errorValid($validator);
        }

        // tambah data
        $datas = $request->only(["menu_id", "title", "icon", "link",]);
        $data = Submenu::create([
            'menu_id' => $datas['menu_id'],
            'title' => $datas['title'],
            'icon' => $datas['icon'],
            'link' => $datas['link'],
            'active' => 1,
            'order' => count(Submenu::whereMenu_id($datas['menu_id'])->get())
        ]);
        return $this->pesan->tambah($data);
    }
    // update
    public function update($id, Request $request)
    {
        //cek semua inputan sudah terisi
        $validator = $this->pesan->valid($request, [
            'title'   => 'required',
            'icon' => 'required',
            'link' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->pesan->errorValid($validator);
        }

        //update data
        $datas = $request->only(["title", "icon", "link"]);
        $data = Submenu::find($id)->update([
            'title' => $datas['title'],
            'icon' => $datas['icon'],
            'link' => $datas['link']
        ]);
        return $this->pesan->edit($data);
    }

    //delete
    public function destroy($id)
    {
        $user = Submenu::find($id);
        if (!$user) {
            return $this->pesan->hapus();
        }
        $user->delete();

        return $this->pesan->hapus($user);
    }
}
