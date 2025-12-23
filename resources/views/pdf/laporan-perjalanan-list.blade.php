<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Perjalanan Dinas</title>

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
            border: 1px solid #000;
        }
        th {
            background: #f1f1f1;
            padding: 6px;
        }
        td {
            padding: 5px;
        }
    </style>
</head>
<body>

<h3 style="text-align:center;">LAPORAN PERJALANAN DINAS</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>No SPT</th>
            <th>Tgl SPT</th>
            <th>Tujuan</th>
            <th>Pelaksanaan</th>
            <th>Uraian</th>
            <th>Status SPT</th>
            <th>Status Laporan</th>
            <th>Status Bayar</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $i => $d)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $d->nomor_spt }}</td>
            <td>{{ $d->tanggal_spt }}</td>
            <td>{{ $d->tujuan_spt }}</td>
            <td>
                {{ $d->tanggal_mulai }} s/d {{ $d->tanggal_selesai }}
            </td>
            <td>{{ $d->uraian_spt }}</td>
            <td>{{ strtoupper($d->status) }}</td>
            <td>{{ strtoupper($d->status_laporan ?? '-') }}</td>
            <td>{{ strtoupper($d->status_bayar_laporan ?? '-') }}</td>
        </tr>
        @endforeach
    </tbody>

</table>

</body>
</html>
