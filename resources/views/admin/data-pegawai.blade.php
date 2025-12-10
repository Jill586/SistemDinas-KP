@extends('layouts.app')

@section('title', 'Data Pegawai')

@section('content')
<div class="card shadow mb-4">
  <div class="card-header text-start bg-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0 fw-bold">Data Pegawai</h5>
      <div class="d-flex justify-content-between align-items-center mb-3">
          <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalExcel">
            Import Excel
          </button>

          <button class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
            + Tambah
          </button>
      </div>
  </div>

  <div class="card-body">
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
    {{-- 🔽 Show Entries --}}
    <div class="d-flex align-items-center mb-2 mb-sm-0">
      <select id="showEntries" class="form-select w-auto me-2">
        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
      </select>
    </div>

    {{-- 🔍 Search --}}
    <div class="d-flex align-items-center">
      <label for="search" class="me-2 text-secondary">Search:</label>
      <input type="text" id="search" name="search"
            class="form-control"
            style="width: 180px; font-size: 0.95rem; padding: 8px 12px;">
    </div>
  </div>

    {{-- 🔢 Tabel Data --}}
    <div class="table-responsive">
      <table class="table table-striped table-bordered align-middle">
        <thead class="table-light">
          <tr class="text-center">
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Nomor HP</th>
            <th>NIP</th>
            <th>Eselonisasi</th>
            <th>Golongan</th>
            <th>Jabatan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="pegawaiTable">
          @forelse ($pegawai as $index => $row)
          <tr>
            <td class="text-center">{{ ($pegawai->currentPage() - 1) * $pegawai->perPage() + $loop->iteration }}</td>
            <td>{{ $row->nama }}</td>
            <td>{{ $row->email }}</td>
            <td>{{ $row->nomor_hp }}</td>
            <td>{{ $row->nip }}</td>
            <td>{{ $row->jabatan->nama_jabatan ?? '-' }}</td>
            <td>
              {{ $row->golongan->nama_golongan ?? '-' }}<br>
              <small class="text-muted">{{ $row->pangkat_golongan ?? '-' }}</small>
            </td>
            <td>{{ $row->jabatan_struktural ?? '-' }}</td>
            <td>
              <div class="d-flex gap-1">

                <button type="button" class="btn btn-warning btn-sm btn-edit"
                    data-id="{{ $row->id }}"
                    data-nama="{{ $row->nama }}"
                    data-email="{{ $row->email }}"
                    data-nomor_hp="{{ $row->nomor_hp }}"
                    data-nip="{{ $row->nip }}"
                    data-golongan="{{ $row->golongan_id }}"
                    data-jabatan="{{ $row->jabatan_id }}"
                    data-jabatan_struktural="{{ $row->jabatan_struktural }}"
                    data-pangkat_golongan="{{ $row->pangkat_golongan }}">
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

              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="9" class="text-center">Data Pegawai belum ada</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $pegawai->onEachSide(2)->links('pagination::bootstrap-5') }}
        </div>
  </div>
</div>

