import os

views_dir = 'resources/views/klinik'
dirs = ['patients', 'doctors', 'emr', 'jasa', 'poli']

template = """@extends('parts.header')
@section('content')
<div class="subheader py-2 py-lg-6 subheader-solid">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white mb-0 px-0 py-2">
                <li class="breadcrumb-item active" aria-current="page">Manajemen Klinik - {name}</li>
            </ol>
        </nav>
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">Data {name}</h3>
                </div>
            </div>
            <div class="card-body">
                <p>Fitur {name} sedang dalam pengembangan.</p>
            </div>
        </div>
    </div>
</div>
@endsection
"""

for d in dirs:
    os.makedirs(os.path.join(views_dir, d), exist_ok=True)
    with open(os.path.join(views_dir, d, 'index.blade.php'), 'w', encoding='utf-8') as f:
        f.write(template.replace('{name}', d.capitalize()))

print('Views created.')
