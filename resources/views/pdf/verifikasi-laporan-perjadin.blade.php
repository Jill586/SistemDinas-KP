<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verifikasi Laporan Perjalanan Dinas</title>

    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        table, th, td { border: 1px solid #000; }
        th { background: #f1f1f1; padding: 6px; font-weight: bold; }
        td { padding: 5px; }
        h3 { text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>

<h3>VERIFIKASI LAPORAN PERJALANAN DINAS</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nomor SPT</th>
            <th>Tanggal SPT</th>
            <th>Nama Pegawai</th>
            <th>Tujuan</th>
            <th>Status Laporan</th>
            <th>Tanggal Dibuat</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data as $i => $d)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $d->nomor_spt }}</td>
            <td>{{ \Carbon\Carbon::parse($d->tanggal_spt)->translatedFormat('d M Y') }}</td>
            <td>{{ $d->pegawai->pluck('nama')->join(', ') }}</td>
            <td>{{ $d->tujuan_spt }}</td>
            <td style="text-transform: uppercase;">
                {{ str_replace('_', ' ', $d->status_laporan) }}
            </td>
            <td>
                {{ $d->created_at
                        ? \Carbon\Carbon::parse($d->created_at)->translatedFormat('d F Y')
                        : '-' }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
