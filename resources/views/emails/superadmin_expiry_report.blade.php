<!DOCTYPE html>
<html>
<head>
    <title>Laporan Harian Tagihan Otomatis Langganan POS</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <h2 style="color: #0056b3;">Laporan Tagihan Otomatis Hari Ini</h2>
    <p>Halo Superadmin,</p>
    <p>Berikut adalah daftar klien yang tagihannya baru saja digenerate secara otomatis hari ini, beserta klien yang masa tenggangnya sudah habis (suspended):</p>
    
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%; margin-top: 20px;">
        <thead style="background-color: #f8f9fa;">
            <tr>
                <th>Nama Partner</th>
                <th>Paket Langganan</th>
                <th>Sisa Masa Aktif</th>
                <th>Jatuh Tempo</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData as $data)
            <tr>
                <td>{{ $data['companyName'] }}</td>
                <td>{{ $data['subscriptionName'] }}</td>
                <td>{{ $data['sisaHari'] }} hari</td>
                <td>{{ $data['dueDate'] }}</td>
                <td>
                    @if($data['isSuspended'])
                        <span style="color: red; font-weight: bold;">SUSPENDED</span>
                    @else
                        <span style="color: green; font-weight: bold;">Tagihan Baru</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada tagihan otomatis hari ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <p style="margin-top: 30px;">
        Salam,<br>
        <strong>Sistem Otomatis DSMS POS</strong>
    </p>
</body>
</html>
