<?php

namespace App\Controllers;

use App\Models\Modelkategori;

class Kategori extends BaseController
{
    protected $kategori;

    public function __construct()
    {
        $this->kategori = new Modelkategori();
    }

    public function index(): string
    {

        $tombolCari = $this->request->getPost('tombolkategori');

        if (isset($tombolCari)) {
            $cari = $this->request->getPost('carikategori');
            session()->set('carikategori', $cari);
            redirect()->to('/kategori');
        } else {
            $cari = session()->get('carikategori');
        }

        $dataKategori = $cari ? $this->kategori->cariData($cari) : $this->kategori;

        $noHalaman = $this->request->getVar('page_kategori') ? $this->request->getVar('page_kategori') : 1;
        $data = [
            'datakategori' => $dataKategori->paginate(10, 'kategori'),
            'pager' => $this->kategori->pager,
            'noHalaman' => $noHalaman,
            'cari' => $cari
        ];
        return view('kategori/data', $data);
    }

    function formTambah()
    {
        if ($this->request->isAJAX()) {
            $msg = [
                'data' => view('kategori/modalformtambah')
            ];

            echo json_encode($msg);
        } else {
            exit('Maaf tidak ada halaman yang bisa ditampilkan');
        }
    }

    public function simpandata()
    {
        if ($this->request->isAJAX()) {
            $namakategori = $this->request->getVar('namakategori');

            $this->kategori->insert([
                'katnama' => $namakategori
            ]);

            $msg = [
                'sukses' => 'kategori berhasil ditambahkan'
            ];
            echo json_encode($msg);
        }
    }

    function hapus()
    {
        if ($this->request->isAJAX()) {
            $idkategori = $this->request->getVar('idkategori');

            $this->kategori->delete($idkategori);

            $msg = [
                'sukses' => 'kategori berhasil dihapus'
            ];
            echo json_encode($msg);
        }
    }
}
