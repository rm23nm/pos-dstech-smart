<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mekanik Progres</title>
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }
        .header {
            background-color: #1e293b;
            color: white;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #3b82f6;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 1.8rem;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 8px;
        }
        .card-header {
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1.5rem;
            border-radius: 8px 8px 0 0 !important;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DASHBOARD MEKANIK</h1>
        <p class="mb-0 text-muted" style="color: #cbd5e1 !important;">Daftar kendaraan yang sedang dikerjakan</p>
    </div>

    <div class="container-fluid px-4">
        <div class="card mb-5">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <div class="card-title mb-0">
                    <h4 class="mb-0">Tabel Progres Mekanik</h4>
                </div>
                <div class="card-toolbar mt-3 mt-md-0">
                    <div class="d-flex align-items-center">
                        <label class="me-2 mb-0 fw-bold">Pilih Mekanik:</label>
                        <select class="form-select form-select-sm" id="filterMekanik" style="width: 200px;">
                            <option value="">-- Semua Mekanik --</option>
                            @foreach($mekaniks as $m)
                                <option value="{{ $m->KodeMekanik }}">{{ $m->NamaMekanik }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="dataTable" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>No PKB</th>
                                <th>Plat Nomor</th>
                                <th>Mekanik</th>
                                <th>Keluhan</th>
                                <th>Status</th>
                                <th>Laporan Mekanik</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Update -->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Progres Servis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateForm">
                        @csrf
                          <input type="hidden" name="id" id="pkb_id">
                          <div class="form-group mb-3">
                              <label class="fw-bold mb-2">Keluhan / Permintaan</label>
                              <div id="keluhanChecklist" class="border rounded p-2 bg-light">
                                  <!-- Checkboxes injected here -->
                              </div>
                          </div>
                          <div class="form-group mb-3">
                              <label class="fw-bold mb-2">Ambil Alih / Pilih Mekanik</label>
                              <select class="form-select" name="KodeMekanikUpdate" id="KodeMekanikUpdate">
                                  <option value="">-- Pilih Mekanik --</option>
                                  @foreach($mekaniks as $m)
                                      <option value="{{ $m->KodeMekanik }}">{{ $m->NamaMekanik }}</option>
                                  @endforeach
                              </select>
                              <small class="text-muted">Pilih nama Anda untuk mengambil alih pekerjaan ini.</small>
                          </div>
                          <div class="form-group mb-3">
                              <label class="fw-bold mb-2">Status Pekerjaan</label>
                            <select class="form-select" name="StatusServis" id="StatusServis">
                                <option value="0">Menunggu</option>
                                <option value="1">Dikerjakan</option>
                                <option value="2">Selesai</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label class="fw-bold mb-2">Laporan Mekanik (Kebutuhan Sparepart / Jasa / Kendala)</label>
                            <textarea class="form-control" name="LaporanMekanik" id="LaporanMekanik" rows="5"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveUpdate()">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        var table;
        jQuery(document).ready(function() {
            jQuery('#filterMekanik').change(function() {
                table.ajax.reload();
            });

            table = jQuery('#dataTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ route('dashboard-mekanik.getData') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = "{{ csrf_token() }}";
                        d.KodeMekanik = jQuery('#filterMekanik').val();
                    }
                },
                columns: [
                    { data: 'NoPKB' },
                    { 
                        data: 'PlatNomor',
                        render: function(data) {
                            return `<span class="fw-bold text-primary">${data}</span>`;
                        }
                    },
                    { data: 'NamaMekanik' },
                    { 
                        data: 'Keluhan',
                        render: function(data) {
                            try {
                                let parsed = JSON.parse(data);
                                if(Array.isArray(parsed)) {
                                    return parsed.map(item => {
                                        let icon = item.done ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="far fa-circle text-muted"></i>';
                                        return `<div class="mb-1">${icon} ${item.text}</div>`;
                                    }).join('');
                                }
                            } catch(e) { }
                            return data || '-';
                        }
                    },
                    { 
                        data: 'StatusServis',
                        render: function(data, type, row) {
                            let statusHtml = '';
                            if(data == 0) statusHtml = '<span class="badge bg-danger px-3 py-2">Menunggu</span>';
                            else if(data == 1) statusHtml = '<span class="badge bg-warning text-dark px-3 py-2">Dikerjakan</span>';
                            else if(data == 2) statusHtml = '<span class="badge bg-success px-3 py-2">Selesai</span>';
                            else statusHtml = data;

                            // Check overdue
                            if ((data == 0 || data == 1) && row.EstimasiWaktu > 0) {
                                let createdAt = new Date(row.created_at.replace(' ', 'T'));
                                let estimatedFinish = new Date(createdAt.getTime() + row.EstimasiWaktu * 60000);
                                let now = new Date();
                                
                                let timeStr = estimatedFinish.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                                statusHtml += `<br><span class="text-info d-block mt-2" style="font-size: 0.8rem; font-weight: bold;"><i class="fas fa-clock"></i> Target Selesai: ${timeStr}</span>`;
                                
                                if (now > estimatedFinish) {
                                    statusHtml += '<span class="badge bg-danger mt-1" style="font-size: 0.75rem;"><i class="fas fa-exclamation-triangle"></i> Lewat Waktu!</span>';
                                }
                            }
                            return statusHtml;
                        }
                    },
                    { data: 'LaporanMekanik' },
                    { 
                        data: 'id',
                        render: function(data, type, row) {
                            var laporan = row.LaporanMekanik ? row.LaporanMekanik.replace(/"/g, '&quot;') : '';
                            var keluhan = row.Keluhan ? row.Keluhan.replace(/'/g, "\\'").replace(/"/g, '&quot;').replace(/\n/g, "\\n").replace(/\r/g, "\\r") : '';
                            var mekanikCode = row.KodeMekanik ? row.KodeMekanik : '';
                            return `<button class="btn btn-sm btn-primary" onclick="openUpdateModal(${data}, ${row.StatusServis}, '${laporan}', '${keluhan}', '${mekanikCode}')"><i class="fas fa-edit"></i> Update</button>`;
                        }
                    }
                ]
            });
            
            // Auto reload every 10 seconds to keep dashboard fresh
            setInterval(function() {
                table.ajax.reload(null, false);
            }, 10000);
        });

        function openUpdateModal(id, status, laporan, keluhanStr, mekanikCode) {
            jQuery('#pkb_id').val(id);
            jQuery('#StatusServis').val(status);
            jQuery('#LaporanMekanik').val(laporan);
            jQuery('#KodeMekanikUpdate').val(mekanikCode || '');
            
            let keluhanHtml = '';
            try {
                if (keluhanStr) {
                    // decode HTML entities for JSON parsing
                    const decodedStr = keluhanStr.replace(/&quot;/g, '"');
                    const keluhanArr = JSON.parse(decodedStr);
                    if (Array.isArray(keluhanArr)) {
                        keluhanArr.forEach((k, idx) => {
                            const checked = (k.done === true || k.done === 'true' || k.done === 1 || k.done === '1') ? 'checked' : '';
                            keluhanHtml += `
                                <div class="form-check mb-2 border-bottom pb-2">
                                    <input type="hidden" name="keluhanText[${idx}]" value="${k.text}">
                                    <input class="form-check-input" type="checkbox" name="keluhanDone[${idx}]" value="1" id="keluhan_${idx}" ${checked}>
                                    <label class="form-check-label ms-1" style="font-size: 0.95rem; cursor: pointer;" for="keluhan_${idx}">
                                        ${k.text}
                                    </label>
                                </div>
                            `;
                        });
                    } else {
                        keluhanHtml = `<p class="text-muted mb-0">${decodedStr}</p>`; // Fallback for old string data
                    }
                }
            } catch(e) {
                 keluhanHtml = `<p class="text-muted mb-0">${keluhanStr.replace(/&quot;/g, '"')}</p>`; // Fallback for old string data
            }
            jQuery('#keluhanChecklist').html(keluhanHtml || '<em class="text-muted">Tidak ada keluhan</em>');

            jQuery('#updateModal').modal('show');
        }

        function saveUpdate() {
            var id = jQuery('#pkb_id').val();
            var formData = jQuery('#updateForm').serialize();
            jQuery.ajax({
                url: "{{ url('dashboard-mekanik/update') }}/" + id,
                type: 'POST',
                data: formData,
                success: function(res) {
                    if(res.success) {
                        Swal.fire('Berhasil!', res.message, 'success');
                        jQuery('#updateModal').modal('hide');
                        table.ajax.reload(null, false);
                    }
                },
                error: function(err) {
                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menyimpan data.', 'error');
                }
            });
        }
    </script>
</body>
</html>
