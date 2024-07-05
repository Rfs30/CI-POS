<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Invoice Print</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets') ?>/adminlte/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('assets') ?>/adminlte/dist/css/adminlte.min.css">
</head>

<body class="p-5">
    <div class="wrapper">
        <!-- Main content -->
        <section class="">
            <!-- title row -->
            <div class="row">
                <div class="col-12">
                    <h2 class="page-header text-primary">
                        <i class="fas fa-shopping-cart"></i> <b>KasirPintar</b>
                        <small class="float-right">Tanggal : <?= date('d/m/Y') ?></small>
                    </h2>
                </div>
                <!-- /.col -->
            </div>

            <!-- Table row -->
            <div class="row">
                <div class="col-12 text-center">
                    <p>
                    <h1>Daftar Produk</h1>
                    </p>
                </div>
                <div class="col-12 table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="text-align:center">No</th>
                                <th>Kode Barcode</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th style="text-align:center">Harga Beli(Rp)</th>
                                <th style="text-align:center">Harga Jual(Rp)</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $nomor = 1;
                            foreach ($dataproduk as $row) :
                            ?>
                                <tr>
                                    <td style="text-align:right"><?= $nomor++; ?></td>
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
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- ./wrapper -->
    <!-- Page specific script -->
    <script>
        window.addEventListener("load", window.print());
    </script>
</body>

</html>