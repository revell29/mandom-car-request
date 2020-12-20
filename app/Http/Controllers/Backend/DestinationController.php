<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MsDestination;
use DataTables;

class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MsDestination::withTrashed()->select('*');
            return DataTables::of($data)
                ->editColumn('kota', function ($row) {
                    return "<a href='" . route('destinasi.edit', $row->id) . "'>$row->kota</a>";
                })
                ->editColumn('deleted_at', function ($item) {
                    $green = "<span style='color: green'><i class='icon-checkmark'></i></span>";
                    $red = "<span style='color: red'><i class='icon-x'></i></span>";
                    return is_null($item->deleted_at) ? $green : $red;
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('pages.destinasi.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.destinasi.create_edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        MsDestination::create($request->all());
        return response()->json(['message' => 'Destinasi berhasil ditambahkan.'], 200);
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
        $data = MsDestination::find($id);
        return view('pages.destinasi.create_edit', compact('data'));
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
        $data = MsDestination::find($id);
        $data->update($request->all());
        return response()->json(['message' => 'Destinasi berhasil diupdate'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MsDestination::whereIn('id', explode(',', $id))->delete();
        return response()->json(['message' => 'Destinasi di nonaktifkan.'], 200);
    }


    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        MsDestination::whereIn('id', explode(',', $id))->restore();
        return response()->json(['message' => 'Destinasi diaktifkan.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remove($id)
    {
        MsDestination::whereIn('id', explode(',', $id))->forceDelete();
        return response()->json(['message' => 'Destinasi berhasil dihapus.'], 200);
    }
}
