<?php
session_start();

if (empty($_SESSION['cart'])) {
    header('Location: home.html');  
    exit();
}

$total_price = 0;

if (isset($_POST['place_order'])) {
    unset($_SESSION['cart']);
    echo "<h2>Order placed successfully!</h2>";
    echo "<p>Thank you for your order! Your cart has been cleared.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }

        .checkout-container {
            width: 80%;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #4CAF50;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .cart-item-name {
            font-weight: bold;
            width: 30%;
        }

        .cart-item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-right: 10px;
        }

        .cart-item-details {
            display: flex;
            align-items: center;
        }

        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        .place-order-btn {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 20px;
            text-align: center;
        }

        .place-order-btn:hover {
            background-color: #45a049;
        }

        .back-btn {
            display: inline-block;
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 20px;
        }

        .back-btn:hover {
            background-color: #e53935;
        }
    </style>
</head>

<body>

    <div class="checkout-container">
        <h2>Checkout</h2>

        <?php
        if (!empty($_SESSION['cart'])) {
            echo "<ul>";
            foreach ($_SESSION['cart'] as $cart_item) {
                $image_url = isset($cart_item['image']) ? $cart_item['image'] : 'default-image.jpg';  
                
                echo "<li class='cart-item'>
                        <div class='cart-item-details'>
                            <img class='cart-item-image' src='{$image_url}' alt='{$cart_item['name']}'>
                            <span class='cart-item-name'>{$cart_item['name']} - {$cart_item['quantity']} x \${$cart_item['price']}</span>
                        </div>
                    </li>";
                $total_price += $cart_item['quantity'] * $cart_item['price'];
            }
            echo "</ul>";
            echo "<div class='total'>Total: \${$total_price}</div>";
        } else {
            echo "<p>Your cart is empty.</p>";
        }
        ?>

        <form method="POST">
            <button type="submit" name="place_order" class="place-order-btn">Place Order</button>
        </form>
        <a href="view_cart.php" class="back-btn">Back to Cart</a>
    </div>

</body>

</html>
