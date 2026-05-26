@extends('parts.header')
	
@section('content')

<div class="subheader py-2 py-lg-6 subheader-solid">
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-white mb-0 px-0 py-2">
				<li class="breadcrumb-item active" aria-current="page">Laporan Barang Expired</li>
			</ol>
		</nav>
	</div>
</div>
<div class="d-flex flex-column-fluid">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 px-4">
				<div class="row">
					<div class="col-lg-12 col-xl-12 px-4">
						<div class="card card-custom gutter-b bg-transparent shadow-none border-0">
							<div class="card-header align-items-center border-bottom-dark px-0">
								<div class="card-title mb-0">
									<h3 class="card-label mb-0 font-weight-bold text-body">
										Laporan Barang Expired
										<small class="text-muted font-size-sm d-block">
											Peringatan: {{ $alertDays }} hari sebelum expired
										</small>
									</h3>
								</div>
								<div class="card-toolbar">
									@if(!empty($company->ExpiredAlertWA))
									<button type="button" id="btnKirimWA" class="btn btn-success rounded-pill font-weight-bold">
										<i class="fab fa-whatsapp mr-1"></i> Kirim Notifikasi WA
									</button>
									@else
									<a href="{{ route('company-setting') }}" class="btn btn-warning rounded-pill font-weight-bold">
										<i class="fas fa-cog mr-1"></i> Atur Nomor WA Notifikasi
									</a>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>

				{{-- Info Banner --}}
				<div class="row mb-2">
					<div class="col-12 px-4">
						<div class="alert alert-custom alert-light-warning alert-dismissible fade show" role="alert">
							<div class="alert-icon"><i class="flaticon-warning"></i></div>
							<div class="alert-text">
								<strong>Keterangan warna:</strong>
								<span class="badge badge-danger ml-2">Merah</span> = Sudah Expired &nbsp;|&nbsp;
								<span class="badge badge-warning ml-1">Kuning</span> = Akan Expired dalam {{ $alertDays }} hari ke depan.
								Untuk mengubah batas peringatan, buka <a href="{{ route('company-setting') }}">Pengaturan Perusahaan</a>.
							</div>
							<div class="alert-close">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true"><i class="ki ki-close"></i></span>
								</button>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-12 px-4">
						<div class="card card-custom gutter-b bg-white border-0">
							<div class="card-body">
								<div class="dx-viewport demo-container">
				                	<div id="data-grid-demo">
				                  		<div id="gridContainer"></div>
				                	</div>
				              	</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script type="text/javascript">
    window.jsPDF = window.jspdf.jsPDF;
    var alertDays = {{ $alertDays ?? 90 }};
    var waRoute   = "{{ route('report-expired-sendwa') }}";
    var csrfToken = "{{ csrf_token() }}";

	jQuery(document).ready(function() {
        var oData = <?php echo json_encode($data) ?>;
        bindGridExpired(oData);

        jQuery('#btnKirimWA').on('click', function() {
            var btn = jQuery(this);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim...');

            jQuery.ajax({
                url: waRoute,
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function(res) {
                    if (res.success) {
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message });
                    } else {
                        Swal.fire({ icon: 'warning', title: 'Perhatian', text: res.message });
                    }
                },
                error: function(xhr) {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat mengirim notifikasi.' });
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="fab fa-whatsapp mr-1"></i> Kirim Notifikasi WA');
                }
            });
        });
	});

    function bindGridExpired(data) {
		jQuery("#gridContainer").dxDataGrid({
			allowColumnResizing: true,
			dataSource: data,
			keyExpr: "KodeItem",
			showBorders: true,
            allowColumnReordering: true,
            columnAutoWidth: true,
            paging: {
                enabled: true,
                pageSize: 30
            },
            export: {
                enabled: true,
                formats: ['pdf','xlsx'],
            },
            searchPanel: {
	            visible: true,
	            width: 240,
	            placeholder: "Search..."
	        },
            selection: {
                mode: "single"
            },
            columns: [
                {
                    dataField: "KodeItem",
                    caption: "Kode Item",
                    allowEditing: false,
                    allowExporting: true,
                },
                {
                    dataField: "Barcode",
                    caption: "Barcode",
                    allowEditing: false,
                    allowExporting: true,
                },
                {
                    dataField: "NamaItem",
                    caption: "Nama Item",
                    allowEditing: false,
                    allowExporting: true
                },
                {
                    dataField: "Stock",
                    caption: "Saldo Stok",
                    allowEditing: false,
                    allowExporting: true,
                    format: { type: 'fixedPoint', precision: 2 },
                },
                {
                    dataField: "NamaSatuan",
                    caption: "Satuan",
                    allowEditing: false,
                    allowExporting: true
                },
                {
                    dataField: "ExpiredDate",
                    caption: "Tanggal Kedaluwarsa",
                    dataType: "date",
                    format: "yyyy-MM-dd",
                    allowEditing: false,
                    allowExporting: true,
                    sortOrder: "asc"
                },
                {
                    caption: "Status",
                    allowEditing: false,
                    allowExporting: false,
                    calculateCellValue: function(rowData) {
                        if (!rowData.ExpiredDate) return '';
                        var expDate = new Date(rowData.ExpiredDate);
                        var today   = new Date();
                        today.setHours(0,0,0,0);
                        expDate.setHours(0,0,0,0);
                        var diffDays = Math.ceil((expDate - today) / (1000 * 60 * 60 * 24));
                        if (diffDays < 0) return '⛔ Sudah Expired';
                        if (diffDays === 0) return '⛔ Expired Hari Ini';
                        if (diffDays <= alertDays) return '⚠️ Akan Expired (' + diffDays + ' hari lagi)';
                        return '✅ Aman';
                    }
                },
            ],
            onRowPrepared: function(e) {
                if (e.rowType === "data") {
                    if (e.data.ExpiredDate) {
                        var expDate = new Date(e.data.ExpiredDate);
                        var today   = new Date();
                        today.setHours(0,0,0,0);
                        expDate.setHours(0,0,0,0);
                        var diffDays = Math.ceil((expDate - today) / (1000 * 60 * 60 * 24));

                        if (diffDays < 0) {
                            // Sudah expired (Merah)
                            e.rowElement.css("background-color", "#ffcccc");
                        } else if (diffDays <= alertDays) {
                            // Akan expired (Kuning)
                            e.rowElement.css("background-color", "#ffffcc");
                        }
                    }
                }
            },
            onExporting(e) {
                switch (e.format) {
                    case "xlsx":
                        const workbook = new ExcelJS.Workbook();
                        const worksheet = workbook.addWorksheet('Laporan Expired');
                        DevExpress.excelExporter.exportDataGrid({
                            component: e.component,
                            worksheet,
                            autoFilterEnabled: true,
                        }).then(() => {
                            workbook.xlsx.writeBuffer().then((buffer) => {
                                saveAs(new Blob([buffer], { type: 'application/octet-stream' }), 'Laporan_Barang_Expired.xlsx');
                            });
                        });
                        break;
                    case "pdf":
                        const doc = new jsPDF({
                            orientation: 'landscape',
                            unit: 'pt',
                            format: [1000, 612],
                        });
                        DevExpress.pdfExporter.exportDataGrid({
                            jsPDFDocument: doc,
                            component: e.component,
                        }).then(() => {
                            const header = 'Laporan Barang Expired';
                            const pageWidth = doc.internal.pageSize.getWidth();
                            const headerWidth = doc.getTextDimensions(header).w;
                            doc.setFontSize(15);
                            doc.text(header, (pageWidth - headerWidth) / 2, 20);
                            doc.save('Laporan_Barang_Expired.pdf');
                        });
                        break;
                }
            },
		});
	}
</script>
@endpush
