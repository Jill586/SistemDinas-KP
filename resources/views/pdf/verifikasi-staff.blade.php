<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #444;
        }
        th {
            font-weight: bold;
            background: #f0f0f0;
            text-align: center;
        }
        td {
            padding: 6px;
        }
    </style>
</head>
<body>

    <h2 style="text-align:center">Laporan Verifikasi Staff</h2>

    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>Nomor SPT</th>
                <th>Tanggal SPT</th>
                <th>Tujuan</th>
                <th>Nama Pegawai</th>
                <th>Status</th>
                <th>Operator</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->nomor_spt }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tanggal_spt)->format('d-m-Y') }}</td>
                    <td>{{ $row->tujuan_spt }}</td>
                    <td>{{ $row->pegawai->pluck('nama')->implode(', ') }}</td>
                    <td>{{ ucfirst($row->status) }}</td>
                    <td>{{ $row->operator->name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
