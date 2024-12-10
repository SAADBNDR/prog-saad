<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "car_park");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$result = $mysqli->query("SELECT * FROM users WHERE id='$user_id'");
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $username = $_POST['username'];

    $stmt = $mysqli->prepare("UPDATE users SET name=?, phone=?, email=?, username=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $phone, $email, $username, $user_id);
    $stmt->execute();

    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>الملف الشخصي</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('img/bb.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #333;
        }
        body {
            background-color: #f5f5f5;
            font-family: 'Arial', sans-serif;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #007bff;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-dark">
    <a class="navbar-brand text-white">احجز موقفك من بيتك وانت مرتاح</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a class="nav-link text-white" href="index.php">الصفحة الرئيسية</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="my_bookings.php">حجوزاتي</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="new_booking.php">حجز جديد</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="profile.php">الملف الشخصي</a></li>
        </ul>
    </div>
    <form class="form-inline my-2 my-lg-0" action="logout.php" method="POST">
        <button class="btn btn-outline-light my-2 my-sm-0" type="submit">تسجيل الخروج</button>
    </form>
</nav>

<div class="container">
    <h2>الملف الشخصي</h2>
    <form method="POST">
        <div class="form-group">
            <label>الاسم:</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div class="form-group">
            <label>رقم الهاتف:</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>" required>
        </div>
        <div class="form-group">
            <label>البريد الإلكتروني:</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="form-group">
            <label>اسم المستخدم:</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
    </form>
</div>

</body>
</html>
