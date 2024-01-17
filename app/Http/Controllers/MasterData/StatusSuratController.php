<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\StatusSurat;
use App\Models\Transaction\SuratMasuk;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\DataTables;

class StatusSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('content.status-surat.index');
    }

    public function data(Request $request)
    {
        $data = StatusSurat::orderBy('id')->get();

        return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('action', function($data){
                    $html = '';
                    $html .= "<a href='".route('status-surat.edit', $data->id)."' class='btn btn-warning btn-sm'><span class='mdi mdi-square-edit-outline'></span></a>";
                    $html .= "<button class='btn btn-danger btn-sm ms-2' onclick='deleteData(`$data->id`)'><span class='mdi mdi-delete'></span></button>";

                    return $html;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.status-surat.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = [
                'name' => $request->name,
                'description' => $request->description,
            ];

            StatusSurat::create($data);

            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil Simpan Data Status Surat'
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
        $data['status'] = StatusSurat::where('id', $id)->first();
        return view('content.status-surat.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $data = [
                'name' => $request->name,
                'description' => $request->description,
            ];

            StatusSurat::where('id', $id)->update($data);

            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil Rubah Data Status Surat'
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
            $surat = SuratMasuk::where('status_surat', $id)->count();
            if($surat > 0){
                throw new Exception('Tidak dapat menghapus Status Surat ini karena ada data surat dengan status yang anda pilih, tidak bisa menghapus Unit Kerja', 422);
            }

            $org = StatusSurat::where('id', $id)->delete();

            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil Hapus Data Status Surat'
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
