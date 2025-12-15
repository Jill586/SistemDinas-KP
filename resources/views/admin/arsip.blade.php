@extends('layouts.app')

@section('title', 'Arsip Periode')

@section('content')
<div class="card mb-3 shadow rounded-2">
    <div class="card-body">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-0 fw-bold">Arsip Periode E-SPT</h5>
                <small class="text-muted">Daftar arsip per tahun & total anggaran</small>
            </div>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalArsip">
                Arsipkan
            </button>
        </div>

        {{-- SUMMARY TOTAL ANGGARAN --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="border rounded p-3 bg-light">
                    <div class="fw-semibold">Total Anggaran Periode Aktif</div>
                    <div class="fs-5 fw-bold">
                        Rp {{ number_format($arsipAktif?->total_anggaran ?? 0, 0, ',', '.') }}
                    </div>
                    <small class="text-muted">
                        dari Rp {{ number_format($arsipAktif?->batas_anggaran ?? 0, 0, ',', '.') }}
                    </small>
                </div>
            </div>
        </div>

        {{-- TABEL ARSIP --}}
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center" id="dataTable">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Periode</th>
                        <th>Total SPT</th>
                        <th>Luar Riau</th>
                        <th>Dalam Riau</th>
                        <th>Siak</th>
                        <th>Total Anggaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($arsip as $i => $a)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $a->nama_periode }}</td>
                        <td>{{ $a->total_spt }}</td>
                        <td>Rp {{ number_format($a->total_luar_riau, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($a->total_dalam_riau, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($a->total_siak, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($a->total_anggaran, 0, ',', '.') }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit{{ $a->id }}">
                                <i class="bx bx-edit"></i>
                            </button>

                            <form action="{{ route('arsip.periode.destroy', $a->id) }}"
                                method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="btn btn-danger btn-sm"
                                    onclick="hapusArsip({{ $a->id }})">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- MODAL ARSIP --}}
<div class="modal fade" id="modalArsip" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('arsip.periode.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Arsipkan Periode {{ date('Y') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <label class="form-label fw-semibold">Batas Anggaran Periode (Rp)</label>
                <input type="number" name="batas_anggaran" class="form-control" required>
                <small class="text-muted">Plafon anggaran (100%) periode ini</small>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan Arsip</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Arsip -->
@foreach ($arsip as $a)
<div class="modal fade" id="modalEdit{{ $a->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content shadow">
      <form action="{{ route('arsip.periode.update', $a->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title">Edit Arsip Periode: {{ $a->nama_periode }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Total SPT</label>
              <input type="number" name="total_spt" class="form-control" value="{{ $a->total_spt }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Total Anggaran (Rp)</label>

            </div>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label fw-semibold">Luar Riau</label>
              <input type="number" name="total_luar_riau" class="form-control" value="{{ $a->total_luar_riau }}" required>
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label fw-semibold">Dalam Riau</label>
              <input type="number" name="total_dalam_riau" class="form-control" value="{{ $a->total_dalam_riau }}" required>
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label fw-semibold">Siak</label>
              <input type="number" name="total_siak" class="form-control" value="{{ $a->total_siak }}" required>
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" onclick="submitEdit({{ $a->id }})">Simpan Perubahan</button>
        </div>

      </form>
    </div>
  </div>
</div>
@endforeach





<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>


function arsipkanPeriode() {
    Swal.fire({
        title: 'Simpan Arsip Periode?',
        text: 'Data akan disimpan ke dalam arsip.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#6c63ff',
        cancelButtonColor: '#6c757d',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formArsipPeriode').submit();
        }
    });
}


function hapusArsip(id) {
    Swal.fire({
        title: "Hapus Arsip?",
        text: "Data yang dihapus tidak bisa dikembalikan.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#e3342f",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Hapus"
    }).then((r) => {
        if (r.isConfirmed) {
            document.querySelector(`form[action$='/${id}']`).submit();
        }
    });
}

function submitEdit(id) {
    Swal.fire({
        title: "Simpan Perubahan?",
        text: "Data arsip akan diperbarui.",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Simpan"
    }).then((r) => {
        if (r.isConfirmed) {
            document.querySelector(`#modalEdit${id} form`).submit();
        }
    });
}


</script>

@endsection

@push('styles')
<style>
.modal { z-index: 20050 !important; }
.modal-backdrop { z-index: 20040 !important; }
.modal-content.shadow { box-shadow: 0 8px 30px rgba(45,55,72,0.12); }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.modal').forEach(function(modalEl) {
    modalEl.addEventListener('shown.bs.modal', function () {
      const firstInput = modalEl.querySelector('input, select, textarea');
      if (firstInput) firstInput.focus();
    });
  });
});
</script>
@endpush
