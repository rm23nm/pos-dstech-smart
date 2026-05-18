@extends('parts.header')

@section('content')
<!-- Custom premium styles for glassmorphism and modern UI elements -->
<style>
    .card-stat {
        background: linear-gradient(135deg, #ffffff 0%, #f3f6f9 100%);
        border: 1px solid rgba(224, 230, 238, 0.6);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }
    .text-premium-primary {
        color: #1bc5bd;
    }
    .text-premium-success {
        color: #3699ff;
    }
    .text-premium-warning {
        color: #ffa800;
    }
    .text-premium-danger {
        color: #f64e60;
    }
    .btn-premium {
        background: linear-gradient(135deg, #3699ff 0%, #0062cc 100%);
        border: none;
        color: #fff;
        border-radius: 20px;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(54, 153, 255, 0.4);
        transition: all 0.3s ease;
    }
    .btn-premium:hover {
        background: linear-gradient(135deg, #0062cc 0%, #004085 100%);
        box-shadow: 0 6px 20px rgba(0, 98, 204, 0.6);
        color: #fff;
        transform: scale(1.03);
    }
</style>

<div class="subheader py-2 py-lg-6 subheader-solid">
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-white mb-0 px-0 py-2">
				<li class="breadcrumb-item"><a href="{{ route('dashboardadmin') }}" class="text-muted">Admin</a></li>
				<li class="breadcrumb-item active" aria-current="page">Dashboard Multi-App DSTech</li>
			</ol>
		</nav>
	</div>
</div>

<div class="d-flex flex-column-fluid">
	<div class="container-fluid">
		
		<!-- 1. PREMIUM STATISTICS CARDS -->
		<div class="row mb-6">
			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card card-stat">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div>
								<span class="text-muted font-weight-bold text-uppercase font-size-sm">Total Pendapatan Global</span>
								<h3 class="font-weight-bolder text-dark-75 mt-2 mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
							</div>
							<div class="symbol symbol-50 symbol-light-success">
								<span class="symbol-label">
									<i class="flaticon-coins font-size-h3 text-premium-success"></i>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card card-stat">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div>
								<span class="text-muted font-weight-bold text-uppercase font-size-sm">Total Klien Berlangganan</span>
								<h3 class="font-weight-bolder text-dark-75 mt-2 mb-0">{{ $totalClients }} Mitra</h3>
							</div>
							<div class="symbol symbol-50 symbol-light-primary">
								<span class="symbol-label">
									<i class="flaticon-users font-size-h3 text-premium-primary"></i>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card card-stat">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div>
								<span class="text-muted font-weight-bold text-uppercase font-size-sm">Pendapatan SmartPro (WA)</span>
								<h3 class="font-weight-bolder text-dark-75 mt-2 mb-0">Rp {{ number_format($appStats['smartpro']['revenue'] ?? 0, 0, ',', '.') }}</h3>
							</div>
							<div class="symbol symbol-50 symbol-light-warning">
								<span class="symbol-label">
									<i class="flaticon-whatsapp font-size-h3 text-premium-warning"></i>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card card-stat">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div>
								<span class="text-muted font-weight-bold text-uppercase font-size-sm">Pendapatan Masjidku</span>
								<h3 class="font-weight-bolder text-dark-75 mt-2 mb-0">Rp {{ number_format($appStats['masjidku']['revenue'] ?? 0, 0, ',', '.') }}</h3>
							</div>
							<div class="symbol symbol-50 symbol-light-danger">
								<span class="symbol-label">
									<i class="flaticon-home-2 font-size-h3 text-premium-danger"></i>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- 2. FILTER DATA SECTION -->
		<div class="row">
			<div class="col-12">
				<div class="card card-custom gutter-b bg-white border-0 shadow-sm" style="border-radius:16px;">
					<div class="card-header border-0 bg-transparent py-4">
						<div class="card-title">
							<h3 class="card-label font-weight-bolder text-dark">Filter Integrasi Multi-App</h3>
						</div>
					</div>
					<div class="card-body">
						<div class="row align-items-end">
							<div class="col-md-3 mb-3">
								<label class="text-body font-weight-bold">Aplikasi Sumber</label>
								<select id="AppSource" class="form-control rounded-pill">
									<option value="all">Semua Aplikasi</option>
									<option value="pos">POS dstechsmart</option>
									<option value="smartpro">SmartPro (WA Gateway)</option>
									<option value="masjidku">Masjidku Smart</option>
									<option value="smartaccess">Smart Gate Access</option>
								</select>
							</div>
							<div class="col-md-3 mb-3">
								<label class="text-body font-weight-bold">Tanggal Awal</label>
								<input type="date" id="TglAwal" class="form-control rounded-pill">
							</div>
							<div class="col-md-3 mb-3">
								<label class="text-body font-weight-bold">Tanggal Akhir</label>
								<input type="date" id="TglAkhir" class="form-control rounded-pill">
							</div>
							<div class="col-md-3 mb-3">
								<button class="btn btn-primary rounded-pill font-weight-bold w-100 py-3" id="btSearch">
									<i class="flaticon-search mr-2"></i> Tampilkan Data
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- 3. CENTRALIZED DATA GRID -->
		<div class="row">
			<div class="col-12">
				<div class="card card-custom gutter-b bg-white border-0 shadow-sm" style="border-radius:16px;">
					<div class="card-header border-0 bg-transparent py-4 d-flex align-items-center justify-content-between flex-wrap">
						<div class="card-title my-2">
							<h3 class="card-label font-weight-bolder text-dark">Daftar Transaksi Langganan Lintas Aplikasi</h3>
						</div>
						<div class="d-flex align-items-center my-2">
							<button type="button" id="btnBulkWA" class="btn btn-premium px-6 py-3 font-weight-bolder mr-2" disabled>
								<i class="fab fa-whatsapp mr-2"></i> Kirim WA Blast Prospek
							</button>
						</div>
					</div>
					<div class="card-body">
						<div class="dx-viewport demo-container">
							<div id="data-grid-demo">
								<div id="gridContainerHeader"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<!-- 4. BROADCAST MODAL -->
<div class="modal fade" id="broadcastModal" tabindex="-1" role="dialog" aria-labelledby="broadcastModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
			<div class="modal-header border-0 bg-light-primary" style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
				<h5 class="modal-title font-weight-bolder text-primary" id="broadcastModalLabel">
					<i class="fab fa-whatsapp text-success mr-2 font-size-h3"></i> Kirim Whatsapp Prospek & Follow-Up Klien
				</h5>
				<button type="button" class="close text-primary" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body p-6">
				<div class="alert alert-custom alert-light-primary fade show mb-5" role="alert">
					<div class="alert-icon"><i class="flaticon-info"></i></div>
					<div class="alert-text font-weight-bold">
						Anda akan mengirimkan pesan WhatsApp personal ke <span id="clientCountText" class="text-danger">0</span> klien terpilih. Gunakan placeholder berikut untuk pesan dinamis:<br>
						<code>[NamaClient]</code> - Nama Klien | <code>[AppSource]</code> - Aplikasi Asal | <code>[Paket]</code> - Paket Langganan
					</div>
				</div>
				<div class="form-group">
					<label class="font-weight-bold text-dark">Template Pesan WhatsApp</label>
					<textarea id="messageTemplate" class="form-control" rows="8" style="border-radius: 12px; font-family: 'Courier New', monospace;"></textarea>
				</div>
			</div>
			<div class="modal-footer border-0">
				<button type="button" class="btn btn-secondary rounded-pill font-weight-bold px-6" data-dismiss="modal">Batal</button>
				<button type="button" id="btnSendBroadcast" class="btn btn-premium px-8 py-3">
					<i class="fa fa-paper-plane mr-2"></i> Kirim Broadcast Sekarang
				</button>
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script type="text/javascript">
	jQuery(document).ready(function() {
		// Set default date range to current month
		var now = new Date();
    	var day = ("0" + now.getDate()).slice(-2);
    	var month = ("0" + (now.getMonth() + 1)).slice(-2);
    	var firstDay = now.getFullYear()+"-"+month+"-01";
    	var NowDay = now.getFullYear()+"-"+month+"-"+day;

    	jQuery('#TglAwal').val(firstDay);
    	jQuery('#TglAkhir').val(NowDay);

        GetLedgerData();
	});

    jQuery('#btSearch').click(function () {
        GetLedgerData();
    });

	var selectedRowIds = [];
	var selectedClients = [];

    function GetLedgerData() {
        $.ajax({
            type: 'post',
            url: "{{ route('dstechgloballedger-data') }}",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                'TglAwal' : jQuery('#TglAwal').val(),
                'TglAkhir' : jQuery('#TglAkhir').val(),
                'AppSource' : jQuery('#AppSource').val()
            },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    bindGridHeader(response.data);
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            },
            error: function(err) {
                Swal.fire('Error!', 'Gagal memuat data dari server.', 'error');
            }
        });
    }

	function bindGridHeader(data) {
		var dataGridInstance = jQuery("#gridContainerHeader").dxDataGrid({
			allowColumnResizing: true,
			dataSource: data,
			keyExpr: "id",
			showBorders: true,
            allowColumnReordering: true,
            columnAutoWidth: true,
            selection: {
                mode: "multiple"
            },
            paging: {
                enabled: true,
                pageSize: 10
            },
            searchPanel: {
	            visible: true,
	            width: 260,
	            placeholder: "Cari data transaksi..."
	        },
            onSelectionChanged: function (selectedItems) {
                var data = selectedItems.selectedRowsData;
                selectedRowIds = [];
                selectedClients = [];
                if (data.length > 0) {
                    jQuery("#btnBulkWA").prop('disabled', false);
                    for (var i = 0; i < data.length; i++) {
                        selectedRowIds.push(data[i].id);
                    }
                    jQuery("#clientCountText").text(data.length);
                } else {
                    jQuery("#btnBulkWA").prop('disabled', true);
                }
            },
            columns: [
                {
                    dataField: "dstech_app_source",
                    caption: "Aplikasi Asal",
                    cellTemplate: function(element, info) {
                        var val = info.value;
                        var badgeClass = "badge-light-primary";
                        var label = val.toUpperCase();
                        
                        if (val === 'pos') {
                            badgeClass = "badge-light-success";
                            label = "POS DSTECH";
                        } else if (val === 'smartpro') {
                            badgeClass = "badge-light-warning";
                            label = "SMARTPRO WA";
                        } else if (val === 'masjidku') {
                            badgeClass = "badge-light-danger";
                            label = "MASJIDKU";
                        } else if (val === 'smartaccess') {
                            badgeClass = "badge-light-info";
                            label = "SMART GATE";
                        }

                        element.append("<span class='badge " + badgeClass + " font-weight-bold px-3 py-2 text-uppercase'>" + label + "</span>");
                    }
                },
                {
                    dataField: "dstech_client_id",
                    caption: "ID Klien",
                },
                {
                    dataField: "dstech_client_name",
                    caption: "Nama Klien",
                },
                {
                    dataField: "dstech_client_phone",
                    caption: "No. WhatsApp/HP",
                },
                {
                    dataField: "dstech_package_name",
                    caption: "Paket",
                },
                {
                    dataField: "dstech_amount",
                    caption: "Nominal Langganan",
                    format: { type: 'fixedPoint', precision: 0 },
                    customizeText: function (cellInfo) {
                        return "Rp " + parseFloat(cellInfo.value).toLocaleString('id-ID');
                    }
                },
                {
                    dataField: "dstech_payment_status",
                    caption: "Status",
                    cellTemplate: function(element, info) {
                        var val = info.value;
                        var badge = "badge-success";
                        if(val === 'pending') badge = "badge-warning";
                        if(val === 'expired') badge = "badge-danger";
                        element.append("<span class='badge " + badge + " font-weight-bold px-3 py-1 text-uppercase'>" + val + "</span>");
                    }
                },
                {
                    dataField: "dstech_start_date",
                    caption: "Mulai Langganan",
                },
                {
                    dataField: "dstech_end_date",
                    caption: "Jatuh Tempo",
                },
                {
                    dataField: "dstech_transaction_id",
                    caption: "No. Transaksi",
                }
            ],
		}).dxDataGrid('instance');
	}

    // Modal Broadcast Handling
    jQuery("#btnBulkWA").click(function() {
        var defaultTemplate = "Halo [NamaClient],\n\n" + 
            "Kami ingin mengucapkan terima kasih atas kepercayaan Anda menggunakan layanan [AppSource] kami dengan paket [Paket].\n\n" + 
            "Untuk info promo terbaru, update fitur premium, atau perpanjangan akun, silakan hubungi tim Support DS Tech Smart Perkasa via chat ini.\n\n" + 
            "Salam Hangat,\n" + 
            "PT. DSTECH SMART PERKASA";
        
        jQuery("#messageTemplate").val(defaultTemplate);
        jQuery("#broadcastModal").modal('show');
    });

    jQuery("#btnSendBroadcast").click(function() {
        var template = jQuery("#messageTemplate").val();
        if(!template.trim()) {
            Swal.fire('Peringatan!', 'Template pesan tidak boleh kosong.', 'warning');
            return;
        }

        jQuery("#btnSendBroadcast").prop('disabled', true).text('Mengirim...');

        $.ajax({
            type: 'post',
            url: "{{ route('dstechgloballedger-broadcast') }}",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                'client_ids': selectedRowIds,
                'message_template': template
            },
            dataType: 'json',
            success: function(response) {
                jQuery("#btnSendBroadcast").prop('disabled', false).html('<i class="fa fa-paper-plane mr-2"></i> Kirim Broadcast Sekarang');
                if(response.success) {
                    jQuery("#broadcastModal").modal('hide');
                    Swal.fire('Selesai!', response.message, 'success');
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            },
            error: function(err) {
                jQuery("#btnSendBroadcast").prop('disabled', false).html('<i class="fa fa-paper-plane mr-2"></i> Kirim Broadcast Sekarang');
                Swal.fire('Error!', 'Gagal mengirimkan pesan broadcast.', 'error');
            }
        });
    });
</script>
@endpush
