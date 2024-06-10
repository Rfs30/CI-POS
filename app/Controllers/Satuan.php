<?php

namespace App\Controllers;

use App\Models\Modelsatuan;

class Satuan extends BaseController
{

    public function __construct()
    {
        $this->satuan = new Modelsatuan();
    }

    public function index(): string
    {

        $tombolCari = $this->request->getPost('tombolsatuan');

        if (isset($tombolCari)) {
            $cari = $this->request->getPost('carisatuan');
            session()->set('carisatuan', $cari);
            redirect()->to('/satuan');
        } else {
            $cari = session()->get('carisatuan');
        }

        $dataSatuan = $cari ? $this->satuan->cariData($cari) : $this->satuan;

        $noHalaman = $this->request->getVar('page_satuan') ? $this->request->getVar('page_satuan') : 1;
        $data = [
            'datasatuan' => $dataSatuan->paginate(10, 'satuan'),
            'pager' => $this->satuan->pager,
            'noHalaman' => $noHalaman,
            'cari' => $cari
        ];
        return view('satuan/data', $data);
    }

    function formTambah()
    {
        $aksi = $this->request->getPost('aksi');
        if ($this->request->isAJAX()) {
            $msg = [
                'data' => view('satuan/modalformtambah', ['aksi' => $aksi])
            ];

            echo json_encode($msg);
        } else {
            exit('Maaf tidak ada halaman yang bisa ditampilkan');
        }
    }

    public function simpandata()
    {
        if ($this->request->isAJAX()) {
            $namasatuan = $this->request->getVar('namasatuan');

            $this->satuan->insert([
                'satnama' => $namasatuan
            ]);

            $msg = [
                'sukses' => 'satuan berhasil ditambahkan'
            ];
            echo json_encode($msg);
        }
    }

    function hapus()
    {
        if ($this->request->isAJAX()) {
            $idsatuan = $this->request->getVar('idsatuan');

            $this->satuan->delete($idsatuan);

            $msg = [
                'sukses' => 'satuan berhasil dihapus'
            ];
            echo json_encode($msg);
        }
    }

    function formEdit()
    {
        if ($this->request->isAJAX()) {
            $idSatuan =  $this->request->getVar('idsatuan');

            $ambildatasatuan = $this->satuan->find($idSatuan);
            $data = [
                'idsatuan' => $idSatuan,
                'namasatuan' => $ambildatasatuan['satnama']
            ];

            $msg = [
                'data' => view('satuan/modalformedit', $data)
            ];
            echo json_encode($msg);
        }
    }

    function updatedata()
    {
        if ($this->request->isAJAX()) {
            $idSatuan = $this->request->getVar('idsatuan');
            $namaSatuan = $this->request->getVar('namasatuan');

            $this->satuan->update($idSatuan, [
                'satnama' => $namaSatuan
            ]);

            $msg = [
                'sukses' =>  'Data satuan berhasil diupdate'
            ];
            echo json_encode($msg);
        }
    }
}
