<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "car_park");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit();
}

$users_result = $mysqli->query("SELECT * FROM users");

$bookings_result = $mysqli->query("SELECT bookings.*, users.username, parkings.location AS parking_location 
                                   FROM bookings 
                                   JOIN users ON bookings.user_id = users.id 
                                   JOIN parkings ON bookings.parking_id = parkings.id");

$parkings_result = $mysqli->query("SELECT parkings.*, 
                                          (SELECT COUNT(*) FROM bookings WHERE bookings.parking_id = parkings.id) AS booked_spots 
                                   FROM parkings");
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم الأدمن</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('img/bb.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #333;
        }
        .container {
            margin-top: 30px;
        }
        .card-header {
            background-color: #343a40;
            color: white;
        }
        .table th, .table td {
            text-align: center;
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .card-body {
            background-color: #ffffff;
        }
        .section-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #343a40;
        }
    </style>
    <script>
        function confirmDelete(message) {
            return confirm(message);
        }
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-dark">
        <a class="navbar-brand text-white" href="admin_dashboard.php">لوحة تحكم الأدمن</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link text-white" href="index.php">الصفحة الرئيسية</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="my_bookings.php">حجوزاتي</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="new_booking.php">حجز جديد</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="profile.php">الملف الشخصي</a></li>
            </ul>
        </div>
        <form class="form-inline my-2 my-lg-0" action="logout.php" method="POST">
            <button class="btn btn-outline-danger my-2 my-sm-0" type="submit">تسجيل الخروج</button>
        </form>
    </nav>

    <div class="container">
        <h2 class="section-title">لوحة تحكم الأدمن</h2>

        <div class="card mb-4">
            <div class="card-header">
                <h4>المستخدمون</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>اسم المستخدم</th>
                            <th>الهاتف</th>
                            <th>البريد الإلكتروني</th>
                            <th>الدور</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $users_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['name']) ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['phone']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= $user['role'] == 1 ? 'أدمن' : 'مستخدم عادي' ?></td>
                                <td>
                                    <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">تعديل</a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirmDelete('هل أنت متأكد أنك تريد حذف هذا المستخدم؟');">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button type="submit" name="delete_user" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h4>الحجوزات</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>اسم المستخدم</th>
                            <th>تاريخ بدء الحجز</th>
                            <th>تاريخ انتهاء الحجز</th>
                            <th>الموقع</th>
                            <th>السعر الإجمالي</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($booking = $bookings_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($booking['username']) ?></td>
                                <td><?= htmlspecialchars($booking['start_datetime']) ?></td>
                                <td><?= htmlspecialchars($booking['end_datetime']) ?></td>
                                <td><?= htmlspecialchars($booking['parking_location']) ?></td>
                                <td><?= htmlspecialchars($booking['total_price']) ?> ريال</td>
                                <td>
                                    <a href="edit_booking.php?id=<?= $booking['id'] ?>" class="btn btn-warning btn-sm">تعديل</a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirmDelete('هل أنت متأكد أنك تريد حذف هذا الحجز؟');">
                                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                        <button type="submit" name="delete_booking" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h4>المواقف</h4>
            </div>
            <div class="card-body">
                <a href="add_parking.php" class="btn btn-success mb-3">إضافة موقف جديد</a>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>الموقع</th>
                            <th>السعر</th>
                            <th>المحجوز</th>
                            <th>المتاح</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($parking = $parkings_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($parking['location']) ?></td>
                                <td><?= htmlspecialchars($parking['price']) ?> ريال</td>
                                <td><?= $parking['booked_spots'] ?> موقف محجوز</td>
                                <td><?= 50 - $parking['booked_spots'] ?> موقف متاح</td>
                                <td>
                                    <a href="edit_parking.php?id=<?= $parking['id'] ?>" class="btn btn-warning btn-sm">تعديل</a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirmDelete('هل أنت متأكد أنك تريد حذف هذا الموقف؟');">
                                        <input type="hidden" name="parking_id" value="<?= $parking['id'] ?>">
                                        <button type="submit" name="delete_parking" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $mysqli->query("DELETE FROM users WHERE id = '$user_id'");
    header("Location: admin_dashboard.php");
}

if (isset($_POST['delete_booking'])) {
    $booking_id = $_POST['booking_id'];
    $mysqli->query("DELETE FROM bookings WHERE id = '$booking_id'");
    header("Location: admin_dashboard.php");
}

if (isset($_POST['delete_parking'])) {
    $parking_id = $_POST['parking_id'];
    $mysqli->query("DELETE FROM parkings WHERE id = '$parking_id'");
    header("Location: admin_dashboard.php");
}
?>