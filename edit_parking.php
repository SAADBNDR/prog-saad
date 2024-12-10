<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "car_park");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $parking_id = $_GET['id'];

    $parking_result = $mysqli->query("SELECT * FROM parkings WHERE id = '$parking_id'");

    if (!$parking_result || $parking_result->num_rows == 0) {
        die("الموقف غير موجود أو حدث خطأ أثناء جلب البيانات.");
    }

    $parking = $parking_result->fetch_assoc();
} else {
    die("لم يتم توفير معرف الموقف.");
}

if (isset($_POST['update_parking'])) {
    $location = $_POST['location'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    $update_query = "UPDATE parkings SET location = '$location', price = '$price', status = '$status' WHERE id = '$parking_id'";
    if (!$mysqli->query($update_query)) {
        die("حدث خطأ أثناء تحديث البيانات: " . $mysqli->error);
    }
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الموقف</title>
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
        }

        .card {
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .card-header {
            background-color: #28a745;
            color: white;
            font-size: 1.25rem;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="admin_dashboard.php">لوحة تحكم الأدمن</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="index.php">الصفحة الرئيسية</a></li>
            <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">لوحة التحكم</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">تسجيل الخروج</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="card">
        <div class="card-header text-center">
            <h3>تعديل بيانات الموقف</h3>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label for="location">الموقع</label>
                    <input type="text" class="form-control" id="location" name="location" 
                           value="<?= htmlspecialchars($parking['location']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="price">السعر</label>
                    <input type="number" class="form-control" id="price" name="price" 
                           value="<?= htmlspecialchars($parking['price']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="status">الحالة</label>
                    <select class="form-control" id="status" name="status">
                        <option value="available" <?= $parking['status'] == 'available' ? 'selected' : '' ?>>متاح</option>
                        <option value="booked" <?= $parking['status'] == 'booked' ? 'selected' : '' ?>>محجوز</option>
                    </select>
                </div>
                <button type="submit" name="update_parking" class="btn btn-primary">تحديث</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
