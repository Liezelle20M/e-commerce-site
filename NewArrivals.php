<?php
session_start();
include 'database.php';

// Fetch products from the database
function getProducts() {
    global $conn;
    $query = "SELECT * FROM products";
    $result = mysqli_query($conn, $query);
    $products = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
    return $products;
}

$products = getProducts();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Store</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="styles1.css">
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
    <script src="script.js" async></script>
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
              <li><a class="dropdown-item" href="NewArrivals.php" class="active">New Arrivals</a></li>
              <li><a class="dropdown-item" href="allproducts.php">All</a></li>
            </ul>
          </li>

          
          <a href="orders.php">Orders</a>
          <a href="contact_us.html">Contact</a>
         
          <a  href="cart.php"> <i class="fa-regular fa-cart-shopping"></i></a>
     
  </nav>

  </header>


      <div class="container" id="product-cards">
        <h1>New Arrivals</h1>
        <?php 
        $productChunks = array_chunk($products, 4); // Split products into chunks of 4
        foreach ($productChunks as $productChunk): ?>
            <div class="row" style="margin-top: 30px;">
                <?php foreach ($productChunk as $product): ?>
                    <div class="col-md-3 py-3 py-md-0">
                        <div class="card">

                                    <img class="card-img" src="<?php echo $product['image']; ?>" alt=""></a>
                            <div class="card-body">
                                <h3 class="card-name"><?php echo $product['name']; ?></h3>
                                <div class="star">
                                    <i class="fas fa-star checked"></i>
                                    <i class="fas fa-star checked"></i>
                                    <i class="fa-solid fa-star-half-stroke checked"></i>
                                </div>
                                <h5 class="card-price">R<?php echo $product['price']; ?>
                                    <form method="POST" action="cart.php" class="d-inline">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <button type="submit" class="add-to-cart"><i class="fa-solid fa-cart-shopping"></i></button>
                                    </form>
                                    
                                </h5>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
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
        
                              <li><a href="#">Cart</a></li>
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
