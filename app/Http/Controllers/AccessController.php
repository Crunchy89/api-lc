<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Access;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\Pesan;
use App\Submenu;
use App\Role;

class AccessController extends Controller
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
        (array)$menu = Access::select('*')->join('roles', 'accesses.role_id', 'roles.id')->join('menus', 'accesses.menu_id', 'menus.id')->where('accesses.role_id', auth()->user()->role_id)->where('menus.active', 1)->orderBy('menus.order', 'ASC')->get();
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
        (array)$data = Access::select('*')->join('roles', 'accesses.role_id', 'roles.id')->join('menus', 'accesses.menu_id', 'menus.id')->get();
        return $this->pesan->getAll($data);
        // return response()->json("tes");
    }
    public function check(Request $request)
    {
        $roles = Role::all();
        $menus = Menu::orderBy('order', 'ASC')->get();
        $data = [];
        foreach ($menus as $menu) {
            $push = [
                'menu' => $menu->title,
                'data' => []
            ];
            foreach ($roles as $role) {
                $access = Access::whereRole_id($role->id)->whereMenu_id($menu->id)->first();
                $check = [
                    'id' => $access ? $access->id : "",
                    'role_id' => $role->id,
                    'menu_id' => $menu->id,
                    'value' => $access ? 1 : 0,
                ];
                array_push($push['data'], $check);
            }
            array_push($data, $push);
        }
        // (array)$data = Access::select('*')->join('roles', 'accesses.role_id', 'roles.id')->join('menus', 'accesses.menu_id', 'menus.id')->get();
        return $this->pesan->getAll($data);
        // return response()->json("tes");
    }
    // active deactive user
    public function active(Request $request)
    {
        //get user
        $access = $request->only(['role_id', 'menu_id']);
        (array)$data = Access::whereRole_id($access['role_id'])->whereMenu_id($access['menu_id'])->first();
        if (!$data) {
            Access::create([
                'role_id' => $access['role_id'],
                'menu_id' => $access['menu_id']
            ]);
            return $this->pesan->data(true, "Access diberikan", 200);
        } else {
            $data->destroy();
            return $this->pesan->data(true, "Access dicabut", 200);
        }
    }
}
