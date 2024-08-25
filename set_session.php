<?php
//session_start();

//if(isset($_GET['finalTotal'])) {
    //$total= $_GET['finalTotal'];
   // echo 'Session variable set successfully';
   // echo $total;
//} else {
    
    // Handle error
  //  echo 'Error: Total amount not provided';
//}

    //if (isset($_POST)){
      //  $data = file_get_contents("php://input");
        //$total = json_decode($data, true);
        //echo 'Session variable set successfully';
        //echo $total["value"];
       
    //}


   
    session_start();

    if(isset($_GET['value'])){
        $value = $_GET['value'];
        $_SESSION['total'] = $value;
        echo 'Total: ' . $_SESSION['total'];

    } 
   //echo $_SESSION['total'];//this displays the total

  //require '/vendor/autoload.php';
  /*require __DIR__ . '/vendor/autoload.php';


   $stripe_secret_key = "sk_test_51POmQRRsQYkoe41jV244EbrA4x68hHuZySLRhpuLvDsmDIOO4o8pnHqnwJ6EFWvWqpfPaOxlJEdFATz5FAaVH7ng00rKYMP1pV"
   \Stripe\Stripe::setApiKey($stripe_secret_key);

    $checkout_session= \Stripe\Checkout\Session::create([
      "mode" => "payment",

    ]);*/

    
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the Composer autoloader
require __DIR__ . '/vendor/autoload.php';

//echo "Autoloader included successfully.";

$stripe_secret_key = "sk_test_51POmQRRsQYkoe41jV244EbrA4x68hHuZySLRhpuLvDsmDIOO4o8pnHqnwJ6EFWvWqpfPaOxlJEdFATz5FAaVH7ng00rKYMP1pV";
\Stripe\Stripe::setApiKey($stripe_secret_key);
$checkout_session= \Stripe\Checkout\Session::create([
  "mode" => "payment",
  "success_url" => "http://localhost/e-commerce/success.php",
  "cancel_url" => "http://localhost/e-commerce/index.html",
  "line_items" => [
    [
      "quantity" => 1,
      "price_data" => [
        "currency" => "zar",
        "unit_amount" => $_SESSION['total']*100,
        "product_data" => [
          "name" => "Glasses"
      ]
    ]
  ]
  ]


]);

http_response_code(303);
header("Location:" .$checkout_session->url);


    
?>