@extends('layouts.app')

@section('title', 'Daftar Pengajuan Perjalanan Dinas Saya')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
        <h5 class="mb-0 fw-bold">Daftar Perjalanan Dinas</h5>

        {{-- 🔍 Form Filter Bulan & Tahun --}}
        <form method="GET" action="{{ route('perjalanan-dinas.index') }}" class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
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

            <a href="{{ route('perjalanan-dinas.index') }}" class="btn btn-secondary">
                <i class="bx bx-reset"></i> Reset
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

            {{-- 🔍 Search --}}
            <div class="d-flex align-items-center">
            <label for="search" class="me-2 text-secondary">Search:</label>
            <input type="text" id="search" name="search"
                    class="form-control"
                    style="width: 260px; font-size: 0.95rem; padding: 8px 12px;">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>No</th>
                        <th>No. SPT</th>
                        <th>Tgl. SPT</th>
                        <th>Tujuan</th>
                        <th>Personil</th>
                        <th>Pelaksanaan</th>
                        <th>Status</th>
                        @if(Auth::user()->role == 'super_admin' || Auth::user()->role == 'verifikator1')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($perjalanans as $row)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $row->nomor_spt }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_spt)->format('d M Y') }}</td>
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
                            {{-- Tombol Aksi (hanya tampil untuk super admin) --}}
                                @if(Auth::user()->role == 'super_admin' || Auth::user()->role == 'verifikator1')
                                <td class="d-flex gap-2">
                                    {{-- Tombol Edit (hanya muncul jika status revisi_operator) --}}
                                    @if($row->status == 'revisi_operator')
                                        <button type="button" class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#modalEdit{{ $row->id }}">
                                            <i class="bx bx-edit"></i>
                                        </button>
                                    @endif

                                    {{-- Tombol Show --}}
                                    <button type="button" class="btn btn-info btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#modalShow{{ $row->id }}">
                                        <i class="bx bx-show"></i>
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('perjalanan-dinas.destroy', $row->id) }}"
                                        method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Belum ada data perjalanan dinas</td>
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

