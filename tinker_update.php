<?php

use App\Models\Company;

$company = Company::where('KodePartner', 'DEMO-BENGKEL-001')->first();
if($company) {
    $company->HeadlineBanner = '<h2>Solusi Terbaik untuk Kendaraan Anda</h2>';
    $company->SubHeadlineBanner = '<p>Layanan bengkel profesional, mekanik berpengalaman, dan peralatan modern. Kami menjamin kendaraan Anda kembali dalam kondisi prima.</p>';
    $company->AboutUs = '<p>Demo Bengkel Smart adalah bengkel modern yang mengedepankan kualitas dan kepuasan pelanggan. Dengan pengalaman melayani ribuan kendaraan, kami menyediakan layanan servis berkala, perbaikan mesin, ganti oli, hingga perawatan AC dan kelistrikan mobil Anda.</p>';
    $company->TermAndConditionBookingOnline = '<ul><li>Booking wajib dilakukan minimal H-1 sebelum jadwal kedatangan.</li><li>Harap datang tepat waktu sesuai jadwal booking Anda.</li><li>Pembatalan booking harap diinformasikan minimal 2 jam sebelumnya.</li><li>Estimasi waktu pengerjaan dapat berubah menyesuaikan kondisi aktual kendaraan.</li></ul>';
    $company->save();
    echo "Updated DEMO-BENGKEL-001\n";
} else {
    echo "Company not found\n";
}
