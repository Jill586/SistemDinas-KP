<!DOCTYPE html>
<html>
<head>
    <title>Export PDF Perjalanan Dinas</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #000; }
        th { font-weight: bold; background: #f0f0f0; padding: 6px; }
        td { padding: 6px; }
    </style>
</head>
<body>

<h3 style="text-align:center; margin-bottom:0">LAPORAN PERJALANAN DINAS</h3>
<p style="text-align:center; margin-top:2px">Export PDF</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal SPT</th>
            <th>Jenis SPT</th>
            <th>Tujuan</th>
            <th>Pegawai</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($perjalanans as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->tanggal_spt }}</td>
                <td>{{ ucfirst($p->jenis_spt) }}</td>
                <td>{{ $p->tujuan_spt }}</td>
                <td>
                    @foreach($p->pegawai as $pg)
                        - {{ $pg->nama }} <br>
                    @endforeach
                </td>
                <td>{{ ucfirst($p->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
