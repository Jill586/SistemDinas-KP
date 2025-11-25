@extends('layouts.app')

@section('title', 'Verifikasi Laporan Perjalanan Dinas')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
        <h5 class="mb-0 fw-bold">Daftar Verifikasi Laporan</h5>

        {{-- 🔍 Form Filter Bulan & Tahun --}}
        <form method="GET" action="{{ route('verifikasi-laporan.index') }}" class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
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

            <a href="{{ route('verifikasi-laporan.index') }}" class="btn btn-secondary">
                <i class="bx bx-reset"></i> Reset
            </a>
             <a href="{{ route('verifikasi-laporan.export.excel') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <a href="{{ route('verifikasi-laporan.export.pdf', request()->query()) }}"
            class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>

        </form>
    </div>

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
            <form action="{{ route('verifikasi-laporan.index') }}" method="GET" class="d-flex align-items-center">
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
                        <th>Nama Pegawai</th>
                        <th>Status Laporan</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporans as $index => $row)
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
                            <td>{{ $row->created_at?->translatedFormat('d F Y') ?? '-' }}</td>
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
                            <td colspan="8" class="text-center text-muted py-3">
                                Belum ada laporan perjalanan dinas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $laporans->onEachSide(2)->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>

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

        <!-- RINCIAN BIAYA RIIL -->
        <h6 class="fw-bold">Rincian Biaya Riil Dilaporkan</h6>
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead class="table-light text-center">
              <tr>
                <th>Deskripsi Biaya</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>Harga Satuan (Rp)</th>
                <th>Subtotal (Rp)</th>
                <th>No. Bukti</th>
                <th>File Bukti</th>
                <th>Keterangan</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($row->biayaRiil as $biaya)
              <tr>
                <td>{{ $biaya->deskripsi_biaya }}</td>
                <td class="text-center">{{ $biaya->jumlah }}</td>
                <td class="text-center">{{ $biaya->satuan }}</td>
                <td class="text-end">{{ number_format($biaya->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($biaya->subtotal_biaya, 0, ',', '.') }}</td>
                <td class="text-center">{{ $biaya->nomor_bukti }}</td>
                <td class="text-center">
                    @if ($biaya->path_bukti_file)
                        <a href="{{ asset('storage/' . $biaya->path_bukti_file) }}"
                        target="_blank"
                        class="btn btn-sm btn-primary">
                        <i class="bx bx-show"></i>
                        </a>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>{{ $biaya->keterangan_tambahan ?? '-' }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <p class="fw-bold text-end">
          TOTAL BIAYA RIIL DILAPORKAN: Rp {{ number_format($row->biayaRiil->sum('subtotal_biaya'), 0, ',', '.') }}
        </p>

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
@endpush
