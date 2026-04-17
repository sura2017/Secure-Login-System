<?php
// Start the session at the very top of the file
session_start();
require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    // --- REGISTRATION LOGIC ---
    if ($action == "register") {
        $u = $_POST['username'];
        $raw_password = $_POST['password']; 
        
        // 1. Password Strength Validation
        $passwordRegex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";

        if (!preg_match($passwordRegex, $raw_password)) {
            die("<h1>Error: Password is too weak!</h1>
                 <p>It must be 8+ characters and include Uppercase, Lowercase, Number, and Symbol.</p>
                 <a href='register.html'>Go Back and Fix</a>");
        }

        // 2. Hash the password
        $p = password_hash($raw_password, PASSWORD_DEFAULT);

        $fn = $_POST['first_name'];
        $ln = $_POST['last_name'];
        $dept = $_POST['department'];
        $gen = $_POST['gender'] ?? "Not specified";
        $hobbies_string = isset($_POST['hobbies']) ? implode(", ", $_POST['hobbies']) : "None";
        $others = $_POST['others'];

        // 3. Database Execution
        try {
            $sql = "INSERT INTO users (username, password, first_name, last_name, department, gender, hobbies, others) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssss", $u, $p, $fn, $ln, $dept, $gen, $hobbies_string, $others);

            if ($stmt->execute()) {
                echo "<h1>Registration Success!</h1><p>You can now log in.</p><a href='login.html'>Login Now</a>";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                echo "<h1>Error: Username '$u' is already taken.</h1><a href='register.html'>Try Again</a>";
            } else {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    // --- UPDATED LOGIN LOGIC WITH REDIRECT ---
    if ($action == "login") {
        $u = $_POST['username'];
        $p = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $u);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($p, $user['password'])) {
            // 1. SAVE USER INFO IN SESSION
            $_SESSION['username'] = $user['username'];
            $_SESSION['first_name'] = $user['first_name'];

            // 2. REDIRECT TO DASHBOARD
            header("Location: dashboard.php");
            exit(); // Always use exit after header redirect
        } else {
            echo "<h1>Invalid Login!</h1><p style='color:red;'>Incorrect username or password.</p><a href='login.html'>Try Again</a>";
        }
    }
}
?>