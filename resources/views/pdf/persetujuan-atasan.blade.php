<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Persetujuan Atasan</title>

    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
        h3 { text-align: center; margin-bottom: 0; }
    </style>
</head>
<body>

    <h3>LAPORAN PERSETUJUAN ATASAN</h3>
    <p style="text-align:center; margin-top:0;">Perjalanan Dinas</p>

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

                {{-- Pegawai adalah COLLECTION --}}
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
