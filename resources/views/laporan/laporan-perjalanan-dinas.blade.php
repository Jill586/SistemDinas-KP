@extends('layouts.app')

@section('title', 'Laporan Perjalanan Dinas')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
        <h5 class="mb-0 fw-bold">Daftar Laporan Perjalanan Dinas</h5>

        {{-- 🔍 Form Filter Bulan & Tahun --}}
        <form method="GET" action="{{ route('laporan.index') }}" class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
            <select name="bulan" class="form-select" style="width: 150px;">
                <option value="">-- Bulan --</option>
                @foreach(range(1,12) as $b)
                    <option value="{{ $b }}" {{ (isset($bulan) && $bulan == $b) ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            <select name="tahun" class="form-select" style="width: 130px;">
                <option value="">-- Tahun --</option>
                    @foreach([2024, 2025, 2026] as $t)
                        <option value="{{ $t }}" {{ (isset($tahun) && $tahun == $t) ? 'selected' : '' }}>
                            {{ $t }}
                        </option>
                    @endforeach
            </select>
            
             <select name="status_laporan" class="form-select" style="width: 160px;">
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

            <button type="submit" class="btn btn-primary">
                <i class="bx bx-filter"></i> Filter
            </button>

            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                <i class="bx bx-reset"></i> Reset
            </a>
             <a href="{{ route('laporan-perjalanan.export.excel') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            {{-- 🔽 Show Entries --}}
            <div class="d-flex align-items-center mb-2 mb-sm-0">
            <label for="showEntries" class="me-2 text-secondary">Show</label>
            <select id="showEntries" class="form-select w-auto me-2">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
            </select>
            <span class="text-secondary">entries</span>
            </div>

             {{-- 🔍 Manual Search --}}
            <form action="{{ route('laporan.index') }}" method="GET" class="d-flex align-items-center">
                {{-- tetap kirim parameter perPage biar tidak reset --}}
                <input type="hidden" name="perPage" value="{{ request('perPage', $perPage) }}">

                <label for="search" class="me-2 text-secondary">Search:</label>
                <input type="text"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control me-2"
                    style="width: 260px; font-size: 0.95rem; padding: 8px 12px;">

                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-search"></i>
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>NO</th>
                        <th>NO. SPT</th>
                        <th>TGL. SPT</th>
                        <th>TUJUAN</th>
                        <th>PELAKSANAAN</th>
                        <th>STATUS SPT</th>
                        <th>STATUS LAPORAN</th>
                        <th>DOKUMEN LAPORAN</th>
                        <th>AKSI LAPORAN</th>
                    </tr>
                </thead>
                <tbody>
@forelse($perjalanans as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->nomor_spt ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_spt)->format('d M Y') }}</td>
                            <td>{{ $row->tujuan_spt ?? '-' }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($row->tanggal_mulai)->format('d M Y') }}
                                s/d
                                {{ \Carbon\Carbon::parse($row->tanggal_selesai)->format('d M Y') }}
                            </td>
                            <td class="text-center">
                                @if($row->status == 'selesai')
                                    <span class="badge bg-label-success">SELESAI</span>
                                @elseif($row->status == 'proses')
                                    <span class="badge bg-label-primary">proses</span>
                                @else
                                    <span class="badge bg-label-success">{{ $row->status }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($row->status_laporan == 'belum_dibuat')
                                    <span class="badge bg-label-danger">BELUM DIBUAT</span>
                                @elseif($row->status_laporan == 'diproses')
                                    <span class="badge bg-label-primary">PROSES</span>
                                @elseif($row->status_laporan == 'selesai')
                                    <span class="badge bg-label-success">SELESAI</span>
                                @elseif($row->status_laporan == 'revisi_operator')
                                    <span class="badge bg-label-warning">REVISI OPERATOR</span>
                                @else
                                    <span class="badge bg-label-warning">{{ strtoupper($row->status_laporan) }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($row->status_laporan === 'selesai')
                                    <button type="button"
                                            class="btn btn-info"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDownload{{ $row->id }}">
                                        <i class="bx bx-show"></i>
                                    </button>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($row->status_laporan === 'belum_dibuat')
                                <button class="btn btn-primary btn-edit"
                                    data-id="{{ $row->id }}"
                                    data-nomor="{{ $row->nomor_spt }}"
                                    data-tujuan="{{ $row->tujuan_spt }}"
                                    data-tglmulai="{{ $row->tanggal_mulai }}"
                                    data-tglselesai="{{ $row->tanggal_selesai }}"
                                    data-tanggal_laporan="{{ $row->tanggal_laporan }}"
                                    data-hasil="{{ $row->hasil_kegiatan }}"
                                    data-kendala="{{ $row->kendala }}"
                                    data-saran="{{ $row->saran }}">
                                    <i class="bx bx-edit-alt"></i>
                                </button>
                                @endif

                                @if($row->status_laporan === 'selesai')
                                <button
                                    type="button"
                                    class="btn btn-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDetail{{ $row->id }}">
                                    <i class="bx bx-show"></i>
                                </button>
                                @endif

                                @if($row->status_laporan === 'diproses')
                                <button class="btn btn-warning btn-edit2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditLaporan"
                                    data-id="{{ $row->id }}"
                                    data-nomor="{{ $row->nomor_spt }}"
                                    data-tujuan="{{ $row->tujuan_spt }}"
                                    data-tglmulai="{{ $row->tanggal_mulai }}"
                                    data-tglselesai="{{ $row->tanggal_selesai }}"
                                    data-tanggal_laporan="{{ optional($row->laporan)->tanggal_laporan }}"
                                    data-hasil="{{ optional($row->laporan)->ringkasan_hasil_kegiatan }}"
                                    data-kendala="{{ optional($row->laporan)->kendala_dihadapi }}"
                                    data-saran="{{ optional($row->laporan)->saran_tindak_lanjut }}">
                                    <i class="bx bx-edit"></i>
                                </button>
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data perjalanan dinas</td>
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

{{-- Modal Download khusus untuk row ini --}}
@foreach($perjalanans as $row)
    @if($row->status_laporan === 'selesai')
    <div class="modal fade" id="modalDownload{{ $row->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title fw-bold">Dokumen Laporan</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
                <div class="modal-body text-center">
                    <div class="d-flex flex-column align-items-center gap-2">
                      <div class="d-flex flex-column gap-2 w-100 mb-3">
                        <a href="{{ route('laporan.download', [$row->id, 'doc']) }}" class="btn btn-primary w-100">
                            <i class="bx bxs-file-doc"></i> LAPORAN SPT
                        </a>
                        <!-- <a href="{{ route('laporan.download', [$row->id, 'pdf']) }}" class="btn btn-danger w-100">
                            <i class="bx bxs-file-pdf"></i> LAPORAN PDF
                        </a> -->
                      </div>
                      @php
                          $hasHotel30 = $row->biayaRiil->contains(function ($item) {
                              return stripos($item->deskripsi_biaya, 'Hotel 30%') !== false;
                          });
                      @endphp

                      @if($hasHotel30)
                          <div class="d-flex flex-column gap-2 w-100 mb-3">
                              <a href="{{ route('laporan.download', ['id' => $row->id, 'type' => 'pernyataan']) }}" class="btn btn-primary w-100">
                                  <i class="bx bxs-file-doc"></i> PERNYATAAN 30%
                              </a>
                          </div>
                      @endif
                        <a href="{{ route('laporan.download', ['id' => $row->id, 'type' => 'sp-mutlak']) }}" class="btn btn-primary w-100">
                            <i class="bx bxs-file-doc"></i> SP MUTLAK
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

<!-- MODAL EDIT LAPORAN -->
<div class="modal fade" id="modalEditLaporan" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Laporan Perjalanan Dinas</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" id="formEditLaporan">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nomor SPT</label>
                        <input type="text" class="form-control" id="edit_nomor_spt" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tujuan</label>
                        <input type="text" class="form-control" id="edit_tujuan" readonly>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="text" class="form-control" id="edit_mulai" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="text" class="form-control" id="edit_selesai" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Laporan</label>
                        <input type="date" class="form-control" id="edit_tanggal_laporan" name="tanggal_laporan">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hasil Kegiatan</label>
                        <textarea class="form-control" name="ringkasan_hasil_kegiatan" id="edit_hasil" rows="4"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kendala</label>
                        <textarea class="form-control" name="kendala_dihadapi" id="edit_kendala" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Saran / Tindak Lanjut</label>
                        <textarea class="form-control" name="saran_tindak_lanjut" id="edit_saran" rows="3"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                </div>

            </form>

        </div>
    </div>
</div>

@foreach ($perjalanans as $row)
<div class="modal fade" id="modalDetail{{ $row->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content print-area-{{ $row->id }}">

      {{-- HEADER --}}
      <div class="modal-header">
        <h5 class="modal-title">Detail Laporan Perjalanan Dinas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      {{-- BODY --}}
      <div class="modal-body">

        {{-- INFORMASI UTAMA --}}
        <h6 class="fw-bold mb-3">Informasi Perjalanan Dinas</h6>
        <table class="table table-bordered table-info-perjalanan">
          <tbody>
            <tr>
              <th style="width: 30%">Pelapor</th>
              <td>{{ $row->operator->name ?? '-' }}</td>
            </tr>
            <tr>
              <th>Nomor SPT</th>
              <td>{{ $row->nomor_spt ?? '-' }}</td>
            </tr>
            <tr>
              <th>Tujuan</th>
              <td>{{ $row->tujuan_spt }} ({{ $row->kota_tujuan_id }}, {{ $row->provinsi_tujuan_id }})</td>
            </tr>
            <tr>
              <th>Personil Ditugaskan</th>
              <td>
                @if($row->pegawai && $row->pegawai->isNotEmpty())
                  @foreach($row->pegawai as $p)
                    {{ $p->nama }}@if(!$loop->last), @endif
                  @endforeach
                @else
                  -
                @endif
              </td>
            </tr>
            <tr>
              <th>Tanggal Pelaksanaan</th>
              <td>
                {{ \Carbon\Carbon::parse($row->tanggal_mulai)->format('d M Y') }} -
                {{ \Carbon\Carbon::parse($row->tanggal_selesai)->format('d M Y') }}
              </td>
            </tr>
            <tr>
              <th>Status Laporan</th>
              <td>{{ strtoupper($row->status_laporan) }}</td>
            </tr>
          </tbody>
        </table>

        {{-- DETAIL LAPORAN --}}
        <h6 class="fw-bold mt-4">Detail Laporan</h6>
        <table class="table table-bordered mb-3">
          <tbody>
            <tr>
              <th style="width: 30%">Tanggal Laporan</th>
              <td>
                {{ optional($row->laporan)->tanggal_laporan
                      ? \Carbon\Carbon::parse($row->laporan->tanggal_laporan)->translatedFormat('d F Y')
                      : '-' }}
              </td>
            </tr>
            <tr>
              <th>Hasil Kegiatan</th>
              <td style="white-space: pre-line; text-align: justify;">
                  {{ preg_replace('/^\s+/', '', optional($row->laporan)->ringkasan_hasil_kegiatan ?? '-') }}
              </td>
              </td>
            </tr>
            <tr>
              <th>Kendala</th>
              <td>{{ optional($row->laporan)->kendala_dihadapi ?? '-' }}</td>
            </tr>
            <tr>
              <th>Saran / Tindak Lanjut</th>
              <td>{{ optional($row->laporan)->saran_tindak_lanjut ?? '-' }}</td>
            </tr>
          </tbody>
        </table>

        {{-- BIAYA RIIL --}}
        <h6 class="fw-bold">Rincian Biaya Riil</h6>
        <table class="table table-bordered table-striped">
          <thead class="table-light text-center">
            <tr>
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

        <p class="fw-bold text-end">
          TOTAL BIAYA RIIL: Rp {{ number_format($row->biayaRiil->sum('subtotal_biaya')) }}
        </p>
        <hr>

        {{-- PERHITUNGAN 30% PENGINAPAN --}}
        @php
            // cek apakah ada Hotel 30% dari biaya riil
            $adaHotel30 = $row->biayaRiil->where('deskripsi_biaya', 'Hotel 30%')->isNotEmpty();
        @endphp

        @if ($adaHotel30)            
        <h6 class="fw-bold">Perhitungan (HOTEL 30%)</h6>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>Nama Pegawai</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Total Penginapan (Rp)</th>
                        <th>30% Penginapan (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($row->data_penginapan as $d)
                        <tr>
                            <td>{{ $d['nama'] }}</td>
                            <td class="text-center">{{ $d['jumlah_malam'] }} Malam</td>
                            <td class="text-center">Rp {{ number_format($d['harga_satuan'], 0, ',', '.') }}</td>
                            <td class="text-center">Rp {{ number_format($d['total_penginapan'], 0, ',', '.') }}</td>
                            <td class="fw-bold text-success text-center">
                                {{ number_format($d['uang_30'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        <hr>
        @endif

        {{-- ESTIMASI BIAYA --}}
        <h6 class="fw-bold mt-4">Estimasi Biaya</h6>
        <table class="table table-bordered table-striped">
          <thead class="table-dark text-center">
            <tr>
              <th>Deskripsi</th>
              <th>Personil</th>
              <th>Harga Satuan</th>
              <th>Unit/Hari</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @foreach($row->biaya as $biaya)
            <tr>
              <td>{{ $biaya->deskripsi_biaya }}</td>
              <td>{{ $biaya->personil_name ?? optional($biaya->pegawaiTerkait)->nama ?? 'Semua' }}</td>
              <td class="text-end">Rp {{ number_format($biaya->harga_satuan) }}</td>
              <td class="text-center">{{ $biaya->jumlah_unit }} {{ optional($biaya->sbuItem)->satuan }}</td>
              <td class="text-end">Rp {{ number_format($biaya->subtotal_biaya) }}</td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr class="table-light">
              <th colspan="4" class="text-end">Total Estimasi</th>
              <th class="text-end">Rp {{ number_format($row->biaya->sum('subtotal_biaya')) }}</th>
            </tr>
          </tfoot>
        </table>
      </div>

      {{-- FOOTER --}}
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>

          <button type="button" class="btn btn-primary" onclick="printModal({{ $row->id }})">
              <i class="bx bx-printer"></i> Print
          </button>
      </div>

    </div>
  </div>
</div>
@endforeach

{{-- MODAL EDIT (tidak menggunakan $row di server; diisi via JS) --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form id="formEdit" method="POST" action="" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="aksi" id="aksiInput" value="">
        <div class="modal-header bg-white">
          <h5 class="modal-title">Form Laporan Perjalanan Dinas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body" style="max-height:75vh; overflow-y:auto;">
          <div class="mb-4">
            <h6 class="fw-bold border-bottom pb-2 mb-3">Detail Perjalanan Dinas</h6>
            <div class="row">
              <div class="col-md-6 mb-2"><strong>Nomor SPT:</strong> <span id="nomorSPT">-</span></div>
              <div class="col-md-6 mb-2"><strong>Tujuan:</strong> <span id="tujuanSPT">-</span></div>
              <div class="col-md-12 mb-2">
                <strong>Tanggal Pelaksanaan:</strong> <span id="tanggalPelaksanaan">-</span>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold">Tanggal Laporan <span class="text-danger">*</span></label>
              <input type="date" name="tanggal_laporan" id="tanggalLaporan" class="form-control" required>
            </div>

            <div class="col-md-12 mb-3">
              <label class="form-label fw-bold">Ringkasan Hasil Kegiatan <span class="text-danger">*</span></label>
              <textarea name="ringkasan_hasil_kegiatan" id="hasilKegiatan" class="form-control" rows="4" required></textarea>
            </div>

            <div class="col-md-12 mb-3">
              <label class="form-label fw-bold">Kendala yang Dihadapi</label>
              <textarea name="kendala_dihadapi" id="kendala" class="form-control" rows="3"></textarea>
            </div>

            <div class="col-md-12 mb-4">
              <label class="form-label fw-bold">Saran / Tindak Lanjut</label>
              <textarea name="saran_tindak_lanjut" id="saran" class="form-control" rows="3"></textarea>
            </div>
          </div>

          {{-- Rincian Biaya Riil (form dinamis) --}}
          <div class="mt-4">
            <h6 class="fw-bold border-bottom pb-2 mb-3">Rincian Biaya Riil</h6>

            <div id="listBiayaRiil">
                <div class="biaya-item border rounded-3 p-3 mb-3"
                data-provinsi="{{ $row->provinsi_tujuan }}"
                data-jumlah="{{ $row->biaya->firstWhere('deskripsi_biaya', 'Hotel')->jumlah_unit ?? 1 }}"
                data-harga="{{ $row->biaya->firstWhere('deskripsi_biaya', 'Hotel')->harga_satuan ?? 0 }}">
                  
                <h6 class="fw-bold mb-3">Item Biaya #1</h6>

                    <div class="row g-3">

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Deskripsi Biaya</label>
                            <select name="biaya[0][deskripsi]" class="form-select select-deskripsi">
                                <option value="">-- Pilih Deskripsi Biaya --</option>
                                <option value="Transportasi">Transportasi</option>
                                <option value="Taxi">Taxi</option>
                                <option value="Hotel">Hotel</option>
                                <option value="Hotel 30%">Hotel (30%)</option>
                                <option value="BBM">BBM</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Provinsi Tujuan</label>
                            <select name="biaya[0][provinsi_tujuan]" class="form-select input-provinsi">
                                <option value="">-- Pilih Provinsi Tujuan --</option>
                                @foreach ($provinsi as $p)
                                    <option value="{{ $p->provinsi_tujuan }}">{{ $p->provinsi_tujuan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Jumlah</label>
                            <input type="number" name="biaya[0][jumlah]" class="form-control input-jumlah" value="1">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Satuan</label>
                            <input type="text" name="biaya[0][satuan]" class="form-control input-satuan">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Harga Satuan (Rp)</label>
                            <input type="number" name="biaya[0][harga]" class="form-control input-harga">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nomor Bukti</label>
                            <input type="text" name="biaya[0][bukti]" class="form-control input-nomor-bukti">
                        </div>

                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Upload Bukti (Opsional)</label>
                            <input type="file" name="biaya[0][upload_bukti]" class="form-control input-bukti">
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Keterangan Tambahan</label>
                            <textarea name="biaya[0][keterangan]" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
              </div>
            </div>

            <div class="text-center mt-2 mb-4">
              <button type="button" id="btnTambahBiaya" class="btn btn-outline-primary rounded-pill px-4 py-2 shadow-sm">
                <i class="bx bx-plus-circle me-1"></i> Tambah Rincian Biaya Riil
              </button>
            </div>
          </div>

        <div class="modal-footer d-flex justify-content-end">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bx bx-left-arrow-alt"></i> Kembali
          </button>

          <button type="button"
                  class="btn btn-success fw-bold btnVerifikasi"
                  name="aksi" value="verifikasi">
              <i class="bx bx-check-circle"></i> Serahkan untuk Verifikasi
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.btnVerifikasi');
    if (!btn) return;  // bukan tombol, abaikan

    const form = btn.closest('form'); // ambil form terdekat
    if (!form) return;

    Swal.fire({
        title: 'Kirim untuk Verifikasi?',
        text: "Apakah kamu yakin ingin mensubmit laporan ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, kirim!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('aksiInput').value = 'verifikasi';
          form.submit(); // submit form
        }
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // modal edit
    const modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
    const form = document.getElementById('formEdit');
    const btnsEdit = document.querySelectorAll('.btn-edit');

    btnsEdit.forEach(btn => {
        btn.addEventListener('click', function() {

            form.reset(); //
            form.action = `/laporan/${this.dataset.id}/update`;

            // isi detail header
            document.getElementById('nomorSPT').textContent = this.dataset.nomor || '-';
            document.getElementById('tujuanSPT').textContent = this.dataset.tujuan || '-';
            const tglMulai = this.dataset.tglmulai ? new Date(this.dataset.tglmulai).toLocaleDateString('id-ID') : '-';
            const tglSelesai = this.dataset.tglselesai ? new Date(this.dataset.tglselesai).toLocaleDateString('id-ID') : '-';
            document.getElementById('tanggalPelaksanaan').textContent = `${tglMulai} s/d ${tglSelesai}`;

            // isi field laporan
            document.getElementById('tanggalLaporan').value = this.dataset.tanggal_laporan || '';
            document.getElementById('hasilKegiatan').value = this.dataset.hasil || '';
            document.getElementById('kendala').value = this.dataset.kendala || '';
            document.getElementById('saran').value = this.dataset.saran || '';

            // show modal
            modalEdit.show();
        });
    });

    // tambah / hapus item biaya riil di modal
    const list = document.getElementById('listBiayaRiil');
    const btnTambah = document.getElementById('btnTambahBiaya');

    btnTambah.addEventListener('click', () => {
        const index = list.querySelectorAll('.biaya-item').length;

        const wrapper = document.createElement('div');
        wrapper.className = 'biaya-item border rounded-3 p-3 mb-3';
        wrapper.dataset.provinsi = "{{ $row->provinsi_tujuan }}";

        wrapper.innerHTML = `
        <div class="d-flex justify-content-between mb-2">
            <h6 class="fw-bold mb-3">Item Biaya #${index + 1}</h6>
            <button type="button" class="btn btn-sm btn-danger btn-remove-item">
                Hapus
            </button>
        </div>

            <div class="row g-3">

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Deskripsi Biaya</label>
                    <select name="biaya[${index}][deskripsi]" class="form-select select-deskripsi">
                        <option value="">-- Pilih Deskripsi Biaya --</option>
                        <option value="Transportasi">Transportasi</option>
                        <option value="Taxi">Taxi</option>
                        <option value="Hotel">Hotel</option>
                        <option value="Hotel 30%">Hotel (30%)</option>
                        <option value="BBM">BBM</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Provinsi Tujuan</label>
                    <select name="biaya[${index}][provinsi_tujuan]" class="form-select input-provinsi">
                        <option value="">-- Pilih Provinsi Tujuan --</option>
                        @foreach ($provinsi as $p)
                            <option value="{{ $p->provinsi_tujuan }}">{{ $p->provinsi_tujuan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Jumlah</label>
                    <input type="number" name="biaya[${index}][jumlah]" class="form-control input-jumlah" value="1">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Satuan</label>
                    <input type="text" name="biaya[${index}][satuan]" class="form-control input-satuan">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Harga Satuan (Rp)</label>
                    <input type="number" name="biaya[${index}][harga]" class="form-control input-harga">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Nomor Bukti</label>
                    <input type="text" name="biaya[${index}][bukti]" class="form-control input-nomor-bukti">
                </div>

                <div class="col-md-5">
                    <label class="form-label fw-semibold">Upload Bukti (Opsional)</label>
                    <input type="file" name="biaya[${index}][upload_bukti]" class="form-control input-bukti">
                </div>
            </div>
        `;
        list.appendChild(wrapper);
    });

    // event delegation untuk tombol hapus item biaya
    list.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-remove-item')) {
            e.target.closest('.biaya-item').remove();
            // optional: re-number headers (item #)
            list.querySelectorAll('.biaya-item').forEach((el, i) => {
                const h = el.querySelector('h6');
                if (h) h.textContent = `Item Biaya #${i+1}`;
            });
        }
    });

    // (opsional) kamu bisa tambahkan kalkulasi total di client-side jika mau
});
</script>

<script>
$(document).ready(function () {
    $('#showEntries').on('change', function () {
        let perPage = $(this).val(); // ambil jumlah per page dari dropdown

        $.ajax({
            url: "{{ route('laporan.index') }}",
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
document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".btn-edit2");

    editButtons.forEach(btn => {
        btn.addEventListener("click", function () {

            const id = this.dataset.id;

            // Set action form otomatis
            document.getElementById("formEditLaporan").action = "/laporan/" + id;

            // Isi field
            document.getElementById("edit_nomor_spt").value = this.dataset.nomor;
            document.getElementById("edit_tujuan").value = this.dataset.tujuan;
            document.getElementById("edit_mulai").value = this.dataset.tglmulai;
            document.getElementById("edit_selesai").value = this.dataset.tglselesai;

            document.getElementById("edit_tanggal_laporan").value = this.dataset.tanggal_laporan ?? "";
            document.getElementById("edit_hasil").value = this.dataset.hasil ?? "";
            document.getElementById("edit_kendala").value = this.dataset.kendala ?? "";
            document.getElementById("edit_saran").value = this.dataset.saran ?? "";
        });
    });
});
</script>
<script>
$(document).on('click', '.btn-edit2', function (e) {

    e.preventDefault();
    e.stopPropagation();

    // TUTUP modal laporan perjalanan dinas (kalau sedang terbuka)
    $('.modal').modal('hide');

    // Ambil ID
    let id = $(this).data('id');

    // Set action update
    $('#formEditLaporan').attr('action', '/laporan-perjadin/' + id);

    // Set isi form
    $('#edit_nomor_spt').val($(this).data('nomor'));
    $('#edit_tujuan').val($(this).data('tujuan'));
    $('#edit_mulai').val($(this).data('mulai'));
    $('#edit_selesai').val($(this).data('selesai'));

    $('#edit_tanggal_laporan').val($(this).data('laporan'));
    $('#edit_hasil').val($(this).data('hasil'));
    $('#edit_kendala').val($(this).data('kendala'));
    $('#edit_saran').val($(this).data('saran'));

    // BUKA modal edit
    setTimeout(() => {
        $('#modalEditLaporan').modal('show');
    }, 300);
});


$(document).on('click', '.btn-edit2', function () {

    let id = $(this).data('id');

    // SET AKSI FIX
    $('#formEditLaporan').attr('action', '/laporan-perjadin/' + id + '/edit');

    console.log("ACTION:", $('#formEditLaporan').attr('action')); // DEBUG

    $('#edit_nomor_spt').val($(this).data('nomor'));
    $('#edit_tujuan').val($(this).data('tujuan'));
    $('#edit_mulai').val($(this).data('tglmulai'));
    $('#edit_selesai').val($(this).data('tglselesai'));

    $('#edit_tanggal_laporan').val($(this).data('tanggal_laporan'));
    $('#edit_hasil').val($(this).data('hasil'));
    $('#edit_kendala').val($(this).data('kendala'));
    $('#edit_saran').val($(this).data('saran'));

    $('#modalEditLaporan').modal('show');
});</script>

<script>
function printModal(id) {
    let modalContent = document.querySelector(`#modalDetail${id} .modal-content`).innerHTML;

    let printWindow = window.open('', '', 'width=900,height=800');
    printWindow.document.write(`
        <html>
        <head>
            <title>Laporan Perjalanan Dinas</title>

            <style>
                body {
                    font-family: "Times New Roman", Times, serif;
                    padding: 20px;
                }

                h5,h6 {
                    margin-bottom: 4px;
                    font-size: 14px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }

                table, th, td {
                    border: 1px solid #000;
                }

                th, td {
                    padding: 6px;
                    font-size: 13px;
                }

                .no-border {
                    border: none !important;
                }

                .section-title {
                    font-weight: bold;
                    margin-top: 20px;
                    margin-bottom: 10px;
                    font-size: 16px;
                }

                .info-box {
                    border: 1px solid #888;
                    padding: 10px;
                    margin-bottom: 15px;
                    background: #f8f8f8;
                }
            </style>

            <style>
              @media print {
                  .modal-footer,
                  .no-print {
                      display: none !important;
                  }

                  /* Hilangkan shadow dan background modal */
                  .modal {
                      position: static !important;
                  }
                  .modal-dialog {
                      margin: 0 !important;
                      max-width: 100% !important;
                  }
                  .modal-content {
                      border: none !important;
                      box-shadow: none !important;
                  }
              }
              </style>

            <style>
              @media print {
                  .table-info-perjalanan th {
                      text-align: left !important;
                  }
                  .table-info-perjalanan td {
                      text-align: left !important;
                  }
              }
            </style>

        </head>
        <body>
            ${modalContent}
        </body>
        </html>
    `);

    printWindow.document.close();
    printWindow.focus();

    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 500);
}
</script>

<script>
    document.addEventListener("change", function (e) {
        if (!e.target.classList.contains("select-deskripsi")) return;

        const row = e.target.closest(".biaya-item");

        const provinsiSelect = row.querySelector(".input-provinsi");
        const jumlahInput = row.querySelector(".input-jumlah");
        const satuanInput = row.querySelector(".input-satuan");
        const hargaInput = row.querySelector(".input-harga");
        const buktiInput = row.querySelector(".input-bukti");
        const nomorBuktiInput = row.querySelector(".input-nomor-bukti");

        if (e.target.value === "Hotel 30%") {

            // Disable kolom harga satuan
            hargaInput.disabled = true;

            provinsiSelect.value = row.dataset.provinsi;
            jumlahInput.value = row.dataset.jumlah;
            satuanInput.value = "Malam";
            hargaInput.value = row.dataset.harga;

            buktiInput.disabled = true;
            nomorBuktiInput.disabled = true;

        } else {
            hargaInput.disabled = false;
            buktiInput.disabled = false;
            nomorBuktiInput.disabled = false;
        }
    });
</script>

@endpush
