<?php
$message = ""; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $conn = new mysqli('localhost', 'root', '', 'user_db');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $email);

    if ($stmt->execute()) {
        $message = "Registration successful! <a href='signin.php'>Sign in</a>";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="signupstyle.css"> 
</head>

<body>

<div class="backround2">
    <h2>Sign Up</h2>
    <form action="signup.php" method="post">
        <label>Username:</label>
        <input type="text" name="username" required style="width: 300px; height: 35px; padding: 5px; margin-right: 10px;"><br><br>
        
        <label>Password:</label>
        <input type="password" name="password" required style="width: 300px; height: 35px; padding: 5px; margin-right: 10px;"><br><br>
        
        <label>Email:</label>
        <input type="email" name="email" required style="width: 300px; height: 35px; padding: 5px; margin-right: 10px;"><br><br>
        
        <button type="submit" style="width: 100%; height: 40px;">Sign Up</button>
    </form>
    <?php if (!empty($message)) : ?>
    <p style="text-align: center; color: green;"><?php echo $message; ?></p>
<?php endif; ?>


<p>Already have an account? <a href="signin.php">Sign In</a></p>
</div>





</body>
</html>
