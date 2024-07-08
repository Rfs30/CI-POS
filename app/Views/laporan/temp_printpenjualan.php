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
                    <h1>Data Penjualan</h1>
                    </p>
                </div>
                <div class="col-12 table-responsive">
                    <table class="table table-striped table-bordered">
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

                            $nomor = 1;
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