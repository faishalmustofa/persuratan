<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Reference\JenisSurat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class JenisSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('content.jenis-surat.index');
    }

    public function data()
    {
        $jenisSurat = JenisSurat::orderBy('id')->get();

        return DataTables::of($jenisSurat)
                ->addIndexColumn()
                ->editColumn('action', function($data){
                    $html = '<a href="'.route('jenis-surat.edit', base64_encode($data->id)).'" class="btn btn-outline-warning btn-sm rounded-pill px-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data" > <span class="mdi mdi-square-edit-outline"></span> </a>
                    <button onclick="deleteData(`'.$data->id.')" class="btn btn-outline-danger btn-sm rounded-pill px-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data" > <span class="mdi mdi-delete"></span> </button>';

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
        $data['allJenisSurat'] = JenisSurat::get();
        return view('content.jenis-surat.create', $data);
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
    public function edit($id)
    {
        $data['allJenisSurat'] = JenisSurat::get();
        $data['jenis_surat'] = JenisSurat::find(base64_decode($id));
        return view('content.jenis-surat.create', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        dd($request->all());
        // DB::beginTransaction();
        // try {
        //     dd($request->all());
        //     $org = JenisSurat::where('id', $id);

        //     $data = [
        //         'nama' => $request->nama,
        //         'format' => $request->format,
        //         'deskripsi' => $request->deskripsi
        //     ];

        //     $org->update($data);

        //     DB::commit();
        //     return response()->json([
        //         'status' => JsonResponse::HTTP_OK,
        //         'message' => 'Berhasil Update Data Jenis Surat'
        //     ]);
        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     return response()->json([
        //         'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
        //         'message' => 'Terjadi Kesalahan Pada Sistem, Harap Coba Lagi',
        //         'detail' => $th
        //     ]);
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
