<?php

namespace App\Controllers;

use PHPUnit\Util\Json;
use App\Models\Modeldataproduk;
use Config\Services;

use Escpos\PrintConnectors\WindowsPrintConnector;
use Escpos\CapabilityProfile;
use Escpos\Printer;
use Mike42\Escpos\CapabilityProfile as EscposCapabilityProfile;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector as PrintConnectorsWindowsPrintConnector;
use Mike42\Escpos\Printer as EscposPrinter;
use PHPUnit\TextUI\Output\Printer as OutputPrinter;

class Penjualan extends BaseController
{
    public function index(): string
    {
        return view('penjualan/index');
    }

    public function input()
    {
        $data = [
            'nofaktur' => $this->buatFaktur()
        ];
        return view('penjualan/input', $data);
    }

    public function buatFaktur()
    {
        $tgl = date('Y-m-d');

        $query = $this->db->query("SELECT MAX(jual_faktur) AS nofaktur FROM penjualan WHERE DATE_FORMAT(jual_tgl,'%Y-%m-%d') = '$tgl'");
        $hasil = $query->getRowArray();
        $data = $hasil['nofaktur'];

        $lastNoUrut = substr($data, -4);

        // nomor urut di tambah 1
        $nextNoUrut = intval($lastNoUrut) + 1;

        // membuat format nomor transaksi berikutnya
        $fakturPenjualan = 'J' . date('dmy', strtotime($tgl)) . sprintf('%04s', $nextNoUrut);

        return $fakturPenjualan;
    }

    public function dataDetail()
    {
        $nofaktur = $this->request->getPost('nofaktur');

        $tempPenjualan = $this->db->table('temp_penjualan');
        $queryTampil = $tempPenjualan->select('detjual_id as id, detjual_kodebarcode as kode, namaproduk, detjual_hargajual as hargajual, detjual_jml as qty, detjual_subtotal as subtotal')
            ->join('produk', 'detjual_kodebarcode=kodebarcode')->where('detjual_faktur', $nofaktur)->orderBy('detjual_id', 'asc');

        $data = [
            'datadetail' => $queryTampil->get()
        ];

        $msg = [
            'data' => view('penjualan/viewdetail', $data)
        ];
        echo json_encode($msg);
    }

    public function viewDataProduk()
    {
        if ($this->request->isAJAX()) {
            $keyword = $this->request->getPost('keyword');
            $data = [
                'keyword' => $keyword
            ];
            $msg = [
                'viewmodal' => view('penjualan/viewmodalcariproduk', $data)
            ];
            echo json_encode($msg);
        }
    }

    public function listDataProduk()
    {
        if ($this->request->isAJAX()) {
            $keywordkode = $this->request->getPost('keywordkode');
            $request = Services::request();
            $modelProduk = new Modeldataproduk($request);
            if ($request->getMethod(true) == 'POST') {
                $lists = $modelProduk->get_datatables($keywordkode);
                $data = [];
                $no = $request->getPost("start");
                foreach ($lists as $list) {
                    $no++;
                    $row = [];
                    $row[] = $no;
                    $row[] = $list->kodebarcode;
                    $row[] = $list->namaproduk;
                    $row[] = $list->katnama;
                    $row[] = number_format($list->stok_tersedia, 0, ",", ".");
                    $row[] = number_format($list->harga_jual, 0, ",", ".");
                    $row[] = "<button type=\"button\" class=\"btn btn-sm btn-primary\" onclick=\"pilihitem('" . $list->kodebarcode . "','" . $list->namaproduk . "')\">Pilih</button>";
                    $data[] = $row;
                }
                $output = [
                    "draw" => $request->getPost('draw'),
                    "recordsTotal" => $modelProduk->count_all($keywordkode),
                    "recordsFiltered" => $modelProduk->count_filtered($keywordkode),
                    "data" => $data
                ];
                echo json_encode($output);
            }
        }
    }

