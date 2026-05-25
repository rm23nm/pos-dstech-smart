@extends('parts.header')
	
@section('content')
<div class="subheader py-2 py-lg-6 subheader-solid">
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-white mb-0 px-0 py-2">
				<li class="breadcrumb-item active" aria-current="page">Slide Login</li>
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
						<div class="card card-custom gutter-b bg-transparent shadow-none border-0" >
							<div class="card-header align-items-center  border-bottom-dark px-0">
								<div class="card-title mb-0">
									<h3 class="card-label mb-0 font-weight-bold text-body">
                                        Manajemen Slide Login Presentasi
									</h3>
								</div>
							    <div class="icons d-flex">
									<button class="btn btn-outline-primary rounded-pill font-weight-bold me-1 mb-1" data-bs-toggle="modal" data-bs-target="#addSlideModal">Tambah Slide</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-12 px-4">
						<div class="card card-custom gutter-b bg-white border-0" >
							<div class="card-body" >
                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>No. Urut</th>
                                                <th>Gambar</th>
                                                <th>Judul</th>
                                                <th>Akun Demo</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($slides as $slide)
                                            <tr>
                                                <td>{{ $slide->order_num }}</td>
                                                <td>
                                                    <img src="{{ asset($slide->image_path) }}" alt="Slide Image" style="height: 60px; border-radius: 8px; object-fit: cover;">
                                                </td>
                                                <td class="text-start">
                                                    <strong>{{ $slide->title }}</strong><br>
                                                    <small class="text-muted">{{ Str::limit($slide->description, 50) }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $slide->demo_email }}</span><br>
                                                    <small>Pass: {{ $slide->demo_password }}</small>
                                                </td>
                                                <td>
                                                    @if($slide->is_active)
                                                        <span class="badge bg-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-danger">Nonaktif</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning mb-1" data-bs-toggle="modal" data-bs-target="#editSlideModal{{ $slide->id }}"><i class="bi bi-pencil"></i></button>
                                                    <a href="{{ route('loginslide-destroy', $slide->id) }}" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Hapus slide ini?')"><i class="bi bi-trash"></i></a>
                                                </td>
                                            </tr>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="editSlideModal{{ $slide->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content text-start">
                                                        <form action="{{ route('loginslide-update', $slide->id) }}" method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Edit Slide</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label>Judul Slide</label>
                                                                    <input type="text" name="title" class="form-control" value="{{ $slide->title }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label>Deskripsi (Opsional)</label>
                                                                    <textarea name="description" class="form-control" rows="2">{{ $slide->description }}</textarea>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label>Email Demo (Opsional)</label>
                                                                        <input type="email" name="demo_email" class="form-control" value="{{ $slide->demo_email }}">
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label>Password Demo (Opsional)</label>
                                                                        <input type="text" name="demo_password" class="form-control" value="{{ $slide->demo_password }}">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label>No. Urut Tampil</label>
                                                                        <input type="number" name="order_num" class="form-control" value="{{ $slide->order_num }}">
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label>Ganti Gambar Baru (Biarkan kosong jika tidak ingin mengubah)</label>
                                                                        <input type="file" name="image" class="form-control" accept="image/*">
                                                                    </div>
                                                                </div>
                                                                <div class="form-check form-switch mt-2">
                                                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $slide->is_active ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Aktifkan Slide Ini</label>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Edit Modal -->

                                            @endforeach
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

<!-- Add Modal -->
<div class="modal fade" id="addSlideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('loginslide-store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Slide Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Judul Slide *</label>
                        <input type="text" name="title" class="form-control" required placeholder="Contoh: Aplikasi Point of Sales">
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi (Opsional)</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Penjelasan singkat fitur..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Email Demo (Opsional)</label>
                            <input type="email" name="demo_email" class="form-control" placeholder="Contoh: demo@pos.com">
                            <small class="text-muted">Isi jika slide ini terhubung dengan akun demo spesifik.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Password Demo (Opsional)</label>
                            <input type="text" name="demo_password" class="form-control" placeholder="12345678">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>No. Urut Tampil</label>
                            <input type="number" name="order_num" class="form-control" value="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Gambar Slide *</label>
                            <input type="file" name="image" class="form-control" required accept="image/*">
                        </div>
                    </div>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                        <label class="form-check-label">Aktifkan Slide Ini</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Slide</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
