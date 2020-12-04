<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MsDepartement;
use DB;
use DataTables;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = MsDepartement::withTrashed();
            return DataTables::of($data)
                ->editColumn('name', function ($item) {
                    return "<a href='" . route('departement.edit', $item->id) . "'>$item->name</a>";
                })
                ->editColumn('deleted_at', function ($item) {
                    $green = "<span style='color: green'><i class='icon-checkmark'></i></span>";
                    $red = "<span style='color: red'><i class='icon-x'></i></span>";
                    return is_null($item->deleted_at) ? $green : $red;
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('pages.departement.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("pages.departement.create_edit");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            MsDepartement::create($request->all());
            DB::commit();
            return response()->json(['message' => 'Departement berhasil ditambahkan'], 200);
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
        $data = MsDepartement::find($id);
        return view('pages.departement.create_edit', compact('data'));
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
        try {
            DB::beginTransaction();
            $data = MsDepartement::find($id);
            $data->update($request->all());
            DB::commit();
            return response()->json(['message' => 'Departement berhasil diperbarui.'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MsDepartement::withTrashed()->whereIn('id', explode(',', $id))->delete();
        return response()->json(['message' => 'Departement berhasil dinonaktifkan'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        MsDepartement::withTrashed()->whereIn('id', explode(',', $id))->restore();
        return response()->json(['message' => 'Departement berhasil diaktifkan'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remove($id)
    {
        MsDepartement::withTrashed()->whereIn('id', explode(',', $id))->forceDelete();
        return response()->json(['message' => 'Departement berhasil dihapus'], 200);
    }
}
