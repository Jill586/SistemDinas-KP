@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="mb-4">

  {{-- ============================
       ROW 1: CARD-KARD UTAMA
       ============================ --}}
 <div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card mb-3 shadow rounded-2">
            <div class="card-body">
                <form method="GET" action="{{ route('dashboard') }}" class="row g-2 align-items-end">

                    <div class="col-md-2">
                        <label class="form-label mb-1">Pilih Tahun</label>
                        <select name="tahun" class="form-select">
                            <option value="">-- Tahun --</option>
                            @foreach([2024, 2025, 2026] as $t)
                                <option value="{{ $t }}" {{ (isset($tahun) && $tahun == $t) ? 'selected' : '' }}>
                                    {{ $t }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-filter"></i> Filter
                        </button>

                        <a href="{{ route('verifikasi-pengajuan.index') }}" class="btn btn-secondary">
                            <i class="bx bx-reset"></i> Reset
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>


    {{-- Pegawai --}}
    <div class="col-5th px-2">
      <div class="card h-100">
        <div class="card-body d-flex flex-column justify-content-between">
          <div class="d-flex align-items-start justify-content-between mb-2">
            <div class="avatar flex-shrink-0 bg-label-success rounded">
              <i class="bx bx-line-chart bx-sm text-success"></i>
            </div>
          </div>
          <div>
            <span class="fw-semibold d-block mb-1 text-uppercase">Pegawai</span>
            <h3 class="card-title mb-2">{{ $jumlahPegawai }}</h3>
            <span class="text-success fw-semibold">Aktif</span>
          </div>
        </div>
      </div>
    </div>

    {{-- Perjalanan Bulan Ini --}}
    <div class="col-5th px-2">
      <div class="card h-100">
        <div class="card-body d-flex flex-column justify-content-between">
          <div class="d-flex align-items-start justify-content-between mb-2">
            <div class="avatar flex-shrink-0 bg-label-info rounded">
              <i class="bx bx-bus bx-sm text-info"></i>
            </div>
          </div>
          <div>
            <span class="fw-semibold d-block mb-1 text-uppercase">Perjalanan Bulan Ini</span>
            <h3 class="card-title mb-2">{{ $jumlahPerjalanan }}</h3>
            <span class="text-success fw-semibold">+{{ $perjalananHariIni }}</span> hari ini
          </div>
        </div>
      </div>
    </div>

    {{-- Perjalanan Hari Ini --}}
    <div class="col-5th px-2">
      <div class="card h-100">
        <div class="card-body d-flex flex-column justify-content-between">
          <div class="d-flex align-items-start justify-content-between mb-2">
            <div class="avatar flex-shrink-0 bg-label-warning rounded">
              <i class="bx bx-car bx-sm text-warning"></i>
            </div>
          </div>
          <div>
            <span class="fw-semibold d-block mb-1 text-uppercase">Perjalanan Hari Ini</span>
            <h3 class="card-title mb-2">{{ $perjalananHariIni }}</h3>
            <span class="text-warning fw-semibold">
              {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}
            </span>
          </div>
        </div>
      </div>
    </div>

    {{-- Total Pengeluaran --}}
    <div class="col-5th px-2">
      <div class="card h-100">
        <div class="card-body d-flex flex-column justify-content-between">
          <div class="d-flex align-items-start justify-content-between mb-2">
            <div class="avatar flex-shrink-0 bg-label-primary rounded">
              <i class="bx bx-credit-card bx-sm text-primary"></i>
            </div>
          </div>
          <div>
            <span class="fw-semibold d-block mb-1">Total Pengeluaran</span>
            <h3 class="card-title mb-2">
              Rp {{ number_format($totalBiaya, 0, ',', '.') }}
            </h3>

            @if($totalBaru > 0)
              <small class="text-success fw-semibold">
                + Rp {{ number_format($totalBaru, 0, ',', '.') }}
                <span class="text-muted">dari pengajuan terakhir</span>
              </small>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- Total Real Cost --}}
    <div class="col-5th px-2">
      <div class="card h-100">
        <div class="card-body d-flex flex-column justify-content-between">
          <div class="d-flex align-items-start justify-content-between mb-2">
            <div class="avatar flex-shrink-0 bg-label-danger rounded">
              <i class="bx bx-money bx-sm text-danger"></i>
            </div>
          </div>
          <div>
            <span class="fw-semibold d-block mb-1 text-uppercase">Total Real Cost</span>
            <h3 class="card-title mb-2">
              Rp {{ number_format($totalRealCost, 0, ',', '.') }}
            </h3>
            <span class="text-muted small">Biaya riil dari laporan perjalanan</span>
          </div>
        </div>
      </div>
    </div>

  </div>

