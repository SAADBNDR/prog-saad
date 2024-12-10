<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "car_park");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $mysqli->query("SELECT * FROM users WHERE username='$username'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php");
            exit();
        } else {
            $error_message = "البيانات المدخلة غير صحيحة!";
        }
    } else {
        $error_message = "البيانات المدخلة غير صحيحة!";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
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
            background-color: #f4f4f9;
            font-family: 'Arial', sans-serif;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 25px;
            padding: 15px;
        }
        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
            width: 100%;
            padding: 15px;
            border-radius: 25px;
        }
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .alert {
            margin-top: 20px;
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>تسجيل الدخول</h2>
    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="username">اسم المستخدم</label>
            <input type="text" id="username" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">كلمة المرور</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">تسجيل الدخول</button>
    </form>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
    <?php endif; ?>

    <div class="footer">
        <p>ليس لديك حساب؟ <a href="register.php">تسجيل حساب جديد</a></p>
    </div>
</div>

</body>
</html>
