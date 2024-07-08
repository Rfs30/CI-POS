<?= $this->extend('layout/main') ?>

<?= $this->section('menu') ?>

<li class="nav-item">
    <a href="/" class="nav-link">
        <i class="nav-icon fas fa-home"></i>
        <p>
            Home
        </p>
    </a>
</li>

<?php if (session()->idlevel == 1) : ?>
    <li class="nav-header">Master</li>
    <li class="nav-item">
        <a href="<?= site_url('kategori') ?>" class="nav-link">
            <i class="nav-icon fa fa-tasks"></i>
            <p>
                Kategori
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="<?= site_url('satuan') ?>" class="nav-link">
            <i class="nav-icon fa fa-edit"></i>
            <p>
                Satuan
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="<?= site_url('produk') ?>" class="nav-link">
            <i class="nav-icon fa fa-table"></i>
            <p>
                Produk
            </p>
        </a>
    </li>

    <li class="nav-header">Laporan</li>
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>
                Laporan
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="<?= site_url('laporan/stokproduk'); ?>" class="nav-link ">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Laporan Stok Produk</p>
                </a>
            </li>
        </ul>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="<?= site_url('laporan/penjualan'); ?>" class="nav-link ">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Laporan Penjualan</p>
                </a>
            </li>
        </ul>
    </li>
<?php endif; ?>

<?php if (session()->idlevel == 2) : ?>
    <li class="nav-header">Transaksi</li>
    <li class="nav-item">
        <a href="<?= site_url('penjualan') ?>" class="nav-link">
            <i class="nav-icon fas fa-cash-register"></i>
            <p>
                Penjualan
            </p>
        </a>
    </li>

    <li class="nav-header">Laporan</li>
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>
                Laporan
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="<?= site_url('laporan/stokproduk'); ?>" class="nav-link ">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Laporan Stok Produk</p>
                </a>
            </li>
        </ul>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="<?= site_url('laporan/penjualan'); ?>" class="nav-link ">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Laporan Penjualan</p>
                </a>
            </li>
        </ul>
    </li>
<?php endif; ?>

<?= $this->endSection(); ?>