@extends('layouts.app')

@section('title', 'Verifikasi Laporan Perjalanan Dinas')

@section('content')
<div class="card mb-3 shadow rounded-2">
    <div class="card-body">
        <form method="GET" action="{{ route('verifikasi-laporan.index') }}" class="row g-2 align-items-end">

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
                        @foreach([2024, 2025, 2026] as $t)
                            <option value="{{ $t }}" {{ (isset($tahun) && $tahun == $t) ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                        @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label mb-1">Pilih Status</label>
                <select name="status_laporan" class="form-select">
                    <option value="">-- Status --</option>
                    <option value="belum_dibuat"
                        {{ request('status_laporan') == 'belum_dibuat' ? 'selected' : '' }}>
                        Belum Dibuat
                    </option>

                    <option value="diproses"
                        {{ request('status_laporan') == 'diproses' ? 'selected' : '' }}>
                        Diproses
                    </option>

                    <option value="selesai"
                        {{ request('status_laporan') == 'selesai' ? 'selected' : '' }}>
                        Selesai
                    </option>
                    <option value="revisi_operator"
                        {{ request('status_laporan') == 'revisi_operator' ? 'selected' : '' }}>
                        Revisi Operator
                    </option>
                </select>
            </div>

            <div class="col-md-6 d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-filter"></i> Filter
                </button>

                <a href="{{ route('verifikasi-laporan.index') }}" class="btn btn-secondary">
                    <i class="bx bx-reset"></i> Reset
                </a>
            </div>

        </form>
    </div>
</div>

<div class="card shadow rounded-2">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Daftar Perjalanan Dinas</h5>

        <!-- 🔽 Dropdown Titik Tiga -->
        <div class="dropdown">
            <button class="btn btn-light btn-sm" type="button" id="dropdownMenuButton"
                data-bs-toggle="dropdown" aria-expanded="false"
                style="border-radius: 50%; width: 35px; height: 35px; padding: 0;">
                <i class="bx bx-dots-vertical-rounded fs-4"></i>
            </button>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                <li>
                    <a class="dropdown-item" href="{{ route('verifikasi-laporan.export.pdf') }}">
                        <i class="bx bxs-file-pdf me-1"></i> Export PDF
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('verifikasi-laporan.export.excel') }}">
                        <i class="bx bxs-file me-1"></i> Export Excel
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="card-body">

          <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">

             {{-- 🔍 Manual Search --}}
            <form action="{{ route('verifikasi-laporan.index') }}" method="GET" class="d-flex align-items-center">
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

        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tabel Verifikasi --}}
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Nomor SPT</th>
                        <th>Tanggal SPT</th>
                        <th>Tujuan</th>
                        <th>Personil</th>
                        <th>Uraian</th>
                        <th>Status Laporan</th>
                        <th>Status bayar</th>
                        <th>Total Dibayar</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporans as $index => $row)
                        @php
                            $totalSemuaPegawai = 0;

                            foreach ($row->pegawai as $pg) {
                                $totalDibayarPegawai = 0;

                                $uangHarian = $row->biaya
                                    ->where('pegawai_id_terkait', $pg->id)
                                    ->filter(fn($b) => $b->sbuItem && $b->sbuItem->kategori_biaya === 'UANG_HARIAN')
                                    ->first();

                                $totalDibayarPegawai += $uangHarian->subtotal_biaya ?? 0;

                                foreach ($row->biayaRiil->where('pegawai_id', $pg->id) as $riil) {
                                    $totalDibayarPegawai += $riil->subtotal_biaya;
                                }

                                $totalSemuaPegawai += $totalDibayarPegawai;
                            }
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $row->nomor_spt ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_spt)->format('d M Y') }}</td>
                            <td>{{ $row->tujuan_spt ?? '-' }}</td>
                            <td>
                                @foreach($row->pegawai as $i => $u)
                                    {{ $u->nama }}@if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td>{{ $row->uraian_spt ?? '-' }}</td>
                            <td class="text-center">
                                @if ($row->status_laporan === 'diproses')
                                    <span class="badge bg-label-primary">Diproses</span>
                                @elseif ($row->status_laporan === 'revisi operator')
                                    <span class="badge bg-label-warning">Revisi</span>
                                @elseif ($row->status_laporan === 'selesai')
                                    <span class="badge bg-label-success">Selesai</span>
                                @else
                                    <span class="badge bg-secondary">Belum Dibuat</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(optional($row->laporan)->status_bayar === 'belum_bayar')
                                    <span class="badge bg-label-danger">BELUM BAYAR</span>
                                @elseif(optional($row->laporan)->status_bayar === 'verifikasi')
                                    <span class="badge bg-label-warning">VERIFIKASI</span>
                                @elseif(optional($row->laporan)->status_bayar === 'sudah_bayar')
                                    <span class="badge bg-label-success">SUDAH BAYAR</span>
                                @else
                                    <span>-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                Rp {{ number_format($totalSemuaPegawai) }}
                            </td>
                            <td>{{ $row->created_at?->translatedFormat('d F Y') ?? '-' }}</td>
                            <td class="text-center">
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <button type="button" class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalVerifikasi{{ $row->id }}">
                                        <i class="bx bx-check-circle"></i>
                                    </button>

                                    <button 
                                        class="btn btn-sm btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDokumen{{ $row->id }}">
                                        <i class="bx bx-file"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-3">
                                Belum ada laporan perjalanan dinas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center flex-wrap mt-3">
            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $laporans->onEachSide(2)->links('pagination::bootstrap-5') }}
            </div>

            {{-- 🔽 Show Entries --}}
            <div class="d-flex align-items-center mb-2 mb-sm-0">
                <select id="showEntries" class="form-select w-auto me-2">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
        </div>
    </div>
