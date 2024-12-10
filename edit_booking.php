<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "car_park");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];
    $result = $mysqli->query("SELECT * FROM bookings WHERE id='$booking_id'");
    if ($result && $result->num_rows > 0) {
        $booking = $result->fetch_assoc();
    } else {
        die("الحجز غير موجود.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $start_datetime = $_POST['start_datetime'];
        $end_datetime = $_POST['end_datetime'];
        $parking_location = htmlspecialchars($_POST['parking_location']);
        $total_price = $_POST['total_price'];

        $stmt = $mysqli->prepare("UPDATE bookings SET start_datetime=?, end_datetime=?, parking_location=?, total_price=? WHERE id=?");
        $stmt->bind_param("ssssi", $start_datetime, $end_datetime, $parking_location, $total_price, $booking_id);

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
    <title>تعديل الحجز</title>
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
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
        }

        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .alert {
            text-align: center;
            font-size: 1rem;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">احجز موقفك من بيتك وانت مرتاح</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="index.php">الصفحة الرئيسية</a></li>
            <li class="nav-item"><a class="nav-link" href="my_bookings.php">حجوزاتي</a></li>
            <li class="nav-item"><a class="nav-link" href="new_booking.php">حجز جديد</a></li>
            <li class="nav-item"><a class="nav-link" href="profile.php">الملف الشخصي</a></li>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">لوحة تحكم الأدمن</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <form class="form-inline my-2 my-lg-0" action="logout.php" method="POST">
        <button class="btn btn-outline-danger my-2 my-sm-0" type="submit">تسجيل الخروج</button>
    </form>
</nav>

<div class="container">
    <h2 class="text-center mb-4">تعديل الحجز</h2>
    <form method="POST">
        <div class="form-group">
            <label>تاريخ بدء الحجز:</label>
            <input type="datetime-local" name="start_datetime" class="form-control" value="<?= htmlspecialchars($booking['start_datetime']) ?>" required>
        </div>
        <div class="form-group">
            <label>تاريخ انتهاء الحجز:</label>
            <input type="datetime-local" name="end_datetime" class="form-control" value="<?= htmlspecialchars($booking['end_datetime']) ?>" required>
        </div>
        <div class="form-group">
            <label>الموقع:</label>
            <input type="text" name="parking_location" class="form-control" value="<?= htmlspecialchars($booking['parking_location']) ?>" required>
            <?php
                if (empty($booking['parking_location'])) {
                    echo "<div class='alert alert-warning mt-2'>الموقع غير موجود.</div>";
                }
            ?>
        </div>
        <div class="form-group">
            <label>السعر الإجمالي:</label>
            <input type="number" name="total_price" class="form-control" value="<?= htmlspecialchars($booking['total_price']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">تحديث</button>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-3"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
    </form>
</div>

</body>
</html>
