<?= $this->extend('layout/menu') ?>

<?= $this->section('title') ?>
<h3>Input Kasir</h3>
<?= $this->endSection() ?>


<?= $this->section('isi') ?>

<div class="card card-default color-palette-box">
    <div class="card-header">
        <h3 class="card-title">
            <button type="button" class="btn btn-warning btn-sm" onclick="window.location='<?= site_url('penjualan') ?>'">&laquo; Kembali</button>
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="nofaktur">Faktur</label>
                    <input type="text" class="form-control form-control-sm" style="color:red;font-weight:bold;" name="nofaktur" id="nofaktur" readonly value="<?= $nofaktur; ?>">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" class="form-control form-control-sm" name="tanggal" id="tanggal" readonly value="<?= date('Y-m-d'); ?>">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="napel">Pelanggan</label>
                    <div class="input-group mb-3">
                        <input type="text" value="-" class="form-control form-control-sm" name="napel" id="napel" readonly>
                        <input type="hidden" name="kopel" id="kopel" value="0">
                        <div class="input-group-append">
                            <button class="btn btn-sm btn-primary" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="tanggal">Aksi</label>
                    <div class="input-group">
                        <button class="btn btn-danger btn-sm" type="button" id="btnHapusTransaksi">
                            <i class="fa fa-trash-alt"></i>
                        </button>&nbsp;
                        <button class="btn btn-success" type="button" id="btnSimpanTransaksi">
                            <i class="fa fa-save"></i>
                        </button>&nbsp;
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="kodebarcode">Kode Produk</label>
                    <input type="text" class="form-control form-control-sm" name="kodebarcode" id="kodebarcode" autofocus>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Nama Produk</label>
                    <input type="text" style="font-weight : bold; font-size: 16pt;" class="form-control form-control-sm" name="namaproduk" id="namaproduk" readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="jml">Jumlah</label>
                    <input type="number" class="form-control form-control-sm" name="jumlah" id="jumlah" value="1">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="jml">Total Bayar</label>
                    <input type="text" class="form-control form-control-lg" name="totalbayar" id="totalbayar" style="text-align: right; color:blue; font-weight : bold; font-size:30pt;" value="0" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 dataDetailPenjualan">

            </div>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<div class="viewmodalpembayaran" style="display: none;"></div>
<script>
    function batalTransaksi() {
        Swal.fire({
            title: "Batal Transaksi?",
            text: "Yakin bata transaksi?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Batal!",
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url: "<?= site_url('penjualan/batalTransaksi') ?>",
                    data: {
                        nofaktur: $('#nofaktur').val()
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses == 'berhasil') {
                            window.location.reload();
                        }

                    },
                    error: function(xhr, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                })
            }
        });
    }
    $(document).ready(function() {
        $('body').addClass('sidebar-collapse')
        dataDetailPenjualan();
        hitungTotalBayar();

        $('#kodebarcode').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                cekKode();
            }
        })
        $('#btnHapusTransaksi').click(function(e) {
            e.preventDefault();
            batalTransaksi();
        })

        $('#btnSimpanTransaksi').click(function(e) {
            e.preventDefault();
            pembayaran();
        });

        $('#jumlah').keydown(function(e) {
            if (e.keyCode == 16) {
                e.preventDefault();
                $('#kodebarcode').focus();
            }
        });

        $(this).keydown(function(e) {
            if (e.keyCode == 16) {
                e.preventDefault();
                $('#kodebarcode').focus();
            }

            if (e.keyCode == 115) {
                e.preventDefault();
                batalTransaksi();
            }

            if (e.keyCode == 119) {
                e.preventDefault();
                pembayaran();
            }
        });
    });

    function pembayaran() {
        let nofaktur = $('#nofaktur').val();
        $.ajax({
            type: "post",
            url: "<?= site_url('penjualan/pembayaran') ?>",
            data: {
                nofaktur: nofaktur,
                tglfaktur: $('#tanggal').val(),
                kopel: $('#kopel').val()
            },
            dataType: "json",
            success: function(response) {
                if (response.error) {
                    Swal.fire({
                        icon: "warning",
                        title: "Maaf",
                        text: response.error
                    });
                }

                if (response.data) {
                    $('.viewmodalpembayaran').html(response.data).show();
                    $('#modalpembayaran').on('shown.bs.modal', function(event) {
                        $('#jmluang').focus();
                    });

                    $('#modalpembayaran').modal('show');
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    }

    function dataDetailPenjualan() {

        $.ajax({
            type: "post",
            url: "<?= site_url('penjualan/dataDetail') ?>",
            data: {
                nofaktur: $('#nofaktur').val()
            },
            dataType: "json",
            beforeSend: function() {
                $('.dataDetailPenjualan').html('<i class="fa fa-spin fa-spinner"></i>')
            },
            success: function(response) {
                if (response.data) {
                    $('.dataDetailPenjualan').html(response.data);
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    }

    function cekKode() {
        let kode = $('#kodebarcode').val();

        if (kode.length == 0) {
            $.ajax({
                url: "<?= site_url('penjualan/viewDataProduk') ?>",
                dataType: "json",
                success: function(response) {
                    $('.viewmodal').html(response.viewmodal).show();

                    $('#modalproduk').modal('show');
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        } else {
            $.ajax({
                type: "post",
                url: "<?= site_url('penjualan/simpanTemp') ?>",
                data: {
                    kodebarcode: kode,
                    namaproduk: $('#namaproduk').val(),
                    jumlah: $('#jumlah').val(),
                    nofaktur: $('#nofaktur').val(),
                },
                dataType: "json",
                success: function(response) {
                    if (response.totaldata == 'banyak') {
                        $.ajax({
                            url: "<?= site_url('penjualan/viewDataProduk') ?>",
                            dataType: "json",
                            data: {
                                keyword: kode
                            },
                            type: "post",
                            success: function(response) {
                                $('.viewmodal').html(response.viewmodal).show();

                                $('#modalproduk').modal('show');
                            },
                        });
                    }

                    if (response.sukses == 'berhasil') {
                        dataDetailPenjualan();
                        kosong();
                    }

                    if (response.error) {
                        Swal.fire({
                            icon: "error",
                            title: "Error...",
                            html: response.error,
                        });
                        dataDetailPenjualan();
                        kosong();
                    }
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    }

    function kosong() {
        $('#kodebarcode').val('');
        $('#namaproduk').val('');
        $('#jumlah').val('1');
        $('#kodebarcode').focus();

        hitungTotalBayar();
    }

    function hitungTotalBayar() {
        $.ajax({
            url: "<?= site_url('penjualan/hitungTotalBayar') ?>",
            dataType: "json",
            data: {
                nofaktur: $('#nofaktur').val(),
            },
            type: "post",
            success: function(response) {
                $('#totalbayar').val(response.totalbayar);
            }
        });
    }
</script>
<?= $this->endSection() ?>