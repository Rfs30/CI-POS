<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Login
$routes->get('/', 'Login::index');
$routes->post('/login/cekUser', 'Login::cekUser');
$routes->get('/logout', 'Login::logout');

// back office
$routes->get('/layout', 'Layout::index');

// route kategori
$routes->get('/kategori', 'Kategori::index');
$routes->post('/kategori', 'Kategori::index');
$routes->post('/kategori/simpandata', 'Kategori::simpandata');
$routes->post('/kategori/hapus', 'Kategori::hapus');
$routes->get('/kategori/formTambah', 'Kategori::formTambah');
$routes->post('/kategori/formTambah', 'Kategori::formTambah');
$routes->post('/kategori/formEdit', 'Kategori::formEdit');
$routes->post('/kategori/updatedata', 'Kategori::updatedata');

// route satuan
$routes->get('/satuan', 'Satuan::index');
$routes->post('/satuan', 'Satuan::index');
$routes->post('/satuan/simpandata', 'Satuan::simpandata');
$routes->post('/satuan/hapus', 'Satuan::hapus');
$routes->post('/satuan/formTambah', 'Satuan::formTambah');
$routes->post('/satuan/formEdit', 'Satuan::formEdit');
$routes->post('/satuan/updatedata', 'Satuan::updatedata');

// route produk
$routes->get('/produk', 'Produk::index');
$routes->post('/produk', 'Produk::index');
$routes->post('/produk/hapus', 'Produk::hapus');
$routes->post('/produk/updatedata', 'Produk::updatedata');
$routes->get('/produk/edit/(:any)', 'Produk::edit/$1');
$routes->get('/produk/add', 'Produk::add');
$routes->get('/produk/ambilDataKategori', 'Produk::ambilDataKategori');
$routes->get('/produk/ambilDataSatuan', 'Produk::ambilDataSatuan');
$routes->post('/produk/simpandata', 'Produk::simpandata');
$routes->get('/produk/printdata', 'Laporan::PrintDataProduk');

// route penjualan
$routes->get('/penjualan', 'Penjualan::index');
$routes->get('/penjualan/input', 'Penjualan::input');
$routes->get('/penjualan/viewDataProduk', 'Penjualan::viewDataProduk');
$routes->post('/penjualan/viewDataProduk', 'Penjualan::viewDataProduk');
$routes->post('/penjualan/dataDetail', 'Penjualan::dataDetail');
$routes->post('/penjualan/listDataProduk', 'Penjualan::listDataProduk');
$routes->post('/penjualan/simpanTemp', 'Penjualan::simpanTemp');
$routes->post('/penjualan/hitungTotalBayar', 'Penjualan::hitungTotalBayar');
$routes->post('/penjualan/hapusItem', 'Penjualan::hapusItem');
$routes->post('/penjualan/batalTransaksi', 'Penjualan::batalTransaksi');
$routes->post('/penjualan/pembayaran', 'Penjualan::pembayaran');
$routes->post('/penjualan/simpanPembayaran', 'Penjualan::simpanPembayaran');
$routes->post('/penjualan/cetakStruk', 'Penjualan::cetakStruk');


$routes->get('laporan/stokproduk', 'Laporan::laporanStokProdak');
