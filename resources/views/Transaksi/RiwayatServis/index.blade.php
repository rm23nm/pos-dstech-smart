@extends('parts.header')

@section('title', 'Riwayat Servis Kendaraan')

@section('content')
<div class="container-fluid py-4">

  <!-- Header -->
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h4 class="fw-bold mb-1" style="color:#1a237e"><i class="bi bi-car-front-fill me-2"></i>Riwayat Servis Kendaraan</h4>
      <p class="text-muted mb-0 small">Cari histori perbaikan & sparepart berdasarkan Plat Nomor kendaraan</p>
    </div>
    <a href="{{ route('fpenjualan-bengkelpos') }}" class="btn btn-outline-primary btn-sm">
      <i class="bi bi-plus-circle me-1"></i> Transaksi Baru
    </a>
  </div>

  <!-- Search Box -->
  <div class="card shadow-sm border-0 mb-4" style="border-radius:16px;">
    <div class="card-body p-4">
      <div class="input-group input-group-lg" style="max-width:600px;">
        <span class="input-group-text bg-white border-end-0" style="border-radius:12px 0 0 12px;">
          <i class="bi bi-search text-muted"></i>
        </span>
        <input
          type="text"
          id="inputPlatNomor"
          class="form-control border-start-0 ps-0 fw-semibold"
          placeholder="Masukkan Plat Nomor... Cth: B 1234 CD"
          style="border-radius:0; font-size:1.1rem; letter-spacing:2px; text-transform:uppercase;"
        >
        <button class="btn btn-primary px-4 fw-bold" id="btnCariRiwayat" style="border-radius:0 12px 12px 0;">
          <i class="bi bi-search me-1"></i> CARI
        </button>
      </div>
      <small class="text-muted mt-2 d-block">Pencarian tidak membedakan huruf besar/kecil dan spasi. <strong>B1234CD</strong> sama dengan <strong>B 1234 CD</strong>.</small>
    </div>
  </div>

  <!-- Results -->
  <div id="resultContainer">
    <!-- Placeholder state -->
    <div id="stateEmpty" class="text-center py-5 text-muted">
      <i class="bi bi-car-front display-1 text-secondary opacity-25"></i>
      <p class="mt-3 fs-5">Masukkan plat nomor dan tekan CARI untuk melihat riwayat servis</p>
    </div>
    <div id="stateLoading" class="text-center py-5 d-none">
      <div class="spinner-border text-primary" role="status" style="width:3rem;height:3rem;"></div>
      <p class="mt-3 text-muted">Mencari riwayat servis...</p>
    </div>
    <div id="stateNoData" class="text-center py-5 d-none">
      <i class="bi bi-inbox display-1 text-secondary opacity-25"></i>
      <p class="mt-3 fs-5 fw-semibold">Tidak ada riwayat servis ditemukan</p>
      <p class="text-muted">Kendaraan dengan plat nomor ini belum pernah melakukan servis.</p>
    </div>
    <div id="stateResult" class="d-none">
      <!-- Summary Card -->
      <div class="row g-3 mb-4" id="summaryCards"></div>

      <!-- Timeline -->
      <h6 class="fw-bold text-uppercase text-muted small mb-3">
        <i class="bi bi-clock-history me-1"></i> Riwayat Kunjungan Servis
      </h6>
      <div id="timelineContainer"></div>
    </div>
  </div>

</div>

