@extends('layouts.app')

@section('title', 'Dokumen SPT/SPPD')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
        <h5 class="mb-0 fw-bold">Dokumen SPT/SPPD</h5>

        {{-- 🔍 Form Filter Bulan & Tahun --}}
        <form method="GET" action="{{ route('dokumen-perjalanan-dinas.index') }}" class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
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
                <i class="bx bx-filter"></i> Filter
            </button>

            <a href="{{ route('dokumen-perjalanan-dinas.index') }}" class="btn btn-secondary">
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

             {{-- 🔍 Manual Search --}}
            <form action="{{ route('dokumen-perjalanan-dinas.index') }}" method="GET" class="d-flex align-items-center">
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


  {{-- === Tabel Dokumen === --}}
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
                @switch($row->status)
                  @case('disetujui')
                    <span class="badge bg-label-success">SELESAI</span>
                    @break
                  @case('ditolak')
                    <span class="badge bg-label-danger">DITOLAK</span>
                    @break
                  @case('revisi_operator')
                    <span class="badge bg-label-warning">REVISI OPERATOR</span>
                    @break
                  @case('verifikasi')
                    <span class="badge bg-label-warning">VERIFIKASI</span>
                    @break
                  @default
                    <span class="badge bg-label-primary">PROSES</span>
                @endswitch
              </td>
              <td class="text-center">
                <button type="button" class="btn btn-info d-flex align-items-center mx-auto"
                        data-bs-toggle="modal"
                        data-bs-target="#modalDetail{{ $row->id }}">
                  <i class="bx bx-show me-1"></i>
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center">Belum ada data dokumen SPT/SPPD</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $dokumens->onEachSide(2)->links('pagination::bootstrap-5') }}
        </div>
  </div>
</div>

{{-- === Modal Detail Dokumen === --}}
@foreach($dokumens as $row)
<div class="modal fade" id="modalDetail{{ $row->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      {{-- Header Modal --}}
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Dokumen SPT/SPPD</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      {{-- Body Modal --}}
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
        <div class="row mb-3">
          <div class="col-md-6 mb-2 mb-md-0">
            <a href="{{ route('dokumen.download', ['id' => $row->id, 'type' => 'sppd_pdf']) }}"
              class="btn btn-danger w-100">
              <i class="bx bxs-file-pdf me-1"></i> SPPD PDF
            </a>
          </div>

          <div class="col-md-6">

            {{-- Jika lebih dari satu personil --}}
            @if ($row->pegawai->count() > 1)
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

            {{-- Jika hanya satu personil --}}
            @elseif ($row->pegawai->count() == 1)
              @php $pg = $row->pegawai->first(); @endphp
              <a class="btn btn-primary w-100"
                href="{{ route('dokumen.download', [
                    'id' => $row->id,
                    'type' => 'sppd_word',
                    'pegawai_id' => $pg->id
                ]) }}">
                <i class="bx bxs-file-doc me-1"></i> SPPD DOCX
              </a>

            {{-- Tidak ada personil --}}
            @else
              <button class="btn btn-secondary w-100" disabled>
                <i class="bx bxs-file-doc me-1"></i> Tidak ada personil
              </button>
            @endif

          </div>
        </div>

        {{-- Tombol: Download SPT TTD (Jika Ada) --}}
        @if($row->spt_ttd)
          <div class="row">
            <div class="col-md-12 mt-1">
              <a href="{{ route('perjalanan-dinas.downloadTtd', $row->id) }}"
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
