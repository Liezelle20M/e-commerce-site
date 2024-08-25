<?php
session_start();
require_once 'database.php'; 

// Retrieve values from session
$total = isset($_SESSION['total']) ? $_SESSION['total'] : 0;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];



// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['user_id'], $_POST['total'], $_POST['tracking_number'], $_POST['product_id'])) {
        $user_id = $_POST['user_id'];
        $total = $_POST['total'];
        $tracking_number = $_POST['tracking_number'];
        $product_ids = $_POST['product_id']; // This is an array

        

        // Insert order details into the database
        $orderQuery = "INSERT INTO order_dets (user_id, total, tracking_number) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($orderQuery)) {
            $stmt->bind_param("ids", $user_id, $total, $tracking_number);
            if ($stmt->execute()) {
                // Get the last inserted order ID
                $order_id = $stmt->insert_id;

                

                // Insert each product into the order_items table
                $itemQuery = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
                if ($itemStmt = $conn->prepare($itemQuery)) {
                    foreach ($cartItems as $item) {
                        // Extract item details
                        $product_id = $item['id'];
                        $quantity = $item['quantity'];
                        $price = $item['price'];

                        // Bind parameters and execute
                        $itemStmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
                        if ($itemStmt->execute()) {
                            echo '<pre>Inserted product_id: ' . $product_id . '</pre>';
                        } else {
                            echo '<pre>Error inserting order item with product_id ' . $product_id . ': ' . $itemStmt->error . '</pre>';
                        }
                    }
                } else {
                    echo '<pre>Error preparing order_items statement: ' . $conn->error . '</pre>';
                }

                // Redirect to the order confirmation page or display a success message
                echo "<script>alert('Order placed successfully!'); window.location.href = 'index.html';</script>";
                exit;
            } else {
                echo '<pre>Error executing order_dets statement: ' . $stmt->error . '</pre>';
            }
        } else {
            echo '<pre>Error preparing order_dets statement: ' . $conn->error . '</pre>';
        }
    } else {
        echo "<pre>Required POST data is missing.</pre>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <style>
        /* Style for the entire page */
        body {
            margin: 0;
            padding: 0;
            background-image: url(images/about.jpg); /* Set black background color */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Style for the container div */
        #container-s {
            background-color: white; /* Set white background color */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5); /* Add box shadow for depth */
            display: flex; /* Use flexbox for centering */
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            flex-direction: column; /* Stack elements vertically */
            text-align: center; /* Align text center */
        }
    </style>
</head>
<body>
    <div id="container-s">
        <h1>Thank you for shopping with Mazaza EyeWear</h1>
        <p>This is your tracking number:</p>
        <p id="random"></p>
        <form id="orderForm" action="" method="post">
            <input type="hidden" id="user_id" name="user_id" value="">
            <input type="hidden" id="total" name="total" value="<?php echo $total; ?>">
            <div id="cart_items_container"></div>
            <input type="hidden" id="tracking_number" name="tracking_number">
            <button type="submit">Submit Order Details</button>
        </form>
    </div> 

    <script>
        // Function to generate a random string with capital letters and numbers
        function generateRandomString() {
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            var numbers = '0123456789';
            var randomString = '';

            for (var i = 0; i < 5; i++) {
                randomString += characters.charAt(Math.floor(Math.random() * characters.length));
            }

            for (var j = 0; j < 9; j++) {
                randomString += numbers.charAt(Math.floor(Math.random() * numbers.length));
            }

            return randomString;
        }

        // Append the generated random string to the <p> element with id "random"
        var trackingNumber = generateRandomString();
        document.getElementById('random').textContent = trackingNumber;

        // Set the tracking number in the hidden input field
        document.getElementById('tracking_number').value = trackingNumber;

        // Function to set user ID and cart items from PHP session
        function setUserAndCartDetails() {
            var userId = <?php echo json_encode($user_id); ?>;
            var cartItems = <?php echo json_encode($cartItems); ?>;

            document.getElementById('user_id').value = userId;

            // Add hidden inputs for each cart item
            var cartItemsContainer = document.getElementById('cart_items_container');
            cartItems.forEach(function(item) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'product_id[]'; // Use array notation to handle multiple IDs
                input.value = item.id;
                cartItemsContainer.appendChild(input);
            });
        }

        // Set user and cart details on page load
        setUserAndCartDetails();
    </script>
</body>
</html>