    public function simpanTemp()
    {
        if ($this->request->isAJAX()) {
            $kodebarcode = $this->request->getPost('kodebarcode');
            $namaproduk = "" . $this->request->getPost('namaproduk');
            $jumlah = $this->request->getPost('jumlah');
            $nofaktur = $this->request->getPost('nofaktur');

            if (strlen($namaproduk) > 0) {
                $queryCekProduk = $this->db->table('produk')->where('kodebarcode', $kodebarcode)->where('namaproduk', $namaproduk)->get();
            } else {
                $queryCekProduk = $this->db->table('produk')->like('kodebarcode', $kodebarcode)->orlike('namaproduk', $kodebarcode)->get();
            }

            $totalData = $queryCekProduk->getNumRows();

            if ($totalData > 1) {
                $msg = [
                    'totaldata' => 'banyak'
                ];
            } else if ($totalData == 1) {
                // lakukan insert ke temp penjualan
                $tblTempPenjualan = $this->db->table('temp_penjualan');
                $rowProduk = $queryCekProduk->getRowArray();

                $stokProduk = $rowProduk['stok_tersedia'];

                if (intval($stokProduk) == 0) {
                    $msg = [
                        'error' => 'Maaf stok habis...'
                    ];
                } elseif ($jumlah > intval($stokProduk)) {
                    $msg = [
                        'error' => 'Maaf stok tidak mencukupi'
                    ];
                } else {
                    $insertdata = [
                        'detjual_faktur' => $nofaktur,
                        'detjual_kodebarcode' => $rowProduk['kodebarcode'],
                        'detjual_hargabeli' => $rowProduk['harga_beli'],
                        'detjual_hargajual' => $rowProduk['harga_jual'],
                        'detjual_jml' => $jumlah,
                        'detjual_subtotal' => floatval($rowProduk['harga_jual']) * $jumlah
                    ];

                    $tblTempPenjualan->insert($insertdata);

                    $msg = ['sukses' => 'berhasil'];
                }
            } else {
                $msg = ['error' => 'Maaf Produk tidak ditemukan...'];
            }
            echo json_encode($msg);
        }
    }

    public function hitungTotalBayar()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');

            $tblTempPenjualan = $this->db->table('temp_penjualan');

            $queryTotal = $tblTempPenjualan->select('SUM(detjual_subtotal) as totalbayar')->where('detjual_faktur', $nofaktur)->get();
            $rowTotal = $queryTotal->getRowArray();

            $msg = [
                'totalbayar' => number_format($rowTotal['totalbayar'], 0, ",", ".")
            ];

