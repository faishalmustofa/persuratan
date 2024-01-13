<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Master\Organization;
use App\Models\Transaction\DisposisiSuratMasuk;
use App\Models\Transaction\SuratMasuk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;

class DisposisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('content.surat_masuk.disposisi');
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
        $tujuan = $request->tujuan_disposisi;
        $dataSurat = SuratMasuk::where('tx_number', $request->tx_number)
                        ->with('klasifikasiSurat')
                        ->with('derajatSurat')
                        ->first();
        $jml_lampiran = $dataSurat->lampiran == null ? '' : $dataSurat->lampiran_type . ' ' . $dataSurat->jml_lampiran;

        $template_document = new TemplateProcessor(public_path().'/document/blanko_disposisi.docx');
        $template_document->cloneBlock('tujuan_section', count($tujuan),true,true);
        // $template_document->cloneRow('tujuan', count($tujuan));
        for ($i=0; $i < count($tujuan); $i++) {
            $dt_tujuan = Organization::where('id', $tujuan[$i])->first();
            $template_document->setValues(array(
                "tujuan#".$i+1 => $dt_tujuan->leader_alias == 'null' ? strtoupper($dt_tujuan->nama) : strtoupper($dt_tujuan->leader_alias),
            ));

            DisposisiSuratMasuk::create([
                'tx_number' => $request->tx_number,
                'no_agenda' => $dataSurat->no_agenda,
                'tujuan_disposisi' => $tujuan[$i],
            ]);
        }

        SuratMasuk::where('tx_number', $request->tx_number)->update([
            'status_surat' => 2
        ]);

        $template_document->setValues(array(
            'klasifikasi' => $dataSurat->klasifikasiSurat->nama,
            'derajat' => $dataSurat->derajatSurat->nama,
            'no_agenda' => $dataSurat->no_agenda,
            'asal_surat' => $dataSurat->entity_asal_surat_detail,
            'no_surat' => $dataSurat->no_surat,
            'tgl_surat' => $dataSurat->tgl_surat,
            'perihal' => $dataSurat->perihal,
            'catatan' => $dataSurat->catatan,
            'lampiran' => $dataSurat->lampiran != null ? $dataSurat->lampiran : 'Tidak Ada Lampiran',
            'jml_lampiran' => $jml_lampiran,
        ));

        $filename = 'Blanko Disposisi - '. $request->tx_number .'.docx';
        $path = public_path().'/document/disposisi/'.$filename;
        $template_document->saveAs($path);

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'Berhasil Buat Agenda Surat',
            'file' => $filename,
        ]);
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
}
