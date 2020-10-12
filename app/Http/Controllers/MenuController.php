<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Access;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\Pesan;
use App\Submenu;

class MenuController extends Controller
{
    // midleware
    public function __construct()
    {
        //verifikasi semua fungsi harus memakai token kecuali method store
        $this->middleware('auth:api');
        $this->pesan = new Pesan();
    }

    //getMenu
    public function getMenu()
    {
        (array)$menu = Menu::whereActive(1)->orderBy('order', 'ASC')->get();
        $response = [];
        foreach ($menu as $data) {
            $submenu = Submenu::whereMenu_id($data->id)->whereActive(1)->orderBy('order', 'ASC')->get();
            $res = [
                'title' => $data->title,
                'icon' => $data->icon,
                'link' => $data->link,
                'submenu' => $submenu
            ];
            array_push($response, $res);
        }
        return $this->pesan->getall($response);
    }
    // getAll
    public function index()
    {
        (array)$data = Menu::orderBy('order', 'ASC')->get();
        return $this->pesan->getAll($data);
    }
    // getById
    public function show($id)
    {
        //get user
        (array)$menu = Menu::find($id);
        return $this->pesan->byId($menu);
    }
    // active deactive user
    public function active($id)
    {
        //get user
        (array)$menu = Menu::find($id);
        if ($menu) {
            (int)$active = 0;
            (string)$pesan = "";
            if ($menu->active == 1) {
                $active = 0;
                $pesan = 'Menu di non aktifkan';
            } else {
                $active = 1;
                $pesan = 'Menu di aktifkan';
            }
            Menu::whereId($id)->update([
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
            'title'   => 'required',
            'icon' => 'required',
            'link' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->pesan->errorValid($validator);
        }

        // tambah data
        $menus = $request->only(["title", "icon", "link",]);
        $menu = Menu::create([
            'title' => $menus['title'],
            'icon' => $menus['icon'],
            'link' => $menus['link'],
            'active' => 1,
            'order' => (count(Menu::all()) + 1)
        ]);
        Access::create([
            'role_id' => 1,
            'menu_id' => $menu->id
        ]);
        return $this->pesan->tambah($menu);
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
        $menus = $request->only(["title", "icon", "link"]);
        $menu = Menu::find($id)->update([
            'title' => $menus['title'],
            'icon' => $menus['icon'],
            'link' => $menus['link']
        ]);
        return $this->pesan->edit($menu);
    }

    //delete
    public function destroy($id)
    {
        $user = Menu::find($id);
        if (!$user) {
            return $this->pesan->hapus();
        }
        $user->delete();

        return $this->pesan->hapus($user);
    }
}
