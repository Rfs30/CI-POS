<script src="<?= base_url('assets/adminlte/plugins/autoNumeric.js') ?>"></script>
<div class="modal fade" id="modalpembayaran" tabindex="-1" aria-labelledby="modalpembayaranLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalpembayaranLabel">Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('penjualan/simpanPembayaran', ['class' => 'frmpembayaran']) ?>
            <div class="modal-body">
                <input type="hidden" name="nofaktur" value="<?= $nofaktur ?>">
                <input type="hidden" name="kopel" value="<?= $kopel ?>">
                <input type="hidden" name="totalkotor" id="totalkotor" value="<?= $totalbayar ?>">

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Disc(%)</label>
                            <input type="text" name="dispersen" id="dispersen" class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="">Disc(Rp)</label>
                            <input type="text" name="disuang" id="disuang" class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="">Total Pembayaran</label>
                    <input type="text" name="totalbersih" id="totalbersih" class="form-control form-control-lg" value="<?= $totalbayar ?>" style="font-weight:bold; text-align:right; color:blue; font-size:24pt;" readonly>
                </div>

                <div class="form-group">
                    <label for="">Jumlah Uang</label>
                    <input type="text" name="jmluang" id="jmluang" class="form-control" style="font-weight:bold; text-align:right; color:red; font-size:20pt;" autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="">Sisa Uang</label>
                    <input type="text" name="sisauang" id="sisauang" class="form-control" style="font-weight:bold; text-align:right; color:blue; font-size:24pt;" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success tombolSimpan">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        $('#dispersen').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '2'
        });

        $('#disuang').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0'
        });

        $('#totalbersih').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0'
        });
        $('#jmluang').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0'
        });
        $('#sisauang').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0'
        });

        $('#dispersen').keyup(function(e) {
            hitungDiskon();
        });
        $('#disuang').keyup(function(e) {
            hitungDiskon();
        });
        $('#jmluang').keyup(function(e) {
            hitungSisauang();
        });

        $('.frmpembayaran').submit(function(e) {
            e.preventDefault();

            let jmluang = ($('#jmluang').val() == "") ? 0 : $('#jmluang').autoNumeric('get');
            let sisauang = ($('#sisauang').val() == "") ? 0 : $('#sisauang').autoNumeric('get');

            if (parseFloat(jmluang) == 0 || parseFloat(jmluang) == "") {
                Toast.fire({
                    icon: "warning",
                    title: "Maaf jumlah uang belum diinput..."
                });
            } else if (parseFloat(sisauang) < 0) {
                Toast.fire({
                    icon: "error",
                    title: "Jumlah uang belum mencukupi"
                });
            } else {
                $.ajax({
                    type: "post",
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: "json",
                    beforeSend: function() {
                        $('.tombolSimpan').prop('disabled', true);
                        $('.tombolSimpan').html('<i class="fa fa-spin fa-spinner"></i>');
                    },
                    complete: function() {
                        $('.tombolSimpan').prop('disabled', false);
                        $('.tombolSimpan').html('Simpan');
                    },
                    success: function(response) {
                        if (response.sukses == 'berhasil') {
                            Swal.fire({
                                title: "Cetak Struk ?",
                                text: "Mau di Cetak Struk nya ?",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#d33",
                                confirmButtonText: "Yes, Cetak !",
                                cancelButtonText: "Tidak",
                                focusConfirm: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    alert('cetak struk');
                                    $.ajax({
                                        type: "post",
                                        url: "<?= site_url('penjualan/cetakStruk') ?>",
                                        data: {
                                            nofaktur: response.nofaktur
                                        },
                                        success: function(response) {
                                            alert(response);
                                            window.location.reload();
                                        },
                                        error: function(xhr, thrownError) {
                                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                                        }
                                    });
                                } else {
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
            return false;
        })
    });

    function hitungDiskon() {

        let totalkotor = $('#totalkotor').val();
        let dispersen = ($('#dispersen').val() == "") ? 0 : $('#dispersen').autoNumeric('get');
        let disuang = ($('#disuang').val() == "") ? 0 : $('#disuang').autoNumeric('get');
        let hasil;
        hasil = parseFloat(totalkotor) - (parseFloat(totalkotor) * parseFloat(dispersen) / 100) - parseFloat(disuang);

        $('#totalbersih').val(hasil);
        let totalbersih = $('#totalbersih').val();
        $('#totalbersih').autoNumeric('set', totalbersih);

    }

    function hitungSisauang() {
        let totalpembayaran = $('#totalbersih').autoNumeric('get');
        let jumlahuang = ($('#jmluang').val() == "") ? 0 : $('#jmluang').autoNumeric('get');

        sisauang = parseFloat(jumlahuang) - parseFloat(totalpembayaran);

        $('#sisauang').val(sisauang);

        let sisauangx = $('#sisauang').val();
        $('#sisauang').autoNumeric('set', sisauangx);
    }
</script>