@extends('parts.header')
@section('content')
<div class="subheader py-2 py-lg-6 subheader-solid">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white mb-0 px-0 py-2">
                <li class="breadcrumb-item active" aria-current="page">Master Data</li>
                <li class="breadcrumb-item active" aria-current="page">Mekanik</li>
            </ol>
        </nav>
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-3">
                <div class="card-title">
                    <h3 class="card-label">Data Mekanik</h3>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMekanik" onclick="resetForm()">
                        <i class="fas fa-plus"></i> Tambah Mekanik
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tableData">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Mekanik</th>
                                <th>No HP</th>
                                <th>Persentase Komisi (%)</th>
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
</div>

<!-- Modal -->
<div class="modal fade" id="modalMekanik" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Mekanik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formMekanik">
                <div class="modal-body">
                    <input type="hidden" id="edit_mode" value="0">
                    <div class="mb-3">
                        <label>Kode Mekanik <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="KodeMekanik" id="KodeMekanik" required>
                    </div>
                    <div class="mb-3">
                        <label>Nama Mekanik <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="NamaMekanik" id="NamaMekanik" required>
                    </div>
                    <div class="mb-3">
                        <label>No HP</label>
                        <input type="text" class="form-control" name="NoHP" id="NoHP">
                    </div>
                    <div class="mb-3">
                        <label>Persentase Komisi (%)</label>
                        <input type="number" step="0.01" class="form-control" name="PersentaseKomisi" id="PersentaseKomisi" value="0">
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select class="form-control" name="Status" id="Status">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSave">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    var table;
    jQuery(document).ready(function() {
        table = jQuery('#tableData').DataTable({
            processing: true,
            ajax: {
                url: "{{ route('mekanik.getData') }}",
                type: "POST",
                data: { _token: "{{ csrf_token() }}" }
            },
            columns: [
                { data: 'KodeMekanik' },
                { data: 'NamaMekanik' },
                { data: 'NoHP' },
                { data: 'PersentaseKomisi' },
                { data: 'Status', render: function(data) {
                    return data == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>';
                }},
                { data: 'KodeMekanik', render: function(data, type, row) {
                    return `<button class="btn btn-sm btn-warning" onclick="editData('${row.KodeMekanik}', '${row.NamaMekanik}', '${row.NoHP}', '${row.PersentaseKomisi}', '${row.Status}')"><i class="fas fa-edit"></i> Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteData('${row.KodeMekanik}')"><i class="fas fa-trash"></i> Hapus</button>`;
                }}
            ]
        });

        jQuery('#formMekanik').on('submit', function(e) {
            e.preventDefault();
            let isEdit = jQuery('#edit_mode').val() == '1';
            let url = isEdit ? "{{ url('mekanik/update') }}/" + jQuery('#KodeMekanik').val() : "{{ route('mekanik.store') }}";
            
            jQuery.ajax({
                url: url,
                type: "POST",
                data: jQuery(this).serialize() + "&_token={{ csrf_token() }}",
                success: function(res) {
                    if (res.success) {
                        Swal.fire('Berhasil', res.message, 'success');
                        jQuery('#modalMekanik').modal('hide');
                        table.ajax.reload();
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }
                },
                error: function(err) {
                    Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                }
            });
        });
    });

    function resetForm() {
        jQuery('#formMekanik')[0].reset();
        jQuery('#KodeMekanik').removeAttr('readonly');
        jQuery('#edit_mode').val('0');
        jQuery('#modalTitle').text('Tambah Mekanik');
    }

    function editData(kode, nama, hp, komisi, status) {
        resetForm();
        jQuery('#KodeMekanik').val(kode).attr('readonly', true);
        jQuery('#NamaMekanik').val(nama);
        jQuery('#NoHP').val(hp);
        jQuery('#PersentaseKomisi').val(komisi);
        jQuery('#Status').val(status);
        jQuery('#edit_mode').val('1');
        jQuery('#modalTitle').text('Edit Mekanik');
        jQuery('#modalMekanik').modal('show');
    }

    function deleteData(kode) {
        Swal.fire({
            title: 'Hapus Data?',
            text: "Data mekanik akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                jQuery.ajax({
                    url: "{{ route('mekanik.destroy') }}",
                    type: "POST",
                    data: { KodeMekanik: kode, _token: "{{ csrf_token() }}" },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire('Terhapus!', res.message, 'success');
                            table.ajax.reload();
                        } else {
                            Swal.fire('Gagal!', res.message, 'error');
                        }
                    }
                });
            }
        });
    }
</script>
@endpush
