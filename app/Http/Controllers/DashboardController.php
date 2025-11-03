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
        $tanggal_awal = $request->get(
            "tanggal_awal",
            Carbon::now()->subDays(7)->format("Y-m-d"),
        );
        $tanggal_akhir = $request->get(
            "tanggal_akhir",
            Carbon::now()->format("Y-m-d"),
        );

        // get data within date range
        $result = BahanPangan::whereBetween("tanggal", [
            $tanggal_awal,
            $tanggal_akhir,
        ])
            ->orderBy("komoditas", "asc")
            ->orderBy("tanggal", "asc")
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

        return view("dashboard", [
            "tanggal_awal" => $tanggal_awal,
            "tanggal_akhir" => $tanggal_akhir,
            "data_per_komoditas" => $data_per_komoditas,
        ]);
    }

    public function showVisualization(Request $request)
    {
        $provinsi = $request->get("provinsi");
        $komoditas = $request->get("komoditas");

        $query = BahanPangan::query();

        if ($provinsi) {
            $query->where("provinsi", $provinsi);
        }

        if ($komoditas) {
            $query->where("komoditas", $komoditas);
        }

        $data = $query
            ->select("provinsi", "komoditas", "harga", "tanggal")
            ->orderBy("tanggal")
            ->get();

        // Group data for chart
        $chartData = [];
        foreach ($data as $item) {
            $chartData[$item->provinsi][$item->komoditas][] = [
                "tanggal" => $item->tanggal->format("Y-m-d"),
                "harga" => $item->harga,
            ];
        }

        $provinces = BahanPangan::distinct()->pluck("provinsi");
        $commodities = BahanPangan::distinct()->pluck("komoditas");

        return view(
            "admin.bahan_pangan.visualization",
            compact(
                "chartData",
                "provinces",
                "commodities",
                "provinsi",
                "komoditas",
            ),
        );
    }
}
