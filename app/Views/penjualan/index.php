<?= $this->extend('layout/menu') ?>

<?= $this->section('title') ?>
<h3>Menu Penjualan</h3>
<?= $this->endSection() ?>


<?= $this->section('isi') ?>
<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3>Input</h3>

                <p>Kasir</p>
            </div>
            <div class="icon">
                <i class="fas fa-cash-register"></i>
            </div>
            <a href="<?= site_url('penjualan/input') ?>" class="small-box-footer">Input Kasir <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3>Data</h3>

                <p>Penjualan</p>
            </div>
            <div class="icon">
                <i class="fas fa-database"></i>
            </div>
            <a href="<?= site_url('penjualan/data') ?>" class="small-box-footer">Data Penjualan <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>