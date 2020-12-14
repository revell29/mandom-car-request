<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\MobilRequest;
use Illuminate\Http\Request;
use DataTables;
use DB;
use App\Models\MsMobil;
use Symfony\Component\VarDumper\Cloner\Data;

class MobilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MsMobil::select('*');

            return DataTables::of($data)
                ->editColumn('no_polisi', function ($row) {
                    return "<a href='" . route('mobil.edit', $row->id) . "'>$row->no_polisi</a>";
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('pages.mobil.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.mobil.create_edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MobilRequest $request)
    {
        MsMobil::create($request->all());
        return response()->json(['message' => 'Mobil berhasil ditambahkan.'], 200);
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
        $data = MsMobil::find($id);
        return view('pages.mobil.create_edit', compact('data'));
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
        $data = MsMobil::find($id);
        $data->update($request->all());

        return response()->json(['message' => 'Data mobil berhasil diperbarui.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MsMobil::whereIn('id', explode(',', $id))->delete();
        return response()->json(['message' => 'Data mobil berhasil dihapus.'], 200);
    }
}
