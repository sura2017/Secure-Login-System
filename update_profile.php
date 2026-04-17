<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['username'])) { header("Location: login.html"); exit(); }

$u = $_SESSION['username'];
$user = $conn->query("SELECT * FROM users WHERE username='$u'")->fetch_assoc();

// Handle the Update Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dept = $_POST['department'];
    $others = $_POST['others'];
    
    $sql = "UPDATE users SET department=?, others=? WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $dept, $others, $u);
    
    if ($stmt->execute()) {
        header("Location: dashboard.php?msg=updated");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Update Profile</title>
</head>
<body>
    <div class="card login-card">
        <h2>Update Profile</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label>Department</label>
                <select name="department">
                    <option <?php if($user['department'] == 'Computer Science') echo 'selected'; ?>>Computer Science</option>
                    <option <?php if($user['department'] == 'Software Engineering') echo 'selected'; ?>>Software Engineering</option>
                </select>
            </div>
            <div class="form-group">
                <label>About Me</label>
                <textarea name="others"><?php echo $user['others']; ?></textarea>
            </div>
            <div class="button-container">
                <button type="submit" class="btn-primary">Save Changes</button>
                <a href="dashboard.php" style="text-decoration:none; color:gray;">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>