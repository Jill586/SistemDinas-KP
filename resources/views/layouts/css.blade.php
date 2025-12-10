<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<title>E-SPT</title>
<meta name="description" content="" />

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="../assets3/img/favicon/favicon.ico" />

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

<!-- Icons -->
<link rel="stylesheet" href="../assets3/vendor/fonts/boxicons.css" />

<!-- Core CSS -->
<link rel="stylesheet" href="../assets3/vendor/css/core.css" class="template-customizer-core-css" />
<link rel="stylesheet" href="../assets3/vendor/css/theme-default.css" class="template-customizer-theme-css" />
<link rel="stylesheet" href="../assets3/css/demo.css" />

<!-- Vendors CSS -->
<link rel="stylesheet" href="../assets3/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
<link rel="stylesheet" href="../assets3/vendor/libs/apex-charts/apex-charts.css" />

<!-- Helpers -->
<script src="../assets3/vendor/js/helpers.js"></script>
<script src="../assets3/js/config.js"></script>

<style>
/* Samakan border dan radius dengan .form-control */
.select2-container .select2-selection--single,
.select2-container .select2-selection--multiple {
    border: 1px solid #d9dee3 !important; /* sama dengan input normal */
    border-radius: 0.375rem !important;   /* radius bootstrap */
    min-height: 38px;                     /* tinggi input */
    padding: 0.375rem 0.75rem;
    font-family: 'Public Sans', sans-serif; /* font default template */
    font-size: 0.9375rem;                   /* sama dengan input */
    color: #697a8d;                         /* warna teks default */
}

/* Placeholder agar sama dengan input biasa */
.select2-container--default .select2-selection--multiple .select2-selection__placeholder,
.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #b4bdc6;  /* warna placeholder Bootstrap */
    font-family: 'Public Sans', sans-serif;
    font-size: 0.9375rem;
}

/* Teks item yang dipilih */
.select2-container--default .select2-selection--multiple .select2-selection__rendered li,
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #697a8d;
    font-family: 'Public Sans', sans-serif;
    font-size: 0.9375rem;
    line-height: 1.5;
}
</style>

<style>
  /* Pastikan avatar di tengah secara vertikal dan horizontal */
  .avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    border-radius: 8px;
  }

  /* Ukuran icon Boxicon biar konsisten */
  .avatar i {
    font-size: 24px;
    line-height: 1;
  }

  /* Pastikan tinggi card seragam */
  .card.h-100 {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  /* Hilangkan margin aneh dari card-body */
  .card-body {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
  }

  .col-5th {
  width: 20%;
}

@media (max-width: 1200px) {
  .col-5th {
    width: 25%;
  }
}

@media (max-width: 992px) {
  .col-5th {
    width: 33.33%;
  }
}

@media (max-width: 768px) {
  .col-5th {
    width: 50%;
  }
}

@media (max-width: 576px) {
  .col-5th {
    width: 100%;
  }
}

</style>

<style>
  .select2-container {
    width: 100% !important;
}

.select2-dropdown {
    z-index: 999999 !important;
}

.select2-container--open .select2-selection--multiple {
    height: auto !important;
}
</style>


