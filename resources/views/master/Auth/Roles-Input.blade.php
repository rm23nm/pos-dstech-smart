@extends('parts.header')
	
@section('content')

<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6 subheader-solid">
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-white mb-0 px-0 py-2">
				<li class="breadcrumb-item active" aria-current="page">
					<a href="{{route('roles')}}">Kelompok Akses</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Input Kelompok Akses</li>
			</ol>
		</nav>
	</div>
</div>
<!--end::Subheader-->
<!--begin::Entry-->
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
									<h3 class="card-label mb-0 font-weight-bold text-body">
										@if (count($roles) > 0)
                                    		Edit Kelompok Akses
                                    		<input type="hidden" name="formtype" id="formtype" value="edit">
	                                	@else
	                                    	Tambah Kelompok Akses
	                                    	<input type="hidden" name="formtype" id="formtype" value="add">
	                                	@endif
									</h3>
								</div>
							</div>
						
						</div>


					</div>
				</div>

				<div class="row">
					<div class="col-12  px-4">
						<div class="card card-custom gutter-b bg-white border-0" >
							<div class="card-body" >
                            	<div class="form-group row">
                            		<div class="col-md-12">
                            			<label  class="text-body" style="display: none;">Kode Grup</label>
                            			<fieldset class="form-group mb-3">
                            				<input type="hidden" class="form-control" id="id" name="id" placeholder="Masukan Kode Grup" value="{{ count($roles) > 0 ? $roles[0]['id'] : '' }}" >
                            			</fieldset>
                            			
                            		</div>
                            		
                            		<div class="col-md-12">
                            			<label  class="text-body">Nama Grup</label>
                            			<fieldset class="form-group mb-3">
                            				<input type="text" class="form-control" id="RoleName" name="RoleName" placeholder="Masukan Nama Kelompok AKses" value="{{ count($roles) > 0 ? $roles[0]['RoleName'] : '' }}" required="" {{ count($roles) > 0 ? $roles[0]['RoleName'] == 'SuperAdmin' ? 'readonly' : '' : '' }}>
                            			</fieldset>
                            		</div>
                            	</div>
							</div>
						</div>
					</div>

                    @php
                        $premiumCategories = [
                            'pos' => ['PermissionName' => 'Operasional POS Kasir', 'Icon' => 'fas fa-cash-register', 'submenu' => [], 'ParentType' => 1],
                            'billiard' => ['PermissionName' => 'Sewa Billing & IoT', 'Icon' => 'fas fa-lightbulb', 'submenu' => [], 'ParentType' => 1],
                            'booking' => ['PermissionName' => 'Booking & Reservasi', 'Icon' => 'far fa-calendar-alt', 'submenu' => [], 'ParentType' => 1],
                            'display' => ['PermissionName' => 'Layar Antrean & KDS', 'Icon' => 'fas fa-desktop', 'submenu' => [], 'ParentType' => 1],
                            'resto' => ['PermissionName' => 'Manajemen Resto', 'Icon' => 'fas fa-utensils', 'submenu' => [], 'ParentType' => 1],
                            'inventory' => ['PermissionName' => 'Inventori & Stok', 'Icon' => 'fas fa-boxes', 'submenu' => [], 'ParentType' => 1],
                            'consignment' => ['PermissionName' => 'Barang Konsinyasi', 'Icon' => 'fas fa-handshake', 'submenu' => [], 'ParentType' => 1],
                            'purchasing' => ['PermissionName' => 'Pembelian & Supplier', 'Icon' => 'fas fa-shopping-cart', 'submenu' => [], 'ParentType' => 1],
                            'crm' => ['PermissionName' => 'Mitra Bisnis & CRM', 'Icon' => 'fas fa-users', 'submenu' => [], 'ParentType' => 1],
                            'finance' => ['PermissionName' => 'Kas, Bank & Biaya', 'Icon' => 'fas fa-wallet', 'submenu' => [], 'ParentType' => 1],
                            'accounting' => ['PermissionName' => 'Akuntansi & COA', 'Icon' => 'fas fa-book', 'submenu' => [], 'ParentType' => 1],
                            'reports_sales' => ['PermissionName' => 'Laporan Bisnis & Stok', 'Icon' => 'fas fa-chart-bar', 'submenu' => [], 'ParentType' => 1],
                            'reports_accounting' => ['PermissionName' => 'Laporan Keuangan', 'Icon' => 'fas fa-balance-scale', 'submenu' => [], 'ParentType' => 1],
                            'system' => ['PermissionName' => 'Sistem & Pengaturan', 'Icon' => 'fas fa-cogs', 'submenu' => [], 'ParentType' => 1]
                        ];

                        foreach ($permissionrole as $lv1) {
                            if (!empty($lv1['submenu'])) {
                                foreach ($lv1['submenu'] as $lv2) {
                                    $targetCat = null;
                                    $l2Name = strtolower(trim($lv2['PermissionName']));

                                    if ($l2Name === 'bussiness partner') {
                                        $targetCat = 'crm';
                                    } elseif (in_array($l2Name, ['controller', 'manajemen gate'])) {
                                        $targetCat = 'billiard';
                                    } elseif ($l2Name === 'item master data') {
                                        $targetCat = 'inventory';
                                    } elseif ($l2Name === 'finance') {
                                        $finSub = [];
                                        $acctSub = [];
                                        if (!empty($lv2['submenu'])) {
                                            foreach ($lv2['submenu'] as $lv3) {
                                                $l3Link = strtolower(trim($lv3['Link']));
                                                if (in_array($l3Link, ['bank', 'metodepembayaran', 'kelompokrekening'])) {
                                                    $finSub[] = $lv3;
                                                } else {
                                                    $acctSub[] = $lv3;
                                                }
                                            }
                                        }
                                        if (!empty($finSub)) {
                                            $premiumCategories['finance']['submenu'][] = [
                                                'PermissionName' => 'Master Keuangan', 'MenuID' => 'art_fin',
                                                'Icon' => $lv2['Icon'], 'submenu' => $finSub, 'ParentType' => 1
                                            ];
                                        }
                                        if (!empty($acctSub)) {
                                            $premiumCategories['accounting']['submenu'][] = [
                                                'PermissionName' => 'Master Akuntansi', 'MenuID' => 'art_acc',
                                                'Icon' => $lv2['Icon'], 'submenu' => $acctSub, 'ParentType' => 1
                                            ];
                                        }
                                        continue;
                                    } elseif ($l2Name === 'pengaturan toko') {
                                        $targetCat = 'system';
                                    } elseif ($l2Name === 'pembelian') {
                                        $targetCat = 'purchasing';
                                    } elseif ($l2Name === 'penjualan') {
                                        $posSub = [];
                                        $bookSub = [];
                                        if (!empty($lv2['submenu'])) {
                                            foreach ($lv2['submenu'] as $lv3) {
                                                $l3Link = strtolower(trim($lv3['Link']));
                                                if (str_contains($l3Link, 'booking')) {
                                                    $bookSub[] = $lv3;
                                                } else {
                                                    $posSub[] = $lv3;
                                                }
                                            }
                                        }
                                        if (!empty($posSub)) {
                                            $premiumCategories['pos']['submenu'][] = [
                                                'PermissionName' => 'Transaksi POS', 'MenuID' => 'art_pos',
                                                'Icon' => $lv2['Icon'], 'submenu' => $posSub, 'ParentType' => 1
                                            ];
                                        }
                                        if (!empty($bookSub)) {
                                            $premiumCategories['booking']['submenu'][] = [
                                                'PermissionName' => 'Pemesanan Tempat', 'MenuID' => 'art_book',
                                                'Icon' => $lv2['Icon'], 'submenu' => $bookSub, 'ParentType' => 1
                                            ];
                                        }
                                        continue;
                                    } elseif (in_array($l2Name, ['info kitchen', 'queue antrian fnb', 'monitor counter (recall)', 'antrian fnb dapur', 'queue lapangan', 'customer display pos'])) {
                                        $targetCat = 'display';
                                    } elseif ($l2Name === 'konsinyasi') {
                                        $targetCat = 'consignment';
                                    } elseif ($l2Name === 'inventory') {
                                        $targetCat = 'inventory';
                                    } elseif ($l2Name === 'jurnal entry') {
                                        $targetCat = 'accounting';
                                    } elseif (in_array($l2Name, ['transaksi biaya', 'kas masuk', 'kas keluar', 'transfer kas', 'transaksi bank', 'opening balance'])) {
                                        $targetCat = 'finance';
                                    } elseif (in_array($l2Name, ['lap penjualan', 'lap pembelian', 'laporan inventory'])) {
                                        $targetCat = 'reports_sales';
                                    } elseif ($l2Name === 'lap akutansi') {
                                        $targetCat = 'reports_accounting';
                                    } elseif (in_array($l2Name, ['autorisasi', 'pengguna'])) {
                                        $targetCat = 'system';
                                    } elseif ($l2Name === 'paket') {
                                        $targetCat = 'billiard';
                                    } elseif ($l2Name === 'master resto') {
                                        $targetCat = 'resto';
                                    } elseif (in_array($l2Name, ['produk', 'term and conditon', 'app setting', 'pengguna', 'invoice pengguna', 'article', 'serial number generator', 'voucher'])) {
                                        $targetCat = 'system';
                                    }

                                    if ($targetCat && isset($premiumCategories[$targetCat])) {
                                        $premiumCategories[$targetCat]['submenu'][] = $lv2;
                                    }
                                }
                            }
                        }

                        $activePremiumCategories = [];
                        foreach ($premiumCategories as $key => $cat) {
                            if (!empty($cat['submenu'])) {
                                $activePremiumCategories[$key] = $cat;
                            }
                        }

                        $sectionGroups = [
                            'front_office' => ['label' => 'OPERASIONAL (FRONT-OFFICE)', 'keys' => ['pos', 'billiard', 'booking', 'display']],
                            'back_office' => ['label' => 'MANAJEMEN & INVENTORI (BACK-OFFICE)', 'keys' => ['resto', 'inventory', 'consignment', 'purchasing', 'crm']],
                            'financial' => ['label' => 'KEUANGAN & LAPORAN', 'keys' => ['finance', 'accounting', 'reports_sales', 'reports_accounting']],
                            'system' => ['label' => 'PENGATURAN & SISTEM', 'keys' => ['system']]
                        ];
                    @endphp

					<div class="col-12  px-4">
						<div class="card card-custom gutter-b bg-white border-0" >
							<div class="card-header border-0 align-items-center">
								<h3 class="card-label mb-0 font-weight-bold text-body">Hak Akses (Sesuai Kategori Sidebar)
								</h3>
							</div>
							<div class="card-body">
								<div >
									<div class="dd" id="nestable">
										<ol class="dd-list">
                                            @foreach ($sectionGroups as $sKey => $sec)
                                                @php
                                                    $hasActiveCats = false;
                                                    foreach ($sec['keys'] as $cKey) {
                                                        if (isset($activePremiumCategories[$cKey])) {
                                                            $hasActiveCats = true;
                                                            break;
                                                        }
                                                    }
                                                @endphp
                                                @if ($hasActiveCats)
                                                    <li class="dd-item dd-nodrag">
                                                        <div class="dd-handle" style="background:#f8f9fa; color:#8a8a9e; font-weight:bold; letter-spacing:1px; font-size:13px; text-transform:uppercase;">
                                                            {{ $sec['label'] }}
                                                        </div>
                                                        <ol class="dd-list">
                                                            @foreach ($sec['keys'] as $cKey)
                                                                @if (isset($activePremiumCategories[$cKey]))
                                                                    @php $lv1 = $activePremiumCategories[$cKey]; @endphp
                                                                    <li class="dd-item" data-id="cat_{{ $cKey }}">
                                                                        <div class="dd-handle" style="font-size:13px; font-weight:600;"><i class="{{ $lv1['Icon'] }} me-2"></i> {{ $lv1['PermissionName'] }}</div>
                                                                        <ol class="dd-list">
                                                                            @foreach ($lv1['submenu'] as $lv2)
                                                                                <li class="dd-item" data-id="{{ $lv2['MenuID'] }}">
                                                                                    <div class="dd-handle" style="font-size:13px;">{{ $lv2['PermissionName'] }}</div>
                                                                                    @if(strpos($lv2['MenuID'], 'art_') === false)
                                                                                        <div class="inner-content">
                                                                                            <div class="custom-control switch custom-switch-info custom-control-inline form-check form-switch me-0">
                                                                                                <input type="checkbox" class="form-check-input permission-chk" id="chk{{ str_replace(' ','',$lv2['MenuID']) }}" value="{{ $lv2['MenuID'] }}" data-parent="{{ $lv2['MenuInduk'] }}" {{ (isset($lv2['Selected']) && $lv2['Selected'] != "") ? 'checked' : '' }}>
                                                                                                <label class="form-check-label me-1" for="chk{{ str_replace(' ','',$lv2['MenuID']) }}"></label>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                    @if (isset($lv2['submenu']) && count($lv2['submenu']) > 0)
                                                                                        <ol class="dd-list">
                                                                                            @foreach ($lv2['submenu'] as $lv3)
                                                                                                <li class="dd-item" data-id="{{ $lv3['MenuID'] }}">
                                                                                                    <div class="dd-handle" style="font-size:13px;">{{ $lv3['PermissionName'] }}</div>
                                                                                                    <div class="inner-content">
                                                                                                        <div class="custom-control switch custom-switch-info custom-control-inline form-check form-switch me-0">
                                                                                                            <input type="checkbox" class="form-check-input permission-chk" id="chk{{ str_replace(' ','',$lv3['MenuID']) }}" value="{{ $lv3['MenuID'] }}" data-parent="{{ $lv3['MenuInduk'] }}" data-grandparent="{{ $lv2['MenuInduk'] ?? '' }}" {{ (isset($lv3['Selected']) && $lv3['Selected'] != "") ? 'checked' : '' }}>
                                                                                                            <label class="form-check-label me-1" for="chk{{ str_replace(' ','',$lv3['MenuID']) }}"></label>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </li>
                                                                                            @endforeach
                                                                                        </ol>
                                                                                    @endif
                                                                                </li>
                                                                            @endforeach
                                                                        </ol>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ol>
                                                    </li>
                                                @endif
                                            @endforeach
										</ol>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-12">
	        			<button type="button" class="btn btn-success text-white font-weight-bold me-1 mb-1" id="btSave">Simpan</button>
	        		</div>
				</div>
			</div>
			
		</div>
	</div>
	
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/jquery.nestable.js')}}"></script>
<script type="text/javascript">
	jQuery('#nestable').nestable({
		collapsedClass:'dd-collapsed',
	}).nestable('collapseAll')
	
	jQuery(function () {
		jQuery('#btSave').click(function () {
			jQuery('#btSave').text('Tunggu Sebentar');
		    jQuery('#btSave').attr('disabled',true);
			
            var checkedIds = new Set();
            jQuery('.permission-chk:checked').each(function() {
                checkedIds.add(jQuery(this).val());
                var parentId = jQuery(this).attr('data-parent');
                if (parentId && parentId !== "") { checkedIds.add(parentId); }
                var grandParentId = jQuery(this).attr('data-grandparent');
                if (grandParentId && grandParentId !== "") { checkedIds.add(grandParentId); }
            });

            var oDetail = [];
            checkedIds.forEach(function(id) {
                oDetail.push({ 'id': id });
            });

			var oData = {
				'id' : jQuery('#id').val(),
				'RoleName' : jQuery('#RoleName').val(),
				'permissionrole' : oDetail
			};

			var formtype = $('#formtype').val();
			if (formtype == 'add') {
				$.ajax({
					url: "{{route('roles-store')}}",
					type: 'POST',
					contentType: 'application/json',
					headers: {
		                'X-CSRF-TOKEN': '{{ csrf_token() }}'
		            },
		            data: JSON.stringify(oData),
		            success: function(response) {
		            	if (response.success == true) {
		            		Swal.fire({
		                        html: "Data berhasil disimpan!",
		                        icon: "success",
		                        title: "Horray...",
		                    }).then((result)=>{
		                        window.location.href = '{{url("roles")}}';
		                    });
		            	} else {
		            		Swal.fire({
		                      icon: "error",
		                      title: "Opps...",
		                      text: response.message,
		                    })
		                    jQuery('#btSave').text('Simpan');
		                    jQuery('#btSave').attr('disabled',false);
		            	}
		            }
				})
			} else {
				$.ajax({
					url: "{{route('roles-edit')}}",
					type: 'POST',
					contentType: 'application/json',
					headers: {
		                'X-CSRF-TOKEN': '{{ csrf_token() }}'
		            },
		            data: JSON.stringify(oData),
		            success: function(response) {
		            	if (response.success == true) {
		            		Swal.fire({
		                        html: "Data berhasil disimpan!",
		                        icon: "success",
		                        title: "Horray...",
		                    }).then((result)=>{
		                        window.location.href = '{{url("roles")}}';
		                    });
		            	} else {
		            		Swal.fire({
		                      icon: "error",
		                      title: "Opps...",
		                      text: response.message,
		                    })
		                    jQuery('#btSave').text('Simpan');
		                    jQuery('#btSave').attr('disabled',false);
		            	}
		            }
				})
			}
		})
	});
</script>
@endpush