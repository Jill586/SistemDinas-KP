@extends('layouts.app')

@section('title', 'Data Pegawai')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header text-start bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">DATA PEGAWAI</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            + Tambah Pegawai
        </button>
    </div>

    <div class="card-body">
        {{-- 🔍 Form Pencarian --}}
        <div class="mb-3 d-flex justify-content-start">
            <input type="text" id="search" name="search" class="form-control w-25 me-2"
                   placeholder="Cari Pegawai">
        </div>

        {{-- 🔢 Tabel Data --}}
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Nomor HP</th>
                        <th>NIP</th>
                        <th>Golongan</th>
                        <th>Jabatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="pegawaiTable">
                    @forelse ($pegawai as $index => $row)
                        <tr>
                            <td>{{ ($pegawai->currentPage() - 1) * $pegawai->perPage() + $loop->iteration }}</td>
                            <td>{{ $row->nama }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->nomor_hp }}</td>
                            <td>{{ $row->nip }}</td>
                            <td>{{ $row->golongan }}</td>
                            <td>{{ $row->jabatan }}</td>
                            <td class="d-flex gap-2">
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalEdit{{ $row->id }}">
                                    <i class="bx bx-edit"></i>
                                </button>

                                <form action="{{ route('pegawai.destroy', $row->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Edit --}}
                        <div class="modal fade" id="modalEdit{{ $row->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Data Pegawai</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>

                                    <form action="{{ route('pegawai.update', $row->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nama Pegawai</label>
                                                <input type="text" name="nama" class="form-control"
                                                       value="{{ $row->nama }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control"
                                                       value="{{ $row->email }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nomor HP</label>
                                                <input type="text" name="nomor_hp" class="form-control"
                                                       value="{{ $row->nomor_hp }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">NIP</label>
                                                <input type="text" name="nip" class="form-control"
                                                       value="{{ $row->nip }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Golongan</label>
                                                <input type="text" name="golongan" class="form-control"
                                                       value="{{ $row->golongan }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Jabatan</label>
                                                <input type="text" name="jabatan" class="form-control"
                                                       value="{{ $row->jabatan }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Data Pegawai belum ada</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <form action="{{ route('pegawai.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Pegawai</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor HP</label>
                        <input type="text" name="nomor_hp" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIP</label>
                        <input type="text" name="nip" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Golongan</label>
                        <input type="text" name="golongan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="jabatan" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Tambah Pegawai</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#search').on('keyup', function() {
            let search = $(this).val();
            $.ajax({
                url: "{{ route('data-pegawai') }}", // ✅ perbaikan route
                type: "GET",
                data: { search: search },
                success: function(response) {
                    $('#pegawaiTable').html(response.html); // ✅ target tbody
                },
                error: function(xhr) {
                    console.log(xhr.responseText); // buat debug kalau masih error
                }
            });
        });
    });
</script>
@endpush
