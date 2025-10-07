@extends('layouts.app')

@section('title', 'Dokumen SPT/SPPD')

@section('content')
<div class="card">
    {{-- Header --}}
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-bold">Daftar Dokumen SPT/SPPD</h5>
    </div>

    <div class="card-body">
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
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_spt)->format('d M Y') }}</td>
                            <td>{{ $row->tujuan_spt ?? '-' }}</td>
                            <td>
                                @foreach($row->pegawai as $p)
                                    {{ $p->nama }}@if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($row->tanggal_mulai)->format('d M Y') }}
                                s/d
                                {{ \Carbon\Carbon::parse($row->tanggal_selesai)->format('d M Y') }}
                            </td>
                            <td class="text-center">
                                @if($row->status == 'disetujui')
                                    <span class="badge bg-label-success">SELESAI</span>
                                @elseif($row->status == 'ditolak')
                                    <span class="badge bg-label-danger">DITOLAK</span>
                                @elseif($row->status == 'revisi_operator')
                                    <span class="badge bg-label-warning">REVISI OPERATOR</span>
                                @elseif($row->status == 'verifikasi')
                                    <span class="badge bg-label-warning">VERIFIKASI</span>
                                @else
                                    <span class="badge bg-label-primary">PROSES</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- Tombol Detail --}}
                                <button type="button" class="btn btn-info d-flex align-items-center mx-auto"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDetail{{ $row->id }}">
                                    <i class="bx bx-show me-1"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- Modal Detail --}}
                        <div class="modal fade" id="modalDetail{{ $row->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Dokumen SPT/SPPD</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center">

                                        {{-- Baris 1: Download SPT --}}
                                        <div class="row mb-3">
                                            <div class="col-md-6 mb-2 mb-md-0">
                                                <a href="{{ route('dokumen.download', ['id' => $row->id, 'type' => 'spt_pdf']) }}"
                                                class="btn btn-danger w-100">
                                                    <i class="bx bxs-file-pdf me-1"></i> SPT PDF
                                                </a>
                                            </div>
                                            <div class="col-md-6">
                                                <a href="{{ route('dokumen.download', ['id' => $row->id, 'type' => 'spt_word']) }}"
                                                class="btn btn-primary w-100">
                                                    <i class="bx bxs-file-doc me-1"></i> SPT DOCX
                                                </a>
                                            </div>
                                        </div>

                                        {{-- Baris 2: Download SPPD --}}
                                        <div class="row">
                                            {{-- Tombol Download SPPD PDF --}}
                                            <div class="col-md-6 mb-2 mb-md-0">
                                                <a href="{{ route('dokumen.download', ['id' => $row->id, 'type' => 'sppd_pdf']) }}"
                                                class="btn btn-danger w-100">
                                                    <i class="bx bxs-file-pdf me-1"></i> SPPD PDF
                                                </a>
                                            </div>

                                        {{-- Dropdown / Tombol Download SPPD Word --}}
                                        <div class="col-md-6">
                                            @if ($row->pegawai->count() > 1)
                                                {{-- Jika lebih dari satu personil, tampilkan dropdown --}}
                                                <div class="dropdown w-100">
                                                    <button class="btn btn-primary dropdown-toggle w-100" type="button"
                                                            id="dropdownSPPD{{ $row->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bx bxs-file-doc me-1"></i> SPPD DOCX
                                                    </button>
                                                    <ul class="dropdown-menu w-100" aria-labelledby="dropdownSPPD{{ $row->id }}">
                                                        @foreach ($row->pegawai as $pg)
                                                            <li>
                                                                <a class="dropdown-item"
                                                                href="{{ route('dokumen.download', [
                                                                            'id' => $row->id,
                                                                            'type' => 'sppd_word',
                                                                            'pegawai_id' => $pg->id
                                                                ]) }}">
                                                                    Untuk : {{ $pg->nama }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @elseif ($row->pegawai->count() == 1)
                                                {{-- Jika hanya satu personil, tampilkan tombol langsung --}}
                                                @php $pg = $row->pegawai->first(); @endphp
                                                <a class="btn btn-primary w-100"
                                                href="{{ route('dokumen.download', [
                                                            'id' => $row->id,
                                                            'type' => 'sppd_word',
                                                            'pegawai_id' => $pg->id
                                                ]) }}">
                                                    <i class="bx bxs-file-doc me-1"></i> SPPD DOCX
                                                </a>
                                            @else
                                                {{-- Jika tidak ada personil --}}
                                                <button class="btn btn-secondary w-100" disabled>
                                                    <i class="bx bxs-file-doc me-1"></i> Tidak ada personil
                                                </button>
                                            @endif
                                        </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data dokumen SPT/SPPD</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