<!-- 🟡 Modal Edit (global) -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Data Pegawai</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nama Pegawai</label>
              <input type="text" id="edit_nama" name="nama" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" id="edit_email" name="email" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Nomor HP</label>
              <input type="text" id="edit_nomor_hp" name="nomor_hp" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">NIP</label>
              <input type="text" id="edit_nip" name="nip" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Eselonisasi</label>
              <select id="edit_jabatan" name="jabatan_id" class="form-select" required>
                  <option value="">-- Pilih Jabatan --</option>
                  @foreach($jabatans as $jabatan)
                      <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                  @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Golongan</label>
              <select id="edit_golongan" name="golongan_id" class="form-select" required>
                  <option value="">-- Pilih Golongan --</option>
                  @foreach($golongans as $golongan)
                      <option value="{{ $golongan->id }}">{{ $golongan->nama_golongan }}</option>
                  @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Jabatan Struktural</label>
              <select id="edit_jabatan_struktural" name="jabatan_struktural" class="form-select">
                  <option value="">-- Pilih Jabatan Struktural --</option>
                  <option value="Kepala Dinas">Kepala Dinas</option>
                  <option value="Sekretaris">Sekretaris</option>
                  <option value="Kasubbag Umum dan Kepegawaian">Kasubbag Umum dan Kepegawaian</option>
                  <option value="Kasubbag Keuangan">Kasubbag Keuangan</option>
                  <option value="Kabid TIK">Kabid TIK</option>
                  <option value="Kabid IKPS">Kabid IKPS</option>
                  <option value="Staff Pelaksana">Staff Pelaksana</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Pangkat</label>
              <select id="edit_pangkat_golongan" name="pangkat_golongan" class="form-select" required>
                  <option value="">-- Pilih Pangkat --</option>
                  <option value="Pembina Utama Madya (IV/d)">Pembina Utama Madya (IV/d)</option>
                  <option value="Pembina Utama Muda (IV/c)">Pembina Utama Muda (IV/c)</option>
                  <option value="Pembina Tk. I (IV/b)">Pembina Tk. I (IV/b)</option>
                  <option value="Pembina (IV/a)">Pembina (IV/a)</option>
                  <option value="Penata Tk. I (III/d)">Penata Tk. I (III/d)</option>
                  <option value="Penata (III/c)">Penata (III/c)</option>
                  <option value="Penata Muda Tk. I (III/b)">Penata Muda Tk. I (III/b)</option>
                  <option value="Penata Muda (III/a)">Penata Muda (III/a)</option>
                  <option value="Pengatur Tk. I (II/d)">Pengatur Tk. I (II/d)</option>
                  <option value="Pengatur (II/c)">Pengatur (II/c)</option>
                  <option value="Pengatur Muda Tk. I (II/b)">Pengatur Muda Tk. I (II/b)</option>
                  <option value="Pengatur Muda (II/a)">Pengatur Muda (II/a)</option>  
                  <option value="Juru Tk. I (I/d)">Juru Tk. I (I/d)</option>
                  <option value="Juru (I/c)">Juru (I/c)</option>
                  <option value="Juru Muda Tk. I (I/b)">Juru Muda Tk. I (I/b)</option>
                  <option value="Juru Muda (I/a)">Juru Muda (I/a)</option>
                  <option value="Golongan V">Golongan V</option>
                  <option value="Golongan VI">Golongan VI</option>
                  <option value="Golongan VII">Golongan VII</option>
                  <option value="Golongan VIII">Golongan VIII</option>
                  <option value="Golongan IX">Golongan IX</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- 🟢 Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Data Pegawai</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('pegawai.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nama Pegawai</label>
              <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Nomor HP</label>
              <input type="text" name="nomor_hp" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">NIP</label>
              <input type="text" name="nip" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Eselonisasi</label>
              <select name="jabatan_id" class="form-select" required>
                  <option value="">-- Pilih Jabatan --</option>
                  @foreach($jabatans as $jabatan)
                      <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                  @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Golongan</label>
              <select name="golongan_id" class="form-select" required>
                  <option value="">-- Pilih Golongan --</option>
                  @foreach($golongans as $golongan)
                      <option value="{{ $golongan->id }}">{{ $golongan->nama_golongan }}</option>
                  @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Jabatan Struktural</label>
              <select name="jabatan_struktural" class="form-select">
                  <option value="">-- Pilih Jabatan Struktural --</option>
                  <option value="Kepala Dinas">Kepala Dinas</option>
                  <option value="Sekretaris">Sekretaris</option>
                  <option value="Kasubbag Umum">Kasubbag Umum dan Kepegawaian</option>
                  <option value="Kasubbag Keuangan">Kasubbag Keuangan</option>
                  <option value="Kabid TIK">Kabid TIK</option>
                  <option value="Kabid IKPS">Kabid IKPS</option>
                  <option value="Staff Pelaksana">Staff Pelaksana</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Pangkat</label>
              <select name="pangkat_golongan" class="form-select" required>
                  <option value="">-- Pilih Pangkat --</option>
                  <option value="Pembina Utama Madya (IV/d)">Pembina Utama Madya (IV/d)</option>
                  <option value="Pembina Utama Muda (IV/c)">Pembina Utama Muda (IV/c)</option>
                  <option value="Pembina Tk. I (IV/b)">Pembina Tk. I (IV/b)</option>
                  <option value="Pembina (IV/a)">Pembina (IV/a)</option>
                  <option value="Penata Tk. I (III/d)">Penata Tk. I (III/d)</option>
                  <option value="Penata (III/c)">Penata (III/c)</option>
                  <option value="Penata Muda Tk. I (III/b)">Penata Muda Tk. I (III/b)</option>
                  <option value="Penata Muda (III/a)">Penata Muda (III/a)</option>
                  <option value="Pengatur Tk. I (II/d)">Pengatur Tk. I (II/d)</option>
                  <option value="Pengatur (II/c)">Pengatur (II/c)</option>
                  <option value="Pengatur Muda Tk. I (II/b)">Pengatur Muda Tk. I (II/b)</option>
                  <option value="Pengatur Muda (II/a)">Pengatur Muda (II/a)</option>  
                  <option value="Juru Tk. I (I/d)">Juru Tk. I (I/d)</option>
                  <option value="Juru (I/c)">Juru (I/c)</option>
                  <option value="Juru Muda Tk. I (I/b)">Juru Muda Tk. I (I/b)</option>
                  <option value="Juru Muda (I/a)">Juru Muda (I/a)</option>
                  <option value="Golongan V">Golongan V</option>
                  <option value="Golongan VI">Golongan VI</option>
                  <option value="Golongan VII">Golongan VII</option>
                  <option value="Golongan VIII">Golongan VIII</option>
                  <option value="Golongan IX">Golongan IX</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah Pegawai</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- 🟢 Modal Import Excel --}}
