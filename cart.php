<?php
session_start();
include 'database.php';

// Add product to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    $quantity = 1;

    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();


    // If the product is not found in the 'products' table, check the 'productBestSeller' table
    if (!$product) {
        $query = "SELECT * FROM productBS WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
    }
    //from table all products
    if (!$product) {
        $query = "SELECT * FROM allProducts WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
    }


    if ($product) {
        $cartItem = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => $quantity
        ];

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = $cartItem;
        }
    }

    header('Location: cart.php');
    exit;
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $productId = $_GET['remove'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $productId) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header('Location: cart.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
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
         
          <a  href="cart.php"> <i class="fa-regular fa-cart-shopping" class="active"></i></a>
     
  </nav>

  </header>





    <section id="cart" class="cartstyles" class="cart">
        <table width="100%">
            <thead>
                <tr>
                    <td>Delete</td>
                    <td>Image</td>
                    <td>Product</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Subtotal</td>
                </tr>
            </thead>
            <tbody class="cart-items">
                <?php if (isset($_SESSION['cart'])): ?>
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <tr class="cart-row"  data-item-id="<?php echo $item['id']; ?>">
                            <td>
                                <a href="cart.php?remove=<?php echo $item['id']; ?>" class="delete-btn"><i class="fa-solid fa-trash"></i></a>
                            </td>
                            <td><img src="<?php echo $item['image']; ?>" alt="" class="cart-img"></td>
                            <td class="cart-title"><?php echo $item['name']; ?></td>
                            <td class="cart-price">R<?php echo $item['price']; ?></td>
                            <td><input type="number" value="<?php echo $item['quantity']; ?>" class="cart-quantity"></td>
                            <td class="cart-subtotal">R<?php echo $item['price'] * $item['quantity']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table> 
    </section>

    <section id="cart2" class="cartstyles">
        <div id="coupon">
            <h3>Apply Promo Code</h3>
            <div>
                <input type="text" placeholder="Enter Your Promo Code" id="PromoId">
                <button id="apply">Apply</button>
            </div>
        </div>
        <div id="subtotal">
            <h3>Total</h3>
            <table>
                <tr>
                    <td>Subtotal</td>
                    <td class="cart-total-price">R0</td>
                </tr>
                <tr>
                    <td>Total Items</td>
                    <td class="cart-total-items">0</td>
                </tr>
                <tr>
                    <td>Promo Code</td>
                    <td class="promoCode">R0</td>
                </tr>
                <tr>
                    <td>Shipping Cost</td>
                    <td class="SC">R0</td>
                </tr>
                <tr>
                    <td><strong>Total</strong></td>
                    <td class="TTotal"><strong>R0</strong></td>
                </tr>
            </table>
            <a href="checkout.php"><button>Checkout</button></a>
        </div>
    </section>

    


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
       <script src="script.js"></script> 

       <script>
        document.addEventListener('DOMContentLoaded', function() {
            //new
            loadCartQuantities();
            updateCartTotal();

            document.querySelectorAll('.cart-quantity').forEach(function(input) {
                input.addEventListener('change', function() {
                    var quantity = input.value;
                    if (isNaN(quantity) || quantity <= 0) {
                        input.value = 1;
                    }

                    // new
                    saveCartQuantities();
                    updateCartTotal();
                    
                });
            });

            document.getElementById('apply').addEventListener('click', function() {
                applyPromoCode();
                
            });

        

        });

        function updateCartTotal() {
            var cartItemsContainer = document.querySelector('.cart-items');
            var cartRows = cartItemsContainer.getElementsByClassName('cart-row');
            var total = 0;
            var totalItems = 0;

            for (var i = 0; i < cartRows.length; i++) {
                var cartRow = cartRows[i];
                var priceElement = cartRow.getElementsByClassName('cart-price')[0];
                var quantityElement = cartRow.getElementsByClassName('cart-quantity')[0];
                var subtotalElement = cartRow.getElementsByClassName('cart-subtotal')[0]; 
                var price = parseFloat(priceElement.innerText.replace('R', ''));
                var quantity = quantityElement.value;
                var subtotal = price * quantity; // Calculate the subtotal for the item
                subtotalElement.innerText = "R" + subtotal.toFixed(2); // Update the subtotal element
                total = total + (price * quantity);
                totalItems += parseInt(quantity);
                
            }
          
            total = Math.round(total * 100) / 100;
            document.getElementsByClassName('cart-total-price')[0].innerText = "R" + total;
            document.getElementsByClassName('cart-total-items')[0].innerText = totalItems; // Update total items count


            applyPromoCode(); // Reapply promo code to update total
        }

        function applyPromoCode() {
            var total = parseFloat(document.getElementsByClassName('cart-total-price')[0].innerText.replace('R', ''));
            var promoPrice = 0;
            var code = document.getElementById('PromoId').value;

            if (code === 'NewBuyer10') {
                promoPrice = 150;
            } else if (code === 'Fifth05') {
                promoPrice = 200;
            } else {
                promoPrice = 0;
            }

            document.getElementsByClassName('promoCode')[0].innerText = "R" + promoPrice;

            var shippingCost = (total > 999) ? 0 : 100;
            document.getElementsByClassName('SC')[0].innerText = "R" + shippingCost;

            var finalTotal = total - promoPrice + shippingCost;
            document.getElementsByClassName('TTotal')[0].innerText = "R" + finalTotal;
            

            //experiment
            //window.location.href = 'set_session.php?total=' + finalTotal;
            let user ={
                "username": "Mike",
                "passwo": "Mike234",
                "email": "mike@gmail.com",
                "value": finalTotal
            }
            /*fetch("set_session.php", {
                "method": "POST",
                "headers": {
                    "Content-Type": "application/json; charset=utf-8"
                },
                "body": JSON.stringify(user)
            }).then(function(response){
                return response.text();
            }).then(function(data){
                console.log(data);
            })*/

            let queryString = new URLSearchParams(user).toString();

// Send the request using GET method with query parameters
fetch("set_session.php?" + queryString, {
    "method": "GET",
    "headers": {
        "Content-Type": "application/json; charset=utf-8"
    }
}).then(function(response) {
    return response.text();
}).then(function(data) {
    console.log(data);
}).catch(function(error) {
    console.error('Error:', error);
});
            

        }

       

        function saveCartQuantities() {
        var cartItemsContainer = document.querySelector('.cart-items');
        var cartRows = cartItemsContainer.getElementsByClassName('cart-row');
        var quantities = {};

        for (var i = 0; i < cartRows.length; i++) {
            var cartRow = cartRows[i];
            var id = cartRow.getAttribute('data-item-id'); // Assuming each row has a unique data-item-id
            var quantityElement = cartRow.getElementsByClassName('cart-quantity')[0];
            quantities[id] = quantityElement.value;
        }

        localStorage.setItem('cartQuantities', JSON.stringify(quantities));
    }

    function loadCartQuantities() {
        var savedQuantities = localStorage.getItem('cartQuantities');
        if (savedQuantities) {
            var quantities = JSON.parse(savedQuantities);
            var cartItemsContainer = document.querySelector('.cart-items');
            var cartRows = cartItemsContainer.getElementsByClassName('cart-row');

            for (var i = 0; i < cartRows.length; i++) {
                var cartRow = cartRows[i];
                var id = cartRow.getAttribute('data-item-id');
                var quantityElement = cartRow.getElementsByClassName('cart-quantity')[0];

                if (quantities[id] !== undefined) {
                    quantityElement.value = quantities[id];
                }
            }
        }
    }

        
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
