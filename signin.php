<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    if (isset($_SESSION['last_failed_attempt'])) {
        $time_diff = time() - $_SESSION['last_failed_attempt'];
        if ($time_diff < 10) {
            $remaining_time = 10 - $time_diff;
            echo "You have made too many failed attempts. Please try again in $remaining_time seconds.";
            exit;
        } else {
           
            unset($_SESSION['failed_attempts']);
            unset($_SESSION['last_failed_attempt']);
        }
    }

    $conn = new mysqli('localhost', 'root', '', 'user_db');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $stored_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        
        if ($password === $stored_password) {
            
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user_id;

            
            unset($_SESSION['failed_attempts']);
            unset($_SESSION['last_failed_attempt']);
            
            header("Location: welcome.php");
            exit();
        } else {
            
            if (!isset($_SESSION['failed_attempts'])) {
                $_SESSION['failed_attempts'] = 1;
            } else {
                $_SESSION['failed_attempts'] += 1;
            }

           
            if ($_SESSION['failed_attempts'] >= 3) {
                $_SESSION['last_failed_attempt'] = time(); 
                echo "Too many failed attempts. Please try again after 10 seconds.";
            } else {
                echo "Invalid password.";
            }
        }
    } else {
        echo "No user found with that username.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign In</title>
    <link rel="stylesheet" type="text/css" href="signinstyle.css">
</head>
<body>
    
<div class="backround1">
    <h2>Sign In</h2>
    <form action="signin.php" method="post">
        <label>Username:</label>
        <input type="text" name="username" required><br>
        
        <label>Password:</label>
        <input type="password" name="password" required><br>
        
        <button type="submit">Sign In</button>
    </form>
    <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
</div>
</body>
</html>
