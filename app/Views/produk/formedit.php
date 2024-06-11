<?= $this->extend('layout/menu') ?>

<?= $this->section('title') ?>
<h3><i class="fa fa-table"></i> Edit Produk </h3>
<?= $this->endSection() ?>


<?= $this->section('isi') ?>
<script src="<?= base_url('assets/adminlte/plugins/autoNumeric.js') ?>"></script>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <button type="button" class="btn btn-sm btn-warning" onclick="window.location='<?= site_url('produk') ?>'">
                <i class="fa fa-backward"></i> Kembali
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

        <?= form_open_multipart('', ['class' => 'formsimpanproduk']) ?>
        <?= csrf_field(); ?>
        <div class="form-group row">
            <label for="kodebarcode" class="col-sm-4 col-form-label">Kode Barcode</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="kodebarcode" name="kodebarcode" value="<?= $kode; ?>" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="namaproduk" class="col-sm-4 col-form-label">Nama Produk</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="namaproduk" name="namaproduk" value="<?= $nama; ?>">
                <div class="errorNamaProduk invalid-feedback" style="display: none;"></div>
            </div>
        </div>
        <div class="form-group row">
            <label for="stok" class="col-sm-4 col-form-label">Stok Tersedia</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="stok" name="stok" value="<?= $stok; ?>">
                <div class="errorStok invalid-feedback" style="display: none;"></div>
            </div>
        </div>
        <div class="form-group row">
            <label for="kategori" class="col-sm-4 col-form-label">Kategori</label>
            <div class="col-sm-4">
                <select name="kategori" id="kategori" class="form-control">
                    <?php
                    foreach ($datakategori as $k) :
                        if ($k['katid'] == $produkkategori) :
                            echo "<option value=\"$k[katid]\" selected>$k[katnama]</option>";
                        else :
                            echo "<option value=\"$k[katid]\">$k[katnama]</option>";
                        endif;
                    endforeach;
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="satuan" class="col-sm-4 col-form-label">Satuan</label>
            <div class="col-sm-4">
                <select name="satuan" id="satuan" class="form-control">
                    <?php
                    foreach ($datasatuan as $s) :
                        if ($s['satid'] == $produksatuan) :
                            echo "<option value=\"$s[satid]\" selected>$s[satnama]</option>";
                        else :
                            echo "<option value=\"$s[satid]\">$s[satnama]</option>";
                        endif;
                    endforeach;
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="hargabeli" class="col-sm-4 col-form-label">Harga Beli (Rp)</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="hargabeli" value="<?= $hargabeli ?>" name="hargabeli" style="text-align: right;">
                <div class="errorHargaBeli invalid-feedback" style="display: none;"></div>
            </div>
        </div>
        <div class="form-group row">
            <label for="hargajual" class="col-sm-4 col-form-label">Harga Jual (Rp)</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="hargajual" value="<?= $hargajual ?>" name="hargajual" style="text-align: right;">
                <div class="errorHargaJual invalid-feedback" style="display: none;"></div>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-4 col-form-label">Gamabar Produk</label>
            <div class="col-sm-4">
                <img src="<?= base_url($gambarproduk) ?>" style="width: 100px;" class="img-thumnail">
            </div>
        </div>
        <div class="form-group row">
            <label for="uploadgambar" class="col-sm-4 col-form-label">Ganti Gambar(<i>Jika ingin</i>)</label>
            <div class="col-sm-4">
                <input type="file" class="form-control" id="uploadgambar" name="uploadgambar">
                <div class="errorUploadGambar invalid-feedback" style="display: none;"></div>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-4 col-form-label"></label>
            <div class="col-sm-4">
                <button type="submit" class="btn btn-success tombolSimpanProduk">
                    Update
                </button>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>
<div class="viewmodal" style="display:none;"></div>
<script>
    $(document).ready(function() {

        $('#hargabeli').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '2'
        });
        $('#hargajual').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '2'
        });
        $('#stok').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0'
        });

        $('.tombolSimpanProduk').click(function(e) {
            e.preventDefault();
            let form = $('.formsimpanproduk')[0];
            let data = new FormData(form);

            $.ajax({
                type: "post",
                url: "<?= site_url('produk/updatedata') ?>",
                data: data,
                dataType: "json",
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $('.tombolSimpanProduk').html('<i class="fa fa-spin fa-spinner"></i>');
                    $('.tombolSimpanProduk').prop('disabled', true);
                },
                complete: function() {
                    $('.tombolSimpanProduk').html('Update');
                    $('.tombolSimpanProduk').prop('disabled', false);
                },
                success: function(response) {
                    if (response.error) {
                        let msg = response.error;

                        if (msg.errorNamaProduk) {
                            $('.errorNamaProduk').html(msg.errorNamaProduk).show();
                            $('#namaproduk').addClass('is-invalid');
                        } else {
                            $('.errorNamaProduk').fadeOut();
                            $('#namaproduk').removeClass('is-invalid');
                            $('#namaproduk').addClass('is-valid');
                        }

                        if (msg.errorHargaBeli) {
                            $('.errorHargaBeli').html(msg.errorHargaBeli).show();
                            $('#hargabeli').addClass('is-invalid');
                        } else {
                            $('.errorHargaBeli').fadeOut();
                            $('#hargabeli').removeClass('is-invalid');
                            $('#hargabeli').addClass('is-valid');
                        }

                        if (msg.errorHargaJual) {
                            $('.errorHargaJual').html(msg.errorHargaJual).show();
                            $('#hargajual').addClass('is-invalid');
                        } else {
                            $('.errorHargaJual').fadeOut();
                            $('#hargajual').removeClass('is-invalid');
                            $('#hargajual').addClass('is-valid');
                        }

                        if (msg.errorUpload) {
                            $('.errorUpload').html(msg.errorUpload).show();
                            $('#uploadgambar').addClass('is-invalid');
                        }
                    } else {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            html: response.success
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    }
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>