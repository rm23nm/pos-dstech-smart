	
<?php $__env->startSection('content'); ?>

<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6 subheader-solid">
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-white mb-0 px-0 py-2">
				<li class="breadcrumb-item active" aria-current="page">Grup Sales</li>
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
									<h3 class="card-label mb-0 font-weight-bold text-body">Sales 
									</h3>
								</div>
							    <div class="icons d-flex">
									<a href="<?php echo e(url('sales/form/-')); ?>" class="btn btn-outline-primary rounded-pill font-weight-bold me-1 mb-1">Tambah Data</a>
									<div class="icons d-flex">
									<!-- <a href="<?php echo e(url('sales/export')); ?>" class="btn btn-outline-success rounded-pill font-weight-bold me-1 mb-1">Download Excel</a> -->
								</div>
								</div>
							</div>
						
						</div>


					</div>
				</div>

				<div class="row">
					<div class="col-12  px-4">
						<div class="card card-custom gutter-b bg-white border-0" >
							<div class="card-body" >
								<div class="table-responsive" id="printableTable">
									<table id="orderTable" class="display" style="width:100%">
										<thead>
											<tr>
												<th>Kode Sales</th>
												<th>Nama Sales</th>
												<th>Status</th>
												<th>Email</th>
												<th>No. HP</th>
												<th>Alamat</th>
												<th>Provinsi</th>
												<th>Kota</th>
												<th>Kelurahan</th>
												<th>Kecamatan</th>
												<th class=" no-sort text-end">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php if(count($sales) > 0): ?>
												<?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<tr>
													<td><?php echo e($v['KodeSales']); ?></td>
													<td><?php echo e($v['NamaSales']); ?></td>
													<td> <div class="<?php echo e($v['StatusRecord'] == 'ACTIVE' ? 'mr-0 text-success' : 'mr-0 text-danger'); ?> "><?php echo e($v['StatusRecord']); ?></div> </td>
													<td><?php echo e($v['Email']); ?></td>
													<td><?php echo e($v['NoTlp1']); ?></td>
													<td><?php echo e($v['Alamat']); ?></td>
													<td><?php echo e($v['prov_name']); ?></td>
													<td><?php echo e($v['city_name']); ?></td>
													<td><?php echo e($v['subdis_name']); ?></td>
													<td><?php echo e($v['dis_name']); ?></td>
													<td>
														<div class="card-toolbar text-end">
															<button class="btn p-0 shadow-none" type="button" id="dropdowneditButton1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																<span class="svg-icon">
																	<svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-three-dots text-body" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
																		<path fill-rule="evenodd" d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"></path>
																	</svg>
																</span>
															</button>
															<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdowneditButton1"  style="position: absolute; transform: translate3d(1001px, 111px, 0px); top: 0px; left: 0px; will-change: transform;">
																<a class="dropdown-item" href="<?php echo e(url('sales/form/' . $v['KodeSales'])); ?>">Edit</a>
															</div>
														</div>
													</td>
												</tr>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
	
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#orderTable').DataTable({
			"pagingType": "simple_numbers",
	  
		"columnDefs": [ {
		  "targets"  : 'no-sort',
		  "orderable": false,
		}]
		});
	} );
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('parts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views/master/BussinessPartner/Sales.blade.php ENDPATH**/ ?>