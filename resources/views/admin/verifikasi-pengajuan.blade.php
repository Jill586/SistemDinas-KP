@extends('layouts.app')

@section('title', 'Verifikasi Pengajuan')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Daftar Verifikasi Pengajuan</h5>
    </div>

    <div class="card-body">
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perjalanans as $row)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $row->nomor_spt }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_spt)->format('d M Y') }}</td>
                            <td>{{ $row->user_pengaju->nama ?? '-' }}</td>
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
                                <button type="button" class="btn btn-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalVerifikasi{{ $row->id }}">
                                    <i class="bx bx-check-circle"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Belum ada data pengajuan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
                        Provinsi: {{ $row->provinsi_tujuan_id ?? '-' }},
                        Kota/Kab: {{ $row->kota_tujuan_id ?? '-' }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Operator Pengaju:</strong><br>
                        {{ $row->user_pengaju->nama ?? '-' }}
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
                        Gol: {{ $u->golongan ?? '-' }} - Jabatan: {{ $u->jabatan ?? '-' }}
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

                    {{-- Bagian Bukti Undangan --}}
                    <hr>
                    <h6 class="fw-bold">Bukti Undangan</h6>
                    @if($row->bukti_undangan)
                        <div class="alert alert-light p-3" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-alt text-info me-2"></i>
                                <div class="flex-grow-1">
                                    <strong>File Bukti Undangan:</strong>
                                    <span class="text-muted">{{ basename($row->bukti_undangan) }}</span>
                                </div>
                                <div class="ms-3">
                                    <a href="{{ Storage::url($row->bukti_undangan) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-info me-2"
                                       title="Lihat Bukti Undangan">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                    <a href="{{ Storage::url($row->bukti_undangan) }}"
                                       download
                                       class="btn btn-sm btn-outline-success"
                                       title="Download Bukti Undangan">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning p-3" role="alert">
                            <i class="bx bx-alarm-exclamation text-warning me-2"></i>
                            <strong>Bukti undangan tidak tersedia.</strong>
                            <span class="text-muted">Operator belum mengunggah bukti undangan untuk pengajuan ini.</span>
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

                {{-- Form Verifikasi --}}
                <hr>
                <h6 class="fw-bold mb-3">Form Verifikasi</h6>
                <form action="{{ route('verifikasi-pengajuan.update', $row->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="catatan_verifikator{{ $row->id }}" class="form-label fw-bold">
                            Catatan Verifikator
                            <small class="text-muted">(Wajib diisi jika Revisi atau Tolak)</small>
                        </label>
                        <textarea
                            class="form-control @error('catatan_verifikator') is-invalid @enderror"
                            id="catatan_verifikator{{ $row->id }}"
                            name="catatan_verifikator"
                            rows="3"
                            placeholder="Tuliskan catatan verifikasi di sini...">{{ old('catatan_verifikator') }}</textarea>
                        @error('catatan_verifikator')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
            </div>

        </div>
    </div>
</div>
@endforeach

@endsection
