<?php

namespace App\Controllers;

use App\Models\Modelkategori;
use App\Models\Modelpenjualan;
use App\Models\Modelproduk;
use App\Models\Modelsatuan;

class Laporan extends BaseController
{
    public function __construct()
    {
        $this->produk = new Modelproduk();
        $this->penjualan = new Modelpenjualan();
    }

    public function laporanStokProdak()
    {
        $dataProduk = $this->produk->join('kategori', 'katid=produk_katid')->join('satuan', 'satid=produk_satid');

        $noHalaman = $this->request->getVar('page_produk') ? $this->request->getVar('page_produk') : 1;
        $data = [
            'dataproduk' => $dataProduk->paginate(6, 'produk'),
            'pager' => $this->produk->pager,
            'noHalaman' => $noHalaman,
        ];
        return view('laporan/laporanproduk', $data);
    }

    public function laporanPenjualan()
    {
        $dataPenjualan = $this->penjualan->asArray();

        $noHalaman = $this->request->getVar('page_penjualan') ? $this->request->getVar('page_penjualan') : 1;
        $data = [
            'datapenjualan' => $dataPenjualan->paginate(6, 'penjualan'),
            'pager' => $this->penjualan->pager,
            'noHalaman' => $noHalaman,
        ];
        return view('laporan/laporanpenjualan', $data);
    }

    public function PrintDataProduk(): string
    {
        $dataProduk = $this->produk->join('kategori', 'katid=produk_katid')->join('satuan', 'satid=produk_satid')->findAll();
        $data = [
            'dataproduk' => $dataProduk,
        ];
        return view('laporan/temp_printlaporan', $data);
    }

    public function printDataPenjualan(): string
    {
        $dataPenjualan = $this->penjualan->findAll();
        $data = [
            'datapenjualan' => $dataPenjualan,
        ];
        return view('laporan/temp_printpenjualan', $data);
    }
}
