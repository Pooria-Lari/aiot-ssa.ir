<?php
session_start();
require 'config.php'; // اتصال به دیتابیس

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // جلوگیری از ورود خالی
    if (empty($username) || empty($password)) {
        $error = 'لطفاً نام کاربری و رمز عبور را وارد کنید.';
    } else {
        // جستجوی کاربر در دیتابیس
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $admin = $stmt->fetch();

        // بررسی رمز عبور با هش
        if ($admin && password_verify($password, $admin['password'])) {
            // ورود موفق
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user'] = $admin['username'];
            $_SESSION['admin_id'] = $admin['id'];

            // بازسازی سشن برای امنیت بیشتر
            session_regenerate_id(true);

            header("Location: admin.php");
            exit;
        } else {
            $error = 'نام کاربری یا رمز عبور اشتباه است.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>ورود مدیر</title>
    <style>
        * { box-sizing: border-box; }
        body {
            direction: rtl;
            font-family: Tahoma, sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #2563eb, #1e293b);
        }
        .login-box {
            width: 100%;
            max-width: 380px;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .login-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #1e293b;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #1d4ed8;
        }
        .error {
            color: #dc2626;
            text-align: center;
            margin-top: 15px;
            padding: 10px;
            background: #fee2e2;
            border-radius: 8px;
            font-size: 14px;
        }
        .footer-text {
            text-align: center;
            margin-top: 20px;
            color: #64748b;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>🔐 ورود به پنل مدیریت</h2>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <div class="form-group">
                <input
                    type="text"
                    name="username"
                    placeholder="👤 نام کاربری"
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    required
                    autofocus>
            </div>
            <div class="form-group">
                <input
                    type="password"
                    name="password"
                    placeholder="🔑 رمز عبور"
                    required>
            </div>
            <button type="submit">ورود به پنل</button>
        </form>

        <div class="footer-text">
            <!-- در صورت نیاز لینک بازیابی رمز -->
        </div>
    </div>
</body>
</html>