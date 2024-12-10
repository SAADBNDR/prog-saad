<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "car_park");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM parkings WHERE available_spots > 0";
$parkings_result = $mysqli->query($query);

if ($parkings_result === false) {
    die("استعلام المواقف فشل: " . $mysqli->error); 
}

if ($parkings_result->num_rows == 0) {
    echo "لا توجد مواقف متاحة حالياً."; 
    exit;
}

$user_id = $_SESSION['user_id'];
$result = $mysqli->query("SELECT * FROM users WHERE id='$user_id'");
$user = $result->fetch_assoc();

$total_price = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $parking_id = $_POST['parking_id'];
    $start_datetime = $_POST['start_datetime'];
    $end_datetime = $_POST['end_datetime'];

    $check_query = "SELECT available_spots, price FROM parkings WHERE id='$parking_id'";
    $check_result = $mysqli->query($check_query);
    $parking = $check_result->fetch_assoc();

    if ($parking['available_spots'] > 0) {
        $start_time = strtotime($start_datetime);
        $end_time = strtotime($end_datetime);

        $duration = ($end_time - $start_time) / 3600; 

        $total_price = $duration * $parking['price'];

        $insert_query = "INSERT INTO bookings (user_id, parking_id, start_datetime, end_datetime, total_price, status)
                         VALUES ('$user_id', '$parking_id', '$start_datetime', '$end_datetime', '$total_price', 'booked')";
        $mysqli->query($insert_query);

        $update_query = "UPDATE parkings SET available_spots = available_spots - 1 WHERE id = '$parking_id'";
        $mysqli->query($update_query);

        header("Location: my_bookings.php");
        exit();
    } else {
        echo "عذراً، لا توجد مواقف متاحة في هذا الموقع.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حجز جديد</title>
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

        .footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        .form-group {
            background-color: rgba(0, 0, 0, 0.5); 
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 15px;
        }

        .form-group label, .form-group input {
            color: white; 
        }

        h2, p {
            color: white;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.8); 
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.8); 
            color: #333;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            color: white;
        }

    </style>
    <script>
        function calculatePrice() {
            var startDatetime = document.getElementById("start_datetime").value;
            var endDatetime = document.getElementById("end_datetime").value;
            var parkingPrice = parseFloat(document.getElementById("parking_price").value);

            if (startDatetime && endDatetime && parkingPrice) {
                var startTime = new Date(startDatetime);
                var endTime = new Date(endDatetime);
                var duration = (endTime - startTime) / 3600000; 

                if (duration > 0) {
                    var totalPrice = duration * parkingPrice;
                    document.getElementById("total_price").value = totalPrice.toFixed(2);
                } else {
                    document.getElementById("total_price").value = "0.00";
                }
            }
        }

        function updateParkingPrice() {
            var selectedOption = document.getElementById("parking_id").selectedOptions[0];
            var parkingPrice = parseFloat(selectedOption.getAttribute("data-price"));
            document.getElementById("parking_price").value = parkingPrice; 
            calculatePrice();
        }
    </script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="index.php">احجز موقفك من بيتك وانت مرتاح</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="index.php">الصفحة الرئيسية</a></li>
            <li class="nav-item"><a class="nav-link" href="my_bookings.php">حجوزاتي</a></li>
            <li class="nav-item"><a class="nav-link" href="new_booking.php">حجز جديد</a></li>
            <li class="nav-item"><a class="nav-link" href="profile.php">الملف الشخصي</a></li>
          
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">لوحة التحكم الادمن</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <form class="form-inline my-2 my-lg-0" action="logout.php" method="POST">
        <button class="btn btn-outline-danger my-2 my-sm-0" type="submit">تسجيل الخروج</button>
    </form>
</nav>

<div class="container">
    <h2 class="text-center mt-5">حجز موقف جديد</h2>

    <form method="POST">
        <div class="form-group">
            <label for="parking_id">اختر الموقف</label>
            <select class="form-control" id="parking_id" name="parking_id" onchange="updateParkingPrice()" required>
                <option value="">اختر موقف</option>
                <?php while ($parking = $parkings_result->fetch_assoc()): ?>
                    <option value="<?= $parking['id'] ?>" data-price="<?= $parking['price'] ?>">
                        <?= $parking['location'] ?> - <?= number_format($parking['price'], 2) ?> ريال (<?= $parking['available_spots'] ?> مواقف متاحة)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="start_datetime">تاريخ ووقت البدء</label>
            <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" onchange="calculatePrice()" required>
        </div>

        <div class="form-group">
            <label for="end_datetime">تاريخ ووقت الانتهاء</label>
            <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" onchange="calculatePrice()" required>
        </div>

        <div class="form-group">
            <label for="total_price">التكلفة الإجمالية</label>
            <input type="text" class="form-control" id="total_price" name="total_price" value="<?= number_format($total_price, 2) ?>" readonly>
        </div>

        <input type="hidden" id="parking_price" value="0.00"> 

        <button type="submit" class="btn btn-primary btn-block">إتمام الحجز</button>
    </form>
</div>

</body>
</html>
