<?php 
    session_start();//not sure
    include ("database.php");
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="styles1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="bg">
    
    <h2>WELCOME TO MAZAZA EYEWEAR</h2>

    <div id="login">
        <form method="post" action="login.php">

            <h3 class="formName">Sign In</h3>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
          </div>
                <p class="recover">
                <a href="#">Recover Password</a>
                </p>
                <input type="submit" class="btn" value="Sign In" name="signIn">

            <div class="links">
                <p>Don't have account yet?<a href="register.php"> Register</a></p>
               
            </div>

                
        </form>
        
    </div>
    

    
    
</body>
</html>

<?php 
    
    if (isset($_POST['signIn'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        // Check if email exists
        $checkEmail = "SELECT * FROM users WHERE email=?";
        if ($stmt = $conn->prepare($checkEmail)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                // Email exists, now check the password
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    // Password is correct
                    echo "Login successful";
                    // Set session variables or perform other login actions
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    // Redirect to the dashboard or home page
                    header("Location: NewArrivals.php");
                    exit;
                } else {
                    echo "Incorrect password";
                }
            } else {
                echo "No account found with that email address";
            }
    
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    }
    
    $conn->close();
?>