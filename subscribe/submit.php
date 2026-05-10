<?php
require 'config.php';

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $full_name = htmlspecialchars(strip_tags(trim($_POST['full_name'] ?? '')));
    $phone = htmlspecialchars(strip_tags(trim($_POST['phone'] ?? '')));
    $student_number = htmlspecialchars(strip_tags(trim($_POST['student_number'] ?? '')));
    $skills = htmlspecialchars(strip_tags(trim($_POST['skills'] ?? '')));

    if (!preg_match('/^[0-9]{11}$/', $phone))
    {
        $message = "شماره تلفن باید 11 رقم و فقط شامل عدد باشد.";
    }
    elseif (!preg_match('/^[0-9]+$/', $student_number))
    {
        $message = "شماره دانشجویی فقط باید شامل عدد باشد.";
    }
    elseif (!empty($full_name) && !empty($phone) && !empty($student_number))
    {
        $check_sql = "SELECT COUNT(*) FROM users WHERE student_number = :student_number";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([':student_number' => $student_number]);

        if ($check_stmt->fetchColumn() > 0)
        {
            $message = "!خطا: این شماره دانشجویی قبلاً ثبت نام کرده است";
        }
        else
        {
            $sql = "INSERT INTO users (full_name, phone, student_number, skills)
                    VALUES (:full_name, :phone, :student_number, :skills)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':full_name' => $full_name,
                ':phone' => $phone,
                ':student_number' => $student_number,
                ':skills' => $skills
            ]);

            $message = "ثبت نام با موفقیت انجام شد.";
        }
    }
    else
    {
        $message = "لطفاً تمامی فیلدهای اجباری را پر کنید.";
    }
}
?>

<?php require 'header.php'; ?>


<main class="min-h-screen flex items-center justify-center">

<div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg dark:bg-gray-800">
    <h2 class="text-3xl font-bold text-center mb-6 text-gray-800 dark:text-white">
        فرم ثبت نام
    </h2>

    <?php if(!empty($message)): ?>
        <p class="
            p-3 
            rounded-lg 
            mb-4 
            text-center 
            font-medium
            <?= strpos($message, 'موفقیت') !== false 
                ? 'bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200' 
                : 'bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-200' ?>
        ">
           <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form action="" method="POST">

        <label class="block mb-2 text-right text-gray-700 font-medium dark:text-gray-300">
            :نام و نام خانوادگی
        </label>
        <input 
            type="text" 
            name="full_name"
            class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            required>

        <label class="block mb-2 text-right text-gray-700 font-medium dark:text-gray-300">
            :شماره تلفن
        </label>
        <input 
            type="tel" 
            name="phone"
            class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            required>

        <label class="block mb-2 text-right text-gray-700 font-medium dark:text-gray-300">
            :شماره دانشجویی
        </label>
        <input 
            type="text"
            name="student_number"
            class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            required>

        <label class="block mb-2 text-right text-gray-700 font-medium dark:text-gray-300">
            :مهارت‌ها
        </label>
        <textarea 
            name="skills"
            rows="5"
            class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </textarea>

        <button 
            type="submit"
            class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition duration-300 dark:bg-blue-500 dark:hover:bg-blue-600">
            ثبت اطلاعات
        </button>
    </form>
</div>
</main>

<?php require 'footer.php'; ?>
