<?php

namespace App\Repositories;

use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class HomeRepository
{
    public function __construct(){

    }

    public function getMenu(){
        $menu = Menu::where('enable', true)->where('main_menu', true)->orderBy('position', 'asc')->get();
        return $menu;
    }

    public function getTotalPenghargaan(){
        return DB::select("SELECT COUNT (id) AS total_penghargaan FROM penghargaan");
    }

    public function getTotalInovasi(){
        return DB::select("SELECT COUNT (id) AS jumlah_inovasi FROM data_prestasi");
    }

    public function getVisi(){
        return DB::select("SELECT * FROM egp_tampil_visi ()");
    }

    public function getTotalPenghargaanReady(){
        return DB::select("SELECT COUNT (id) AS total_penghargaan FROM penghargaan WHERE jenis = 1");
    }
}
