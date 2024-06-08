<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Layout::index');
$routes->get('/kategori', 'Kategori::index');
$routes->post('/kategori', 'Kategori::index');
$routes->post('/kategori/simpandata', 'Kategori::simpandata');
$routes->post('/kategori/hapus', 'Kategori::hapus');
$routes->get('/kategori/formTambah', 'Kategori::formTambah');
