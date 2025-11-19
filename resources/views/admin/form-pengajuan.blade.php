@extends('layouts.app')

@section('title', 'Form Pengajuan Perjalanan Dinas')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header text-start bg-white">
        <h5 class="mb-0 fw-bold">FORM PENGAJUAN PERJALANAN DINAS</h5>
    </div>
    <div class="card-body">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif


        {{-- Pesan Error --}}
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Perhatian!</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('perjalanan-dinas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Tanggal & Jenis --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal SPT</label>
                    <input type="date" name="tanggal_spt" class="form-control"
                           value="{{ old('tanggal_spt', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jenis Perjalanan Dinas</label>
                    <select name="jenis_spt" id="jenis_spt" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="dalam_daerah" {{ old('jenis_spt') == 'dalam_daerah' ? 'selected' : '' }}>
                            Dalam Daerah (Kabupaten Siak > 8 Jam)
                        </option>
                        <option value="luar_daerah_dalam_provinsi" {{ old('jenis_spt') == 'luar_daerah_dalam_provinsi' ? 'selected' : '' }}>
                            Luar Daerah (Dalam Provinsi Riau)
                        </option>
                        <option value="luar_daerah_luar_provinsi" {{ old('jenis_spt') == 'luar_daerah_luar_provinsi' ? 'selected' : '' }}>
                            Luar Daerah (Luar Provinsi Riau)
                        </option>
                    </select>
                </div>
            </div>

            {{-- Jenis Kegiatan --}}
            <div class="mb-3">
                <label class="form-label">Jenis Kegiatan</label>
                <textarea name="jenis_kegiatan" rows="3" class="form-control" required>{{ old('jenis_kegiatan') }}</textarea>
            </div>

            {{-- Dinamis Form Perjalanan --}}
            <div id="form-dinamis"></div>

            {{-- Dasar & Uraian --}}
            <div class="mb-3">
                <label class="form-label">Dasar SPT</label>
                <textarea name="dasar_spt" rows="3" class="form-control" required>{{ old('dasar_spt') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Uraian / Maksud Tujuan</label>
                <textarea name="uraian_spt" rows="3" class="form-control" required>{{ old('uraian_spt') }}</textarea>
            </div>

            {{-- Upload Undangan --}}
            <div class="mb-3">
                <label class="form-label">Bukti Undangan/Surat Tugas (opsional)</label>
                <input type="file" name="bukti_undangan" class="form-control"
                       accept=".pdf,.jpg,.jpeg,.png">
            </div>

            {{-- Transportasi --}}
            <div class="mb-3">
                <label class="form-label">Transportasi <span class="text-danger">*</span></label>
                <select name="alat_angkut" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    <option value="Pesawat Udara">Pesawat Udara</option>
                    <option value="Transportasi Darat Antar Kota">Transportasi Darat Antar Kota</option>
                    <option value="Kendaraan Dinas/Umum">Kendaraan Dinas/Umum</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Detail Alat Angkut Lainnya</label>
                <input type="text" name="alat_angkut_lainnya" class="form-control"
                       value="{{ old('alat_angkut_lainnya') }}">
            </div>

            {{-- Personil --}}
            <div class="mb-3">
            <label class="form-label">Personil yang Berangkat <span class="text-danger">*</span></label>
            <select name="pegawai_ids[]" id="pegawai_ids" class="form-select" multiple required>
                @foreach($pegawai as $p)
                @php
                    $sedangDinas = in_array($p->id, $pegawaiSedangDinas ?? []);
                @endphp

                <option value="{{ $p->id }}"
                    {{ (collect(old('pegawai_ids'))->contains($p->id)) ? 'selected' : '' }}
                    {{ $sedangDinas ? 'disabled' : '' }}>
                    {{ $p->nama }} (NIP: {{ $p->nip ?? '-' }})
                    {{ $sedangDinas ? ' — Sedang Dinas' : '' }}
                </option>
                @endforeach
            </select>
            </div>

            {{-- Tanggal --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control"
                           value="{{ old('tanggal_mulai') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control"
                           value="{{ old('tanggal_selesai') }}" required>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="text-end">
                <a href="{{ route('perjalanan-dinas.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Ajukan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    function renderForm(jenis) {
        let html = '';

        if (jenis === 'dalam_daerah') {
            html = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tempat/Instansi Tujuan Utama</label>
                        <input type="text" name="tujuan_spt" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Provinsi Tujuan (Sesuai SBU)</label>
                        <input type="text" class="form-control" value="RIAU" readonly>
                        <input type="hidden" name="provinsi_tujuan_id" value="RIAU">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kecamatan Tujuan (di Siak)</label>
                        <input type="text" name="kecamatan_spt" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Desa/Kampung Tujuan</label>
                        <input type="text" name="desa_spt" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Jarak (KM) jika relevan</label>
                        <input type="number" name="jarak_km" class="form-control">
                    </div>
                </div>`;
        }
        else if (jenis === 'luar_daerah_dalam_provinsi') {
            html = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tempat/Instansi Tujuan Utama</label>
                        <input type="text" name="tujuan_spt" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Provinsi Tujuan (Sesuai SBU)</label>
                        <input type="text" class="form-control" value="RIAU" readonly>
                        <input type="hidden" name="provinsi_tujuan_id" value="RIAU">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Kota/Kabupaten Tujuan (Sesuai SBU)</label>
                        <input type="text" name="kota_tujuan_id" class="form-control">
                    </div>
                </div>`;
        }
        else if (jenis === 'luar_daerah_luar_provinsi') {
            let provinsi = [
                'Aceh','Bali','Banten','Bengkulu','DI Yogyakarta','DKI Jakarta',
                'Gorontalo','Jambi','Jawa Barat','Jawa Tengah','Jawa Timur',
                'Kalimantan Barat','Kalimantan Selatan','Kalimantan Tengah','Kalimantan Timur','Kalimantan Utara',
                'Kepulauan Bangka Belitung','Kepulauan Riau','Lampung',
                'Maluku','Maluku Utara',
                'Nusa Tenggara Barat','Nusa Tenggara Timur',
                'Papua','Papua Barat','Papua Barat Daya','Papua Pegunungan','Papua Selatan','Papua Tengah',
                'Riau','Sulawesi Barat','Sulawesi Selatan','Sulawesi Tengah','Sulawesi Tenggara','Sulawesi Utara',
                'Sumatera Barat','Sumatera Selatan','Sumatera Utara'
            ];
            provinsi.sort();

            let provOptions = provinsi.map(p => `<option value="${p}">${p}</option>`).join('');

            html = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tempat/Instansi Tujuan Utama</label>
                        <input type="text" name="tujuan_spt" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Provinsi Tujuan (Sesuai SBU)</label>
                        <select name="provinsi_tujuan_id" id="provinsi" class="form-select" required>
                            <option value="">-- Pilih Provinsi --</option>
                            ${provOptions}
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Kota/Kabupaten Tujuan (Sesuai SBU)</label>
                        <input type="text" name="kota_tujuan_id" class="form-control" required>
                    </div>
                </div>`;
        }

        $('#form-dinamis').html(html);

        // apply select2 untuk provinsi (jika ada)
        if ($('#provinsi').length) {
            $('#provinsi').select2({
                dropdownParent: $('#provinsi').parent(),
                dropdownPosition: 'below'
            }).on('select2:open', function () {
                let $dropdown = $('.select2-container--open .select2-dropdown');
                $dropdown.removeClass('select2-dropdown--above').addClass('select2-dropdown--below');
            });
        }
    }

    // event
    $('#jenis_spt').on('change', function() {
        renderForm($(this).val());
    });

    // render awal
    renderForm($('#jenis_spt').val());

    // Inisialisasi select2 untuk Personil
    $('#pegawai_ids').select2({
        placeholder: "Pilih personil",
        allowClear: true,
        width: '100%'
    });
});
</script>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {

    // Jika ada session success dari controller
    @if (session('success'))
        Swal.fire({
            title: "Success!",
            icon: "success",
            draggable: true,
            text: "{{ session('success') }}"
        });
    @endif

});
</script>
@endpush

@endpush