<div class="row g-4 mb-4 align-items-stretch">

   <div class="row align-items-stretch mt-4">

    {{-- ===================== KOLOM KIRI ===================== --}}
    <div class="col-md-5 col-lg-5 d-flex flex-column gap-4">

        {{-- CARD 1 : ANGGARAN PERIODE --}}
        <div class="card shadow">
            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">Anggaran Periode {{ date('Y') }}</h5>
            </div>

            <div class="card-body py-3">

                <h6 class="fw-bold mb-1 mt-2">Anggaran Perjalanan Dinas</h6>

                {{-- TOTAL --}}
                <div class="position-relative mb-2" style="height: 10px;">
                    <small class="position-absolute badge bg-success"
                        style="right: 0; top: -30px; font-size: 12px;">
                        {{ number_format($persen, 1) }}%
                    </small>

                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                            style="width: {{ $persen }}%">
                        </div>
                    </div>
                </div>

                <div class="text-muted small mb-2">
                    <div>
                        Batas Anggaran:
                        <span class="fw-bold">
                            Rp {{ number_format($batas_anggaran, 0, ',', '.') }}
                        </span>
                    </div>
                    <div>
                        Terpakai:
                        <span class="fw-bold">
                            Rp {{ number_format($totalRealCost, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>


                {{-- SIAK --}}
                            <div class="card shadow mt-3">
                        <div class="card-header bg-white">
                            <h5 class="fw-bold mb-0">Anggaran Dalam Daerah Siak</h5>
                        </div>
                        <div class="card-body">

                        <div class="position-relative mb-2" style="height: 10px;">
                            <small class="position-absolute badge bg-info"
                                style="right: 0; top: -30px; font-size: 12px;">
                                {{ number_format($persenSiak, 1) }}%
                            </small>

                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-info progress-bar-striped"
                                    style="width: {{ $persenSiak }}%">
                                </div>
                            </div>
                        </div>

                        <div class="text-muted small">
                            <div>
                                Batas Anggaran:
                                <span class="fw-bold">
                                    Rp {{ number_format($batasSiak, 0, ',', '.') }}
                                </span>
                            </div>
                            <div>
                                Terpakai:
                                <span class="fw-bold">
                                    Rp {{ number_format($totalSiakReal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        {{-- CARD 2 : TOTAL LUAR & DALAM DAERAH --}}
        <div class="card shadow">
            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">Total Anggaran Luar Daerah</h5>
            </div>

            <div class="card-body">

                <h6 class="fw-semibold mb-2">Total Luar & Dalam Daerah</h6>

                <div class="position-relative mb-2" style="height: 10px;">
                    <small class="position-absolute badge bg-danger"
                        style="right: 0; top: -30px; font-size: 12px;">
                        {{ number_format($persenLuarDalam, 1) }}%
                    </small>

                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-danger progress-bar-striped"
                            style="width: {{ $persenLuarDalam }}%">
                        </div>
                    </div>
                </div>

                <div class="text-muted small mb-4">
                    <div>
                        Batas Anggaran:
                        <span class="fw-bold">
                            Rp {{ number_format($batasLuarDalam, 0, ',', '.') }}
                        </span>
                    </div>
                    <div>
                        Terpakai:
                        <span class="fw-bold">
                            Rp {{ number_format($totalLuarDalamReal, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <h6 class="fw-semibold small mb-2">Rincian</h6>

                {{-- DALAM RIAU --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between small fw-semibold">
                        <span>Luar Daerah Dalam Provinsi Riau</span>
                        <span>{{ number_format($persenDalamRiau, 1) }}%</span>
                    </div>

                    <div class="progress mb-1" style="height: 6px;">
                        <div class="progress-bar bg-primary"
                            style="width: {{ $persenDalamRiau }}%">
                        </div>
                    </div>

                    <div class="text-muted small d-flex justify-content-between">
                        <span>
                            Batas:
                            <span class="fw-bold">
                                Rp {{ number_format($batasDalamRiau, 0, ',', '.') }}
                            </span>
                        </span>
                        <span>
                            Terpakai:
                            <span class="fw-bold">
                                Rp {{ number_format($totalDalamRiauReal, 0, ',', '.') }}
                            </span>
                        </span>
                    </div>
                </div>

                {{-- LUAR DAERAH --}}
                <div>
                    <div class="d-flex justify-content-between small fw-semibold">
                        <span>Luar Daerah (Dalam Provinsi Riau)</span>
                        <span>{{ number_format($persenLuarDaerah, 1) }}%</span>
                    </div>

                    <div class="progress mb-1" style="height: 6px;">
                        <div class="progress-bar bg-warning"
                            style="width: {{ $persenLuarDaerah }}%">
                        </div>
                    </div>

                    <div class="text-muted small d-flex justify-content-between">
                        <span>
                            Batas:
                            <span class="fw-bold">
                                Rp {{ number_format($batasLuarDaerah, 0, ',', '.') }}
                            </span>
                        </span>
                        <span>
                            Terpakai:
                            <span class="fw-bold">
                                Rp {{ number_format($totalLuarDaerahReal, 0, ',', '.') }}
                            </span>
                        </span>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- ===================== KOLOM KANAN ===================== --}}
    <div class="col-md-7 col-lg-7">
        <div class="card shadow h-100">

            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">Penggunaan Anggaran Per Bidang</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive h-100">
                    <table class="table table-bordered table-striped small">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Bidang</th>
                                <th>Total Penggunaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penggunaanPerBidang as $row)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->bidang }}</td>
                                <td class="fw-semibold">
                                    Rp {{ number_format($row->total, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach

                            @if($penggunaanPerBidang->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    Belum ada data penggunaan anggaran
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</div>

</div>

  <div class="row g-4 mb-4">

    {{-- Status Perjalanan Dinas --}}
    <div class="col-md-5 col-lg-5">
      <div class="card shadow h-100">
        <div class="card-header bg-white">
          <h5 class="mb-0 fw-bold">Status Perjalanan Dinas</h5>
        </div>
        <div class="card-body d-flex align-items-center justify-content-center">
          <canvas id="statusPieChart" height="260"></canvas>
        </div>
      </div>
    </div>

    {{-- Daftar Pelapor Aktif --}}
    <div class="col-md-7 col-lg-7">
      <div class="card shadow h-100">
        <div class="card-header bg-white">
          <h5 class="mb-0 fw-bold">Daftar Pelapor Aktif</h5>
        </div>

        <div class="card-body">
          <div class="table-responsive text-nowrap" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-striped table-bordered align-middle mb-0">
              <thead class="table-light text-center sticky-top">
                <tr>
                  <th style="width: 5%">NO</th>
                  <th style="width: 30%">NAMA PEGAWAI</th>
                  <th style="width: 15%">JABATAN</th>
                  <th style="width: 25%">TOTAL PERJALANAN</th>
                </tr>
              </thead>

              <tbody>
              @forelse($topPelapor as $index => $pegawai)
                @if($pegawai->total_perjalanan > 0)
                <tr>
                  <td class="text-center">{{ $index + 1 }}</td>
                  <td class="text-start">
                    <div class="fw-bold text-uppercase">{{ $pegawai->nama }}</div>
                    <small class="text-muted">{{ $pegawai->nip ?? '-' }}</small>
                  </td>
                  <td>
                    {{ $pegawai->jabatan_struktural ?? '-' }}<br>
                    <small class="text-muted">{{ $pegawai->pangkat_golongan ?? '-' }}</small>
                  </td>
                  <td class="text-center">
                    <span class="badge bg-label-primary fw-bold">
                      {{ $pegawai->total_perjalanan }} PERJALANAN
                    </span>
                  </td>
                </tr>
                @endif
              @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-3">
                    <i class="bx bx-info-circle me-1"></i> Belum ada data perjalanan
                  </td>
                </tr>
              @endforelse
              </tbody>

            </table>
          </div>
        </div>

      </div>
    </div>

  </div>{{-- row --}}


  {{-- ============================
       ROW 3: JENIS SPT & DESTINASI
       ============================ --}}
  <div class="row g-4 mb-4">

    {{-- Jenis Perjalanan --}}
    <div class="col-md-6 col-lg-6">
      <div class="card shadow h-100">
        <div class="card-header bg-white">
          <h5 class="mb-0 fw-bold">Jenis Perjalanan Dinas</h5>
        </div>
        <div class="card-body">
          <canvas id="jenisSptChart" height="300"></canvas>
        </div>
      </div>
    </div>

    {{-- Top 5 Destinasi --}}
    <div class="col-md-6 col-lg-6">
      <div class="card shadow h-100">
        <div class="card-header bg-white">
          <h5 class="mb-0 fw-bold">Top 5 Destinasi Perjalanan Dinas</h5>
        </div>
        <div class="card-body d-flex align-items-center justify-content-center">
          <canvas id="destinasiPieChart" style="max-width: 400px; max-height: 400px;"></canvas>
        </div>
      </div>
    </div>

  </div>{{-- row --}}

</div>{{-- wrapper --}}

{{-- ============================
     SCRIPT CHART.JS
     ============================ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- Status Pie Chart --}}
<script>
  const ctx = document.getElementById('statusPieChart').getContext('2d');
  const statusData = {
    labels: {!! json_encode($statusCounts->keys()) !!},
    datasets: [{
      label: 'Jumlah Status',
      data: {!! json_encode($statusCounts->values()) !!},
      backgroundColor: [
        '#4e73df', '#36b9cc', '#f6c23e',
        '#1cc88a', '#e74a3b', '#858796'
      ],
      borderWidth: 1
    }]
  };

  new Chart(ctx, {
    type: 'doughnut',
    data: statusData,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '55%',
      plugins: {
        legend: { position: 'bottom' },
        tooltip: {
          callbacks: {
            label: function(context) {
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const value = context.parsed;
              const percentage = ((value / total) * 100).toFixed(1);
              return `${context.label}: ${value} (${percentage}%)`;
            }
          }
        }
      }
    }
  });
</script>

{{-- Jenis SPT Chart --}}
<script>
  const ctxJenis = document.getElementById('jenisSptChart').getContext('2d');

  new Chart(ctxJenis, {
    type: 'bar',
    data: {
      labels: {!! json_encode($jenisSptCounts->keys()->map(fn($k) =>
        str_replace(
          ['dalam_daerah','luar_daerah_dalam_provinsi','luar_daerah_luar_provinsi'],
          ['Dalam Daerah','Luar Daerah (Dalam Provinsi)','Luar Daerah (Luar Provinsi)'],
          $k
        )
      )) !!},

      datasets: [{
        label: 'Jumlah SPT',
        data: {!! json_encode($jenisSptCounts->values()) !!},
        backgroundColor: ['#4e73df', '#36b9cc', '#e74a3b'],
        borderRadius: 2,
        borderSkipped: false
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: { beginAtZero: true },
        x: { title: { display: true, text: 'Jenis SPT' } }
      },
      plugins: {
        legend: { display: false },
        tooltip: { callbacks: {
          label: context => `${context.parsed.y} perjalanan`
        }}
      }
    }
  });
</script>

{{-- Destinasi Pie Chart --}}
<script>
  const destinasiCtx = document.getElementById('destinasiPieChart').getContext('2d');

  new Chart(destinasiCtx, {
    type: 'pie',
    data: {
      labels: {!! json_encode($topDestinasi->pluck('kota_tujuan_id')) !!},
      datasets: [{
        label: 'Jumlah Perjalanan',
        data: {!! json_encode($topDestinasi->pluck('total')) !!},
        backgroundColor: [
          '#4e73df','#36b9cc','#f6c23e','#1cc88a','#e74a3b'
        ],
        borderColor: '#ffffff',
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'bottom' },
        tooltip: {
          callbacks: {
            label: context =>
              `${context.label}: ${context.parsed}`
          }
        }
      }
    }
  });
</script>

@endsection