</div>

@foreach ($laporans as $row)
<div class="modal fade" id="modalDokumen{{ $row->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form
            action="{{ route('verifikasi-laporan.update', $row->id) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Dokumen Perjalanan Dinas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    @php
                        $laporan = $row->laporan;
                    @endphp

                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>Jenis Dokumen</th>
                                <th width="140">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            {{-- SPT --}}
                            <tr>
                                <td>SPT</td>
                                <td>
                                    @if($laporan && $laporan->file_spt)
                                        <a href="javascript:void(0)"
                                            class="btn btn-info btn-sm me-1 btn-preview-file"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalPreviewFile"
                                            data-file="{{ asset('storage/' . $laporan->file_spt) }}">
                                                <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ asset('storage/' . $laporan->file_spt) }}"
                                        download
                                        class="btn btn-primary btn-sm">
                                            <i class="bx bx-download"></i>
                                        </a>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- SPPD --}}
                            <tr>
                                <td>SPPD</td>
                                <td>
                                    @if($laporan && $laporan->file_sppd)
                                        <a href="javascript:void(0)"
                                            class="btn btn-info btn-sm me-1 btn-preview-file"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalPreviewFile"
                                            data-file="{{ asset('storage/' . $laporan->file_sppd) }}">
                                                <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ asset('storage/' . $laporan->file_sppd) }}"
                                        download
                                        class="btn btn-primary btn-sm">
                                            <i class="bx bx-download"></i>
                                        </a>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Surat Undangan --}}
                            <tr>
                                <td>Surat Undangan / Dasar SPT</td>
                                <td>
                                    @if($laporan && $laporan->file_surat_undangan)
                                        <a href="javascript:void(0)"
                                            class="btn btn-info btn-sm me-1 btn-preview-file"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalPreviewFile"
                                            data-file="{{ asset('storage/' . $laporan->file_surat_undangan) }}">
                                                <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ asset('storage/' . $laporan->file_surat_undangan) }}"
                                        download
                                        class="btn btn-primary btn-sm">
                                            <i class="bx bx-download"></i>
                                        </a>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Laporan Perjadin --}}
                            <tr>
                                <td>Laporan Perjalanan Dinas</td>
                                <td>
                                    @if($laporan && $laporan->file_laporan_perjadin)
                                        <a href="javascript:void(0)"
                                            class="btn btn-info btn-sm me-1 btn-preview-file"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalPreviewFile"
                                            data-file="{{ asset('storage/' . $laporan->file_laporan_perjadin) }}">
                                                <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ asset('storage/' . $laporan->file_laporan_perjadin) }}"
                                        download
                                        class="btn btn-primary btn-sm">
                                            <i class="bx bx-download"></i>
                                        </a>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Bukti Pengeluaran --}}
                            <tr>
                                <td>Bukti Pengeluaran Perjadin</td>
                                <td>
                                    @if($laporan && $laporan->file_bukti_pengeluaran)
                                        <a href="javascript:void(0)"
                                            class="btn btn-info btn-sm me-1 btn-preview-file"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalPreviewFile"
                                            data-file="{{ asset('storage/' . $laporan->file_bukti_pengeluaran) }}">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ asset('storage/' . $laporan->file_bukti_pengeluaran) }}"
                                        download
                                        class="btn btn-primary btn-sm">
                                            <i class="bx bx-download"></i>
                                        </a>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- SPM --}}
                            <tr>
                                <td>Surat Pertanggungjawaban Mutlak</td>
                                <td>
                                    @if($laporan && $laporan->file_spm)
                                        <a href="javascript:void(0)"
                                            class="btn btn-info btn-sm me-1 btn-preview-file"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalPreviewFile"
                                            data-file="{{ asset('storage/' . $laporan->file_spm) }}">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ asset('storage/' . $laporan->file_spm) }}"
                                        download
                                        class="btn btn-primary btn-sm">
                                            <i class="bx bx-download"></i>
                                        </a>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Penginapan 30% --}}
                            <tr>
                                <td>Surat Pernyataan 30% Penginapan</td>
                                <td>
                                    @if($laporan && $laporan->file_surat_30_persen_penginapan)
                                        <a href="javascript:void(0)"
                                            class="btn btn-info btn-sm me-1 btn-preview-file"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalPreviewFile"
                                            data-file="{{ asset('storage/' . $laporan->file_surat_30_persen_penginapan) }}">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ asset('storage/' . $laporan->file_surat_30_persen_penginapan) }}"
                                        download
                                        class="btn btn-primary btn-sm">
                                            <i class="bx bx-download"></i>
                                        </a>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Kwitansi --}}
                            <tr>
                                <td>Kwitansi Pembayaran</td>
                                <td>
                                    @if($laporan && $laporan->file_kwitansi)
                                        <a href="javascript:void(0)"
                                            class="btn btn-info btn-sm me-1 btn-preview-file"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalPreviewFile"
                                            data-file="{{ asset('storage/' . $laporan->file_kwitansi) }}">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ asset('storage/' . $laporan->file_kwitansi) }}"
                                        download
                                        class="btn btn-primary btn-sm">
                                            <i class="bx bx-download"></i>
                                        </a>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>

                <div class="modal-footer">
                        <input type="hidden" name="aksi_verifikasi" value="selesai">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-success fw-bold">
                            <i class="bx bx-check-circle"></i> Serahkan untuk Verifikasi
                        </button>
                    </div>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- Modal Verifikasi -->
