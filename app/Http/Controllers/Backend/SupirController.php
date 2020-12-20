<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MsMobil;
use Illuminate\Http\Request;
use DataTables;
use App\Models\MsSupir;

class SupirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MsSupir::select('*');

            return DataTables::of($data)
                ->editColumn('nip', function ($row) {
                    return "<a href='" . route('supir.edit', $row->id) . "'>$row->nip</a>";
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('pages.supir.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $options = $this->options();
        return view('pages.supir.create_edit', compact('options'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        MsSupir::create($request->all());
        return response()->json(['message' => 'Supir berhasil ditambahkan.'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = MsSupir::find($id);
        $options = $this->options();
        return view('pages.supir.create_edit', compact('data', 'options'));
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
        $data = MsSupir::find($id);
        $data->update($request->all());
        return response()->json(['message' => 'Supir berhasil diupdate'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MsSupir::whereIn('id', explode(',', $id))->remove();
        return response()->json(['message' => 'Supir Berhasil di hapus.'], 200);
    }

    private function options()
    {
        $car = MsMobil::forDropdown();
        $options['mobil'] = $car;

        return $options;
    }
}
