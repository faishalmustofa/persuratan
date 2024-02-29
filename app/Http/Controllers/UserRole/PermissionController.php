<?php

namespace App\Http\Controllers\UserRole;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-role|edit-role|delete-role', ['only' => ['index','show']]);
        $this->middleware('permission:create-role', ['only' => ['create','store']]);
        $this->middleware('permission:edit-role', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-role', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('content.setting.permission', [
            'roles' => Permission::orderBy('id','DESC')->paginate(3)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function data()
    {
        $query = Permission::orderBy('name')->get();

        return DataTables::of($query)
                // ->addIndexColumn()
                ->addColumn('assigned_to', function($query){
                    // $html = Organization::where('id',$query->organization)->first();
                    $html = 'test';
                    // $html = ;

                    return $html;
                })
                ->addColumn('created_date', function($query){
                    // $html = Organization::where('id',$query->organization)->first();
                    $date = $query->created_at;
                    $date = Carbon::parse($date)->translatedFormat('d M Y, g:i A');
                    // $html = ;

                    return $date;
                })
                // ->editColumn('action', function($query){
                //     $html = "<a href='javascript:void(0)' class='btn btn-xs btn-warning' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-title='Edit Permission'>
                //                 <i class='fas fa-pencil'></i>
                //             </a>
                //             <a href='javascript:void(0)' onclick='deleteData(`$query->id`)' class='btn btn-xs btn-danger' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-title='Delete Permission'>
                //                 <i class='fas fa-trash'></i>
                //             </a>";

                //     return $html;
                // })
                ->rawColumns(['assigned_to','created_date'])
                ->make(true);
    }
}
