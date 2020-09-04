<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Laundry\LaundryRequest;
use App\Http\Requests\Laundry\LaundryUpdateRequest;
use App\Models\MsLaundry;
use Auth;
use DataTables;
use DB;
use App\Traits\ImageTrait;

class LaundryController extends Controller
{

    use ImageTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.laundry.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.laundry.create_edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LaundryRequest $request)
    {
        try {
            DB::beginTransaction();

            MsLaundry::create([
                'name' => $request->name,
                'price' => num_uf($request->price),
                'description' => $request->description,
                'image' => $this->singleUpload($request, 'image', 'laundry'),
                'created_by' => Auth::user()->id
            ]);

            DB::commit();
            return response()->json(["message" => "$request->name successfully added"], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["message" => $e->getMessage()], 500);
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
        $data = MsLaundry::withTrashed()->findOrFail($id);
        return view('pages.laundry.create_edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LaundryUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $data = MsLaundry::withTrashed()->find($id);
            $data->update([
                'name' => $request->name,
                'price' => num_uf($request->price),
                'description' => $request->description,
                'updated_by' => Auth::user()->id
            ]);

            if ($request->hasFile('image')) {
                $image = $this->singleUpload($request, 'image', 'laundry');
                $this->removeFile($data->image);
                $data->update(['image' => $image]);
            }

            DB::commit();
            return response()->json(["message" => "$request->name successfully updated"], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["message" => $e->getMessage()], 500);
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
        MsLaundry::withTrashed()->whereIn('id', explode(',', $id))->delete();
        return response()->json(['message' => 'Data has been deactived'], 200);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        MsLaundry::withTrashed()->whereIn('id', explode(',', $id))->restore();
        return response()->json(['message' => 'Data has been deactived'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remove($id)
    {
        foreach (explode(',', $id) as $item) {
            $data = MsLaundry::withTrashed()->where('id', $item)->first();
            if ($data) {
                $data->forceDelete();
                $this->removeFile($data->image);
            }
        }
        return response()->json(['message' => 'Data successfully deleted permanently. '], 200);
    }

    /**
     * Datatables User 
     *
     * @return response
     * */
    public function data(Request $request)
    {
        $data = MsLaundry::withTrashed();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($item) {
                return '<a href="' . route('laundry.edit', $item->id) . '" >' . $item->name . '</a>';
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

    /**
     * Delete image 
     *  
     * @param int $id
     **/
    public function deleteImage($id)
    {
        $data = MsLaundry::find($id);
        $data->update(['image' => null]);
        $this->removeFile($data->image);
        return redirect()->back();
        // return response()->json(['message' => 'Image has been deleted'], 200);
    }
}
