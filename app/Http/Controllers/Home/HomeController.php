<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\HomeService;
use DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $service;

    public function __construct(HomeService $service)
    {
        $this->service  = $service;
        $this->view     = 'home.';
    }

    public function index()
    {
        return view($this->view . 'index');
    }

    public function getStatistikData(Request $req)
    {
        $id     = $req->id;
        $data   = DB::select("SELECT
                            ptgs_lap_total,
                            ptgs_lap_laki,
                            ptgs_lap_cewek,
                            tokoh_total,
                            tokoh_laki,
                            tokoh_cewek,
                            afl_total,
                            keg_total
                        FROM
                            ewa_dashboard_akumulasi (
                                ?
                            )", [$id]);

        return response()->json($data);
    }

    public function getDataPeta(Request $req)
    {
        $id     = $req->id;
        $data   = DB::select("SELECT
                    id_peta,
                    nama_daerah,
                    keg_total,
                    tkh_total,
                    afiliasi_total,
                    ptgs_total,
                    jumlah_total
                FROM
                    ewa_dashboard_peta_semua_jenis (
                        ?
                    )", [$id]);

        return response()->json($data);
    }

    public function getDetailKegiatan($id) {
        $data   = DB::select("SELECT
                    id,
                    kab_kota,
                    jenis,
                    detail_kegiatan,
                    tanggal_kegiatan,
                    lokasi,
                    jumlah_massa,
                    tokoh_afiliasi,
                    petugas_lapangan
                FROM
                    ewa_detail_kegiatan (
                        ?
                    )", [$id]);

        return view($this->view . 'detail_kegiatan', compact('data'));
    }

    public function getDetailTokoh($id) {
        $data   = DB::select("SELECT
                        id,
                        kab_kota,
                        nama_tokoh,
                        lokasi,
                        profil_tokoh,
                        jenis_kelamin,
                        jumlah_kegiatan
                    FROM
                        ewa_detail_tokoh (
                            ?
                        )", [$id]);

        return view($this->view . 'detail_tokoh', compact('data'));
    }

    public function getDetailAfiliasi($id) {
        $data   = DB::select("SELECT
                    id,
                    kab_kota,
                    nama_afiliasi,
                    lokasi,
                    profil_afiliasi,
                    jumlah_kegiatan
                FROM
                    ewa_detail_afiliasi (
                        ?
                    )", [$id]);

        return view($this->view . 'detail_afiliasi', compact('data'));
    }

    public function getDetailPetugasLapangan($id) {

        $data   = DB::select("SELECT
                    id,
                    kab_kota,
                    nama_petugas_lapangan,
                    jenis_kelamin,
                    jumlah_kegiatan
                FROM
                    ewa_detail_petugas_lapangan (
                        $id
                    )");

        return view($this->view . 'detail_petugas_lapangan', compact('data'));
    }
}