<style>
  .timeline-card {
    background: white;
    border-radius: 14px;
    border-left: 5px solid #1a237e;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    margin-bottom: 20px;
    transition: all 0.3s;
    overflow: hidden;
  }
  .timeline-card:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    transform: translateY(-2px);
  }
  .timeline-header {
    background: linear-gradient(135deg, #1a237e 0%, #283593 100%);
    color: white;
    padding: 14px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .timeline-body {
    padding: 16px 20px;
  }
  .part-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 12px;
    border-radius: 8px;
    margin-bottom: 6px;
    background: #f8f9fa;
    font-size: 0.92rem;
  }
  .part-row .part-name {
    font-weight: 600;
    color: #333;
  }
  .part-row .part-price {
    color: #1a237e;
    font-weight: 700;
  }
  .part-row .part-qty {
    color: #888;
    font-size: 0.82rem;
  }
  .badge-mekanik {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 0.82rem;
  }
</style>

@endsection

@push('scripts')
<script>
  function formatRupiah(angka) {
    return 'Rp ' + parseFloat(angka).toLocaleString('id-ID', {minimumFractionDigits: 0});
  }

  function formatDate(tgl) {
    if (!tgl) return '-';
    const d = new Date(tgl);
    return d.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
  }

  function setState(name) {
    ['stateEmpty','stateLoading','stateNoData','stateResult'].forEach(id => {
      document.getElementById(id).classList.add('d-none');
    });
    document.getElementById('state' + name).classList.remove('d-none');
  }

  function cariRiwayat() {
    const plat = document.getElementById('inputPlatNomor').value.trim();
    if (!plat) {
      Swal.fire('Perhatian', 'Masukkan plat nomor terlebih dahulu.', 'warning');
      return;
    }

    setState('Loading');

    $.ajax({
      url: '{{ route("riwayat-servis-getData") }}',
      type: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        PlatNomor: plat.toUpperCase()
      },
      success: function(res) {
        if (!res.success || res.data.length === 0) {
          setState('NoData');
          return;
        }

        // Summary
        const totalKunjungan = res.data.length;
        const totalBiaya = res.data.reduce((sum, item) => sum + parseFloat(item.TotalPenjualan || 0), 0);
        const kunjunganTerakhir = formatDate(res.data[0].TglTransaksi);
        const platFormatted = res.data[0].PlatNomor;

        document.getElementById('summaryCards').innerHTML = `
          <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius:14px;background:linear-gradient(135deg,#1a237e,#283593);">
              <div class="card-body text-white p-3">
                <div class="small opacity-75 mb-1">Plat Nomor</div>
                <div class="fs-4 fw-black letter-spacing-2">${platFormatted}</div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius:14px;">
              <div class="card-body p-3">
                <div class="small text-muted mb-1"><i class="bi bi-calendar-check me-1"></i>Total Kunjungan</div>
                <div class="fs-3 fw-bold text-primary">${totalKunjungan}x</div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius:14px;">
              <div class="card-body p-3">
                <div class="small text-muted mb-1"><i class="bi bi-cash-stack me-1"></i>Total Biaya</div>
                <div class="fs-5 fw-bold text-success">${formatRupiah(totalBiaya)}</div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius:14px;">
              <div class="card-body p-3">
                <div class="small text-muted mb-1"><i class="bi bi-clock me-1"></i>Kunjungan Terakhir</div>
                <div class="fw-semibold" style="font-size:0.9rem;">${kunjunganTerakhir}</div>
              </div>
            </div>
          </div>
        `;

        // Timeline
        let timelineHtml = '';
        res.data.forEach((trx, idx) => {
          let partsHtml = '';
          let partCount = 0;
          if (trx.details && trx.details.length > 0) {
            trx.details.forEach(d => {
              partCount++;
              partsHtml += `
                <div class="part-row">
                  <div>
                    <div class="part-name">${d.NamaItem ?? '-'}</div>
                    <div class="part-qty">Qty: ${d.Qty}</div>
                  </div>
                  <div class="part-price">${formatRupiah(d.TotalTransaksi)}</div>
                </div>
              `;
            });
          } else {
            partsHtml = '<p class="text-muted small mb-0">Tidak ada detail item.</p>';
          }

          timelineHtml += `
            <div class="timeline-card">
              <div class="timeline-header">
                <div>
                  <div class="fw-bold fs-6">${formatDate(trx.TglTransaksi)}</div>
                  <div class="small opacity-75">No. Struk: ${trx.NoTransaksi}</div>
                </div>
                <div class="d-flex flex-column align-items-end gap-1">
                  ${trx.NamaMekanik ? `<span class="badge-mekanik"><i class="bi bi-person-gear me-1"></i>${trx.NamaMekanik}</span>` : ''}
                  <span class="badge bg-warning text-dark fw-bold">${formatRupiah(trx.TotalPenjualan)}</span>
                </div>
              </div>
              <div class="timeline-body">
                <h6 class="text-muted small fw-bold text-uppercase mb-2"><i class="bi bi-tools me-1"></i>Item Servis & Sparepart (${partCount})</h6>
                ${partsHtml}
              </div>
            </div>
          `;
        });

        document.getElementById('timelineContainer').innerHTML = timelineHtml;
        setState('Result');
      },
      error: function() {
        Swal.fire('Error', 'Gagal mengambil data. Silakan coba lagi.', 'error');
        setState('Empty');
      }
    });
  }

  document.getElementById('btnCariRiwayat').addEventListener('click', cariRiwayat);
  document.getElementById('inputPlatNomor').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') cariRiwayat();
  });
  document.getElementById('inputPlatNomor').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
  });
</script>
@endpush
