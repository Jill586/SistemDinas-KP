@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="mb-4">

  {{-- ====== ROW 1 : 4 CARD UTAMA ====== --}}
<div class="row g-4 mb-4">
  {{-- Jumlah Pegawai --}}
  <div class="col-lg-3 col-md-6 col-sm-12">
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

  {{-- Jumlah Perjalanan --}}
  <div class="col-lg-3 col-md-6 col-sm-12">
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
            <span class="text-success fw-semibold"> +{{ $perjalananHariIni }}</span> hari ini
        </div>
      </div>
    </div>
  </div>

  {{-- Perjalanan Hari Ini --}}
  <div class="col-lg-3 col-md-6 col-sm-12">
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
  <div class="col-lg-3 col-md-6 col-sm-12">
    <div class="card h-100">
      <div class="card-body d-flex flex-column justify-content-between">
        <div class="d-flex align-items-start justify-content-between mb-2">
          <div class="avatar flex-shrink-0 bg-label-primary rounded">
            <i class="bx bx-credit-card bx-sm text-primary"></i>
          </div>
        </div>
        <div>
          <span class="fw-semibold d-block mb-1">TOTAL PENGELUARAN</span>
          <h3 class="card-title mb-2">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</h3>
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
</div>

<div class="row g-4 mb-4">
  {{-- ====== KIRI: CHART STATUS PERJALANAN DINAS ====== --}}
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

  {{-- ====== KANAN: TABEL TOP 5 PELAPOR AKTIF ====== --}}
  <div class="col-md-7 col-lg-7">
  <div class="card shadow h-100">
    <div class="card-header bg-white">
      <h5 class="mb-0 fw-bold">Daftar Pelapor Aktif</h5>
    </div>

    <div class="card-body">
      {{-- Tambahkan batas tinggi dan scroll --}}
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
                    @php
                      $total = $pegawai->total_perjalanan;
                      $badgeClass = $total >= 10 ? 'bg-success' : ($total >= 6 ? 'bg-warning' : 'bg-danger');
                    @endphp
                    <span class="badge bg-label-primary fw-bold">
                      {{ $total }} PERJALANAN
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


  {{-- ====== KIRI: CHART JENIS SPT ====== --}}
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

  {{-- ====== KANAN: TOP 5 DESTINASI ====== --}}
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

{{-- CDN Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('statusPieChart').getContext('2d');
  const statusData = {
    labels: {!! json_encode($statusCounts->keys()) !!},
    datasets: [{
      label: 'Jumlah Status',
      data: {!! json_encode($statusCounts->values()) !!},
      backgroundColor: [
        '#4e73df', // draft
        '#36b9cc', // diproses
        '#f6c23e', // revisi_operator
        '#1cc88a', // verifikasi
        '#e74a3b', // ditolak
        '#858796'  // disetujui
      ],
      borderWidth: 1
    }]
  };

  new Chart(ctx, {
    type: 'doughnut', // 'doughnut'
    data: statusData,
    options: {
      responsive: true,
      maintainAspectRatio: false, // penting agar chart mengikuti ukuran CSS
      cutout: '55%', // atur besar lubang di tengah
      plugins: {
        legend: {
          position: 'bottom'
        },
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

<script>
  const ctxJenis = document.getElementById('jenisSptChart').getContext('2d');

  const jenisLabels = {!! json_encode($jenisSptCounts->keys()) !!};
  const jenisData = {!! json_encode($jenisSptCounts->values()) !!};

  new Chart(ctxJenis, {
    type: 'bar',
    data: {
      labels: jenisLabels.map(label => label
        .replace('dalam_daerah', 'Dalam Daerah')
        .replace('luar_daerah_dalam_provinsi', 'Luar Daerah (Dalam Provinsi)')
        .replace('luar_daerah_luar_provinsi', 'Luar Daerah (Luar Provinsi)')
      ),
      datasets: [{
        label: 'Jumlah SPT',
        data: jenisData,
        backgroundColor: ['#4e73df', '#36b9cc', '#e74a3b'],
        borderRadius: 2,
        borderSkipped: false
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 0
          },
          title: {
            display: true,
          }
        },
        x: {
          title: {
            display: true,
            text: 'Jenis SPT'
          }
        }
      },
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return ` ${context.parsed.y} perjalanan`;
            }
          }
        }
      }
    }
  });
</script>

<script>
  const destinasiCtx = document.getElementById('destinasiPieChart').getContext('2d');
  const destinasiLabels = {!! json_encode($topDestinasi->pluck('kota_tujuan_id')) !!};
  const destinasiData = {!! json_encode($topDestinasi->pluck('total')) !!};

  new Chart(destinasiCtx, {
    type: 'pie',
    data: {
      labels: destinasiLabels,
      datasets: [{
        label: 'Jumlah Perjalanan',
        data: destinasiData,
        backgroundColor: [
          '#4e73df',
          '#36b9cc',
          '#f6c23e',
          '#1cc88a',
          '#e74a3b'
        ],
        borderColor: '#ffffff',
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom'
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const value = context.parsed;
              const percentage = ((value / total) * 100).toFixed(1);
              return `${context.label}: ${value} )`;
            }
          }
        },
        title: {
          display: false
        }
      }
    }
  });
</script>


@endsection
