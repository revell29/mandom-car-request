<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MsCustomer;
use Illuminate\Http\Request;
use App\Models\MsFoodCategory;
use DataTables;
use Auth;
use DB;

class FoodCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.f&b.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            $input = $request->all();
            $input['created_by'] = Auth::user()->id;
            MsFoodCategory::create($input);
            DB::commit();
            return response()->json(['message' => 'Food category successfully added.'], 200);
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
        $data = MsFoodCategory::withTrashed()->find($id);
        return response()->json(['data' => $data], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            $input = $request->all();
            $input['created_by'] = Auth::user()->id;
            $data = MsFoodCategory::withTrashed()->findOrFail($id);
            $data->update($input);
            DB::commit();
            return response()->json(['message' => 'Food category successfully updated.'], 200);
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
        MsFoodCategory::withTrashed()->whereIn('id', explode(',', $id))->delete();
        return response()->json(['message' => 'Category has been deactived'], 200);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        MsFoodCategory::withTrashed()->whereIn('id', explode(',', $id))->restore();
        return response()->json(['message' => 'Category has been deactived'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remove($id)
    {
        MsFoodCategory::withTrashed()->whereIn('id', explode(',', $id))->forceDelete();
        return response()->json(['message' => 'Category has been deactived'], 200);
    }

    /**
     * Datatables User 
     *
     * @return response
     * */
    public function data(Request $request)
    {
        $data = MsFoodCategory::withTrashed();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($item) {
                return '<a href="#" id="edit_category" data-id="' . $item->id . '">' . $item->name . '</a>';
            })
            ->editColumn('deleted_at', function ($item) {
                $green = "<span style='color: green'><i class='icon-checkmark'></i></span>";
                $red = "<span style='color: red'><i class='icon-x'></i></span>";
                return is_null($item->deleted_at) ? $green : $red;
            })
            ->editColumn('created_at', function ($row) { })
            ->escapeColumns([])
            ->make(true);
    }
}
