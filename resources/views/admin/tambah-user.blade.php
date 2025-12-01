@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')

<div class="card shadow mb-4">

    <!-- HEADER -->
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Manajemen User</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
            Tambah User
        </button>
    </div>

    <!-- TABLE -->
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Dibuat Pada</th>
                    <th width="160px">Aksi</th>
                </tr>
                </thead>

                <tbody>
                @forelse ($users as $index => $u)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->role}}</td>
                    <td>{{ $u->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <!-- Edit -->
                        <button class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditUser{{ $u->id }}">
                             <i class="bx bx-edit"></i>
                        </button>

                        <!-- Delete -->
                        <form action="{{ route('tambah-user.destroy', $u->id) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">
                                <i class="bx bx-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="5" class="text-center py-3">Belum ada user</td>
                </tr>
                @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>

<!-- ================================================================= -->
<!--                  MODAL TAMBAH USER                               -->
<!-- ================================================================= -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('tambah-user.store') }}" method="POST" class="modal-content">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control"
                        required placeholder="Nama lengkap">
                </div>


                <div class="mb-3">
                    <label class="form-label">Gmail</label>
                    <input type="email" name="email" class="form-control"
                           required placeholder="email@gmail.com">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control"
                           required placeholder="********">
                </div>

               <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="super_admin" {{ $u->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin_bidang" {{ $u->role === 'admin_bidang' ? 'selected' : '' }}>Admin Bidang</option>
                        <option value="verifikator1" {{ $u->role === 'verifikator1' ? 'selected' : '' }}>verifikator1</option>
                        <option value="verifikator2" {{ $u->role === 'verifikator2' ? 'selected' : '' }}>verifikator2</option>
                        <option value="verifikator3" {{ $u->role === 'verifikator3' ? 'selected' : '' }}>verifikator3</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>

        </form>
    </div>
</div>

<!-- ================================================================= -->
<!--                  MODAL EDIT USER (LOOP)                           -->
<!-- ================================================================= -->
@foreach ($users as $u)
<div class="modal fade" id="modalEditUser{{ $u->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('tambah-user.update', $u->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control"
                        required placeholder="Nama lengkap">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           required value="{{ $u->email }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password (kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="form-control"
                           placeholder="******">
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="super_admin" {{ $u->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin_bidang" {{ $u->role === 'admin_bidang' ? 'selected' : '' }}>Admin Bidang</option>
                        <option value="verifikator1" {{ $u->role === 'verifikator1' ? 'selected' : '' }}>verifikator1</option>
                        <option value="verifikator2" {{ $u->role === 'verifikator2' ? 'selected' : '' }}>verifikator2</option>
                        <option value="verifikator3" {{ $u->role === 'verifikator3' ? 'selected' : '' }}>verifikator3</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>

        </form>
    </div>
</div>
@endforeach

<!-- ================================================================= -->
<!--              SWEETALERT NOTIFICATION                              -->
<!-- ================================================================= -->
@if (session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: "{{ session('success') }}",
    timer: 1800,
    showConfirmButton: false
});
</script>
@endif

@if (session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: "{{ session('error') }}",
});
</script>
@endif

@endsection