{{-- Modal Edit --}}
@foreach($perjalanans as $row)
    @if($row->status == 'revisi_operator')
    <div class="modal fade" id="modalEdit{{ $row->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Pengajuan Perjalanan Dinas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('perjalanan-dinas.update', $row->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">

                        {{-- Tanggal & Jenis --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal SPT</label>
                                <input type="date" name="tanggal_spt" class="form-control"
                                       value="{{ old('tanggal_spt', $row->tanggal_spt) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Perjalanan Dinas</label>
                                <select name="jenis_spt" id="jenis_spt_edit{{ $row->id }}"
                                        class="form-select jenis-spt-edit" data-target="{{ $row->id }}" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="dalam_daerah" {{ $row->jenis_spt == 'dalam_daerah' ? 'selected' : '' }}>Dalam Daerah</option>
                                    <option value="luar_daerah_dalam_provinsi" {{ $row->jenis_spt == 'luar_daerah_dalam_provinsi' ? 'selected' : '' }}>Luar Daerah (Dalam Provinsi)</option>
                                    <option value="luar_daerah_luar_provinsi" {{ $row->jenis_spt == 'luar_daerah_luar_provinsi' ? 'selected' : '' }}>Luar Daerah (Luar Provinsi)</option>
                                </select>
                            </div>
                        </div>

                        {{-- Jenis Kegiatan --}}
                        <div class="mb-3">
                            <label class="form-label">Jenis Kegiatan</label>
                            <input type="text" name="jenis_kegiatan" class="form-control"
                                   value="{{ old('jenis_kegiatan', $row->jenis_kegiatan) }}" required>
                        </div>

                        {{-- Dinamis Form Perjalanan --}}
                        <div id="form-dinamis-edit{{ $row->id }}"></div>

                        {{-- Dasar & Uraian --}}
                        <div class="mb-3">
                            <label class="form-label">Dasar SPT</label>
                            <textarea name="dasar_spt" rows="3" class="form-control" required>{{ old('dasar_spt', $row->dasar_spt) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Uraian / Maksud Tujuan</label>
                            <textarea name="uraian_spt" rows="3" class="form-control" required>{{ old('uraian_spt', $row->uraian_spt) }}</textarea>
                        </div>

                        {{-- Upload Undangan --}}
                        <div class="mb-3">
                            <label class="form-label">Bukti Undangan/Surat Tugas (opsional)</label>
                            <input type="file" name="bukti_undangan" class="form-control"
                                   accept=".pdf,.jpg,.jpeg,.png">
                        </div>

                        {{-- Transportasi --}}
                        <div class="mb-3">
                            <label class="form-label">Transportasi</label>
                            <select name="alat_angkut" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="Pesawat Udara" {{ $row->alat_angkut == 'Pesawat Udara' ? 'selected' : '' }}>Pesawat Udara</option>
                                <option value="Transportasi Darat Antar Kota" {{ $row->alat_angkut == 'Transportasi Darat Antar Kota' ? 'selected' : '' }}>Transportasi Darat Antar Kota</option>
                                <option value="Kendaraan Dinas/Umum" {{ $row->alat_angkut == 'Kendaraan Dinas/Umum' ? 'selected' : '' }}>Kendaraan Dinas/Umum</option>
                                <option value="Lainnya" {{ $row->alat_angkut == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Detail Alat Angkut Lainnya</label>
                            <input type="text" name="alat_angkut_lainnya" class="form-control"
                                   value="{{ old('alat_angkut_lainnya', $row->alat_angkut_lainnya) }}">
                        </div>

                        {{-- Personil --}}
                        <div class="mb-3">
                            <label class="form-label">Personil yang Berangkat</label>
                            <select name="pegawai_ids[]" class="form-select pegawai-edit" multiple required>
                                @foreach($pegawai as $p)
                                    <option value="{{ $p->id }}"
                                        {{ in_array($p->id, $row->pegawai->pluck('id')->toArray()) ? 'selected' : '' }}>
                                        {{ $p->nama }} (NIP: {{ $p->nip ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" class="form-control"
                                       value="{{ old('tanggal_mulai', $row->tanggal_mulai) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" class="form-control"
                                       value="{{ old('tanggal_selesai', $row->tanggal_selesai) }}" required>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

        {{-- Modal Detail: diletakkan DI LUAR tabel --}}
        @foreach($perjalanans as $row)
        <div class="modal fade" id="modalShow{{ $row->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold">Detail Pengajuan Perjalanan Dinas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body px-4 py-3">

                        {{-- === INFO SPT === --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Nomor SPT:</strong><br>
                                {{ $row->nomor_spt ?? '-' }}
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

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Tujuan Utama:</strong><br>
                                {{ $row->tujuan_spt }}
                            </div>
                            <div class="col-md-6">
                                <strong>Lokasi Tujuan (SBU):</strong><br>
                                Provinsi: {{ $row->provinsi_tujuan_id }},
                                Kota/Kab:
                                @if ($row->jenis_spt === 'dalam_daerah')
                                    Siak
                                @else
                                    {{ $row->kota_tujuan_id ?? '-' }}
                                @endif
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <strong>Detail Lokasi:</strong><br>
                                Kecamatan: {{ $row->kecamatan_spt ?? '-' }},
                                Desa/Kampung: {{ $row->desa_spt ?? '-' }},
                                Jarak: {{ $row->jarak_km ? $row->jarak_km . ' KM' : '-' }}
                            </div>
                        </div>

                        {{-- === DASAR DAN URAIAN === --}}
                        <div class="mb-3">
                            <strong>Dasar SPT:</strong>
                            <div class="form-control bg-light">{{ $row->dasar_spt }}</div>
                        </div>

                        <div class="mb-4">
                            <strong>Uraian Maksud:</strong>
                            <div class="form-control bg-light">{{ $row->uraian_spt }}</div>
                        </div>

                        {{-- === PERSONIL === --}}
                        <hr>
                        <h6 class="fw-bold mb-2">Informasi Pelaksana</h6>
                        @foreach($row->pegawai as $i => $u)
                            <div class="mb-2 ps-2">
                                <strong>{{ $i+1 }}. {{ $u->nama }}</strong>
                                (NIP: {{ $u->nip ?? '-' }})<br>
                                Gol: {{ $u->golongan ?? '-' }} -
                                Jabatan: {{ $u->jabatan ?? '-' }}
                            </div>
                        @endforeach

                        {{-- === PELAKSANAAN === --}}
                        <hr>
                        <h6 class="fw-bold mb-2">Pelaksanaan</h6>
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

                        {{-- === ESTIMASI BIAYA === --}}
                        <hr>
                        <h6 class="fw-bold mb-2">Estimasi Biaya</h6>
                        @if($row->biaya->isNotEmpty())
                            <div class="table-responsive mt-2">
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
                                                <td>{{ $biaya->personil_name ?? 'Semua' }}</td>
                                                <td class="text-end">
                                                    Rp {{ number_format($biaya->harga_satuan, 0, ',', '.') }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $biaya->jumlah_unit > 0
                                                        ? $biaya->jumlah_unit . ' ' . ($biaya->sbuItem->satuan ?? '')
                                                        : '-' }}
                                                </td>
                                                <td class="text-end">
                                                    Rp {{ number_format($biaya->subtotal_biaya, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-light">
                                            <td colspan="4" class="text-end fw-bold">Total Estimasi Biaya</td>
                                            <td class="text-end fw-bold">
                                                Rp {{ number_format($row->total_estimasi_biaya, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i> Detail estimasi biaya belum tersedia.
                            </div>
                        @endif

                        {{-- === STATUS === --}}
                        <hr>
                        <h6 class="fw-bold mb-2">Status & Catatan</h6>
                        <p>Status Saat Ini:
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
                        </p>
                        <p><strong>Catatan:</strong><br>{{ $row->catatan_verifikator ?? '-' }}</p>

                        {{-- === BUTTON === --}}
                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endsection

@push('scripts')
<script>
$(document).ready(function() {
    function renderFormEdit(jenis, target, data = {}) {
        let html = '';

       if (jenis === 'dalam_daerah') {
    html = `
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Tempat/Instansi Tujuan Utama</label>
                <input type="text" name="tujuan_spt" class="form-control"
                    value="${data.tujuan_spt ?? ''}" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Provinsi Tujuan (SBU)</label>
                <input type="text" class="form-control" value="RIAU" readonly>
                <input type="hidden" name="provinsi_tujuan_id" value="RIAU">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Kota/Kabupaten Tujuan (SBU)</label>
                <input type="text" class="form-control" value="SIAK" readonly>
                <input type="hidden" name="kota_tujuan_id" value="SIAK">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Kecamatan Tujuan</label>
                <input type="text" name="kecamatan_tujuan" class="form-control"
                    value="${data.kecamatan_tujuan ?? ''}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Desa/Kampung Tujuan</label>
                <input type="text" name="desa_tujuan" class="form-control"
                    value="${data.desa_tujuan ?? ''}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Jarak (KM)</label>
                <input type="number" name="jarak_km" class="form-control"
                    value="${data.jarak_km ?? ''}" step="0.1" min="0">
            </div>
        </div>`;
      }
        }
        else if (jenis === 'luar_daerah_dalam_provinsi') {
            html = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tempat/Instansi Tujuan Utama</label>
                        <input type="text" name="tujuan_spt" class="form-control"
                            value="${data.tujuan_spt ?? ''}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Provinsi Tujuan (SBU)</label>
                        <input type="text" class="form-control" value="RIAU" readonly>
                        <input type="hidden" name="provinsi_tujuan_id" value="RIAU">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Kota/Kabupaten Tujuan (SBU)</label>
                        <input type="text" name="kota_tujuan_id" class="form-control"
                            value="${data.kota_tujuan_id ?? ''}">
                    </div>
                </div>`;
        }
        else if (jenis === 'luar_daerah_luar_provinsi') {
            let provinsi = [
                'Aceh','Bali','Banten','Bengkulu','DI Yogyakarta','DKI Jakarta','Gorontalo',
                'Jambi','Jawa Barat','Jawa Tengah','Jawa Timur','Kalimantan Barat',
                'Kalimantan Selatan','Kalimantan Tengah','Kalimantan Timur','Kalimantan Utara',
                'Kepulauan Bangka Belitung','Kepulauan Riau','Lampung','Maluku','Maluku Utara',
                'Nusa Tenggara Barat','Nusa Tenggara Timur','Papua','Papua Barat',
                'Papua Barat Daya','Papua Pegunungan','Papua Selatan','Papua Tengah',
                'Riau','Sulawesi Barat','Sulawesi Selatan','Sulawesi Tengah','Sulawesi Tenggara',
                'Sulawesi Utara','Sumatera Barat','Sumatera Selatan','Sumatera Utara'
            ];
            provinsi.sort();

            let provOptions = provinsi.map(p =>
                `<option value="${p}" ${(data.provinsi_tujuan_id === p) ? 'selected' : ''}>${p}</option>`
            ).join('');

            html = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tempat/Instansi Tujuan Utama</label>
                        <input type="text" name="tujuan_spt" class="form-control"
                            value="${data.tujuan_spt ?? ''}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Provinsi Tujuan (SBU)</label>
                        <select name="provinsi_tujuan_id" class="form-select" required>
                            <option value="">-- Pilih Provinsi --</option>
                            ${provOptions}
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Kota/Kabupaten Tujuan (SBU)</label>
                        <input type="text" name="kota_tujuan_id" class="form-control"
                            value="${data.kota_tujuan_id ?? ''}" required>
                    </div>
                </div>`;
        }

        $(`#form-dinamis-edit${target}`).html(html);
    }

    // Init untuk setiap modal edit
    $('.jenis-spt-edit').each(function(){
        let target = $(this).data('target');
        let jenis = $(this).val();
        let rowData = @json($perjalanans->keyBy('id')); // kirim data perjalanan
        renderFormEdit(jenis, target, rowData[target]);

        $(this).on('change', function(){
            renderFormEdit($(this).val(), target, rowData[target]);
        });
    });

    // init select2 untuk personil edit
    $('.pegawai-edit').select2({
        dropdownParent: $('.modal.show'),
        width: '100%'
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const showEntries = document.getElementById('showEntries');
    if (showEntries) {
        showEntries.addEventListener('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', this.value);
            window.location.href = url.toString();
        });
    }
});
</script>

<script>
$(document).ready(function () {
    // 🔍 Real-time search
    $('#search').on('keyup', function () {
        let search = $(this).val();
        let perPage = $('#showEntries').val();
        let bulan = $('select[name="bulan"]').val();
        let tahun = $('select[name="tahun"]').val();

        $.ajax({
            url: "{{ route('perjalanan-dinas.index') }}",
            type: 'GET',
            data: { search: search, per_page: perPage, bulan: bulan, tahun: tahun },
            success: function (response) {
                $('tbody').html(response.html);
            }
        });
    });

    // 🔄 Change show entries
    $('#showEntries').on('change', function () {
        let perPage = $(this).val();
        let search = $('#search').val();
        let bulan = $('select[name="bulan"]').val();
        let tahun = $('select[name="tahun"]').val();

        $.ajax({
            url: "{{ route('perjalanan-dinas.index') }}",
            type: 'GET',
            data: { per_page: perPage, search: search, bulan: bulan, tahun: tahun },
            beforeSend: function () {
                $('tbody').html('<tr><td colspan="10" class="text-center text-muted">Memuat data...</td></tr>');
            },
            success: function (response) {
                $('tbody').html(response.html);
            }
        });
    });
});
</script>
@endpush

