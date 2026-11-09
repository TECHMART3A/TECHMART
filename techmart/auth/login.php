<?php
session_start();

require_once '../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $db = getDB();

    $stmt = $db->prepare(
        "SELECT * FROM users WHERE username = ?"
    );

    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        header('Location: ../dashboard.php');
        exit;

    } else {
        $error = 'Username atau password salah!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login TECHMART</title>

    <!-- CSS TECHMART -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="login-page">

    <div class="login-container">

        <div class="login-card">

            <div class="login-logo">
                <div class="logo-icon">T</div>

                <h1>TECHMART</h1>

                <p>Your Needs, Our Technology.</p>
            </div>

            <div class="login-heading">
                <h2>Selamat Datang!</h2>

                <p>
                    Silakan login untuk mengakses
                    sistem inventaris TECHMART.
                </p>
            </div>

            <?php if ($error): ?>
                <div class="login-error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="login-form-group">
                    <label for="username">Username</label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Masukkan username"
                        required
                    >
                </div>

                <div class="login-form-group">
                    <label for="password">Password</label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                    >
                </div>

                <button type="submit" class="login-button">
                    Login Sekarang
                </button>

            </form>

            <div class="login-footer">
                &copy; <?= date('Y') ?> TECHMART.
                All Rights Reserved.
            </div>

        </div>

    </div>

</body>
</html>