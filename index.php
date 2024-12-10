<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصفحة الرئيسية</title>
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

        .welcome-text-container {
            background-color: rgba(0, 0, 0, 0.5); 
            padding: 20px;
            border-radius: 10px;
            max-width: 80%;
            margin: 0 auto;
            text-align: center;
        }

        .welcome-text {
            color: #fff;
            font-size: 2rem;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7); 
        }

        .welcome-subtext {
            color: #f5f5f5;
            font-size: 1.2rem;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
        }

        .footer-text {
            background-color: rgba(0, 0, 0, 0.5); 
            color: #fff;
            padding: 10px;
            font-size: 1rem;
            border-radius: 5px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

       
        .footer-text p {
            margin: 0;
        }
    </style>
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
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">لوحة تحكم الأدمن</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <form class="form-inline my-2 my-lg-0" action="logout.php" method="POST">
        <button class="btn btn-outline-danger my-2 my-sm-0" type="submit">تسجيل الخروج</button>
    </form>
</nav>

<div class="container">
    <div class="welcome-text-container">
        <h2 class="welcome-text">موقفي</h2>
        <p class="welcome-subtext">أهلاً وسهلاً بك عزيزنا العميل يمكنك حجز موقفك الخاص الان وبكل سهولة .</p>
    </div>
</div>

<div class="footer-text">
    <p>مشروع التخرج</p>
    <p>سعد بندر تركي القحطاني</p>
    <p>443103262</p>
</div>

</body>
</html>
