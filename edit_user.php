<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "car_park");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $result = $mysqli->query("SELECT * FROM users WHERE id='$user_id'");
    $user = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $role = $_POST['role'];

        $stmt = $mysqli->prepare("UPDATE users SET name=?, phone=?, email=?, username=?, role=? WHERE id=?");
        $stmt->bind_param("ssssii", $name, $phone, $email, $username, $role, $user_id);
        
        if ($stmt->execute()) {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "حدث خطأ أثناء تعديل البيانات.";
        }
    }
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل المستخدم</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('img/bb.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #333;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand {
            color: #fff !important;
            font-weight: bold;
        }
        .navbar-nav .nav-link {
            color: #ddd !important;
        }
        .navbar-nav .nav-link:hover {
            color: #fff !important;
        }
        .container {
            margin-top: 50px;
            max-width: 600px;
        }
        .card {
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 1.25rem;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard.php">لوحة تحكم الادمن</a>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                تعديل المستخدم
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
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
                    <div class="form-group">
                        <label>الدور:</label>
                        <select name="role" class="form-control">
                            <option value="0" <?= $user['role'] == 0 ? 'selected' : '' ?>>مستخدم عادي</option>
                            <option value="1" <?= $user['role'] == 1 ? 'selected' : '' ?>>أدمن</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">تحديث</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
