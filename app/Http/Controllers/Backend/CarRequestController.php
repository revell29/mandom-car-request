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
use Barryvdh\DomPDF\Facade as PDFBar;
use PDF;


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
                $start = Carbon::createFromFormat('d/m/Y H:i', $date[0] . '00:00')->toDateTimeString();
                $end = Carbon::createFromFormat('d/m/Y H:i', $date[1] . '23:59')->toDateTimeString();
                $data->whereBetween(DB::raw('DATE(date)'), [$start, $end]);
            }

            if (auth()->user()->role == 'requester') {
                $data->where('created_by', auth()->user()->id);
            }

            if (!empty($request->employee_id)) {
                $data->where('employee_id', $request->employee_id);
            }

            if (!empty($request->status)) {
                $data->where('status', $request->status);
            }

            $data->orderBy('id', 'DESC')->get();

            return DataTables::of($data)
                ->addColumn('print', function ($row) {
                    if (auth()->user()->role == 'superadmin' || auth()->user()->role == 'approver') {
                        return "<a href='" . route('car_request.print',) . "?no_transaction=" . $row->no_transaksi . "' target='_blank'><i class='icon-printer'></i></a>";
                    } else {
                        return "<i class='icon-lock'></i>";
                    }
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
                    } else {
                        $badge = "badge-info";
                    }

                    if (auth()->user()->role == 'superadmin' || auth()->user()->role == 'approver') {
                        return "<a href='" . route('car_request.edit', $item->id) . "' id='open_modal_staus'><label class='badge " . $badge . "'>$item->status</label></a>";
                    } else {
                        return $item->status;
                    }
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
                'no_transaksi' => $noTransaksi,
                'created_by' => auth()->user()->id
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
    { }

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
        $auth = auth()->user()->id;
        if ($request->status == 'APPROVED') {
            $data->update(['approved_at' => Carbon::now(), 'status' => $request->status, 'supir_id' => $request->supir_id, 'mobil_id' => $request->mobil_id, 'updated_by' => $auth]);
        } else if ($request->status == 'RESERVED') {
            $data->update(['reserved_at' => Carbon::now(), 'status' => $request->status, 'supir_id' => $request->supir_id, 'mobil_id' => $request->mobil_id, 'updated_by' => $auth]);
        } else if ($request->status == 'CANCELED') {
            $data->update(['status' => $request->status, 'updated_by' => $auth]);
        } else if ($request->status == 'PROCESS') {
            $data->update(['status' => $request->status, 'updated_by' => $auth]);
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


    /**
     * Details page Car request
     */
    public function detail(Request $request)
    {
        return view('pages.car_request.detail');
    }

    public function formApprover(Request $request)
    {
        if ($request->ajax()) {
            $data = CarRequest::where('status', 'OPEN')->with('employee', 'departement');

            if (!empty($request->datefrom)) {
                $date = explode(' - ', $request->datefrom);
                $start = Carbon::createFromFormat('d/m/Y H:i', $date[0] . '00:00')->toDateTimeString();
                $end = Carbon::createFromFormat('d/m/Y H:i', $date[1] . '23:59')->toDateTimeString();
                $data->whereBetween(DB::raw('DATE(date)'), [$start, $end]);
            }

            $data->orderBy('id', 'DESC')->get();

            return DataTables::of($data)
                ->addColumn('print', function ($row) {
                    if (auth()->user()->role == 'superadmin' || auth()->user()->role == 'approver') {
                        return "<a href='" . route('car_request.print',) . "?no_transaction=" . $row->no_transaksi . "' target='_blank'><i class='icon-printer'></i></a>";
                    } else {
                        return "<i class='icon-lock'></i>";
                    }
                })
                ->editColumn('status', function ($item) {
                    return \Form::select('status', ['PROCESS' => 'PROCESS', "CANCELED" => 'CANCELED', 'OPEN' => 'OPEN'], $item->status, ['class' => 'form-control status_selected', 'style' => 'width:120px;', 'data-id' => $item->id]);
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('pages.car_request.form_approver');
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $data = CarRequest::with('mobil', 'supir', 'departement', 'employee')->where('no_transaksi', $request->no_transaction)->first();
            return response()->json($data);
        }
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

    public function printPdf(Request $request)
    {
        $data = CarRequest::with('mobil', 'supir', 'employee', 'departement')->where('no_transaksi', $request->get('no_transaction'))->first();
        $pdf = PDF::loadView('pages.car_request.document', compact('data'));
        return $pdf->stream('surat-jalan.pdf');
    }

    public function exportReport(Request $request)
    {
        $data = CarRequest::with('employee', 'departement', 'supir', 'mobil');

        if (!empty($request->datefrom)) {
            $date = explode(' - ', $request->datefrom);
            $start = Carbon::createFromFormat('d/m/Y H:i', $date[0] . '00:00')->toDateTimeString();
            $end = Carbon::createFromFormat('d/m/Y H:i', $date[1] . '23:59')->toDateTimeString();
            $data->whereBetween(DB::raw('DATE(date)'), [$start, $end]);
        }

        if (!empty($request->employee_id)) {
            $data->where('employee_id', $request->employee_id);
        }

        if (!empty($request->status)) {
            $data->where('status', $request->status);
        }

        $data = $data->orderBy('id', 'DESC')->get();
        $pdf = PDFBar::loadView('pages.car_request.report', compact('data', 'request'))->setPaper('a4', 'landscape');
        return $pdf->stream('report-car-request.pdf');
    }
}
