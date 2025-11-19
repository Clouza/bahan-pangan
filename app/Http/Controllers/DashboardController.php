<?php

namespace App\Http\Controllers;

use App\Models\BahanPangan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Exports\BahanPanganExport;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function exportExcel()
    {
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subMonth();

        return Excel::download(new BahanPanganExport($startDate, $endDate), 'bahan_pangan_satu_bulan.xlsx');
    }

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

        // --- Visualization Data Preparation for Bar Chart ---
        $latestPrices = BahanPangan::select('komoditas', 'harga')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('bahan_pangans')
                    ->groupBy('komoditas');
            })
            ->get();

        $chartLabels = $latestPrices->pluck('komoditas');
        $chartValues = $latestPrices->pluck('harga');

        $maxPrice = $chartValues->max();

        $backgroundColors = $chartValues->map(function ($price) use ($maxPrice) {
            return $price == $maxPrice ? '#ef4444' : '#22c55e'; // Red for max, Green for others
        });

        $barChartData = [
            'labels' => $chartLabels,
            'datasets' => [
                [
                    'label' => 'Harga Terkini',
                    'data' => $chartValues,
                    'backgroundColor' => $backgroundColors,
                ],
            ],
        ];
        // --- End Visualization Data Preparation ---

        $commodities = BahanPangan::distinct()->pluck('komoditas');
        $provinces = BahanPangan::distinct()->pluck('provinsi');

        return view('dashboard', [
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'data_per_komoditas' => $data_per_komoditas,
            'commodities' => $commodities,
            'provinces' => $provinces,
            'barChartData' => $barChartData, // Pass bar chart data to the view
        ]);
    }

    public function kursDollar()
    {
        try {
            $response = Http::get('https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies/usd.json');

            if ($response->successful()) {
                $data = $response->json();
                $idrRate = $data['usd']['idr'] ?? null;
                $date = $data['date'] ?? null;

                if ($idrRate && $date) {
                    return view('kurs_dollar', [
                        'date' => \Carbon\Carbon::parse($date)->isoFormat('D MMMM YYYY'),
                        'idrRateFormatted' => number_format($idrRate, 2, ',', '.')
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to fetch currency data: ' . $e->getMessage());
        }

        return view('kurs_dollar', [
            'error' => 'Gagal mengambil data kurs.'
        ]);
    }

    public function hargaEmas()
    {
        try {
            // Fire two requests concurrently
            $responses = Http::pool(fn ($pool) => [
                $pool->as('gold')->get('https://freegoldapi.com/data/latest.json'),
                $pool->as('currency')->get('https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies/usd.json'),
            ]);

            // Check if both requests were successful
            if ($responses['gold']->successful() && $responses['currency']->successful()) {
                $goldData = $responses['gold']->json();
                $currencyData = $responses['currency']->json();

                $idrRate = $currencyData['usd']['idr'] ?? null;
                if (!$idrRate) {
                    throw new \Exception('Kurs IDR tidak ditemukan.');
                }

                // Get the latest price
                $latestGoldEntry = end($goldData);
                $latestPriceUsd = $latestGoldEntry['price']; // This is in USD per ounce
                $latestDate = $latestGoldEntry['date'];

                // Convert to IDR per ounce
                $latestPriceIdrPerOunce = $latestPriceUsd * $idrRate;

                // Convert from ounce to gram (1 troy ounce = 31.1035 grams)
                $gramsPerOunce = 31.1035; // More precise value
                $latestPriceIdrPerGram = $latestPriceIdrPerOunce / $gramsPerOunce;

                // Prepare chart data for the current year
                $currentYear = date('Y');
                $chartHistory = array_filter($goldData, function ($entry) use ($currentYear) {
                    return str_starts_with($entry['date'], $currentYear);
                });

                $chartLabels = [];
                $chartPrices = [];
                foreach ($chartHistory as $entry) {
                    // Convert each historical price to IDR per gram
                    $priceUsdPerOunce = $entry['price'];
                    $priceIdrPerOunce = $priceUsdPerOunce * $idrRate;
                    $priceIdrPerGram = $priceIdrPerOunce / $gramsPerOunce;

                    $chartLabels[] = \Carbon\Carbon::parse($entry['date'])->format('d M');
                    $chartPrices[] = $priceIdrPerGram;
                }

                $chartData = [
                    'labels' => $chartLabels,
                    'datasets' => [
                        [
                            'label' => 'Harga Emas (IDR)',
                            'data' => $chartPrices,
                            'borderColor' => 'rgba(234, 179, 8, 1)',
                            'backgroundColor' => 'rgba(234, 179, 8, 0.2)',
                            'fill' => true,
                            'tension' => 0.1,
                        ],
                    ],
                ];

                return view('harga_emas', [
                    'latestDate' => \Carbon\Carbon::parse($latestDate)->isoFormat('D MMMM YYYY'),
                    'latestPriceFormatted' => number_format($latestPriceIdrPerGram, 2, ',', '.'),
                    'chartData' => $chartData,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Gagal mengambil data harga emas: ' . $e->getMessage());
        }

        return view('harga_emas', [
            'error' => 'Gagal mengambil data harga emas.'
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