<div class="modal fade" id="modalExcel" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Import Data Pegawai dari Excel</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('pegawai.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          {{-- ⚠️ Informasi Format Kolom --}}
          <div class="alert alert-success" role="alert" style="font-size: 14px;">
            <strong>Perhatian:</strong><br>
            Silakan unduh template Excel terlebih dahulu, lalu isi data sesuai format yang tersedia.

            <div class="mt-2">
              <a href="{{ asset('templates/pegawai_template.xlsx') }}"
                class="btn btn-success"
                download>
                <i class="bi bi-download"></i> Download Template Excel
              </a>
            </div>

            <hr>

            <strong>Format Kolom Excel:</strong>
            <ul class="mt-2 mb-0">
              <li><strong>Kolom A</strong> = NAMA</li>
              <li><strong>Kolom B</strong> = EMAIL</li>
              <li><strong>Kolom C</strong> = NO. TELEPON</li>
              <li><strong>Kolom D</strong> = NIP</li>
              <li><strong>Kolom E</strong> = GOLONGAN</li>
              <li><strong>Kolom F</strong> = JABATAN</li>
              <li><strong>Kolom G</strong> = JABATAN STRUKTURAL</li>
              <li><strong>Kolom H</strong> = PANGKAT</li>
            </ul>

            <div class="mt-2">
              <strong>Catatan:</strong> File tidak boleh mengandung rumus Excel dan tidak boleh ada data duplikat.
            </div>
          </div>

          <div>
            <label class="form-label fw-bold">Pilih File Excel</label>
            <input type="file" name="file" class="form-control" accept=".xls, .xlsx" required>
            <small class="text-muted">Format yang didukung: .xls, .xlsx</small>
          </div>

          {{-- Pesan error jika ada --}}
          @if ($errors->has('file'))
            <span class="text-danger small">{{ $errors->first('file') }}</span>
          @endif
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">
            Import Sekarang
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    // Fungsi utama untuk load data via AJAX
    function loadData() {
      const search = $('#search').val();
      const perPage = $('#showEntries').val();
      $.ajax({
        url: "{{ route('data-pegawai') }}",
        type: "GET",
        data: { 
          search: search, 
          per_page: perPage, 
          page: $('.pagination .active span').text() || 1, // tambahkan ini
          ajax: true 
        },
        beforeSend: function () {
          $('tbody').html('<tr><td colspan="9" class="text-center text-muted">Memuat data...</td></tr>');
        },
        success: function (response) {
          $('#pegawaiTable').html(response.html);
          attachModalEvents(); // re-attach event edit setelah tabel diupdate
        },
        error: function (xhr) {
          console.error('Error:', xhr.responseText);
        }
      });
    }

    // Saat user mengetik di kolom search
    $('#search').on('keyup', loadData);

    // Saat dropdown jumlah entries berubah
    $('#showEntries').on('change', loadData);

    // Fungsi untuk membuka modal edit dan isi datanya
    function attachModalEvents() {
      $('.btn-edit').off('click').on('click', function () {
        const id = $(this).data('id');
        $('#edit_nama').val($(this).data('nama'));
        $('#edit_email').val($(this).data('email'));
        $('#edit_nomor_hp').val($(this).data('nomor_hp'));
        $('#edit_nip').val($(this).data('nip'));
        $('#edit_golongan').val($(this).data('golongan'));
        $('#edit_jabatan').val($(this).data('jabatan'));
        $('#edit_jabatan_struktural').val($(this).data('jabatan_struktural'));
        $('#edit_pangkat_golongan').val($(this).data('pangkat_golongan'));
        $('#editForm').attr('action', `/pegawai/${id}`);

        const modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
      });
    }

    // Jalankan saat pertama kali halaman dimuat
    attachModalEvents();
  });
</script>
@endpush
