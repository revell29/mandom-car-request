<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Car\CarRequest as RequestCar;
use DataTables;
use DB;
use App\Models\CarRequest;
use App\Models\MsDepartement;
use App\Models\MsEmployee;
use App\Models\MsMobil;
use App\Models\MsSupir;
use Carbon\Carbon;

class CarRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CarRequest::with('employee', 'departement');

            if (!empty($request->datefrom)) {
                $date = explode(' - ', $request->datefrom);
                $start = Carbon::createFromFormat('d/m/Y', $date[0])->toDateString();
                $end = Carbon::createFromFormat('d/m/Y', $date[1])->toDateString();
                $data->whereBetween('date', [$start, $end]);
            }

            if (!empty($request->employee_id)) {
                $data->where('employee_id', $request->employee_id);
            }

            if (!empty($request->status)) {
                $data->where('status', $request->status);
            }

            $data->orderBy('id', 'DESC')->get();

            return DataTables::of($data)
                ->editColumn('no_transaksi', function ($row) {
                    return "<a href='" . route('car_request.show', $row->id) . "'>$row->no_transaksi</a>";
                })
                ->editColumn('status', function ($item) {
                    if ($item->status == 'PROCESS') {
                        $badge = "badge-info";
                    } else if ($item->status == 'APPROVED') {
                        $badge = "badge-success";
                    } else if ($item->status == 'CANCELED') {
                        $badge = "badge-danger";
                    } else if ($item->status == 'RESERVED') {
                        $badge = "badge-success";
                    }

                    return "<a href='" . route('car_request.edit', $item->id) . "' id='open_modal_staus'><label class='badge " . $badge . "'>$item->status</label></a>";
                })
                ->escapeColumns([])
                ->make(true);
        }

        $options = $this->options();
        return view('pages.car_request.index', compact('options'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $options = $this->options();
        return view('pages.car_request.create_edit', compact('options'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestCar $request)
    {
        try {
            DB::beginTransaction();
            $checkData = CarRequest::orderBy('id', 'DESC')->first();
            $newID      = 1;
            $prefix = "TR/REQ/" . date('m') . '/' . date('yy') . '/';

            if (!$checkData) {
                $noTransaksi  = $prefix . str_pad($newID++, 5, '0', STR_PAD_LEFT);
            } else {
                $noTransaksi  = $prefix . str_pad((substr($checkData->no_transaksi, strlen($prefix)) + 1), 5, '0', STR_PAD_LEFT);
            }

            $request->merge([
                'no_transaksi' => $noTransaksi
            ]);
            CarRequest::create($request->all());

            DB::commit();
            return response()->json(['message' => 'Data berhasil di input, harap menunggu persetujuan admin.'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $options = $this->options();
        $data = CarRequest::find($id);
        return view('pages.car_request.create_edit', compact('options', 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $options = $this->options();
        $data = CarRequest::find($id);
        return view('pages.car_request.partials.modal_status', compact('data', 'options'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = CarRequest::find($id);
        if ($request->status == 'APPROVED') {
            $data->update(['approved_at' => Carbon::now(), 'status' => $request->status]);
        } else if ($request->status == 'RESERVED') {
            $data->update(['reserved_at' => Carbon::now(), 'status' => $request->status]);
        } else if ($request->status == 'CANCELED') {
            $data->update(['status' => $request->status]);
        }

        return response()->json(['message' => 'Status berhasil diperbarui.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function options()
    {
        $employee = MsEmployee::forDropdown();
        $options['employee'] = $employee;

        $departement = MsDepartement::forDropdown();
        $options['departement'] = $departement;

        $mobil = MsMobil::forDropdown();
        $options['mobil'] = $mobil;

        $supir = MsSupir::forDropdown();
        $options['supir'] = $supir;

        $status = ['PROCESS' => 'PROCESS', 'APPROVED' => 'APPROVED', 'CANCELED' => 'CANCELED', 'RESERVED' => 'RESERVED'];
        $options['status'] = $status;

        return $options;
    }
}
