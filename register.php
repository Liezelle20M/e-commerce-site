<?php
    session_start();
    include("database.php");


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register page</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="styles1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="bg">

<h2>WELCOME TO MAZAZA EYEWEAR</h2>

<div id="login">

    <form method="post" action="register.php">
        
        <h3 class="formName">Register</h3>
        <div class="input-group">
           <i class="fas fa-user"></i>
           <input type="text" name="fName" id="fName" placeholder="First Name" required>
        </div>
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="lName" id="lName" placeholder="Last Name" required>
        </div>
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" id="email" placeholder="Email" required>
        </div>
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Password" required>
        </div>
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" class="form-control" name="repeat_password" id ="repassword"placeholder="Confirm Password" required>
        </div>

        <input type="submit" class="btn" value="Register" name="signUp">
    </form>
</div>
      
    
</body>
</html>

<?php

if (isset($_POST['signUp'])) {
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $passwordRepeat = $_POST['repeat_password'];

    // Check if passwords match
    if ($password !== $passwordRepeat) {
        echo "Passwords do not match";
        exit; // Stop further execution
    }

    // Check if email already exists
    $checkEmail = "SELECT * FROM users WHERE email=?";
    if ($stmt = $conn->prepare($checkEmail)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Email Address Already Exists!";
            $stmt->close();
            exit; // Stop further execution
        } else {
            $stmt->close(); // Close previous statement
        }
    } else {
        echo "Error: " . $conn->error;
        exit; // Stop further execution if statement preparation failed
    }

    // Insert new user
    $insertQuery = "INSERT INTO users (first_name, last_name, password, email) VALUES (?, ?, ?, ?)";
    if ($stmt = $conn->prepare($insertQuery)) {
        $stmt->bind_param("ssss", $firstName, $lastName, $passwordHash, $email);

        if ($stmt->execute() === TRUE) {
            echo "You are now registered";
            $stmt->close(); // Close the statement before redirecting
            header("Location: index.html");
            exit; // Ensure redirection
        } else {
            echo "Error: " . $stmt->error;
            $stmt->close();
        }
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close(); // Close the database connection


?>

