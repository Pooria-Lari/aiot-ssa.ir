<?php
$host = 'localhost';
$db   = 'yordatabase';
$user = 'yourusername';
$pass = 'yourpassword';
$charset = 'utf8mb4';

try 
{
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, 
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $user, $pass, 
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $sql = 
    "
        CREATE TABLE IF NOT EXISTS users 
        (
            id INT AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(100) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            student_number VARCHAR(20) NOT NULL,
            skills TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    $pdo->exec($sql);
    $message = "نصب با موفقیت انجام شد.";
} 
catch (PDOException $e) 
{
    $message = "خطا در نصب: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>نصب پروژه</title>

    <?php if (strpos($message, 'موفقیت') !== false): ?>
        <meta http-equiv="refresh" content="3;url=index.php">
    <?php endif; ?>

    <style>
        body 
        {
            font-family: Tahoma, sans-serif;
            direction: rtl;
            background: #f7f7f7;
            padding: 40px;
        }
        .box 
        {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="box">
        <h1>نصب پروژه</h1>
        <p class="<?php echo strpos($message, 'موفقیت') !== false ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </p>

        <?php if (strpos($message, 'موفقیت') !== false): ?>
            <p>تا 3 ثانیه دیگر به صفحه اصلی منتقل می‌شوید...</p>
        <?php endif; ?>
    </div>
</body>
</html>