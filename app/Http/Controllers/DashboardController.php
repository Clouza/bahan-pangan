<?php

namespace App\Http\Controllers;

use App\Models\BahanPangan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // set default date range for table (last 7 days)
        $tanggal_awal = $request->get(
            "tanggal_awal",
            Carbon::now()->subDays(7)->format("Y-m-d"),
        );
        $tanggal_akhir = $request->get(
            "tanggal_akhir",
            Carbon::now()->format("Y-m-d"),
        );

        // set default date range for chart (last 30 days)
        $chart_tanggal_awal = $request->get(
            "chart_tanggal_awal",
            Carbon::now()->subDays(30)->format("Y-m-d"),
        );
        $chart_tanggal_akhir = $request->get(
            "chart_tanggal_akhir",
            Carbon::now()->format("Y-m-d"),
        );
        $chart_komoditas_selected = $request->get("chart_komoditas", []);

        // --- Table Data Preparation ---
        $tableQuery = BahanPangan::query();

        if ($request->filled("komoditas")) {
            $tableQuery->where("komoditas", $request->komoditas);
        }

        if ($request->filled("provinsi")) {
            $tableQuery->where("provinsi", $request->provinsi);
        }

        // get data within date range for table
        $tableResult = $tableQuery
            ->whereBetween("tanggal", [$tanggal_awal, $tanggal_akhir])
            ->orderBy("komoditas", "asc")
            ->orderBy("tanggal", "asc")
            ->get();

        // group data by komoditas for table display
        $data_per_komoditas = [];
        foreach ($tableResult as $row) {
            $komoditas = $row->komoditas;
            if (!isset($data_per_komoditas[$komoditas])) {
                $data_per_komoditas[$komoditas] = [];
            }
            $data_per_komoditas[$komoditas][] = $row->toArray();
        }
        // --- End Table Data Preparation ---

        // --- Visualization Data Preparation ---
        $chartData = [
            "labels" => [],
            "datasets" => [],
        ];

        $chartQuery = BahanPangan::query();

        // Apply chart-specific commodity filter
        if (!empty($chart_komoditas_selected)) {
            $chartQuery->whereIn("komoditas", $chart_komoditas_selected);
        }

        // Apply main province filter to chart
        if ($request->filled("provinsi")) {
            $chartQuery->where("provinsi", $request->provinsi);
        }

        // get data within date range for chart
        $chartResult = $chartQuery
            ->whereBetween("tanggal", [
                $chart_tanggal_awal,
                $chart_tanggal_akhir,
            ])
            ->orderBy("komoditas", "asc")
            ->orderBy("tanggal", "asc")
            ->get();

        if ($chartResult->isNotEmpty()) {
            // Generate all dates within the selected chart date range
            $period = Carbon::parse($chart_tanggal_awal)->daysUntil(
                Carbon::parse($chart_tanggal_akhir),
            );
            $labels = [];
            foreach ($period as $date) {
                $labels[] = $date->format("Y-m-d");
            }
            // Ensure the end date is included if it's not already
            if (
                !in_array(
                    Carbon::parse($chart_tanggal_akhir)->format("Y-m-d"),
                    $labels,
                )
            ) {
                $labels[] = Carbon::parse($chart_tanggal_akhir)->format(
                    "Y-m-d",
                );
            }
            sort($labels); // Ensure labels are sorted chronologically

            // Group chartResult by date and then by commodity for efficient lookup
            $groupedChartData = $chartResult->groupBy(fn($item) => Carbon::parse($item->tanggal)->format('Y-m-d'))->map(function ($dateGroup) {
                    return $dateGroup
                        ->groupBy("komoditas")
                        ->map(function ($commodityGroup) {
                            return $commodityGroup->avg("harga");
                        });
                });

            // Prepare datasets for Chart.js
            $datasets = [];
            $uniqueChartCommodities = $chartResult
                ->pluck("komoditas")
                ->unique();

            foreach ($uniqueChartCommodities as $commodity) {
                $data = [];
                foreach ($labels as $date) {
                    $price = $groupedChartData
                        ->get($date, collect())
                        ->get($commodity);
                    $data[] = $price ? round($price) : null;
                }

                $datasets[] = [
                    "label" => $commodity,
                    "data" => $data,
                    "borderColor" =>
                        "rgba(" .
                        rand(0, 255) .
                        "," .
                        rand(0, 255) .
                        "," .
                        rand(0, 255) .
                        ", 1)", // Random color
                    "fill" => false,
                    "tension" => 0.1,
                ];
            }

            $chartData = [
                "labels" => $labels,
                "datasets" => $datasets,
            ];
        }
        // --- End Visualization Data Preparation ---

        $commodities = BahanPangan::distinct()->pluck("komoditas");
        $provinces = BahanPangan::distinct()->pluck("provinsi");
        \Log::info($chartData);

        return view("dashboard", [
            "tanggal_awal" => $tanggal_awal,
            "tanggal_akhir" => $tanggal_akhir,
            "data_per_komoditas" => $data_per_komoditas,
            "commodities" => $commodities,
            "provinces" => $provinces,
            "chartData" => $chartData, // Pass chart data to the view
            "chart_tanggal_awal" => $chart_tanggal_awal,
            "chart_tanggal_akhir" => $chart_tanggal_akhir,
            "chart_komoditas_selected" => $chart_komoditas_selected,
        ]);
    }

    public function showVisualization(Request $request)
    {
        // This method is no longer used and will be removed.
    }

    public function getHargaPanganByCommodity($commodity)
    {
        $data = BahanPangan::where("komoditas", $commodity)
            ->select("provinsi", "harga")
            ->orderBy("tanggal", "desc")
            ->get()
            ->groupBy("provinsi")
            ->map(function ($group) {
                return $group->first();
            })
            ->values();

        return response()->json($data);
    }
}
