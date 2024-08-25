<?php
session_start();
require_once 'database.php'; // Include your database connection file

// Retrieve the user_id from the session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

// Fetch the user's orders from the database
$orderQuery = "SELECT * FROM order_dets WHERE user_id = ?";
if ($stmt = $conn->prepare($orderQuery)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
} else {
    echo '<pre>Error preparing orders query: ' . $conn->error . '</pre>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /*body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }*/
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            padding-top: 150px;
        }
        h1 {
            text-align: center;
            color: #333;
            padding: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .order-details {
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
    </style>
</head>
<body>
<header class="header">
    <a href="" class="logo"><img src="images/logo.jpg"></a>
    <button class="navbar-toggler" id="navbar-toggler" aria-label="Toggle navigation">
      <i class="fa-solid fa-bars"></i> <!-- Hamburger icon using Font Awesome -->
  </button>
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

          
          <a href="orders.php" class="active">Orders</a>
          <a href="contact_us.html">Contact</a>
         
          <a  href="cart.php"> <i class="fa-regular fa-cart-shopping"></i></a>
     
  </nav>

  </header>

  </header>
    <div class="container">
        <h1>Your Previous Orders</h1>
        <?php if (empty($orders)): ?>
            <p>No orders found.</p>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-details">
                    <h2>Order ID: <?php echo htmlspecialchars($order['order_id']); ?></h2>
                    <p>Total: R<?php echo htmlspecialchars($order['total']); ?></p>
                    <p>Tracking Number: <?php echo htmlspecialchars($order['tracking_number']); ?></p>
                    <h3>Items:</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch order items
                            $itemsQuery = "SELECT * FROM order_items WHERE order_id = ?";
                            if ($itemStmt = $conn->prepare($itemsQuery)) {
                                $itemStmt->bind_param("i", $order['order_id']);
                                $itemStmt->execute();
                                $itemsResult = $itemStmt->get_result();

                                while ($item = $itemsResult->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['product_id']); ?></td>
                                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                        <td>R<?php echo htmlspecialchars($item['price']); ?></td>
                                    </tr>
                                <?php endwhile;
                            } else {
                                echo '<pre>Error preparing order items query: ' . $conn->error . '</pre>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
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
                        <a href="#" class="twiiter"><i class="fa-brands fa-instagram"></i></a>
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
    <!-- footer -->

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