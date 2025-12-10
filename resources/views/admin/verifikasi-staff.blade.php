@extends('layouts.app')

@section('title', 'Verifikasi Staff')

@section('content')
<div class="card mb-3 shadow rounded-2">
    <div class="card-body">
        <form method="GET" action="{{ route('verifikasi-staff.index') }}" class="row g-2 align-items-end">

            <div class="col-md-2">
                <label class="form-label mb-1">Pilih Bulan</label>
                    <select name="bulan" class="form-select">
                        <option value="">-- Bulan --</option>
                        @foreach(range(1,12) as $b)
                            <option value="{{ $b }}" {{ (isset($bulan) && $bulan == $b) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
            </div>

            <div class="col-md-2">
                <label class="form-label mb-1">Pilih Tahun</label>
                    <select name="tahun" class="form-select">
                        <option value="">-- Tahun --</option>
                        @foreach([2024,2025,2026] as $t)
                            <option value="{{ $t }}" {{ (isset($tahun) && $tahun == $t) ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
            </div>

            <div class="col-md-2">
                <label class="form-label mb-1">Pilih Status</label>
                    <select name="status" class="form-select">
                        <option value="">-- Status --</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="revisi_operator" {{ request('status') == 'revisi_operator' ? 'selected' : '' }}>Revisi Operator</option>
                        <option value="verifikasi" {{ request('status') == 'verifikasi' ? 'selected' : '' }}>Verifikasi</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    </select>
            </div>

            <div class="col-md-6 d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-filter"></i> Filter
                </button>

                <a href="{{ route('verifikasi-staff.index') }}"
                class="btn btn-secondary">
                    <i class="bx bx-reset"></i> Reset
                </a>

                <a href="{{ url('/export-verifikasi-staff') }}"
                class="btn btn-success">
                    Export Excel
                </a>

                <a href="{{ url('/export-verifikasi-staff/pdf') }}"
                class="btn btn-danger">
                    Export PDF
                </a>
            </div>

        </form>
    </div>
</div>

<div class="card shadow rounded-2">
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-bold">Daftar Verifikasi Staff</h5>
    </div>

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            {{-- 🔽 Show Entries --}}
            <div class="d-flex align-items-center mb-2 mb-sm-0">
                <select id="showEntries" class="form-select w-auto me-2">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>

             {{-- 🔍 Manual Search --}}
            <form action="{{ route('verifikasi-staff.index') }}" method="GET" class="d-flex align-items-center">
                {{-- tetap kirim parameter perPage biar tidak reset --}}
                <input type="hidden" name="perPage" value="{{ request('perPage', $perPage) }}">

                <label for="search" class="me-2 text-secondary">Search:</label>
                <input type="text"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control me-2"
                    style="width: 180px; font-size: 0.95rem; padding: 8px 12px;">

                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-search"></i>
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>No</th>
                        <th>No. SPT</th>
                        <th>Tgl. SPT</th>
                        <th>Operator Pengaju</th>
                        <th>Tujuan</th>
                        <th>Personil</th>
                        <th>Pelaksanaan</th>
                        <th>Estimasi Biaya</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perjalanans as $row)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                @if ($row->nomor_spt)
                                    {{ $row->nomor_spt }}
                                @else
                                    <span class="fst-italic text-muted">Belum ada</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_spt)->format('d M Y') }}</td>
                            <td>{{ $row->operator->name ?? '-' }}</td>
                            <td>{{ $row->tujuan_spt }}</td>
                            <td>
                                @foreach($row->pegawai as $i => $u)
                                    {{ $u->nama }}@if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($row->tanggal_mulai)->format('d M Y') }}
                                s/d
                                {{ \Carbon\Carbon::parse($row->tanggal_selesai)->format('d M Y') }}
                            </td>
                            <td class="text-end">
                                Rp {{ number_format($row->total_estimasi_biaya ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @if($row->status == 'disetujui')
                                    <span class="badge bg-label-success">SELESAI</span>
                                @elseif($row->status == 'ditolak')
                                    <span class="badge bg-label-danger">DITOLAK</span>
                                 @elseif($row->status == 'draft')
                                    <span class="badge bg-label-info">DRAFT</span>
                                @elseif($row->status == 'revisi_operator')
                                    <span class="badge bg-label-warning">REVISI OPERATOR</span>
                                @elseif($row->status == 'verifikasi')
                                    <span class="badge bg-label-warning">VERIFIKASI</span>
                                @else
                                    <span class="badge bg-label-primary">PROSES</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <button type="button" class="btn btn-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalVerifikasi{{ $row->id }}">
                                    <i class="bx bx-check-circle"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Belum ada data pengajuan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $perjalanans->onEachSide(2)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Semua modal -->
@foreach($perjalanans as $row)
<div class="modal fade" id="modalVerifikasi{{ $row->id }}" tabindex="-1" aria-labelledby="modalVerifikasiLabel{{ $row->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pengajuan Perjalanan Dinas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                {{-- Info SPT --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                       <strong>Nomor SPT:</strong>
                            <button class="btn btn-sm btn-outline-primary ms-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditSPT{{ $row->id }}">
                                <i class="bx bx-edit"></i>
                            </button>
                            <br>
                            <span id="nomorSPTDisplay{{ $row->id }}">{{ $row->nomor_spt ?? '-' }}</span>

                    </div>
                    <div class="col-md-6">
                        <strong>Tanggal SPT:</strong><br>
                        {{ \Carbon\Carbon::parse($row->tanggal_spt)->translatedFormat('d F Y') }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Jenis Perjalanan Dinas:</strong><br>
                        {{ ucfirst(str_replace('_',' ',$row->jenis_spt)) }}
                    </div>
                    <div class="col-md-6">
                        <strong>Jenis Kegiatan:</strong><br>
                        {{ $row->jenis_kegiatan }}
                    </div>
                </div>

                {{-- Jika dalam daerah (Siak) --}}
                @if($row->jenis_spt === 'dalam_daerah')
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tujuan Utama:</strong><br>
                            {{ $row->tujuan_spt }}
                        </div>
                        <div class="col-md-6">
                            <strong>Lokasi Tujuan (SBU):</strong><br>
                            Provinsi: Riau, Kota/Kab: Siak
                        </div>
                    </div>

                   <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Kecamatan Tujuan:</strong><br>
                            {{ $row->kecamatan_spt ?? '-' }}<br>
                               <strong>Jarak (km):</strong><br>
                            {{ $row->jarak_km ?? '-' }} km<br>
                             <strong>Desa Tujuan:</strong><br>
                            {{ $row->desa_spt ?? '-' }}
                        </div>
                    </div>
                @else
                    {{-- Default jika luar daerah --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tujuan Utama:</strong><br>
                            {{ $row->tujuan_spt }}
                        </div>
                        <div class="col-md-6">
                            <strong>Lokasi Tujuan (SBU):</strong><br>
                            Provinsi: {{ $row->provinsi_tujuan_id ?? '-' }},
                            Kota/Kab: {{ $row->kota_tujuan_id ?? '-' }}
                        </div>
                    </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Operator Pengaju:</strong><br>
                        {{ $row->operator->name ?? '-' }}
                    </div>
                </div>

                <div class="mb-3">
                    <strong>Dasar SPT:</strong>
                    <div class="form-control bg-light">{{ $row->dasar_spt }}</div>
                </div>

                <div class="mb-3">
                    <strong>Uraian Maksud:</strong>
                    <div class="form-control bg-light">{{ $row->uraian_spt }}</div>
                </div>

                {{-- Personil --}}
                <hr>
                <h6 class="fw-bold">Informasi Pelaksana</h6>
                @foreach($row->pegawai as $i => $u)
                    <div class="mb-2">
                        <strong>{{ $i+1 }}. {{ $u->nama }}</strong>
                        (NIP: {{ $u->nip ?? '-' }})<br>
                        Jabatan: {{ $u->jabatan->nama_jabatan ?? '-' }} -
                        Gol: {{ $u->golongan->nama_golongan ?? '-' }}
                    </div>
                @endforeach

                {{-- Pelaksanaan --}}
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Tanggal Mulai:</strong><br>
                        {{ \Carbon\Carbon::parse($row->tanggal_mulai)->translatedFormat('d F Y') }}
                    </div>
                    <div class="col-md-4">
                        <strong>Tanggal Selesai:</strong><br>
                        {{ \Carbon\Carbon::parse($row->tanggal_selesai)->translatedFormat('d F Y') }}
                    </div>
                    <div class="col-md-4">
                        <strong>Lama Hari:</strong><br>
                        @php
                            $lama = \Carbon\Carbon::parse($row->tanggal_mulai)
                                ->diffInDays(\Carbon\Carbon::parse($row->tanggal_selesai)) + 1;
                        @endphp
                        {{ $lama }} hari
                    </div>
                </div>

                {{-- Bukti Undangan --}}
                <hr>
                <h6 class="fw-bold">Bukti Undangan</h6>
                @if($row->bukti_undangan)
                    <div class="alert alert-light p-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-file-alt text-info me-2"></i>
                            <div class="flex-grow-1">
                                <strong>File Bukti Undangan:</strong>
                                <span class="text-muted">{{ basename($row->bukti_undangan) }}</span>
                            </div>
                            <div class="ms-2">
                                <a href="{{ asset('storage/' . $row->bukti_undangan) }}" target="_blank" class="btn btn-info me-1">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ asset('storage/' . $row->bukti_undangan) }}" download class="btn btn-primary">
                                    <i class="bx bx-download"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning p-3">
                        <i class="bx bx-alarm-exclamation text-warning me-2"></i>
                        <strong>Bukti undangan tidak tersedia.</strong>
                    </div>
                @endif

                {{-- Estimasi Biaya --}}
                <hr>
                <h6 class="fw-bold">Estimasi Biaya</h6>
                @if($row->biaya->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>Deskripsi Biaya</th>
                                    <th>Personil Terkait</th>
                                    <th>Harga Satuan</th>
                                    <th>Jml. Unit/Hari</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($row->biaya as $biaya)
                                    <tr>
                                        <td>{{ $biaya->deskripsi_biaya }}</td>
                                        <td>{{ $biaya->personil_name ?? ($biaya->pegawaiTerkait->nama ?? 'Semua') }}</td>
                                        <td class="text-end">Rp {{ number_format($biaya->harga_satuan, 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $biaya->jumlah_unit > 0 ? $biaya->jumlah_unit . ' ' . ($biaya->sbuItem->satuan ?? '') : '-' }}</td>
                                        <td class="text-end">Rp {{ number_format($biaya->subtotal_biaya, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan="4" class="text-end fw-bold">Total Estimasi Biaya</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($row->total_estimasi_biaya, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info text-center">Detail estimasi biaya belum tersedia.</div>
                @endif

                {{-- Upload SPT yang telah di TTD --}}
                @if($row->status == 'disetujui')
                <hr>
                <div class="mb-3">
                    <h6 class="fw-bold">Upload SPT yang telah di TTD</h6>
                    <form action="{{ route('perjalanan-dinas.uploadTtd', $row->id) }}"
                        method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="d-flex gap-2 align-items-center">
                            <input type="file"
                                name="spt_ttd"
                                class="form-control flex-grow-1"
                                accept=".pdf,.jpg,.jpeg,.png">

                            <button type="submit" class="btn btn-primary">
                                Upload
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                {{-- Form Verifikasi --}}
                @if($row->status !== 'disetujui' && $row->status !== 'verifikasi' && $row->status !== 'ditolak')
                    <hr>
                    <h6 class="fw-bold mb-3">Form Verifikasi</h6>
                    <form action="{{ route('verifikasi-staff.update', $row->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                Catatan Verifikator
                                <small class="text-muted">(Wajib diisi jika Revisi atau Tolak)</small>
                            </label>
                            <textarea class="form-control" name="catatan_verifikator" rows="3">{{ old('catatan_verifikator') }}</textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" name="aksi_verifikasi" value="revisi_operator" class="btn btn-warning text-white fw-bold">
                                <i class="bx bx-edit"></i> Revisi ke Operator
                            </button>
                            <button type="submit" name="aksi_verifikasi" value="tolak" class="btn btn-danger fw-bold">
                                <i class="bx bx-x-circle"></i> Tolak Pengajuan
                            </button>
                            <button type="submit" name="aksi_verifikasi" value="verifikasi" class="btn btn-success fw-bold">
                                <i class="bx bx-check-circle"></i> Setujui & Teruskan ke Atasan
                            </button>
                        </div>
                    </form>
                @else
                    <p><strong>Catatan : </strong>{{ $row->catatan_verifikator ?? '-' }}</p>
                    <p class="text-muted fst-italic">Surat ini sudah <strong>selesai/ditolak</strong>. Tidak dapat diverifikasi lagi.</p>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Modal Edit Nomor SPT -->
<div class="modal fade" id="modalEditSPT{{ $row->id }}" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="POST" action="{{ route('verifikasi-staff.update', $row->id) }}">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h6 class="modal-title">Edit Nomor SPT</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">Nomor SPT Baru</label>
                    <input type="text" name="nomor_spt" class="form-control"
                           value="{{ $row->nomor_spt }}">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Batal</button>

                    <button type="submit" name="aksi_verifikasi" value="update_nomor_spt"
                        class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </form>
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
            url: "{{ route('verifikasi-staff.index') }}",
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

