<?php
session_start();

if (isset($_GET['add_to_cart'])) {
    $product_id = $_GET['add_to_cart'];
    $product_name = $_GET['product_name'];
    $product_price = $_GET['product_price'];

    $cart_item = [
        'id' => $product_id,
        'name' => $product_name,
        'price' => $product_price,
        'quantity' => 1
    ];

    $product_exists = false;
    foreach ($_SESSION['cart'] as &$cart_item_in_session) {
        if ($cart_item_in_session['id'] == $product_id) {
            $cart_item_in_session['quantity'] += 1;
            $product_exists = true;
            break;
        }
    }

   
    if (!$product_exists) {
        $_SESSION['cart'][] = $cart_item;
    }

    header('Location: view_cart.php');
    exit();
}

if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];

    foreach ($_SESSION['cart'] as $key => $cart_item) {
        if ($cart_item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    header('Location: view_cart.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            color: #4CAF50;
        }

        .cart-container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item-name {
            font-weight: bold;
        }

        .remove-btn {
            color: #f44336;
            text-decoration: none;
            font-size: 14px;
        }

        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        .checkout-btn {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 20px;
        }

        .checkout-btn:hover {
            background-color: #45a049;
        }

        .empty-cart {
            text-align: center;
            margin-top: 50px;
            font-size: 18px;
            color: #777;
        }

    </style>
</head>
<body>

    <div class="cart-container">
        <h2>Your Cart</h2>

        <?php
        if (!empty($_SESSION['cart'])) {
            $total_price = 0;
            echo "<ul>";
            foreach ($_SESSION['cart'] as $cart_item) {
                echo "<li class='cart-item'>
                        <span class='cart-item-name'>{$cart_item['name']} - {$cart_item['quantity']} x \${$cart_item['price']}</span>
                        <a class='remove-btn' href='view_cart.php?remove={$cart_item['id']}'>Remove</a>
                    </li>";
                $total_price += $cart_item['quantity'] * $cart_item['price'];
            }
            echo "</ul>";
            echo "<div class='total'>Total: \${$total_price}</div>";
            echo "<a href='checkout.php' class='checkout-btn'>Proceed to Checkout</a>";
        } else {
            echo "<p class='empty-cart'>Your cart is empty.</p>";
        }
        ?>
    </div>

</body>
</html>
