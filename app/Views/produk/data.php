<?= $this->extend('layout/menu') ?>

<?= $this->section('title') ?>
<h3>Manajemen Data Produk</h3>
<?= $this->endSection() ?>


<?= $this->section('isi') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <a button type="button" class="btn btn-sm btn-primary mr-1" href="<?= site_url('produk/printdata') ?>" target="_blank">
                <i class="fa fa-print"></i> Print
            </a>
        </h3>

        <h3 class="card-title">
            <button type="button" class="btn btn-sm btn-primary" onclick="window.location='<?= site_url('produk/add') ?>'">
                <i class="fa fa-plus"></i> Tambah Data
            </button>
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
            <?= form_open('produk') ?>
            <?= csrf_field(); ?>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Cari Kode/Nama Produk" name="cariproduk" autofocus>
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit" name="tombolcariproduk">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
            <?= form_close(); ?>
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
                        <th>#</th>
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
                            <td>
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="window.location='produk/edit/<?= $row['kodebarcode'] ?>'">
                                    <i class="fa fa-pencil-alt"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="hapus('<?= $row['kodebarcode'] ?>','<?= $row['namaproduk'] ?>')">
                                    <i class="fa fa-trash-alt"></i>
                                </button>
                            </td>
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
    <script>
        function hapus(kode, nama) {
            Swal.fire({
                html: `Yakin menghapus data produk dengan nama <strong>${nama}</strong> ini?`,
                text: "Hapus Produk",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus !",
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "<?= site_url('produk/hapus') ?>",
                        data: {
                            kode: kode
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.sukses) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Berhasil",
                                    text: response.sukses,
                                }).then((result) => {
                                    /* Read more about isConfirmed, isDenied below */
                                    if (result.isConfirmed) {
                                        Swal.fire("Saved!", "", "success");
                                        window.location.reload();
                                    }
                                });
                            }
                        },
                        error: function(xhr, thrownError) {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }
                    });
                }
            });
        }
    </script>
    <?= $this->endSection(); ?>