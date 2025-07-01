<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login/login.php");
    exit();
}

include '../connection/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    die('Admin ID not provided');
}

$admin_id = $_POST['id'];
$stmt = $conn->prepare("SELECT * FROM admin WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Admin not found');
}

$admin = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>GONI ICT ID Card</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            gap: 30px;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            background: black;
        }

        .card {
            width: 250px;
            height: 400px;
            background: #fff;
            border-top: 15px solid rgb(68, 145, 227);
            border-bottom: 15px solid rgb(68, 145, 227);
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
            position: relative;
        }

        .log {
            text-align: center;
            margin-top: 5px;
            position: absolute;
            top: -45px;
            left: 2%;

        }

        .log img {
            width: 250px;
            height: 160px;
        }

        .photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid rgb(68, 145, 227);
            margin-top: 10px;
            position: absolute;
            top: 17%;
        }

        .photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .info {
            text-align: center;
            font-size: 14px;
            margin: 10px 0;
            position: absolute;
            top: 37%;

        }

        .info span {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .role {
            background: rgb(68, 145, 227);
            color: #fff;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            margin: 5px 0;
            position: absolute;
            top: 58%;
        }

        .qr {
            margin-top: auto;

        }

        .qr img {
            width: 80px;
            height: 80px;

        }

        .back-content {
            font-size: 11px;
            text-align: center;
            margin-top: 80px;
            font-weight: 600;
            line-height: 1.5;
        }

        @media print {
            body {
                background: black;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .card {
                box-shadow: black;
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }

        .log_watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.2;
            /* Faded watermark */
            z-index: 0;
            /* Behind everything */
        }

        .log_watermark img {
            width: 300px;
            height: auto;
        }

        .back-content,
        .qr {
            position: relative;
            z-index: 1;
            /* Keep text and QR above watermark */
        }
    </style>
</head>

<body>

    <!-- FRONT SIDE -->
    <div class="card">
        <!-- Logo -->
        <div class="log">
            <img src="../assets/images/gooni_ict.png" alt="GONI ICT Logo">
        </div>

        <!-- Photo -->
        <div class="photo">
            <img src="../uploads/<?= htmlspecialchars($admin['image']) ?>" alt="Admin Photo" alt="Admin Photo">
        </div>

        <!-- Info -->
        <div class="info">
            <span><?= htmlspecialchars($admin['name']) ?></span>
            ID: GONI ICT000<?= htmlspecialchars($admin['admin_id']) ?><br>
            <?= htmlspecialchars($admin['email']) ?>
        </div>

        <!-- Role -->
        <div class="role"><?= htmlspecialchars($admin['role']) ?></div>

        <!-- QR Code -->
        <div class="qr">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=<?= urlencode('Admin ID: '.$admin['admin_id'].', Name: '.$admin['name']) ?>" alt="QR Code">
        </div>
    </div>


    <!-- BACK SIDE -->
    <div class="card">
        <!-- Logo -->
        <div class="log">
            <img src="../assets/images/gooni_ict.png" alt="GONI ICT Logo">
        </div>
        <!-- Watermark -->
        <div class="log_watermark">
            <img src="../assets/images/gooni_ict.png" alt="Watermark">
        </div>
        <!-- Contact Details -->
        <div class="back-content">
            This card is the property of GONI ICT.<br>
            If found, please return.<br><br>
            Phone: +252617999682 / +252684260764<br>
            Email: info@goniict.com<br>
            Web: www.goniict.com<br>
            Location: Mogadishu, Somalia
        </div>

        <!-- QR Code -->
        <div class="qr">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=<?= urlencode('Admin ID: '.$admin['admin_id'].', Name: '.$admin['name']) ?>" alt="QR Code">
        </div>
    </div>




</body>

</html>