@foreach ($laporans as $row)
<div class="modal fade" id="modalVerifikasi{{ $row->id }}" tabindex="-1" aria-labelledby="modalVerifikasiLabel{{ $row->id }}" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Verifikasi Laporan Perjalanan Dinas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- HEADER (Styled Info Box) -->
        <div class="border rounded p-3 mb-4" style="background-color: #f8fcff; border-color: #6ac4ffff;">
        <div class="d-flex align-items-start">
            <div class="me-2">
            <i class="bi bi-info-circle-fill text-primary fs-4"></i>
            </div>
            <div>
            <p class="mb-1 fw-bold text-primary">Verifikasi Laporan Perjalanan Dinas</p>
            <ul class="mb-0 ps-3">
                <li>Pelapor: <strong>{{ $row->operator->name ?? '-' }}</strong></li>
                <li>Nomor SPT: <strong>{{ $row->nomor_spt }}</strong></li>
                <li>Pastikan data laporan sesuai dengan <span class="text-danger">dokumen pendukung perjalanan dinas</span></li>
                <li>Gunakan format dan penulisan <span class="text-danger">yang rapi dan konsisten</span></li>
            </ul>
            </div>
        </div>
        </div>

        <!-- DETAIL PERJALANAN -->
        <h6 class="fw-bold">Detail Perjalanan Dinas</h6>
        <div class="row">
          <div class="col-md-6">
            <p><strong>Nomor SPT:</strong><br>
            {{ $row->nomor_spt }}</p>
            <p><strong>Tujuan Utama:</strong><br>
            {{ $row->tujuan_spt }}</p>
            <p><strong>Tanggal Pelaksanaan:</strong><br>
              {{ \Carbon\Carbon::parse($row->tanggal_mulai)->format('d F Y') }}
              s/d
              {{ \Carbon\Carbon::parse($row->tanggal_selesai)->format('d F Y') }}
            </p>
            <p><strong>Personil yang Ditugaskan:</strong><br>
              @foreach($row->pegawai as $p)
                {{ $p->nama }}@if(!$loop->last), @endif
              @endforeach
            </p>
            <p><strong>Diajukan Oleh (Operator):</strong><br>
            {{ $row->operator->name ?? '-' }}</p>
          </div>
          <div class="col-md-6">
            <p><strong>Tanggal SPT:</strong><br>
            {{ \Carbon\Carbon::parse($row->tanggal_spt)->format('d F Y') }}</p>
            <p><strong>Lama Perjalanan:</strong><br>
            @php
                $lama = \Carbon\Carbon::parse($row->tanggal_mulai)
                ->diffInDays(\Carbon\Carbon::parse($row->tanggal_selesai)) + 1;
            @endphp
            {{ $lama }} hari</p>
          </div>
        </div>

        <hr>

        <!-- HASIL LAPORAN PEGAWAI -->
        <h6 class="fw-bold">Hasil Laporan Pegawai</h6>
        <div class="row">
          <div class="col-md-6">
            <p><strong>Pelapor:</strong><br>
            {{ $row->operator->name ?? '-' }}</p>
          </div>
          <div class="col-md-6">
            <p><strong>Tanggal Laporan:</strong><br>
            {{ optional($row->laporan)->tanggal_laporan
                    ? \Carbon\Carbon::parse($row->laporan->tanggal_laporan)->translatedFormat('d F Y')
                    : '-' }}
          </div>
        </div>

        <p class="mb-1 fw-bold">Hasil Kegiatan:</p>
        <div class="p-2 bg-light border rounded mb-3"
            style="white-space: pre-line; text-align: justify; line-height: 1.6;">
            {{ ltrim(optional($row->laporan)->ringkasan_hasil_kegiatan ?? '-') }}
        </div>

        <hr>

        {{-- BIAYA RIIL --}}
        <h6 class="fw-bold">Rincian Biaya Riil</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
            <thead class="table-light text-center">
                <tr>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Provinsi Tujuan</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
                <th>No Bukti</th>
                <th>File Bukti</th>
                <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($row->biayaRiil as $biaya)

                    @if($biaya->deskripsi_biaya === 'Hotel 30%')
                        @continue
                    @endif
                <tr>
                <td>{{ $biaya->pegawaiTerkait->nama ?? '-' }}</td>
                <td>{{ $biaya->deskripsi_biaya }}</td>
                <td>{{ $biaya->provinsi_tujuan ?? '-'}}</td>
                <td class="text-center">{{ $biaya->jumlah }}</td>
                <td class="text-center">{{ $biaya->satuan ?? '-'}}</td>
                <td class="text-center">{{ number_format($biaya->harga_satuan) }}</td>
                <td class="text-center">{{ number_format($biaya->subtotal_biaya) }}</td>
                <td class="text-center">{{ $biaya->nomor_bukti ?? '-' }}</td>
                <td class="text-center">
                    @if(!empty($biaya->path_bukti_file))
                    <div class="d-flex justify-content-center gap-1">
                        <a href="{{ asset('storage/' . $biaya->path_bukti_file) }}" target="_blank" class="btn btn-sm btn-info">
                            <i class="bx bx-show"></i>
                        </a>
                        <a href="{{ asset('storage/' . $biaya->path_bukti_file) }}" download class="btn btn-sm btn-primary">
                            <i class="bx bx-download"></i>
                        </a>
                    </div>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>{{ $biaya->keterangan_tambahan ?? '-' }}</td>
                </tr>

                @empty
                <tr>
                <td colspan="7" class="text-center text-muted">Tidak ada rincian biaya riil.</td>
                </tr>
                @endforelse
            </tbody>
            </table>
        </div>

        <p class="fw-bold text-end">
          TOTAL BIAYA RIIL: Rp {{ number_format($row->biayaRiil->sum('subtotal_biaya')) }}
        </p>
        <hr>

        {{-- PERHITUNGAN 30% PENGINAPAN --}}
        @php
        $hotel30 = $row->biayaRiil
            ->where('deskripsi_biaya', 'Hotel 30%')
            ->groupBy('pegawai_id_terkait');
        @endphp

        @if($row->hotel30_grouped->isNotEmpty())
            <h6 class="fw-bold">Perhitungan (HOTEL 30%)</h6>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>Nama Pegawai</th>
                        <th>Jumlah Malam</th>
                        <th>Harga Satuan</th>
                        <th>Total Penginapan (Rp)</th>
                        <th>30% Penginapan (Rp)</th>
                    </tr>
                </thead>

                <tbody>
                @foreach ($row->hotel30_grouped as $pegawaiId => $items)
                    @php
                        $pegawaiNama = $items->first()->pegawaiTerkait->nama ?? '-';
                        $jumlah = $items->first()->jumlah ?? 0;
                        $hargaPenginapan = $row->biaya
                            ->where('pegawai_id_terkait', $pegawaiId)
                            ->filter(function ($b) {
                                return $b->sbuItem && $b->sbuItem->kategori_biaya === 'PENGINAPAN';
                            })
                            ->first()->harga_satuan ?? 0;

                        $totalPenginapan = $jumlah * $hargaPenginapan;
                        $total30 = $items->sum('subtotal_biaya');
                    @endphp

                    <tr>
                        <td>{{ $pegawaiNama }}</td>
                        <td class="text-center">{{ $jumlah }} Malam</td>
                        <td class="text-center">Rp {{ number_format($hargaPenginapan, 0, ',', '.') }}</td>
                        <td class="text-center">Rp {{ number_format($totalPenginapan, 0, ',', '.') }}</td>
                        <td class="fw-bold text-success text-center">
                            Rp {{ number_format($total30, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

            <hr>
        @endif

        {{-- Estimasi Biaya --}}
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
                                        <td class="text-center">
                                            {{ $biaya->jumlah_unit > 0 ? $biaya->jumlah_unit . ' ' . ($biaya->sbuItem->satuan ?? '') : '-' }}
                                        </td>
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

        {{-- NOMINAL DIBAYARKAN --}}
        @php
            $totalSemuaPegawai = 0;
        @endphp

        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light text-center">
            </thead>
                <tbody>
                   @php
                    $mapping = [
                        'BBM' => [
                            'Transportasi Darat Siak - Dumai (PP)',
                            'Transportasi Darat Siak - Pekanbaru (PP)',
                            'Transportasi Darat Siak - Bengkalis (PP)',
                            'Transportasi Darat Siak - Rokan Hilir (PP)',
                        ],

                        'Transportasi Darat' => 'Taksi Riau',
                        'Taxi' => 'Taksi Riau',

                        'Hotel' => [
                            'Hotel Riau Kepala Daerah/Eselon I',
                            'Hotel Riau Anggota DPRD/Eselon II',
                            'Hotel Riau Eselon III/Golongan IV',
                            'Hotel Riau Eselon IV/Golongan III',
                            'Hotel Riau Golongan II/I & Non ASN',
                        ],

                        'Hotel 30%' => [
                            'Hotel Riau Kepala Daerah/Eselon I',
                            'Hotel Riau Anggota DPRD/Eselon II',
                            'Hotel Riau Eselon III/Golongan IV',
                            'Hotel Riau Eselon IV/Golongan III',
                            'Hotel Riau Golongan II/I & Non ASN',
                        ],
                    ];
                    @endphp

                    @foreach ($row->pegawai as $pg)
                        @php
                            $biayaRiilPegawai = $row->biayaRiil->where('pegawai_id', $pg->id);
                            $totalDibayarPegawai = 0;
                        @endphp

                        <h6 class="fw-bold mt-4">Nominal Dibayarkan — {{ $pg->nama }}</h6>

                        <table class="table table-bordered table-striped">
                        <thead class="table-light text-center">
                            <tr class="text-center">
                                <th>Deskripsi</th>
                                <th>Real Cost (Riil)</th>
                                <th>Nominal Harian</th>
                                <th>Unit/Hari</th>
                                <th>Batas Atas (SBU)</th>
                                <th>Nominal Dibayarkan</th>
                            </tr>
                        </thead>
                        <tbody>

                            @php
                                // Ambil UANG HARIAN berdasarkan pegawai_id
                                $uangHarian = $row->biaya
                                    ->where('pegawai_id_terkait', $pg->id)
                                    ->filter(function ($b) {
                                        return $b->sbuItem && $b->sbuItem->kategori_biaya === 'UANG_HARIAN';
                                    })
                                    ->first();

                                // Tambahkan ke total
                                $totalDibayarPegawai += $uangHarian->subtotal_biaya ?? 0;
                            @endphp

                            @if($uangHarian)
                                <tr>
                                    <td>Uang Harian</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">
                                        Rp {{ number_format($uangHarian->harga_satuan) }}
                                    </td>
                                    <td class="text-center">
                                        {{ $uangHarian->jumlah_unit }}
                                        {{ optional($uangHarian->sbuItem)->satuan }}
                                    </td>
                                    <td class="text-center">
                                        Rp {{ number_format($uangHarian->subtotal_biaya) }}
                                    </td>
                                    <td class="text-end">
                                        Rp {{ number_format($uangHarian->subtotal_biaya) }}
                                    </td>
                                </tr>
                            @endif

                                @foreach ($biayaRiilPegawai as $riil)
                                    @php
                                        // REAL COST
                                        $realCost = $riil->subtotal_biaya;

                                        // --- CARI DESKRIPSI ESTIMASI DARI PEMETAAN ---
                                        $key = $riil->deskripsi_biaya;
                                        $targetDeskripsi = $mapping[$key] ?? null;

                                        $estimasi = null;

                                        if ($targetDeskripsi) {

                                            // Jika mapping adalah array (Hotel)
                                            if (is_array($targetDeskripsi)) {
                                                $estimasi = $row->biaya
                                                    ->where('pegawai_id_terkait', $pg->id)
                                                    ->whereIn('deskripsi_biaya', $targetDeskripsi)
                                                    ->first();
                                            } else {
                                                // jika mapping single value
                                                $estimasi = $row->biaya
                                                    ->where('pegawai_id_terkait', $pg->id)
                                                    ->where('deskripsi_biaya', $targetDeskripsi)
                                                    ->first();
                                            }
                                        }

                                        // Nominal Harian (Harga Satuan dari Estimasi SBU)
                                        $nominalHarian = $estimasi->harga_satuan ?? 0;

                                        // Batas Atas dari estimasi
                                        $batasAtas = $estimasi->subtotal_biaya ?? 0;

                                        // Nominal Dibayarkan
                                        if ($batasAtas > 0) {
                                            $dibayar = min($realCost, $batasAtas);
                                        } else {
                                            $dibayar = $realCost; // fallback jika tidak ada batas SBU
                                        }

                                        $totalDibayarPegawai += $dibayar;

                                    @endphp

                                    <tr>
                                        <td>{{ $riil->deskripsi_biaya }}</td>
                                        <td class="text-center">Rp {{ number_format($realCost) }}</td>
                                        <td class="text-center">Rp {{ number_format($nominalHarian) }}</td>
                                        <td class="text-center">
                                            {{ optional($estimasi)->jumlah_unit ?? '-' }}
                                            {{ optional(optional($estimasi)->sbuItem)->satuan ?? '' }}
                                        </td>
                                        <td class="text-center">Rp {{ number_format($batasAtas) }}</td>
                                        <td class="text-end">
                                            Rp {{ number_format($dibayar) }}
                                        </td>
                                    </tr>
                                @endforeach
                                    <tr class="table-light">
                                        <td colspan="5" class="text-end">TOTAL DIBAYARKAN</td>
                                        <td class="text-end">Rp {{ number_format($totalDibayarPegawai) }}</td>
                                    </tr>
                                    @php
                                        $totalSemuaPegawai += $totalDibayarPegawai;
                                    @endphp
                                    @php
                                        $row->total_dibayar_semua = $totalSemuaPegawai;
                                    @endphp
                            </tbody>
                        </table>
                    @endforeach
                </tbody>
        </table>
        </div>

        <hr>

        <h6 class="fw-bold">Dokumentasi Perjalanan Dinas</h6>
        @if ($row->laporan && $row->laporan->foto_dokumentasi)
            @php
                $fotos = json_decode($row->laporan->foto_dokumentasi, true);
            @endphp

            <div class="d-flex gap-3 flex-wrap mt-2 mb-4">
                @foreach ($fotos as $foto)
                    <img src="{{ asset('storage/' . $foto) }}"
                        class="img-thumbnail"
                        width="250">
                @endforeach
            </div>
        @endif

        <!-- FORM VERIFIKASI -->
        @if($row->status_laporan !== 'selesai')
        <h6 class="fw-bold">Form Verifikasi Laporan</h6>
          <form action="{{ route('verifikasi-laporan.update', $row->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
              <label for="catatan_preview{{ $row->id }}" class="form-label fw-bold">
                Catatan Preview
                <small class="text-muted">(Wajib diisi jika Revisi)</small>
              </label>
              <textarea
                class="form-control @error('catatan_preview') is-invalid @enderror"
                id="catatan_preview{{ $row->id }}"
                name="catatan_preview"
                rows="3"
                placeholder="Tuliskan catatan preview di sini...">{{ old('catatan_preview') }}</textarea>
              @error('catatan_preview')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
              <button type="submit" name="aksi_verifikasi" value="revisi_operator" class="btn btn-danger fw-bold">
                <i class="bx bx-edit"></i> Kembalikan untuk Revisi
              </button>
              <button type="submit" name="aksi_verifikasi" value="selesai" class="btn btn-success fw-bold">
                <i class="bx bx-check-circle"></i> Setujui Laporan
              </button>
            </div>
          </form>
        @else
          <p class="text-muted fst-italic">Laporan ini sudah <strong>selesai</strong>. Tidak dapat diverifikasi lagi.</p>
        @endif

      </div>
    </div>
  </div>
</div>
@endforeach

<!-- MODAL PREVIEW FILE -->
<div class="modal fade" id="modalPreviewFile" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0" style="height:85vh">
                <iframe id="iframePreview"
                    style="width:100%; height:100%; border:none;">
                </iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#showEntries').on('change', function () {
        let perPage = $(this).val(); // ambil jumlah per page dari dropdown

        $.ajax({
            url: "{{ route('verifikasi-laporan.index') }}",
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalPreviewFile');
    const iframe = document.getElementById('iframePreview');

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const fileUrl = button.getAttribute('data-file');
        iframe.src = fileUrl;
    });

    modal.addEventListener('hidden.bs.modal', function () {
        iframe.src = '';
    });
    
});
</script>
@endpush
