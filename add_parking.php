<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "car_park");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit();
}

$result = $mysqli->query("SELECT total_parking_spots FROM settings WHERE id = 1");
$settings = $result->fetch_assoc();
$max_parking_spots = $settings['total_parking_spots'];

$current_parkings_result = $mysqli->query("SELECT COUNT(*) AS current_count FROM parkings");
$current_parkings = $current_parkings_result->fetch_assoc();
$current_parkings_count = $current_parkings['current_count'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($current_parkings_count >= $max_parking_spots) {
        $error = "تم الوصول إلى الحد الأقصى للمواقف المسموح بها.";
    } else {
        $location = $_POST['location'];
        $price = $_POST['price'];
        $status = $_POST['status'];

        $stmt = $mysqli->prepare("INSERT INTO parkings (location, price, status) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $location, $price, $status);

        if ($stmt->execute()) {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "حدث خطأ أثناء إضافة الموقف.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة موقف جديد</title>
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
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard.php">لوحة تحكم الأدمن</a>
                </li>              
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                إضافة موقف جديد
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="form-group">
                        <label>الموقع:</label>
                        <input type="text" name="location" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>السعر:</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>الحالة:</label>
                        <select name="status" class="form-control">
                            <option value="available">متاح</option>
                            <option value="booked">محجوز</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">إضافة الموقف</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
