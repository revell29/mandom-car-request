<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Http\Requests\User\UserUpdateRequest;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
use DataTables;
use Session;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $options = $this->options();
        return view('pages.user.create_edit', compact('options'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try {
            DB::beginTransaction();

            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'role' => $request->role,
                'password' => \bcrypt($request->password),
                'created_by' => Auth::user()->id
            ]);

            DB::commit();
            return response()->json(['message' => 'User successfully created.'], 200);
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
        $data = User::findOrFail($id);
        $options = $this->options();
        return view('pages.user.create_edit', compact('data', 'options'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = User::findOrFail($id);

            $data->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'role' => $request->role,
                'updated_by' => Auth::user()->id
            ]);

            if (!empty($request->password)) {
                $data->update(['password' => \bcrypt($request->password)]);
            }

            DB::commit();
            Session::flash('notification', trans('notification.itemmaster.created'));
            return response()->json(['message' => 'User has been updated'], 200);
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
        User::whereIn('id', explode(',', $id))->delete();
        return response()->json(['message' => trans('lang.user_delete')], 200);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        User::whereIn('id', explode(',', $id))->restore();
        return response()->json(['message' => trans('lang.user_delete')], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remove($id)
    {
        User::whereIn('id', explode(',', $id))->forceDelete();
        return response()->json(['message' => trans('lang.user_delete')], 200);
    }

    /**
     * Datatables User 
     *
     * @return response
     * */
    public function data(Request $request)
    {
        $data = User::withTrashed();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($item) {
                return '<a href="' . route('user.edit', $item->id) . '">' . $item->name . '</a>';
            })
            ->editColumn('deleted_at', function ($item) {
                $green = "<span style='color: green'><i class='icon-checkmark'></i></span>";
                $red = "<span style='color: red'><i class='icon-x'></i></span>";
                return is_null($item->deleted_at) ? $green : $red;
            })
            ->escapeColumns([])
            ->make(true);
    }

    private function options()
    {
        $role = ['superadmin' => 'Super Admin', 'approver' => 'Approver', 'requester' => 'Requester'];
        $options['role'] = $role;
        return $options;
    }
}
