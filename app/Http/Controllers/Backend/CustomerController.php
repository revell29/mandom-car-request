<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerRequest;
use App\Http\Requests\Customer\CustomerUpadateRequest;
use Illuminate\Http\Request;
use App\Models\MsCustomer;
use App\Traits\ImageTrait;
use DataTables;
use DB;
use Auth;

class CustomerController extends Controller
{
    use ImageTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.customer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.customer.create_edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        try {
            DB::beginTransaction();

            $input = $request->all();
            $input['image'] = $this->singleUpload($request, 'image', 'customer');
            $input['created_by'] = Auth::user()->id;
            MsCustomer::create($input);

            DB::commit();
            return response()->json(['message' => trans('lang.customer.added')], 200);
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
        $data = MsCustomer::withTrashed()->findOrFail($id);
        return view('pages.customer.create_edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerUpadateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $data = MsCustomer::withTrashed()->findOrFail($id);
            $data->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'ktp' => $request->ktp,
                'email' => $request->email,
                'updated_by' => Auth::user()->id
            ]);

            if (!is_null($request->image)) {
                $images = $this->singleUpload($request, 'image', 'customer');
                $this->removeFile($data->image);
                $data->update(['image' => $images]);
            }

            DB::commit();
            return response()->json(['message' => trans('lang.customer.updated')], 200);
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
        MsCustomer::withTrashed()->whereIn('id', explode(',', $id))->delete();
        return response()->json(['message' => 'Customer has been deactived.'], 200);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        MsCustomer::withTrashed()->whereIn('id', explode(',', $id))->restore();
        return response()->json(['message' => 'Customer has been actived.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remove($id)
    {
        MsCustomer::withTrashed()->whereIn('id', explode(',', $id))->forceDelete();
        return response()->json(['message' => 'customer successfully deleted permanently.'], 200);
    }

    /**
     * Datatables User 
     *
     * @return response
     * */
    public function data(Request $request)
    {
        $data = MsCustomer::withTrashed();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($item) {
                return '<a href="' . route('customer.edit', $item->id) . '">' . $item->name . '</a>';
            })
            ->editColumn('deleted_at', function ($item) {
                $green = "<span style='color: green'><i class='icon-checkmark'></i></span>";
                $red = "<span style='color: red'><i class='icon-x'></i></span>";
                return is_null($item->deleted_at) ? $green : $red;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d F Y H:m');
            })
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
        $data = MsCustomer::find($id);
        $data->update(['image' => null]);
        $this->removeFile($data->image);
        return redirect()->back();
        // return response()->json(['message' => 'Image has been deleted'], 200);
    }
}
