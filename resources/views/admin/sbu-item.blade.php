@extends('layouts.app')

@section('title', 'Daftar SBU Items')

@section('content')
<div class="card">
    {{-- Header --}}
    <div class="card-header d-flex justify-content-between align-items-center bg-white flex-wrap gap-2">
        <h5 class="mb-0 fw-bold">Daftar SBU Items</h5>

        <div class="d-flex gap-2 flex-wrap align-items-center">
            {{-- 🔍 Form Filter --}}
            <form method="GET" action="{{ route('sbu-item.index') }}" class="d-flex flex-wrap gap-2">
                <select name="kategori_biaya" class="form-select" style="width: 200px;">
                    <option value="">-- Kategori Biaya --</option>
                    <option value="UANG_HARIAN" {{ request('kategori_biaya') == 'UANG_HARIAN' ? 'selected' : '' }}>Uang Harian</option>
                    <option value="PENGINAPAN" {{ request('kategori_biaya') == 'PENGINAPAN' ? 'selected' : '' }}>Penginapan</option>
                    <option value="TRANSPORTASI_DARAT" {{ request('kategori_biaya') == 'TRANSPORTASI_DARAT' ? 'selected' : '' }}>Transportasi Darat</option>
                    <option value="TRANSPORTASI_DARAT_TAKSI" {{ request('kategori_biaya') == 'TRANSPORTASI_DARAT_TAKSI' ? 'selected' : '' }}>Transportasi Darat Taksi</option>
                    <option value="REPRESENTASI" {{ request('kategori_biaya') == 'REPRESENTASI' ? 'selected' : '' }}>Representasi</option>
                    <option value="TRANSPORTASI_UDARA" {{ request('kategori_biaya') == 'TRANSPORTASI_UDARA' ? 'selected' : '' }}>Transportasi Udara</option>
                </select>

                <select name="provinsi_tujuan" class="form-select" style="width: 200px;">
                    <option value="">-- Provinsi Tujuan --</option>
                    @foreach($provinsiList as $prov)
                        <option value="{{ $prov }}" {{ request('provinsi_tujuan') == $prov ? 'selected' : '' }}>
                            {{ $prov }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-info">
                    <i class="bx bx-filter"></i> Filter
                </button>

                <a href="{{ route('sbu-item.index') }}" class="btn btn-secondary">
                    <i class="bx bx-reset"></i> Reset
                </a>
            </form>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSBUItem">
                + Tambah SBU
            </button>
        </div>
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
                <option value="ALL" {{ $perPage == 'ALL' ? 'selected' : '' }}>All</option>            </select>
            <span class="text-secondary">entries</span>
            </div>

             {{-- 🔍 Manual Search --}}
            <form action="{{ route('sbu-item.index') }}" method="GET" class="d-flex align-items-center">
            <input type="hidden" name="per_page" value="{{ $perPage }}">

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

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Kategori Biaya</th>
                        <th>Uraian Biaya</th>
                        <th>Provinsi Tujuan</th>
                        <th>Satuan</th>
                        <th>Besaran Biaya</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            {{-- Nomor ikut halaman --}}
                            <td class="text-center">
                                @if ($items instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    {{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}
                                @else
                                    {{ $loop->iteration }}
                                @endif
                            </td>
                            <td>{{ $item->kategori_biaya }}</td>
                            <td>{{ $item->uraian_biaya }}</td>
                            <td>{{ $item->provinsi_tujuan }}</td>
                            <td>{{ $item->satuan }}</td>
                            <td>Rp {{ number_format($item->besaran_biaya, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <button 
                                    class="btn btn-sm btn-warning btnEditSbu"
                                    data-id="{{ $item->id }}"
                                    data-kategori="{{ $item->kategori_biaya }}"
                                    data-uraian="{{ $item->uraian_biaya }}"
                                    data-provinsi="{{ $item->provinsi_tujuan }}"
                                    data-satuan="{{ $item->satuan }}"
                                    data-besaran="{{ $item->besaran_biaya }}"
                                >
                                    <i class="bx bx-edit"></i>
                                </button>

                                <form action="{{ route('sbu-item.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data SBU Items</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Pagination --}}
        @if ($items instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="d-flex justify-content-center mt-3">
                {{ $items->onEachSide(2)->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah SBU Item -->
<div class="modal fade" id="modalSBUItem" tabindex="-1" aria-labelledby="modalSBUItemLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="modalSBUItemLabel">Tambah SBU Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" action="{{ route('sbu-item.store') }}">
        @csrf

        <div class="modal-body">

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Kategori Biaya</label>
              <select name="kategori_biaya" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="UANG_HARIAN">Uang Harian</option>
                <option value="PENGINAPAN">Penginapan</option>
                <option value="TRANSPORTASI_DARAT">Transportasi Darat</option>
                <option value="TRANSPORTASI_DARAT_TAKSI">Transportasi Darat Taksi</option>
                <option value="REPRESENTASI">Representasi</option>
                <option value="TRANSPORTASI_UDARA">Transportasi Udara</option>
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Uraian Biaya</label>
              <input type="text" name="uraian_biaya" class="form-control" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Provinsi Tujuan</label>
              <select name="provinsi_tujuan" class="form-select" required>
                <option value="">-- Pilih Provinsi --</option>
                @foreach($provinsiList as $prov)
                  <option value="{{ $prov }}">{{ $prov }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Satuan</label>
              <select name="satuan" class="form-select" required>
                <option value="">-- Pilih Satuan --</option>
                @foreach($satuanList as $sat)
                  <option value="{{ $sat }}">{{ $sat }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Tingkat Pejabat / Golongan</label>
              <select name="tingkat_pejabat_atau_golongan" class="form-select" required>
                <option value="">-- Pilih Tingkat / Golongan --</option>
                @foreach($golonganList as $gol)
                  <option value="{{ $gol }}">{{ $gol }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Tipe Perjalanan</label>
              <select name="tipe_perjalanan" class="form-select" required>
                <option value="">-- Pilih Tipe Perjalanan --</option>
                @foreach($tipePerjalananList as $tipe)
                  <option value="{{ $tipe }}">{{ $tipe }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Besaran Biaya</label>
              <input type="number" name="besaran_biaya" class="form-control" required>
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>

      </form>

    </div>
  </div>
</div>

<!-- Modal Edit SBU Item -->
<div class="modal fade" id="editSbuItemModal" tabindex="-1" aria-labelledby="editSbuItemLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="editSbuItemLabel">Edit SBU Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="editSbuItemForm" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-body">

          <div class="mb-3">
            <label class="form-label">Kategori Biaya</label>
            <select name="kategori_biaya" id="edit_kategori_biaya" class="form-select" required>
              <option value="UANG_HARIAN">Uang Harian</option>
              <option value="PENGINAPAN">Penginapan</option>
              <option value="TRANSPORTASI_DARAT">Transportasi Darat</option>
              <option value="TRANSPORTASI_DARAT_TAKSI">Transportasi Darat Taksi</option>
              <option value="REPRESENTASI">Representasi</option>
              <option value="TRANSPORTASI_UDARA">Transportasi Udara</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Uraian Biaya</label>
            <input type="text" name="uraian_biaya" id="edit_uraian_biaya" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Provinsi Tujuan</label>
            <input type="text" name="provinsi_tujuan" id="edit_provinsi_tujuan" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Satuan</label>
            <input type="text" name="satuan" id="edit_satuan" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Besaran Biaya</label>
            <input type="number" name="besaran_biaya" id="edit_besaran_biaya" class="form-control" required>
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

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#showEntries').on('change', function () {
        let perPage = $(this).val(); // ambil jumlah per page dari dropdown

        $.ajax({
            url: "{{ route('sbu-item.index') }}",
            type: "GET",
            data: {
                per_page: perPage,
                search: $('#search').val(),
                kategori_biaya: $('select[name="kategori_biaya"]').val(),
                provinsi_tujuan: $('select[name="provinsi_tujuan"]').val(),
            },
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
    $(document).on("click", ".btnEditSbu", function () {
    let id = $(this).data('id');
    let kategori = $(this).data('kategori');
    let uraian = $(this).data('uraian');
    let provinsi = $(this).data('provinsi');
    let satuan = $(this).data('satuan');
    let besaran = $(this).data('besaran');

    $("#edit_kategori_biaya").val(kategori);
    $("#edit_uraian_biaya").val(uraian);
    $("#edit_provinsi_tujuan").val(provinsi);
    $("#edit_satuan").val(satuan);
    $("#edit_besaran_biaya").val(besaran);

    $("#editSbuItemForm").attr("action", "{{ url('sbu-item') }}/" + id);
    $("#editSbuItemModal").modal("show");
});

</script>
@endpush

