@extends('layouts.app')

@section('title', 'Dokumen SPT/SPPD')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
        <h5 class="mb-0 fw-bold">Dokumen SPT/SPPD</h5>

        <form method="GET" action="{{ route('dokumen-perjalanan-dinas.index') }}" class="d-flex flex-wrap gap-2 mt-2 mt-md-0">

            <select name="bulan" class="form-select" style="width: 150px;">
                <option value="">-- Bulan --</option>
                @foreach(range(1,12) as $b)
                    <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            <select name="tahun" class="form-select" style="width: 130px;">
                <option value="">-- Tahun --</option>
                @foreach([2024,2025,2026] as $t)
                    <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>
                        {{ $t }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="form-select" style="width: 160px;">
                <option value="">-- Status --</option>
                <option value="draft" {{ request('status')=='draft'?'selected':'' }}>Draft</option>
                <option value="diproses" {{ request('status')=='diproses'?'selected':'' }}>Diproses</option>
                <option value="revisi_operator" {{ request('status')=='revisi_operator'?'selected':'' }}>Revisi Operator</option>
                <option value="verifikasi" {{ request('status')=='verifikasi'?'selected':'' }}>Verifikasi</option>
                <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
                <option value="disetujui" {{ request('status')=='disetujui'?'selected':'' }}>Disetujui</option>
            </select>

            <button type="submit" class="btn btn-primary">
                <i class="bx bx-filter"></i> Filter
            </button>

            <a href="{{ route('dokumen-perjalanan-dinas.index') }}" class="btn btn-secondary">
                <i class="bx bx-reset"></i> Reset
            </a>

            <a href="{{ route('dokumen-spt-sppd.export.excel') }}" class="btn btn-success">
                Export Excel
            </a>

            <a href="{{ route('dokumen-spt-sppd.export.pdf') }}" class="btn btn-danger">
                Export PDF
            </a>

        </form>
    </div>

    <div class="card-body">

        {{-- Show perPage --}}
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <div class="d-flex align-items-center mb-2 mb-sm-0">
                <label class="me-2 text-secondary">Show</label>
                <select id="showEntries" class="form-select w-auto me-2">
                    <option value="10" {{ $perPage==10?'selected':'' }}>10</option>
                    <option value="25" {{ $perPage==25?'selected':'' }}>25</option>
                    <option value="50" {{ $perPage==50?'selected':'' }}>50</option>
                    <option value="100" {{ $perPage==100?'selected':'' }}>100</option>
                </select>
                <span class="text-secondary">entries</span>
            </div>

            {{-- Search --}}
            <form action="{{ route('dokumen-perjalanan-dinas.index') }}" method="GET" class="d-flex align-items-center">
                <input type="hidden" name="perPage" value="{{ $perPage }}">
                <label for="search" class="me-2 text-secondary">Search:</label>
                <input type="text" id="search" name="search"
                    value="{{ request('search') }}"
                    class="form-control me-2"
                    style="width:260px;">
                <button class="btn btn-primary"><i class="bx bx-search"></i></button>
            </form>
        </div>

        {{-- TABEL --}}
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>No. SPT</th>
                        <th>Tgl. SPT</th>
                        <th>Tujuan</th>
                        <th>Personil</th>
                        <th>Pelaksanaan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($dokumens as $row)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $row->nomor_spt ?? '-' }}</td>
                        <td>{{ $row->tanggal_spt ? \Carbon\Carbon::parse($row->tanggal_spt)->format('d M Y') : '-' }}</td>
                        <td>{{ $row->tujuan_spt ?? '-' }}</td>

                        {{-- Personil --}}
                        <td>
                            @forelse($row->pegawai ?? [] as $pg)
                                {{ $pg->nama }}@if(!$loop->last), @endif
                            @empty
                                -
                            @endforelse
                        </td>

                        <td>
                            @if($row->tanggal_mulai && $row->tanggal_selesai)
                                {{ \Carbon\Carbon::parse($row->tanggal_mulai)->format('d M Y') }}
                                s/d
                                {{ \Carbon\Carbon::parse($row->tanggal_selesai)->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="text-center">
                            @switch($row->status)
                                @case('disetujui')
                                    <span class="badge bg-label-success">SELESAI</span>
                                    @break
                                @case('ditolak')
                                    <span class="badge bg-label-danger">DITOLAK</span>
                                    @break
                                @case('verifikasi')
                                    <span class="badge bg-label-warning">VERIFIKASI</span>
                                    @break
                                @case('revisi_operator')
                                    <span class="badge bg-label-warning">REVISI OPERATOR</span>
                                    @break
                                @default
                                    <span class="badge bg-label-primary">PROSES</span>
                            @endswitch
                        </td>

                        <td class="text-center">
                            <button class="btn btn-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDetail{{ $row->id }}">
                                <i class="bx bx-show"></i>
                            </button>
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada data</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $dokumens->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>

@foreach($dokumens as $row)
<div class="modal fade" id="modalDetail{{ $row->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Dokumen SPT/SPPD</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">

                <div class="row mb-3">
                    <div class="col-md-6 mb-2">
                        <a href="{{ route('dokumen.download', ['id'=>$row->id,'type'=>'spt_pdf']) }}"
                           class="btn btn-danger w-100">
                           <i class="bx bxs-file-pdf me-1"></i> SPT PDF
                        </a>
                    </div>

                    <div class="col-md-6">
                        <a href="{{ route('dokumen.download', ['id'=>$row->id,'type'=>'spt_word']) }}"
                           class="btn btn-primary w-100">
                           <i class="bx bxs-file-doc me-1"></i> SPT DOCX
                        </a>
                    </div>
                </div>


                {{-- SPPD Download --}}
                <div class="row mb-3">
                    <div class="col-md-6 mb-2">
                        <a href="{{ route('dokumen.download', ['id'=>$row->id,'type'=>'sppd_pdf']) }}"
                           class="btn btn-danger w-100">
                           <i class="bx bxs-file-pdf me-1"></i> SPPD PDF
                        </a>
                    </div>

                    <div class="col-md-6">

                        @php $listPegawai = $row->pegawai ?? collect(); @endphp

                        @if($listPegawai->count() > 1)

                            <div class="dropdown w-100">
                                <button class="btn btn-primary dropdown-toggle w-100"
                                        data-bs-toggle="dropdown">
                                    <i class="bx bxs-file-doc me-1"></i> SPPD DOCX
                                </button>

                                <ul class="dropdown-menu w-100">
                                    @foreach($listPegawai as $pg)
                                        <li>
                                            <a href="{{ route('dokumen.download', [
                                                    'id'=>$row->id,
                                                    'type'=>'sppd_word',
                                                    'pegawai_id'=>$pg->id
                                                ]) }}"
                                                class="dropdown-item">
                                                Untuk: {{ $pg->nama }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                        @elseif($listPegawai->count() == 1)
                            <a class="btn btn-primary w-100"
                               href="{{ route('dokumen.download', [
                                   'id'=>$row->id,
                                   'type'=>'sppd_word',
                                   'pegawai_id'=>$listPegawai->first()->id
                               ]) }}">
                               <i class="bx bxs-file-doc me-1"></i> SPPD DOCX
                            </a>
                        @else
                            <button class="btn btn-secondary w-100" disabled>
                                Tidak ada personil
                            </button>
                        @endif

                    </div>
                </div>

                {{-- SPT TTD --}}
                @if($row->spt_ttd)
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ route('perjalanan-dinas.downloadTtd',$row->id) }}"
                               class="btn btn-success w-100">
                               <i class="bx bxs-file-pdf me-1"></i> SPT TTD
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#showEntries').on('change', function () {
        let perPage = $(this).val(); // ambil jumlah per page dari dropdown

        $.ajax({
            url: "{{ route('dokumen-perjalanan-dinas.index') }}",
            type: "GET",
            data: { per_page: perPage },
            beforeSend: function () {
                $('tbody').html('<tr><td colspan="10" class="text-center text-muted">Memuat data...</td></tr>');
            },
            success: function (response) {
                // Ambil isi tabel & pagination dari response HTML
                let newTableBody = $(response).find('tbody').html();
                let newPagination = $(response).find('.pagination').html();

                $('tbody').html(newTableBody);
                $('.pagination').html(newPagination);
            },
            error: function () {
                $('tbody').html('<tr><td colspan="10" class="text-center text-danger">Gagal memuat data</td></tr>');
            }
        });
    });
});
</script>
@endpush
