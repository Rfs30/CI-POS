<table class="table table-striped table-sm table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Produk</th>
            <th>Qty</th>
            <th>Hrg.Jual</th>
            <th>Sub Total</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 1;
        foreach ($datadetail->getResultArray() as $produk) :
        ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $produk['kode']; ?></td>
                <td><?= $produk['namaproduk']; ?></td>
                <td><?= $produk['qty']; ?></td>
                <td style="text-align: right;"><?= number_format($produk['hargajual'], 0, ",", "."); ?></td>
                <td style="text-align: right;"><?= number_format($produk['subtotal'], 0, ",", "."); ?></td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="hapusitem('<?= $produk['id'] ?>','<?= $produk['namaproduk'] ?>')">
                        <i class="fa fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        <?php
        endforeach;
        ?>
    </tbody>
</table>

<script>
    function hapusitem(id, nama) {
        Swal.fire({
            title: "Hapus Item ?",
            html: `Yakin menghapus data produk <strong>${nama}</strong> ?`,
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
                    url: "<?= site_url('penjualan/hapusItem') ?>",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses == 'berhasil') {
                            dataDetailPenjualan();
                            kosong();
                        }
                    }
                })
            }
        });
    }
</script>