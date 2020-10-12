<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
use App\Menu;
use App\Submenu;
use App\Access;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Role::create([
            'role' => 'admin',
        ]);
        $role = Role::all();
        User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('makannasi'),
            'active' => 1,
            'email_verified_at' => date('Y-m-d H:i:s'),
            'role_id' => $role->first()->id,
            'remember_token' => Str::random(10),
        ]);
        Menu::create([
            'title' => "Admin Menu",
            'icon' => 'fa fa-fw fa-desktop',
            'link' => '#',
            'active' => 1,
            'order' => 1
        ]);
        $menu = Menu::all();
        Submenu::create(
            [
                'title' => "Role Management",
                'icon' => 'fa fa-fw fa-cogs',
                'link' => 'role',
                'active' => 1,
                'order' => 1,
                'menu_id' => $menu->first()->id
            ]
        );
        Submenu::create([
            'title' => "User Management",
            'icon' => 'fa fa-fw fa-users',
            'link' => 'user',
            'active' => 1,
            'order' => 1,
            'menu_id' => $menu->first()->id
        ]);
        Submenu::create(
            [
                'title' => "Menu Management",
                'icon' => 'fa fa-fw fa-code',
                'link' => 'menu',
                'active' => 1,
                'order' => 1,
                'menu_id' => $menu->first()->id
            ]
        );
        Submenu::create([
            'title' => "Access Management",
            'icon' => 'fa fa-fw fa-lock',
            'link' => 'access',
            'active' => 1,
            'order' => 1,
            'menu_id' => $menu->first()->id
        ]);
        Access::create([
            'role_id' => $role->first()->id,
            'menu_id' => $menu->first()->id
        ]);
    }
}
