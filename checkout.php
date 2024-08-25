<?php
session_start();
include 'database.php'; // This file should contain database connection details


function redirectTo($url) {
    header("Location: $url");
    exit();
}

 //Check if user is logged in
if (isset($_SESSION['user_email'])) {
    $email = $_SESSION['user_email'];
    } else {
        echo "No user is logged in.";
        redirectTo('login.php');
    }

    //echo "Email retrieved from session: " . $email;



// Fetch user details from the database
$sql = "SELECT addresss, contact_no FROM users WHERE email = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    //if ($result->num_rows === 0) {
        // User not found, log them out and redirect to login page
      //  session_destroy();
        //redirectTo('login.php');
    //}

    $user = $result->fetch_assoc();
    $address = $user['addresss'];
    $contact_number = $user['contact_no'];
    //echo "Email retrieved from session: " . $address;
    //echo "Email retrieved from session: " . $contact_number;

} else {
    die("Database query failed: " . $conn->error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_details']) && (!empty($_POST['new_address']) || !empty($_POST['new_contact_number']))) {
        // Update details in the database
        $new_address = !empty($_POST['new_address']) ? $_POST['new_address'] : $address;
        $new_contact_number = !empty($_POST['new_contact_number']) ? $_POST['new_contact_number'] : $contact_number;
        
        $update_sql = "UPDATE users SET addresss = ?, contact_no = ? WHERE email = ?";
        if ($update_stmt = $conn->prepare($update_sql)) {
            $update_stmt->bind_param("sss", $new_address, $new_contact_number, $email);
            $update_stmt->execute();
            
            $address = $new_address;
            $contact_number = $new_contact_number;
        } else {
            die("Database update failed: " . $conn->error);
        }
    }
    
    // Redirect to Stripe checkout
    redirectTo('set_session.php');
}
?>


<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="styles1.css">

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
<!-- bootstrap links -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<!-- bootstrap links -->
<!-- fonts links -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
<title>Checkout</title>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .AC {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 120px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        label {
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .chkbtn {
            padding: 10px 20px;
            background-color: black;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .chkbtn:hover {
            background-color: gray;
        }

        button[type="submit"] {
            margin-top: 20px;
        }


        @media (max-width: 768px) {
    .AC {
        padding: 20px;
        margin: 20px;
        padding-top: 200px;
    }

    h2 {
        font-size: 1.2rem;
        margin-bottom: 15px;
    }

    input[type="text"] {
        padding: 8px;
        font-size: 0.8rem;
    }

    .chkbtn {
        padding: 8px 15px;
        font-size: 0.8rem;
    }
}

@media (max-width: 480px) {
    .AC {
        padding: 15px;
        margin: 10px;
        padding-top: 200px;
    }

    h2 {
        font-size: 1rem;
        margin-bottom: 10px;
    }

    input[type="text"] {
        padding: 6px;
        font-size: 0.75rem;
    }

    .chkbtn {
        padding: 6px 10px;
        font-size: 0.75rem;
    }
}
    </style>
</head>
<body>
<header class="header">
    <a href="" class="logo"><img src="images/logo.jpg"></a>

    <button class="hamburger" id="navbar-toggler" aria-label="Toggle navigation">
  <i class="fa-solid fa-bars"></i> <!-- Hamburger icon using Font Awesome -->
</button>
    <nav class="navbar">
       
      
          <a href="index.html" >Home</a>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Shop
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown" style="background-color: transparent" >
              <li><a class="dropdown-item" href="BestSellers.php">Best Sellers</a></li>
              <li><a class="dropdown-item" href="NewArrivals.php">New Arrivals</a></li>
              <li><a class="dropdown-item" href="allproducts.php">All</a></li>
            </ul>
          </li>

          
          <a href="orders.php">Orders</a>
          <a href="contact_us.html">Contact</a>
         
          <a  href="cart.php"> <i class="fa-regular fa-cart-shopping"></i></a>
     
  </nav>

  </header>

  <div class="AC">
    <?php if (empty($address) && empty($contact_number)): ?>
        <h2>Please fill in your address and contact number before checkout</h2>
        <form method="POST" action="checkout.php">
            <label for="new_address">Address:</label>
            <input type="text" id="new_address" name="new_address" value="<?php echo htmlspecialchars($address); ?>" required>
            <br>
            <label for="new_contact_number">Contact Number:</label>
            <input type="text" id="new_contact_number" name="new_contact_number" value="<?php echo htmlspecialchars($contact_number); ?>" required>
            <br>
            <button type="submit" name="update_details">Save and Continue to Checkout</button>
        </form>
    <?php else: ?>
        <h2>Your current details are:</h2>
        <p>Address: <?php echo htmlspecialchars($address); ?></p>
        <p>Contact Number: <?php echo htmlspecialchars($contact_number); ?></p>
        <p>Would you like to update your details?</p>
        <form method="POST" action="checkout.php">
            <label for="new_address">New Address (leave blank to keep current):</label>
            <input type="text" id="new_address" name="new_address">
            <br>
            <label for="new_contact_number">New Contact Number (leave blank to keep current):</label>
            <input type="text" id="new_contact_number" name="new_contact_number">
            <br>
            <button class ="chkbtn"type="submit" name="update_details">Update and Continue to Checkout</button>
            <button class ="chkbtn" type="submit">Continue to Checkout without Update</button>
        </form>
    <?php endif; ?>
    </div>


    <footer id="footer" style="margin-top: 50px;">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 footer-content">
                    
                    <strong><i class="fas fa-phone"></i> Phone: <strong>+27 71 234 7889</strong></strong><br>
                    <strong><i class="fa-solid fa-envelope"></i> Email: <strong>mazazaeyewear@gmail.com</strong></strong>
                </div>
                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>Useful Links</h4>
                    <ul>
                      <li><a href="index.html">Home</a></li>
                      <li><a href="orders.php">Orders</a></li>
                      <li><a href="contact.html">Contact</a></li>
                      
                      <li><a href="cart.php">Cart</a></li>
                      <li><a href="login.php">Login</a></li>
                      <li><a href="register.php">Register</a></li>
                      <li><a href="logout.php">LogOut</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>Shop</h4>
                    
                    <ul>
                        <li><a href="BestSellers.php">Best Sellers</a></li>
                        <li><a href="NewArrivals.php">New Arrivals</a></li>
                        <li><a href="allproducts.php">View All Sunglasses</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>Our Social Network</h4>
                    <p>Contact, view our content, and interact with us.</p>
                    <div class="social-links mt-3">
                        <a href="#" class="twiiter"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#" class="twiiter"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="instagram"><i class="fa-brands fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="container py-4">
        <div class="copyright">
            &copy; Copyright <strong>Mazaza Eyewear</strong>.All Rights Reserved
        </div>
    </div>
    </footer>

    <script>

document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const navbar = document.querySelector('.navbar');

    console.log('Script loaded'); // Check if this message appears

    hamburger.addEventListener('click', function() {
        console.log('Hamburger clicked'); // Check if this message appears
        navbar.classList.toggle('active');
    });
});

    </script>
</body>
</html>

<?php 


?>
