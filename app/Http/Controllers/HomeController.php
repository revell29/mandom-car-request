<?php

namespace App\Http\Controllers;

use App\Models\MsEmployee;
use App\Models\CarRequest;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['employee'] = MsEmployee::count();
        $data['transaksi'] = CarRequest::count();
        return view('home', compact('data'));
    }

    public function getChart(Request $request)
    {
        $data = CarRequest::chartTransaction();
        $labels = [];
        $all_values = [];
        $dates = [];
        for ($i = 20; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDay($i)->format('Y-m-d');
            $dates[] = $date;

            $labels[] = date('d M Y', strtotime($date));

            if (!empty($data['internal'][$date]) || !empty($data['eksternal'][$date])) {
                $all_values['internal'][] = (float) $data['internal'][$date];
                $all_values['eksternal'][] = (float) $data['eksternal'][$date];
            } else {
                $all_values['internal'][] = 0;
                $all_values['eksternal'][] = 0;
            }
        }

        return response()->json(['label' => $labels, 'data' => $all_values], 200);
    }
}
