<?php

session_start();

if (!isset($_SESSION['admin_logged_in']))
{
    header("Location: login.php");
    exit;
}

require 'config.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user)
{
    die("کاربر پیدا نشد");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $student_number = $_POST['student_number'];
    $skills = $_POST['skills'];

    if (!preg_match('/^[0-9]{11}$/', $phone))
    {
        $error = "شماره تلفن باید 11 رقم و فقط شامل عدد باشد.";
    }

    if (!preg_match('/^[0-9]+$/', $student_number))
    {
        $error = "شماره دانشجویی فقط باید شامل عدد باشد.";
    }
    else 
    {
	    $stmt = $pdo->prepare
        ("
            UPDATE users
            SET full_name = ?,
                phone = ?,
                student_number = ?,
                skills = ?
            WHERE id = ?
        ");

        $stmt->execute
        ([
            $full_name,
            $phone,
            $student_number,
            $skills,
            $id
        ]);

        $_SESSION['success'] = 'اطلاعات دانشجو با موفقیت ویرایش شد.';

        header("Location: admin.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="fa">
<head>
<meta charset="UTF-8">
<title>ویرایش دانشجو</title>
<style>

body
{
    direction:rtl;
    font-family:tahoma;
    background:#f4f6f9;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    margin:0;
}

.container
{
    width:500px;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,.15);
}

h2
{
    text-align:center;
}

label
{
    display:block;
    margin-bottom:5px;
    font-weight:bold;
}

input,
textarea
{
    width:100%;
    padding:10px;
    margin-bottom:15px;
    border:1px solid #ccc;
    border-radius:8px;
    box-sizing:border-box;
}

textarea
{
    height:120px;
}

button
{
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    background:#2563eb;
    color:white;
    cursor:pointer;
}

button:hover
{
    background:#1d4ed8;
}

</style>
</head>

<body>

<div class="container">

<h2>ویرایش اطلاعات دانشجو</h2>

<?php if(!empty($error)): ?>
<div
style="
background:#f8d7da;
color:#721c24;
padding:10px;
border-radius:8px;
margin-bottom:15px;
text-align:center;
font-weight:bold;
">
    <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<form method="POST">

    <p>نام:</p>
    <input 
           type="text"
           name="full_name"
           value="<?= htmlspecialchars($user['full_name']) ?>">

    <p>تلفن:</p>
    <input 
           type="tel"
           name="phone"
           pattern="[0-9]{11}"
           title="لطفاً شماره تلفن 11 رقمی وارد کنید"
           value="<?= htmlspecialchars($user['phone']) ?>">

    <p>شماره دانشجویی:</p>
    <input 
           type="text"
           name="student_number"
           value="<?= htmlspecialchars($user['student_number']) ?>">

    <p>مهارت ها:</p>
    <textarea name="skills"><?= htmlspecialchars($user['skills']) ?></textarea>

    <br><br>

    <button 
          type="submit">
        ذخیره تغییرات
    </button>

</form>

</body>
</html>