            echo json_encode($msg);
        }
    }

    public function hapusItem()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            $tblTempPenjualan = $this->db->table('temp_penjualan');

            $queryHapus = $tblTempPenjualan->delete(['detjual_id' => $id]);

            if ($queryHapus) {
                $msg = [
                    'sukses' => 'berhasil'
                ];

                echo json_encode($msg);
            }
        }
    }

    public function batalTransaksi()
    {
        if ($this->request->isAJAX()) {

            $tblTempPenjualan = $this->db->table('temp_penjualan');
            $hapusData = $tblTempPenjualan->emptyTable();

            if ($hapusData) {
                $msg = [
                    'sukses' => 'berhasil'
                ];
            }

            echo json_encode($msg);
        }
    }

    public function pembayaran()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');
            $tglfaktur = $this->request->getPost('tglfaktur');
            $kopel = $this->request->getPost('kopel');

            $tblTempPenjualan = $this->db->table('temp_penjualan');
            $cekDataTempPenjualan = $tblTempPenjualan->getWhere(['detjual_faktur' => $nofaktur]);

            $queryTotal = $tblTempPenjualan->select('SUM(detjual_subtotal) as totalbayar')->where('detjual_faktur', $nofaktur)->get();
            $rowTotal = $queryTotal->getRowArray();

            if ($cekDataTempPenjualan->getNumRows() > 0) {
                // Modal Pembayaran
                $data = [
                    'nofaktur' => $nofaktur,
                    'kopel' => $kopel,
                    'totalbayar' => $rowTotal['totalbayar']
                ];

                $msg = [
                    'data' => view('penjualan/modalpembayaran', $data)
                ];
            } else {
                $msg = [
                    'error' => 'Maaf itemnya belum ada...'
                ];
            }
            echo json_encode($msg);
        }
    }
    public function simpanPembayaran()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');
            $kopel = $this->request->getPost('kopel');
            $totalkotor = $this->request->getPost('totalkotor');
            $totalbersih = str_replace(",", "", $this->request->getPost('totalbersih'));
            $dispersen = str_replace(",", "", $this->request->getPost('dispersen'));
            $disuang = str_replace(",", "", $this->request->getPost('disuang'));
            $jmluang = str_replace(",", "", $this->request->getPost('jmluang'));
            $sisauang = str_replace(",", "", $this->request->getPost('sisauang'));

            $tblPenjualan = $this->db->table('penjualan');
            $tblTempPenjualan = $this->db->table('temp_penjualan');
            $tblDetailPenjualan = $this->db->table('penjualan_detail');

            // insert rable penjualan
            $dataInsertPenjualan = [
                'jual_faktur' => $nofaktur,
                'jual_tgl' => date('Y-m-d H:i:s'),
                'jual_pelkode' => $kopel,
                'jual_dispersen' => $dispersen,
                'jual_disuang' => $disuang,
                'jual_totalkotor' => $totalkotor,
                'jual_totalbersih' => $totalbersih,
                'jual_jmluang' => $jmluang,
                'jual_sisauang' => $sisauang,
            ];

            $tblPenjualan->insert($dataInsertPenjualan);

            // Inset ke tabel detailpenjualan
            $ambilDataTemp = $tblTempPenjualan->getWhere(['detjual_faktur' => $nofaktur]);

            $fieldDetailPenjualan = [];
            foreach ($ambilDataTemp->getResultArray() as $row) {
                $fieldDetailPenjualan[] = [
                    'detjual_faktur' => $row['detjual_faktur'],
                    'detjual_kodebarcode' => $row['detjual_kodebarcode'],
                    'detjual_hargabeli' => $row['detjual_hargabeli'],
                    'detjual_hargajual' => $row['detjual_hargajual'],
                    'detjual_jml' => $row['detjual_jml'],
                    'detjual_subtotal' => $row['detjual_subtotal']
                ];
            }

            $tblDetailPenjualan->insertBatch($fieldDetailPenjualan);

            // Hapus Temp
            $tblTempPenjualan->emptyTable();

            $msg = [
                'sukses' => 'berhasil',
                'nofaktur' => $nofaktur
            ];
            echo json_encode($msg);
        }
    }

    public function cetakStruk()
    {
        function buatBaris1Kolom($kolom1)
        {
            // Mengatur lebar setiap kolom (dalam satuan karakter)
            $lebar_kolom_1 = 33;

            // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
            $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);

            // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
            $kolom1Array = explode("\n", $kolom1);

            // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
            $jmlBarisTerbanyak = count($kolom1Array);

            // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
            $hasilBaris = array();

            // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
            for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

                // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
                $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");

                // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
                $hasilBaris[] = $hasilKolom1;
            }

            // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
            return implode("\n", $hasilBaris) . "\n";
        }

        function buatBaris3Kolom($kolom1, $kolom2, $kolom3)
        {
            // Mengatur lebar setiap kolom (dalam satuan karakter)
            $lebar_kolom_1 = 11;
            $lebar_kolom_2 = 11;
            $lebar_kolom_3 = 11;

            // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
            $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
            $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
            $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);

            // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
            $kolom1Array = explode("\n", $kolom1);
            $kolom2Array = explode("\n", $kolom2);
            $kolom3Array = explode("\n", $kolom3);

            // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
            $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array));

            // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
            $hasilBaris = array();

            // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
            for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

                // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
                $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
                // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
                $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ", STR_PAD_LEFT);

                $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);

                // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
                $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3;
            }

            // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
            return implode("\n", $hasilBaris) . "\n";
        }

        $profile = EscposCapabilityProfile::load("simple");
        //$connector = new PrintConnectorsWindowsPrintConnector("Microsoft XPS Class Driver");
        $connector = new FilePrintConnector("php://stdout");
        $printer = new EscposPrinter($connector, $profile);

        $nofaktur = $this->request->getPost('nofaktur');
        $tblPenjualan = $this->db->table('penjualan');
        $tblPenjualanDetail = $this->db->table('penjualan_detail');

        $queryPenjualan = $tblPenjualan->getWhere(['jual_faktur' => $nofaktur]);
        $rowPenjualan = $queryPenjualan->getRowArray();

        $printer->initialize();
        $printer->selectPrintMode(EscposPrinter::MODE_FONT_A);
        $printer->text(buatBaris1Kolom("Minimarket KasirPintar"));
        $printer->text(buatBaris1Kolom("Bandung, Telp 08476521484"));
        $printer->text(buatBaris1Kolom("Faktur : $nofaktur"));
        $printer->text(buatBaris1Kolom("Tanggal : $rowPenjualan[jual_tgl]"));


        $printer->text(buatBaris1Kolom("---------------------------------"));

        $queryPenjualanDetail = $tblPenjualanDetail->select('namaproduk,detjual_jml AS jml,satnama,detjual_hargajual AS hargajual,detjual_subtotal AS subtotal')
            ->join('produk', 'kodebarcode=detjual_kodebarcode')->join('satuan', 'satid = produk_satid')->where('detjual_faktur', $nofaktur)->get();


        $totalPembayaran = 0;
        foreach ($queryPenjualanDetail->getResultArray() as $d) {
            $printer->text(buatBaris1Kolom("$d[namaproduk]"));
            $printer->text(buatBaris3Kolom(number_format($d['jml'], 0) . ' ' . $d['satnama'], number_format($d['hargajual'], 0), number_format($d['subtotal'], 0)));

            $totalPembayaran += $d['subtotal'];
        }

        $printer->text(buatBaris1Kolom("---------------------------------"));
        $printer->text(buatBaris3Kolom("", "Total :", number_format($totalPembayaran, 0)));
        $printer->text("\n");
        $printer->text(buatBaris1Kolom("Terima Kasih Atas Kunjungannya..."));

        $printer->feed(4);
        $printer->cut();
        echo "Struk berhasil di cetak";
        $printer->close();
    }
}
