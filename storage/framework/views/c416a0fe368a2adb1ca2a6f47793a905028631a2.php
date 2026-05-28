
<?php $__env->startSection('content'); ?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Permintaan Sparepart Bengkel
                        <span class="d-block text-muted pt-2 font-size-sm">Daftar permintaan material/sparepart dari Service Advisor</span>
                    </h3>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-checkable" id="dataTable">
                    <thead>
                        <tr>
                            <th>Waktu Permintaan</th>
                            <th>No PKB</th>
                            <th>Plat Nomor</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    var table;
    jQuery(document).ready(function() {
        table = jQuery('#dataTable').DataTable({
            processing: true,
            serverSide: false,
            order: [[0, "desc"]],
            ajax: {
                url: "<?php echo e(route('permintaan-sparepart.getData')); ?>",
                type: "POST",
                data: function (d) {
                    d._token = "<?php echo e(csrf_token()); ?>";
                }
            },
            columns: [
                { 
                    data: 'created_at',
                    render: function(data) {
                        if(!data) return '-';
                        let d = new Date(data.replace(' ', 'T'));
                        return d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0') + ' ' + String(d.getHours()).padStart(2,'0') + ':' + String(d.getMinutes()).padStart(2,'0');
                    }
                },
                { data: 'NoPKB' },
                { data: 'PlatNomor' },
                { data: 'NamaItem' },
                { data: 'Qty' },
                { 
                    data: 'StatusGudang',
                    render: function(data) {
                        if(data == 0) return '<span class="badge bg-warning text-dark">Menunggu Gudang</span>';
                        if(data == 1) return '<span class="badge bg-success">Diserahkan</span>';
                        if(data == 2) return '<span class="badge bg-dark">Kosong / Indent</span>';
                        return data;
                    }
                },
                { 
                    data: 'id',
                    render: function(data, type, row) {
                        if(row.StatusGudang == 0) {
                            return `<button class="btn btn-sm btn-primary me-1 mb-1" onclick="serahkanBarang(${data})"><i class="bi bi-check2"></i> Serahkan</button>
                                    <button class="btn btn-sm btn-danger mb-1" onclick="tolakBarang(${data})"><i class="bi bi-x-circle"></i> Kosong</button>`;
                        }
                        return '-';
                    }
                }
            ]
        });
    });

    function serahkanBarang(id) {
        Swal.fire({
            title: 'Serahkan Barang?',
            text: "Stok akan dipotong dari sistem secara otomatis.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Serahkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                jQuery.ajax({
                    url: "<?php echo e(url('gudang/permintaan-sparepart/serahkan')); ?>/" + id,
                    type: 'POST',
                    data: { _token: "<?php echo e(csrf_token()); ?>" },
                    success: function(res) {
                        if(res.success) {
                            Swal.fire('Berhasil!', res.message, 'success');
                            table.ajax.reload();
                        } else {
                            Swal.fire('Gagal!', res.message, 'error');
                        }
                    },
                    error: function(err) {
                        Swal.fire('Error!', err.responseJSON?.message || 'Terjadi kesalahan sistem', 'error');
                    }
                });
            }
        });
    }

    function tolakBarang(id) {
        Swal.fire({
            title: 'Barang Kosong?',
            text: "Status akan diubah menjadi Kosong / Indent.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tandai Kosong',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                jQuery.ajax({
                    url: "<?php echo e(url('gudang/permintaan-sparepart/tolak')); ?>/" + id,
                    type: 'POST',
                    data: { _token: "<?php echo e(csrf_token()); ?>" },
                    success: function(res) {
                        if(res.success) {
                            Swal.fire('Tersimpan!', res.message, 'success');
                            table.ajax.reload();
                        }
                    }
                });
            }
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('parts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views/MasterData/PermintaanSparepart/index.blade.php ENDPATH**/ ?>