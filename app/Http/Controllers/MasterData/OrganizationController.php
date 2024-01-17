<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\Organization;
use App\Models\Transaction\SuratMasuk;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('content.organization.index');
    }

    public function data(Request $request)
    {
        $org = Organization::orderBy('id')->get();

        return DataTables::of($org)
                ->addIndexColumn()
                ->editColumn('parent', function($data){
                    if($data->parent_id != null){
                        $parent = Organization::where('id', $data->parent_id)->first();

                        return "<span> $parent->nama </span>";
                    }
                })
                ->editColumn('blanko', function($data){
                    $html = '';
                    if($data->blanko_path != null){
                        $html = "<span class='badge rounded-pill bg-label-success'>Template Blanko Disposisi Sudah Ada</span>";
                    } else {
                        $html = "<span class='badge rounded-pill bg-label-warning'>Template Blanko Disposisi Belum Ada</span>";
                    }

                    return $html;
                })
                ->editColumn('action', function($data){
                    $html = '';
                    $html .= "<a href='".route('organization.edit', $data->id)."' class='btn btn-warning btn-sm'><span class='mdi mdi-square-edit-outline'></span></a>";
                    $html .= "<button class='btn btn-danger btn-sm mt-2' onclick='deleteData(`$data->id`)'><span class='mdi mdi-delete'></span></button>";

                    return $html;
                })
                ->rawColumns(['action', 'parent', 'blanko'])
                ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['orgAll'] = Organization::get();
        return view('content.organization.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $file = '';
            if ($request->hasFile('blanko_path')) {
                $documentFile = $request->file('blanko_path');
                $filename = 'blanko_'.strtolower($request->nama);
                $path = $filename.'.docx';
                $documentFile->move(public_path().'/document/blanko_disposisi/', $path);
                $file = $path;
            }

            if ($file != '') {
                $file_path = $file;
            } else {
                $file_path = null;
            }

            $data = [
                'nama' => $request->nama,
                'leader_alias' => $request->leader_alias,
                'parent_id' => $request->parent_id,
                'description' => $request->description,
                'blanko_path' => $file_path,
                'suffix_agenda' => $request->suffix_agenda
            ];

            Organization::create($data);

            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil Simpan Data Satker / Unit Kerja'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Terjadi Kesalahan Pada Sistem, Harap Coba Lagi',
                'detail' => $th
            ]);
        }
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
        $data['org'] = Organization::find($id);
        $data['orgAll'] = Organization::get();

        return view('content.organization.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $org = Organization::where('id', $id);

            $file = '';
            if($org->blanko_path == null){
                if ($request->hasFile('blanko_path')) {
                    $documentFile = $request->file('blanko_path');
                    $filename = 'blanko_'.strtolower($request->nama);
                    $path = $filename.'.docx';
                    $documentFile->move(public_path().'/document/blanko_disposisi/', $path);
                    $file = $path;
                }

                if ($file != '') {
                    $file_path = $file;
                } else {
                    $file_path = null;
                }
            }

            $data = [
                'nama' => $request->nama,
                'leader_alias' => $request->leader_alias,
                'parent_id' => $request->parent_id,
                'description' => $request->description,
                'blanko_path' => $org->blanko_path == null ? $file_path : $org->blanko_path,
                'suffix_agenda' => $request->suffix_agenda
            ];

            $org->update($data);

            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil Rubah Data Satker / Unit Kerja'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Terjadi Kesalahan Pada Sistem, Harap Coba Lagi',
                'detail' => $th
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            // Check if has surat
            $surat = SuratMasuk::where('tujuan_surat', $id)->count();
            if($surat > 0){
                throw new Exception('Unit Kerja ini sudah memiliki data Surat Masuk atau Surat Keluar, tidak bisa menghapus Unit Kerja', 422);
            }

            $org = Organization::where('id', $id)->delete();

            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil Hapus Data Unit Kerja / Satker'
            ]);
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json([
                'status' => [
                    'msg' => $th->getMessage() != '' ? $th->getMessage() : 'Err',
                    'code' => $th->getCode() != '' ? $th->getCode() : JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                ],
                'data' => null,
                'err_detail' => $th,
                'message' => $th->getMessage() != '' ? $th->getMessage() : 'Terjadi Kesalahan Saat Hapus Data, Harap Coba lagi!'
            ]);
        }
    }
}
