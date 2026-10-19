<?php
require_once "koneksi.php";

$role_nav = $_SESSION['role'] ?? '';
$nama_nav = $_SESSION['nama'] ?? $_SESSION['username'] ?? 'Pengguna';
?>

<!doctype html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPN - Sistem Informasi Pengelolaan Nilai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <style>
        .tabel-bingkai {
            border: 1px solid #dee2e6;
            border-radius: .5rem;
            overflow: hidden;
            background: #fff;
        }
        .tabel-sip {
            margin-bottom: 0;
        }
        .tabel-sip th, .tabel-sip td {
            white-space: nowrap;
            vertical-align: middle;
            padding: .6rem .8rem;
        }
        .tabel-sip thead th {
            background: #f1f3f5;
            text-align: center;
            border-bottom: 2px solid #ced4da;
        }
        .tabel-sip .tengah {
            text-align: center;
        }
        .tabel-sip form {
            display: inline;
        }
        @media (max-width: 767.98px) {
            .tabel-sip {
                font-size: .875rem;
            }
            .tabel-sip th, .tabel-sip td {
                padding: .45rem .55rem;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">SIPN</a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false"
                aria-label="Buka navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>

                <?php if ($role_nav === 'admin'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#"
                           role="button" data-bs-toggle="dropdown">
                            User
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="daftar_user.php">Daftar User</a></li>
                            <li><a class="dropdown-item" href="form_tambah_user1.php">Tambah User 1</a></li>
                            <li><a class="dropdown-item" href="form_tambah_user2.php">Tambah User 2</a></li>
                            <li><a class="dropdown-item" href="form_tambah_user3.php">Tambah User 3</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#"
                           role="button" data-bs-toggle="dropdown">
                            Siswa
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="daftar_siswa.php">Daftar Siswa</a></li>
                            <li><a class="dropdown-item" href="form_tambah_user2.php">Tambah Siswa</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#"
                           role="button" data-bs-toggle="dropdown">
                            Guru
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="daftar_guru.php">Daftar Guru</a></li>
                            <li><a class="dropdown-item" href="form_tambah_user4.php">Tambah Guru</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#"
                           role="button" data-bs-toggle="dropdown">
                            Mapel
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="mapel.php">Daftar Mapel</a></li>
                            <li><a class="dropdown-item" href="tambah_mapel.php">Tambah Mapel</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="nilai.php">Kelola Nilai</a>
                    </li>

                <?php elseif ($role_nav === 'guru'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="daftar_siswa.php">Daftar Siswa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="mapel_guru.php">Mapel Diampu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="nilai.php">Kelola Nilai</a>
                    </li>

                <?php elseif ($role_nav === 'siswa'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="profil_siswa.php">Profil Saya</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="mapel_siswa.php">Daftar Mapel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="nilai_siswa.php">Nilai Rapor</a>
                    </li>
                <?php endif; ?>
            </ul>

            <span class="navbar-text me-3">
                <?= e($nama_nav) ?> (<?= e($role_nav) ?>)
            </span>
            <a href="logout.php"
   class="btn btn-outline-danger btn-sm"
   onclick="return confirm('Yakin ingin logout?')">
    Logout
            </a>
        </div>
    </div>
</nav>