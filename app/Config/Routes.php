<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Layout::index');

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
