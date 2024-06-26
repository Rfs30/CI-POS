<?= $this->extend('layout/menu') ?>

<?= $this->section('title') ?>
<h3>Manajemen Data Satuan</h3>
<?= $this->endSection() ?>


<?= $this->section('isi') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <button type="button" class="btn btn-sm btn-primary tombolTambah">
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

        <form method="POST" action="/satuan">
            <?= csrf_field(); ?>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Cari Nama Satuan" name="carisatuan" autofocus value="<?= $cari; ?>">
                <button class="btn btn-primary" type="submit" name="tombolsatuan">Cari</button>
            </div>
        </form>

        <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Nama Satuan</th>
                    <th>#</th>
                </tr>
            </thead>


            <tbody>
                <?php $nomor = 1 + (($noHalaman - 1) * 10);
                foreach ($datasatuan as $row) :
                ?>
                    <tr>
                        <td><?= $nomor++; ?></td>
                        <td><?= $row['satnama']; ?></td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm" title="Edit Satuan" onclick="edit('<?= $row['satid'] ?>')">
                                <i class="fa fa-pencil-alt"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="hapus('<?= $row['satid'] ?>','<?= $row['satnama'] ?>')">
                                <i class="fa fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="float-center">
            <?= $pager->links('satuan', 'paging_data'); ?>
        </div>

    </div>
</div>

<div class="viewmodal" style="display: none;"></div>
<script>
    function hapus(id, nama) {
        Swal.fire({
            title: "Hapus Satuan",
            html: `Yakin Hapus nama Satuan <strong>${nama}</strong> Ini ?`,
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
                    url: "<?= site_url('satuan/hapus') ?>",
                    data: {
                        idsatuan: id
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses) {
                            window.location.reload();
                        }
                    }
                });
            }
        });
    }

    function edit(id) {
        $.ajax({
            type: "post",
            url: "<?= site_url('satuan/formEdit') ?>",
            data: {
                idsatuan: id
            },
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodal').html(response.data).show();
                    $('#modalformedit').on('shown.bs.modal', function(event) {
                        $('#namasatuan').focus();
                    });
                    $('#modalformedit').modal('show');
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }


    $(document).ready(function() {
        $('.tombolTambah').click(function(e) {
            e.preventDefault();

            $.ajax({
                url: "<?= site_url('satuan/formTambah') ?>",
                data: {
                    aksi: 0
                },
                type: 'post',
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('.viewmodal').html(response.data).show();
                        $('#modaltambahsatuan').on('shown.bs.modal', function(event) {
                            $('#namasatuan').focus();
                        });
                        $('#modaltambahsatuan').modal('show');
                    }
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>