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

            <button type="submit" class="btn btn-primary">
                <i class="bx bx-search"></i> Filter
            </button>

            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                <i class="bx bx-reset"></i> Reset
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

            {{-- 🔍 Search --}}
            <div class="d-flex align-items-center">
            <label for="search" class="me-2 text-secondary">Search:</label>
            <input type="text" id="search" name="search"
                    class="form-control"
                    style="width: 260px; font-size: 0.95rem; padding: 8px 12px;">
            </div>
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
                                @if($row->status_laporan !== 'selesai')
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
                                    <i class="bx bx-check-circle"></i>
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

{{-- MODAL DETAIL untuk masing-masing row (aman karena di dalam foreach) --}}
@foreach ($perjalanans as $row)
<div class="modal fade" id="modalDetail{{ $row->id }}" tabindex="-1" aria-labelledby="modalDetailLabel{{ $row->id }}" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Laporan Perjalanan Dinas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="border rounded p-3 mb-4" style="background-color: #f8fcff; border-color: #6ac4ffff;">
          <div class="d-flex align-items-start">
            <div class="me-2">
              <i class="bi bi-info-circle-fill text-primary fs-4"></i>
            </div>
            <div>
              <ul class="mb-0 ps-3">
                <li>Pelapor: <strong>{{ optional($row->pegawai->first())->nama ?? '-' }}</strong></li>
                <li>Nomor SPT: <strong>{{ $row->nomor_spt ?? '-' }}</strong></li>
              </ul>
            </div>
          </div>
        </div>

        <h6 class="fw-bold">Informasi Perjalanan Dinas</h6>
        <div class="row mb-3">
          <div class="col-md-6">
            <p><strong>Tujuan :</strong><br>
              {{ $row->tujuan_spt ?? '-' }} ({{ $row->kota_tujuan_id ?? '-' }}, {{ $row->provinsi_tujuan_id ?? '-' }})
            </p>
            <p><strong>Personil yang Ditugaskan:</strong><br>
              @if($row->pegawai && $row->pegawai->isNotEmpty())
                @foreach($row->pegawai as $p)
                  {{ $p->nama }}@if(!$loop->last), @endif
                @endforeach
              @else
                -
              @endif
            </p>
          </div>

          <div class="col-md-6">
            <p><strong>Tanggal Pelaksanaan :</strong><br>
              {{ \Carbon\Carbon::parse($row->tanggal_mulai)->format('d M Y') }}
              s/d
              {{ \Carbon\Carbon::parse($row->tanggal_selesai)->format('d M Y') }}
            </p>

            <p><strong>Status Laporan :</strong><br>
              @php $sl = $row->status_laporan ?? '-'; @endphp
              @if($sl == 'belum_dibuat')
                <span class="badge bg-label-danger">BELUM DIBUAT</span>
              @elseif($sl == 'diproses')
                <span class="badge bg-label-primary">PROSES</span>
              @elseif($sl == 'selesai')
                <span class="badge bg-label-success">SELESAI</span>
              @elseif($sl == 'revisi_operator')
                <span class="badge bg-label-warning">REVISI OPERATOR</span>
              @else
                <span class="badge bg-label-secondary">{{ strtoupper($sl) }}</span>
              @endif
            </p>
          </div>
        </div>

        <hr>

        <h6 class="fw-bold">Detail Laporan</h6>
        <div class="row mb-3">
          <div class="col-md-6">
            <p><strong>Tanggal Laporan:</strong><br>
              {{ $row->tanggal_laporan ? \Carbon\Carbon::parse($row->tanggal_laporan)->format('d F Y') : '-' }}
            </p>
          </div>
        </div>

        <p class="mb-1 fw-bold">Ringkasan Hasil Kegiatan:</p>
        <div class="p-2 bg-light border rounded mb-3">
            {{ optional($row->laporan)->ringkasan_hasil_kegiatan ?? '-' }}
        </div>

        <p class="mb-1 fw-bold">Kendala yang Dihadapi:</p>
        <div class="p-2 bg-light border rounded mb-3">
            {{ optional($row->laporan)->kendala_dihadapi ?? '-' }}
        </div>

        <p class="mb-1 fw-bold">Saran / Tindak Lanjut:</p>
        <div class="p-2 bg-light border rounded mb-3">
            {{ optional($row->laporan)->saran_tindak_lanjut ?? '-' }}
        </div>

        <h6 class="fw-bold">Rincian Biaya Riil Dilaporkan</h6>
        <div class="table-responsive mb-3">
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
              @forelse ($row->biayaRiil ?? [] as $biaya)
                <tr>
                  <td>{{ $biaya->deskripsi_biaya }}</td>
                  <td class="text-center">{{ $biaya->jumlah }}</td>
                  <td class="text-center">{{ $biaya->satuan }}</td>
                  <td class="text-end">{{ number_format($biaya->harga_satuan ?? 0, 0, ',', '.') }}</td>
                  <td class="text-end">{{ number_format($biaya->subtotal_biaya ?? 0, 0, ',', '.') }}</td>
                  <td class="text-center">{{ $biaya->nomor_bukti ?? '-' }}</td>
                  <td class="text-center">
                    @if(!empty($biaya->path_bukti_file))
                      <a href="{{ asset('storage/' . $biaya->path_bukti_file) }}" target="_blank" class="btn btn-sm btn-primary">
                        <i class="bx bx-show"></i>
                      </a>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td>{{ $biaya->keterangan_tambahan ?? '-' }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center text-muted">Tidak ada rincian biaya riil</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <p class="fw-bold text-end">
          TOTAL BIAYA RIIL DILAPORKAN: Rp {{ number_format(collect($row->biayaRiil ?? [])->sum('subtotal_biaya'), 0, ',', '.') }}
        </p>

        <hr>
        <h6 class="fw-bold">Estimasi Biaya</h6>
        @if(!empty($row->biaya) && collect($row->biaya)->isNotEmpty())
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
                    <td>{{ $biaya->personil_name ?? optional($biaya->pegawaiTerkait)->nama ?? 'Semua' }}</td>
                    <td class="text-end">Rp {{ number_format($biaya->harga_satuan ?? 0, 0, ',', '.') }}</td>
                    <td class="text-center">
                      {{ ($biaya->jumlah_unit ?? 0) > 0 ? ($biaya->jumlah_unit . ' ' . optional($biaya->sbuItem)->satuan) : '-' }}
                    </td>
                    <td class="text-end">Rp {{ number_format($biaya->subtotal_biaya ?? 0, 0, ',', '.') }}</td>
                  </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr class="table-light">
                  <td colspan="4" class="text-end fw-bold">Total Estimasi Biaya</td>
                  <td class="text-end fw-bold">Rp {{ number_format($row->biaya->sum('subtotal_biaya') ?? 0, 0, ',', '.') }}</td>
                </tr>
              </tfoot>
            </table>
          </div>
        @else
          <div class="alert alert-info text-center">Detail estimasi biaya belum tersedia.</div>
        @endif

        <p class="fw-bold text-end">
          TOTAL ESTIMASI AWAL: Rp {{ number_format(collect($row->biaya ?? [])->sum('subtotal_biaya'), 0, ',', '.') }}
        </p>

      </div>
    </div>
  </div>
</div>
@endforeach

{{-- MODAL EDIT (tidak menggunakan $row di server; diisi via JS) --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <form id="formEdit" method="POST" action="" enctype="multipart/form-data">
        @csrf
        <div class="modal-header bg-white">
          <h5 class="modal-title fw-bold">Form Laporan Perjalanan Dinas</h5>
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
              {{-- Jika ingin, kamu bisa inject item via JS ketika tombol Edit diklik --}}
              <div class="biaya-item border rounded-3 p-3 mb-3 shadow-sm">
                <div class="d-flex justify-content-between mb-2">
                  <h6 class="fw-bold mb-0">Item Biaya #1</h6>
                </div>
                <div class="row">
                  <div class="col-md-3 mb-2">
                    <label class="form-label">Deskripsi Biaya</label>
                    <select name="biaya[0][deskripsi]" class="form-control">
                      <option value="">-- Pilih Deskripsi Biaya --</option>
                      <option value="Transportasi">Transportasi</option>
                      <option value="Taxi">Taxi</option>
                      <option value="Hotel">Hotel</option>
                      <option value="BBM">BBM</option>
                    </select>
                  </div>
                  <div class="col-md-2 mb-2">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="biaya[0][jumlah]" class="form-control" value="1">
                  </div>
                  <div class="col-md-2 mb-2">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="biaya[0][satuan]" class="form-control">
                  </div>
                  <div class="col-md-2 mb-2">
                    <label class="form-label">Harga Satuan (Rp)</label>
                    <input type="number" name="biaya[0][harga]" class="form-control">
                  </div>
                  <div class="col-md-3 mb-2">
                    <label class="form-label">Nomor Bukti</label>
                    <input type="text" name="biaya[0][bukti]" class="form-control">
                  </div>

                  <div class="col-md-3 mb-2">
                    <label class="form-label">Upload Bukti (Opsional)</label>
                    <input type="file" name="biaya[0][upload_bukti]" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                  </div>
                </div>

                <div class="mb-2">
                  <label class="form-label">Keterangan Tambahan</label>
                  <textarea name="biaya[0][keterangan]" class="form-control" rows="2"></textarea>
                </div>
              </div>
            </div>

            <div class="text-center mt-2 mb-4">
              <button type="button" id="btnTambahBiaya" class="btn btn-outline-primary rounded-pill px-4 py-2 shadow-sm">
                <i class="bx bx-plus-circle me-1"></i> Tambah Rincian Biaya Riil
              </button>
            </div>
          </div>

          {{-- Ringkasan Perbandingan Biaya (akan dihitung di server saat submit) --}}
          <div class="mt-4">
            <h6 class="fw-bold border-bottom pb-2 mb-3">Ringkasan Perbandingan Biaya</h6>

            <div class="table-responsive">
              <table class="table table-bordered align-middle text-end">
                <thead style="background-color: #6f42c1; color: white;">
                  <tr>
                    <th class="text-start">Keterangan</th>
                    <th>Jumlah (Rp)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="text-start">Total Estimasi Biaya (Pengajuan)</td>
                    <td>Rp <span id="totalEstimasi">0</span></td>
                  </tr>
                  <tr>
                    <td class="text-start">Total Biaya Riil Dilaporkan</td>
                    <td>Rp <span id="totalRiil">0</span></td>
                  </tr>
                  <tr id="rowSelisih">
                    <td class="fw-bold text-start">Selisih Dana</td>
                    <td class="fw-bold"><span id="selisihText">Rp 0</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="modal-footer d-flex justify-content-end">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bx bx-left-arrow-alt"></i> Kembali
          </button>

          <button type="submit" name="aksi" value="verifikasi" class="btn btn-success fw-bold">
            <i class="bx bx-check-circle"></i> Serahkan untuk Verifikasi
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // modal edit
    const modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
    const form = document.getElementById('formEdit');
    const btnsEdit = document.querySelectorAll('.btn-edit');

    btnsEdit.forEach(btn => {
        btn.addEventListener('click', function() {
            // set action url (sesuaikan route jika berbeda)
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
        wrapper.className = 'biaya-item border rounded-3 p-3 mb-3 shadow-sm';

        wrapper.innerHTML = `
            <div class="d-flex justify-content-between mb-2">
                <h6 class="fw-bold mb-0">Item Biaya #${index + 1}</h6>
                <button type="button" class="btn btn-sm btn-danger btn-remove-item">Hapus</button>
            </div>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label class="form-label">Deskripsi Biaya</label>
                    <select name="biaya[${index}][deskripsi]" class="form-control">
                        <option value="">-- Pilih Deskripsi Biaya --</option>
                        <option value="Transportasi">Transportasi</option>
                        <option value="Taxi">Taxi</option>
                        <option value="Hotel">Hotel</option>
                        <option value="BBM">BBM</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="biaya[${index}][jumlah]" class="form-control" value="1">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="biaya[${index}][satuan]" class="form-control">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label">Harga Satuan (Rp)</label>
                    <input type="number" name="biaya[${index}][harga]" class="form-control">
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Nomor Bukti</label>
                    <input type="text" name="biaya[${index}][bukti]" class="form-control">
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Upload Bukti (Opsional)</label>
                    <input type="file" name="biaya[${index}][upload_bukti]" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                </div>
            </div>
            <div class="mb-2">
                <label class="form-label">Keterangan Tambahan</label>
                <textarea name="biaya[${index}][keterangan]" class="form-control" rows="2"></textarea>
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
@endpush
