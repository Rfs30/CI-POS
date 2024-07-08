<?= $this->extend('layout/menu') ?>

<?= $this->section('title') ?>
<h3>Data Penjualan</h3>
<?= $this->endSection() ?>


<?= $this->section('isi') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <a button type="button" class="btn btn-sm btn-primary mr-1" href="<?= site_url('laporan/printdatapenjualan') ?>" target="_blank">
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
                        <th>Tanggal</th>
                        <th>No Faktur</th>
                        <th>Diskon (%)</th>
                        <th>Diskon (Rp)</th>
                        <th>Total Kotor</th>
                        <th>Total Bersih</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $nomor = 1 + (($noHalaman - 1) * 3);
                    foreach ($datapenjualan as $row) :
                    ?>
                        <tr>
                            <td><?= $nomor++; ?></td>
                            <td><?= $row['jual_tgl']; ?></td>
                            <td><?= $row['jual_faktur']; ?></td>
                            <td style="text-align: right;"><?= number_format($row['jual_dispersen'], 2, ",", "."); ?></td>
                            <td style="text-align: right;"><?= number_format($row['jual_disuang'], 2, ",", "."); ?></td>
                            <td style="text-align: right;"><?= number_format($row['jual_totalkotor'], 2, ",", "."); ?></td>
                            <td style="text-align: right;"><?= number_format($row['jual_totalbersih'], 2, ",", "."); ?></td>
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