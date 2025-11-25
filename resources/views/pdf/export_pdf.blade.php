<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Dokumen SPT/SPPD</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th {
            background: #f0f0f0;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        h3 {
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h3>Laporan Dokumen SPT / SPPD</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nomor SPT</th>
            <th>Tanggal SPT</th>
            <th>Tujuan</th>
            <th>Pegawai</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $i => $row)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $row->nomor_spt }}</td>
            <td>{{ \Carbon\Carbon::parse($row->tanggal_spt)->format('d/m/Y') }}</td>
            <td>{{ $row->tujuan_spt }}</td>
            <td>
                @foreach($row->pegawai as $p)
                    {{ $p->nama }} <br>
                @endforeach
            </td>
            <td>{{ ucfirst($row->status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
