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

$emp_id = $_POST['id'];
$stmt = $conn->prepare("SELECT * FROM employees WHERE emp_id = ?");
$stmt->bind_param("i", $emp_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('employee not found');
}

$employee = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GONI ICT ID Card</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: black;
            display: flex;
            flex-direction: column;
            gap: 30px;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .card {
            width: 250px;
            height: 400px;
            background: #fff;
            border-top: 15px solid rgb(13, 104, 201);
            border-bottom: 15px solid rgb(13, 104, 201);
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
        }

        .card h2 {
            margin: 10px 0;
            color: #1748ea;
        }

        .photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #1748ea;
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
        }

        .info span {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .role {
            background: #1748ea;
            color: #fff;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            margin: 10px 0;
        }

        .qr {
            margin-top: auto;
        }

        .qr img {
            width: 80px;
            height: 80px;
        }

        .back-content {
            font-size: 12px;
            text-align: center;
            margin-top: 20px;
            line-height: 1.5;
        }

        @media print {
            body {
                background: none;
            }
            .card {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

<!-- FRONT SIDE -->
<div class="card">
    <h2>GONI ICT</h2>
    <div class="photo">
        <img src="../uploads/<?= htmlspecialchars($employee['image']) ?>" alt="Admin Photo">
    </div>
    <div class="info">
        <span><?= htmlspecialchars($employee['name']) ?></span>
        ID: GONI ICT000<?= htmlspecialchars($employee['emp_id']) ?><br>
        <?= htmlspecialchars($employee['email']) ?><br>
    </div>
    <div class="role"><?= htmlspecialchars($employee['position']) ?><br></div>
    <div class="qr">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=<?= urlencode('employee  ID: '.$employee['emp_id'].', Name: '.$employee['name']) ?>" alt="QR Code">
    </div>
</div>

<!-- BACK SIDE -->
<div class="card">
    <h2>GONI ICT</h2>
    <div class="back-content">
        This card is the property of GONI ICT.<br>
        If found, please return.<br><br>
        Phone: +252617999682 / +252684260764<br>
        Email: info@goni ict.com<br>
        Web: www.goni ict.com<br>
        Location: Mogadishu, Somalia
    </div>
    <div class="qr">
    <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=<?= urlencode('employee  ID: '.$employee['emp_id'].', Name: '.$employee['name']) ?>" alt="QR Code">
    </div>
</div>

<script>
    window.onload = function() {
        window.print();
    };
</script>

</body>
</html>
