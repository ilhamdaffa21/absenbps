<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PesertaMagangModel;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\QrCode;
use Dompdf\Dompdf;

class Kartuabsen extends BaseController
{

    public function __construct()
    {
        $this->pesertaMagang = new PesertaMagangModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Ambil QR',
            'isi'   => 'kartuabsen/index',
            'data'  => $this->pesertaMagang->findAll()
        ];
        echo view('layout_admin/v_wrapper', $data);
    }

    public function genKartu()
    {
        $input = $this->request->getPost()['id_peserta'];
        if ($input != "") {
            $data = $this->pesertaMagang->find($input);
            $title = 'ambil qr';
            $writer = new PngWriter();
            $qrCode = QrCode::create($data['nim']);
            $result = $writer->write($qrCode, null, null);
            $dataUri = $result->getDataUri();
            echo view('kartuabsen/wrapper', ['data' => $data, 'uri' => $dataUri, 'title' => $title]);
        } else {
            return redirect()->to('/kartuabsen')->with('error', 'Data tidak ditemukan');
        }
    }
}
