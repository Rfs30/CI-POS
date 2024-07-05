<?= $this->extend('layout/menu') ?>

<?= $this->section('title') ?>
<h3>Laporan Data Produk</h3>
<?= $this->endSection() ?>


<?= $this->section('isi') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <a button type="button" class="btn btn-sm btn-primary mr-1" href="<?= site_url('produk/printdata') ?>" target="_blank">
                <i class="fa fa-print"></i> Print
            </a>
        </h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barcode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Harga Beli(Rp)</th>
                        <th>Harga Jual(Rp)</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $nomor = 1 + (($noHalaman - 1) * 3);
                    foreach ($dataproduk as $row) :
                    ?>
                        <tr>
                            <td><?= $nomor++; ?></td>
                            <td><?= $row['kodebarcode']; ?></td>
                            <td><?= $row['namaproduk']; ?></td>
                            <td><?= $row['katnama']; ?></td>
                            <td><?= $row['satnama']; ?></td>
                            <td style="text-align: right;"><?= number_format($row['harga_beli'], 2, ",", "."); ?></td>
                            <td style="text-align: right;"><?= number_format($row['harga_jual'], 2, ",", "."); ?></td>
                            <td style="text-align: right;"><?= number_format($row['stok_tersedia'], 0, ",", "."); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                <div>
                    <?= $pager->links('produk', 'paging_data'); ?>
                </div>
            </div>
        </div>
    </div>
    <?= $this->endSection(); ?>