<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Food\FoodRequest;
use Illuminate\Http\Request;
use DB;
use Auth;
use DataTables;
use App\Models\MsFood;
use App\Models\MsFoodCategory;
use App\Traits\ImageTrait;

class FoodController extends Controller
{

    use ImageTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.f&b.food.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = MsFoodCategory::categoryDropdown();
        return view('pages.f&b.food.create_edit', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FoodRequest $request)
    {
        try {
            DB::beginTransaction();

            $input = $request->all();
            $input['image'] = $this->singleUpload($request, 'image', 'food');
            $input['created_by'] = Auth::user()->id;
            $input['price'] = num_uf($request->price);
            MsFood::create($input);

            DB::commit();
            return response()->json(["message" => "$request->name successfully added"], 200);
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
        $data = MsFood::withTrashed()->findOrFail($id);
        $category = MsFoodCategory::categoryDropdown();
        return view('pages.f&b.food.create_edit', compact('category', 'data'));
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

            $data = MsFood::withTrashed()->findOrFail($id);
            $data->update([
                'name' => $request->name,
                'price' => num_uf($request->price),
                'category_id' => $request->category_id,
                'description' => $request->description,
                'updated_by' => Auth::user()->id
            ]);

            if (!is_null($request->image)) {
                $image = $this->singleUpload($request, 'image', 'food');
                $this->removeFile($data->image);
                $data->update(['image' => $image]);
            }

            DB::commit();
            return response()->json(["message" => "$request->name successfully updated"], 200);
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
        MsFood::whereIn('id', explode(',', $id))->delete();
        return response()->json(['message' => 'Food has been deactived.'], 200);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        MsFood::whereIn('id', explode(',', $id))->restore();
        return response()->json(['message' => 'Food has been actived.'], 200);
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
            $data = MsFood::withTrashed()->where('id', $item)->first();
            if ($data) {
                $data->forceDelete();
                $this->removeFile($data->image);
            }
        }
        return response()->json(['message' => 'Food successfully deleted permanently. '], 200);
    }

    /**
     * Datatables User 
     *
     * @return response
     * */
    public function data(Request $request)
    {
        $data = MsFood::with('category')->withTrashed();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($item) {
                return '<a href="' . route('food_list.edit', $item->id) . '" >' . $item->name . '</a>';
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
        $data = MsFood::find($id);
        $data->update(['image' => null]);
        $this->removeFile($data->image);
        return redirect()->back();
        // return response()->json(['message' => 'Image has been deleted'], 200);
    }
}
