<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\EmployeeRequest;
use App\Models\MsDepartement;
use Illuminate\Http\Request;
use DB;
use App\Models\MsEmployee;
use DataTables;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MsEmployee::withTrashed()->with('departement');
            return DataTables::of($data)
                ->editColumn('name', function ($item) {
                    return "<a href='" . route('employee.edit', $item->id) . "'>$item->name</a>";
                })
                ->editColumn('deleted_at', function ($item) {
                    $green = "<span style='color: green'><i class='icon-checkmark'></i></span>";
                    $red = "<span style='color: red'><i class='icon-x'></i></span>";
                    return is_null($item->deleted_at) ? $green : $red;
                })
                ->escapeColumns([])
                ->make(true);
        }
        return view('pages.employee.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $options = $this->options();
        return view("pages.employee.create_edit", compact('options'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request)
    {
        try {
            DB::beginTransaction();
            $request->merge([
                'password' => bcrypt($request->password),
                'birth_date' => Carbon::parse($request->birth_date)->toDateString()
            ]);
            MsEmployee::create($request->except('password_confirmation'));
            DB::commit();
            return response()->json(['message' => 'Employee berhasil ditambahkan.'], 200);
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
        $data = MsEmployee::find($id);
        $options = $this->options();
        return view('pages.employee.create_edit', compact('data', 'options'));
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
            $request->merge([
                'password' => bcrypt($request->password),
                'birth_date' => Carbon::parse($request->birth_date)->toDateString()
            ]);
            $data = MsEmployee::find($id);
            $data->update($request->except('password_confirmation'));
            DB::commit();
            return response()->json(['message' => 'Employee berhasil diupdate.'], 200);
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
        MsEmployee::withTrashed()->whereIn('id', explode(',', $id))->delete();
        return response()->json(['message' => 'Employee berhasil dinonaktifkan'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        MsEmployee::withTrashed()->whereIn('id', explode(',', $id))->restore();
        return response()->json(['message' => 'Employee berhasil diaktifkan'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remove($id)
    {
        MsEmployee::withTrashed()->whereIn('id', explode(',', $id))->forceDelete();
        return response()->json(['message' => 'Employee berhasil dihapus'], 200);
    }

    /**
     * Options  
     **/
    private function options()
    {
        $departement = MsDepartement::forDropdown();
        $options['departement'] = $departement;

        return $options;
    }
}
