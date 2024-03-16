<?php

namespace App\Http\Controllers\UserRole;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Master\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-user|edit-user|delete-user', ['only' => ['index','show']]);
        $this->middleware('permission:create-user', ['only' => ['create','store']]);
        $this->middleware('permission:edit-user', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-user', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $organization = Organization::all();
        $role = Role::all();
        $data = [
            'users' => User::latest('id')->paginate(3),
            'organization' => $organization,
            'roles' => $role,
        ];

        return view('content.setting.user-list', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('users.create', [
            'roles' => Role::pluck('name')->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();
            $input['password'] = Hash::make('Propam12345');
            
            $user = User::create($input);
            // $user->assignRole($request->role);
            
            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'User baru telah ditambahkan.',
                'title' => 'Berhasil tambah user baru.',
                'user' => $user,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Terjadi Kesalahan Pada Sistem, Harap Coba Lagi',
                'detail' => $e
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        return view('users.show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user, $id)
    {
        // Check Only Super Admin can update his own Profile
        if ($user->hasRole('Super Admin')){
            if($user->id != auth()->user()->id){
                abort(403, 'USER DOES NOT HAVE THE RIGHT PERMISSIONS');
            }
        }

        $id= base64_decode($id);
        $data = User::find($id);

        if ($data){
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => true,
                'message' => 'Berhasil ambil data.',
                'user' => $data,
            ]);
        } else {
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ]);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user,$id)
    {
        DB::beginTransaction();
        try {
            $id = base64_decode($id);
            $user = User::find($id);

            if ($user) {
                $user->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'organization' => $request->organization,
                    'jabatan' => $request->jabatan,
                ]);

                DB::commit();
                return response()->json([
                    'status' => JsonResponse::HTTP_OK,
                    'success' => true,
                    'message' => 'Berhasil update data user.',
                    'title' => 'User telah diupdate.',
                    'user' => $user,
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'status' => JsonResponse::HTTP_OK,
                    'success' => false,
                    'message' => 'User tidak ditemukan.',
                    'title' => 'User not found.',
                ]);
            }
            
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Terjadi Kesalahan Pada Sistem, Harap Coba Lagi',
                'detail' => $e
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, $id)
    {
        DB::beginTransaction();
        try {
            $id = base64_decode($id);
            $user = User::find($id);

            if ($user) {
                $user->delete();

                DB::commit();
                return response()->json([
                    'status' => JsonResponse::HTTP_OK,
                    'success' => true,
                    'message' => 'Berhasil hapus user.',
                    'title' => 'User telah dihapus.',
                    'user' => $user,
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'status' => JsonResponse::HTTP_OK,
                    'success' => false,
                    'message' => 'User tidak ditemukan.',
                    'title' => 'User not found.',
                ]);
            }
            
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Terjadi Kesalahan Pada Sistem, Harap Coba Lagi',
                'detail' => $e
            ]);
        }
    }

    public function data()
    {
        $query = User::orderBy('name')->get();

        return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('organization', function($query){
                    $org = Organization::where('id',$query->organization)->first();

                    return $org->nama;
                })
                // ->editColumn('email', function($query){

                //     return $query->email;
                // })
                ->editColumn('action', function($query){
                    $html = "<a href='javascript:void(0)' class='btn btn-xs btn-warning' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-title='Edit Permission'>
                                <i class='fas fa-pencil'></i>
                            </a>
                            <a href='javascript:void(0)' onclick='deleteData(`$query->id`)' class='btn btn-xs btn-danger' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-title='Delete Permission'>
                                <i class='fas fa-trash'></i>
                            </a>";

                    return $html;
                })
                ->rawColumns(['organization','action'])
                ->make(true);
    }
}