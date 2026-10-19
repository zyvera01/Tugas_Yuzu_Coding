<?php
session_start();

if (!empty($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!doctype html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SIPN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: #f1f3f5;
        }
        .kotak-login {
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">
    <div class="kotak-login">
        <div class="text-center mb-4">
            <h1 class="fw-semibold mb-1">SIPN</h1>
            <p class="text-muted mb-0">
                Sistem Informasi Pengelolaan Nilai
            </p>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="mb-3">Login</h4>

                <form action="aksi_login.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" class="form-control"
                               id="username" name="username"
                               autocomplete="username" required autofocus>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" class="form-control"
                               id="password" name="password"
                               autocomplete="current-password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Masuk
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>