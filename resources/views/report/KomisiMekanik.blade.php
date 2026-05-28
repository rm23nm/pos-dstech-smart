@extends('parts.header')
	
@section('content')

<div class="subheader py-2 py-lg-6 subheader-solid">
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-white mb-0 px-0 py-2">
				<li class="breadcrumb-item active" aria-current="page">Laporan Komisi Mekanik</li>
			</ol>
		</nav>
	</div>
</div>
<div class="d-flex flex-column-fluid">
	<!--begin::Container-->
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 px-4">
				<div class="row">
					<div class="col-lg-12 col-xl-12 px-4">
						<div class="card card-custom gutter-b bg-transparent shadow-none border-0" >
							<div class="card-header align-items-center  border-bottom-dark px-0">
								<div class="card-title mb-0">
									<h3 class="card-label mb-0 font-weight-bold text-body">Laporan Komisi Mekanik 
									</h3>
								</div>
							</div>
						
						</div>


					</div>
				</div>

				<div class="row">
					<div class="col-12  px-4">
						<div class="card card-custom gutter-b bg-white border-0" >
							<div class="card-header" >
								Filter Data
							</div>
							<div class="card-body" >
                                <form action="#" id="form-filter">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label  class="text-body">Tanggal Awal</label>
                                            <input type="date" name="TglAwal" id="TglAwal" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <label  class="text-body">Tanggal Akhir</label>
                                            <input type="date" name="TglAkhir" id="TglAkhir" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <label  class="text-body">Mekanik</label>
                                            <select name="KodeMekanik" id="KodeMekanik" class="js-example-basic-single js-states form-control bg-transparent" >
                                                <option value="">Semua Mekanik</option>
                                                @foreach($mekanik as $ko)
                                                    <option value="{{ $ko->KodeMekanik }}">
                                                        {{ $ko->NamaMekanik }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <br>
                                            <button type="button" id="btn-cari" class="btn btn-outline-primary rounded-pill font-weight-bold me-1 mb-1">Cari Data</button>
                                        </div>
                                    </div>
                                </form>
							</div>
						</div>
					</div>
					<div class="col-12  px-4">
						<div class="card card-custom gutter-b bg-white border-0" >
							<div class="card-body" >
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

	jQuery(document).ready(function() {
		var now = new Date();
    	var day = ("0" + now.getDate()).slice(-2);
    	var month = ("0" + (now.getMonth() + 1)).slice(-2);
    	var firstDay = now.getFullYear()+"-"+month+"-01";
    	var NowDay = now.getFullYear()+"-"+month+"-"+day;

    	jQuery('#TglAwal').val(firstDay);
    	jQuery('#TglAkhir').val(NowDay);

        loadData();

        jQuery('#btn-cari').click(function() {
            loadData();
        });
	});

    function loadData() {
        Swal.showLoading();
        $.ajax({
            url: "{{ route('laporan-komisi-mekanik-data') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "TglAwal": jQuery('#TglAwal').val(),
                "TglAkhir": jQuery('#TglAkhir').val(),
                "KodeMekanik": jQuery('#KodeMekanik').val()
            },
            success: function(response) {
                Swal.close();
                if (response.success) {
                    bindGrid(response.data);
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(err) {
                Swal.close();
                console.error(err);
            }
        });
    }

	function bindGrid(data) {
		var dataGridInstance = jQuery("#gridContainer").dxDataGrid({
			allowColumnResizing: true,
			dataSource: data,
			keyExpr: "NoTransaksi", // Depending on unique key, this may be changed if multiple items per invoice
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
                    dataField: "NamaMekanik",
                    caption: "Mekanik",
                    allowEditing:false,
                    allowExporting: true,
                    groupIndex: 0
                },
                {
                    dataField: "NoTransaksi",
                    caption: "Nomor Faktur",
                    allowEditing:false,
                    allowExporting: true
                },
                {
                    dataField: "TglTransaksi",
                    caption: "Tanggal",
                    allowEditing:false,
                    allowExporting: true
                },
                {
                    dataField: "NamaItem",
                    caption: "Jasa / Upah",
                    allowEditing:false,
                    allowExporting: true
                },
                {
                    dataField: "Qty",
                    caption: "Qty",
                    allowEditing:false,
                    format: { type: 'fixedPoint', precision: 2 },
                    allowExporting: true
                },
                {
                    dataField: "KomisiMekanik",
                    caption: "Komisi (Rp)",
                    allowEditing:false,
                    format: { type: 'fixedPoint', precision: 2 },
                    allowExporting: true
                }
            ],
            summary: {
                groupItems: [
                    { 
                        column: "KomisiMekanik", 
                        summaryType: "sum" ,
                        alignByColumn: true, 
                        showInGroupFooter: true,
                        displayFormat: "{0}",
                        valueFormat: {
                            type: "fixedPoint",
                            precision: 2
                        }
                    }
                ],
                totalItems: [
                    {
                        column: "KomisiMekanik",
                        summaryType: "sum",
                        displayFormat: "{0}",
                        valueFormat: {
                            type: "fixedPoint",
                            precision: 2
                        }
                    }
                ]
            },
            onExporting(e) {
                switch (e.format) {
                    case "xlsx":
                        const workbook = new ExcelJS.Workbook();
                        const worksheet = workbook.addWorksheet('Komisi');

                        DevExpress.excelExporter.exportDataGrid({
                            component: e.component,
                            worksheet,
                            autoFilterEnabled: true,
                        }).then(() => {
                            workbook.xlsx.writeBuffer().then((buffer) => {
                            saveAs(new Blob([buffer], { type: 'application/octet-stream' }), 'Laporan Komisi Mekanik.xlsx');
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
                            var TglAwal = jQuery("#TglAwal").val().split("-");
                            var TglAkhir = jQuery("#TglAkhir").val().split("-");
                            const header = 'Laporan Komisi Mekanik Periode ' + TglAwal[2]+"/"+TglAwal[1]+"/"+TglAwal[0] + " s/d " + TglAkhir[2]+"/"+TglAkhir[1]+"/"+TglAkhir[0];
                            const pageWidth = doc.internal.pageSize.getWidth();
                            const headerWidth = doc.getTextDimensions(header).w;

                            doc.setFontSize(15);
                            doc.text(header, (pageWidth - headerWidth) / 2, 20);
                            doc.save('Laporan Komisi Mekanik.pdf');
                        });
                        break;
                }
            },
		}).dxDataGrid('instance');
	}
</script>
@endpush
