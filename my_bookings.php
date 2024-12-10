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

$query = "SELECT bookings.*, parkings.location, parkings.id AS parking_number, users.name, users.email, users.phone 
          FROM bookings 
          JOIN parkings ON bookings.parking_id = parkings.id 
          JOIN users ON bookings.user_id = users.id 
          WHERE bookings.user_id = '$user_id' 
          ORDER BY bookings.start_datetime DESC";
$result = $mysqli->query($query);

if (isset($_POST['cancel_booking']) && isset($_POST['booking_id'])) {
    $booking_id = intval($_POST['booking_id']); 

    if ($mysqli->query("DELETE FROM bookings WHERE id='$booking_id' AND user_id='$user_id'")) {
    
        echo "<script>alert('تم إلغاء الحجز بنجاح'); window.location.href='my_bookings.php';</script>";
        exit();
    } else {
      
        echo "<script>alert('فشل في إلغاء الحجز، يرجى المحاولة لاحقاً'); window.location.href='my_bookings.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حجوزاتي</title>
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

        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
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

        .footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .table-bordered {
            border: 1px solid #ddd;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
    <script>
        function confirmCancellation(bookingId) {
            if (confirm("هل أنت متأكد من أنك تريد إلغاء هذا الحجز؟")) {
                document.getElementById('cancelBookingForm-' + bookingId).submit();
            }
        }
    </script>
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
    <div class="card">
        <div class="card-header text-center">
            <h3>حجوزاتي</h3>
        </div>
        <div class="card-body">
            <?php if ($result->num_rows > 0): ?>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>رقم الموقف</th>
                            <th>موقف</th>
                            <th>تاريخ ووقت البدء</th>
                            <th>تاريخ ووقت الانتهاء</th>
                            <th>التكلفة الإجمالية</th>
                            <th>حالة الحجز</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($booking = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $booking['parking_number']; ?></td>
                                <td><?php echo $booking['location']; ?></td>
                                <td><?php echo date("Y-m-d H:i", strtotime($booking['start_datetime'])); ?></td>
                                <td><?php echo date("Y-m-d H:i", strtotime($booking['end_datetime'])); ?></td>
                                <td><?php echo number_format($booking['total_price'], 2); ?> ريال</td>
                                <td><?php echo $booking['status'] == 'booked' ? 'محجوز' : 'غير محجوز'; ?></td>
                                <td>
                                   
                                    <form id="cancelBookingForm-<?php echo $booking['id']; ?>" method="POST" style="display:inline;">
                                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                        <input type="hidden" name="cancel_booking" value="1">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmCancellation(<?php echo $booking['id']; ?>)">إلغاء</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">لا يوجد لديك أي حجوزات حالياً.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="text-center mt-4">
        <button class="btn btn-primary" onclick="window.print()">طباعة الحجز</button>
    </div>
</div>

</body>
</html>  