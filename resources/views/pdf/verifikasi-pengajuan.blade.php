<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Pengajuan - PDF</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 12px;
        }
        th {
            background: #f0f0f0;
        }
    </style>
</head>
<body>
    <h3 style="text-align:center;">Laporan Verifikasi Pengajuan</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor SPT</th>
                <th>Pegawai</th>
                <th>Tujuan</th>
                <th>Tanggal SPT</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $d)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $d->nomor_spt }}</td>
                    <td>{{ $d->pegawai->pluck('nama')->join(', ') }}</td>
                    <td>{{ $d->tujuan_spt }}</td>
                    <td>{{ $d->tanggal_spt }}</td>
                    <td>{{ strtoupper($d->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
