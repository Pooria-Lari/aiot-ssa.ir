<?php
session_start();
require 'config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM admins WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username]);
    $admin = $stmt->fetch();

    // بررسی پسورد با مکانیزم هش امن PHP
    if ($admin && password_verify($password, $admin['password'])) 
    {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $admin['username'];
        header("Location: admin.php");
        exit;
    } 
    else 
    {
        $error = "نام کاربری یا رمز عبور اشتباه است.";
    }
}
?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <title>ورود به پنل مدیریت</title>
</head>

<body style="direction: rtl; text-align: center; margin-top: 100px;">

<h2>ورود به پنل ادمین</h2>

<?php if($error): ?>
<p style="color:red;"><?= $error ?></p>
<?php endif; ?>

<form method="POST" style="display: inline-block; border: 1px solid #ccc; padding: 20px;">
   
    <label>نام کاربری:</label><br>
    <input type="text" name="username" required><br><br>
    
    <label>رمز عبور:</label><br>
    <input type="password" name="password" required><br><br>
    
    <button type="submit">ورود</button>
</form>

</body>
</html>