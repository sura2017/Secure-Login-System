<?php
session_start();
require_once 'db_config.php';

// SECURITY CHECK: If no session exists, send them to login
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$u = $_SESSION['username'];
// Fetch the latest data for this specific user
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $u);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card reg-card">
        <h2>User Dashboard</h2>
        <p>Welcome back, <b><?php echo htmlspecialchars($user['first_name']); ?></b>!</p>
        <hr>
        <div class="grid-row">
            <div><strong>Username:</strong> <?php echo $user['username']; ?></div>
            <div><strong>Department:</strong> <?php echo $user['department']; ?></div>
            <div><strong>Gender:</strong> <?php echo $user['gender']; ?></div>
        </div>
        <div style="margin-top:20px;">
            <strong>Hobbies:</strong> <?php echo $user['hobbies']; ?><br><br>
            <strong>About me:</strong> <?php echo nl2br(htmlspecialchars($user['others'])); ?>
        </div>

        <div class="button-container">
            <a href="update_profile.php"><button class="btn-primary">Update Profile</button></a>
            <a href="logout.php"><button class="btn-danger">Logout</button></a>
        </div>
    </div>
</body>
</html>