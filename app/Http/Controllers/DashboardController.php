<?php

namespace App\Http\Controllers;

use App\Models\BahanPangan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // set default date range (last 7 days)
        $tanggal_awal = $request->get('tanggal_awal', Carbon::now()->subDays(7)->format('Y-m-d'));
        $tanggal_akhir = $request->get('tanggal_akhir', Carbon::now()->format('Y-m-d'));

        // get data within date range
        $result = BahanPangan::whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->orderBy('komoditas', 'asc')
            ->orderBy('tanggal', 'asc')
            ->get();

        // group data by komoditas
        $data_per_komoditas = [];
        foreach ($result as $row) {
            $komoditas = $row->komoditas;
            if (!isset($data_per_komoditas[$komoditas])) {
                $data_per_komoditas[$komoditas] = [];
            }
            $data_per_komoditas[$komoditas][] = $row->toArray();
        }

        return view('dashboard', [
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'data_per_komoditas' => $data_per_komoditas
        ]);
    }
}
