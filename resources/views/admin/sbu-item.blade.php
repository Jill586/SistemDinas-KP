@extends('layouts.app')

@section('title', 'Daftar SBU Items')

@section('content')
<div class="card">
    {{-- Header --}}
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <h5 class="mb-0 fw-bold">Daftar SBU Items</h5>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kategori Biaya</th>
                        <th>Uraian Biaya</th>
                        <th>Provinsi Tujuan</th>
                        <th>Satuan</th>
                        <th>Besaran Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            {{-- Nomor ikut halaman --}}
                            <td>{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</td>
                            <td>{{ $item->kategori_biaya }}</td>
                            <td>{{ $item->uraian_biaya }}</td>
                            <td>{{ $item->provinsi_tujuan }}</td>
                            <td>{{ $item->satuan }}</td>
                            <td>Rp {{ number_format($item->besaran_biaya, 0, ',', '.') }}</td>
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
        <div class="d-flex justify-content-center mt-3">
            {{ $items->onEachSide(2)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
