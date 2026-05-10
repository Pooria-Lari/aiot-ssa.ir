<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) 
{
    header("Location: login.php");
    exit;
}

require 'config.php';

$success = '';

if(isset($_SESSION['success']))
{
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

if (isset($_GET['delete']))
{
    $id = (int)$_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['success'] = 'دانشجو با موفقیت حذف شد.';

    header("Location: admin.php");
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'logout') 
{
    session_destroy();
    header("Location: login.php");
    exit;
}

$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM users
        WHERE full_name LIKE :search1
        OR student_number LIKE :search2
        ORDER BY id DESC";

$stmt = $pdo->prepare($sql);

$stmt->execute
([
    ':search1' => "%$search%",
    ':search2' => "%$search%"
]);

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalUsers = count($users);
?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <title>پنل ادمین</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        table, th, td { border:1px solid #ccc; padding:10px; text-align:center; }
        th { background-color: #f2f2f2; }
        .logout-btn { color: red; float: left; text-decoration: none; font-weight: bold; }
    </style>

</head>

<body style="direction: rtl; text-align: right; padding: 20px;">
<div class="container mt-4">

<a href="?action=logout" class="btn btn-danger float-start">
    خروج از پنل
</a>

<h2>لیست دانشجویان ثبت نام شده</h2>
<p>خوش آمدید، <?= htmlspecialchars($_SESSION['admin_user']) ?> عزیز</p>
 <?php if(!empty($success)): ?>
<div class="alert alert-success">
    <?= htmlspecialchars($success) ?>
</div>
<?php endif; ?>

   <div class="card mb-3">
       <div class="card-body">
           <h5 class="card-title">آمار سیستم</h5>
           <p class="card-text">
               تعداد کل دانشجویان ثبت‌نام شده:
               <strong><?= $totalUsers ?></strong>
           </p>
       </div>
   </div>

<form method="GET" class="row g-2 mb-3">

    <div class="col-md-4">
        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="جستجو نام یا شماره دانشجویی"
            value="<?= htmlspecialchars($search) ?>">
    </div>

    <div class="col-auto">
        <button type="submit" class="btn btn-primary">
            جستجو
        </button>
    </div>

</form>

<table class="table table-bordered table-striped table-hover">
    <tr>
        <th>ردیف</th>
        <!-- <th>شناسه</th> -->
        <th>نام</th>
        <th>تلفن</th>
        <th>شماره دانشجویی</th>
        <th>مهارت ها</th>
        <th>زمان ثبت</th>
        <th>ویرایش</th>
        <th>حذف</th>
    </tr>

    <?php $row = 1; ?>

    <?php foreach($users as $user): ?>
    <tr>
        <td><?= $row++ ?></td>
        <?php
        // echo "<td>{$user['id']}</td>";
        ?>
        <td><?= htmlspecialchars($user['full_name']) ?></td>
        <td><?= htmlspecialchars($user['phone']) ?></td>
        <td><?= htmlspecialchars($user['student_number']) ?></td>
        <td><?= htmlspecialchars($user['skills']) ?></td>
        <td><?= $user['created_at'] ?></td>
        <td>
             <a href="edit.php?id=<?= $user['id'] ?>"
                class="btn btn-warning btn-sm">
                 ویرایش
             </a>
        </td>

        <td>
            <a href="?delete=<?= $user['id'] ?>"
               class="btn btn-danger btn-sm"
               onclick="return confirm('آیا از حذف این رکورد مطمئن هستید؟')">
                حذف
            </a>
        </td>

    </tr>
    <?php endforeach; ?>
</table>

</div>
</body>
</html>