<?php
session_start();

if (isset($_GET['add_to_cart'])) {
    $product_id = $_GET['add_to_cart'];
    $product_name = $_GET['product_name'];
    $product_price = $_GET['product_price'];
    $product_image = $_GET['product_image'];  

    $cart_item = [
        'id' => $product_id,
        'name' => $product_name,
        'price' => $product_price,
        'image' => $product_image,  
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


if (isset($_GET['action']) && $_GET['action'] == 'view_cart') {
    echo "<h2>Your Cart</h2>";

    if (!empty($_SESSION['cart'])) {
        $total_price = 0;
        echo "<ul>";
        foreach ($_SESSION['cart'] as $cart_item) {
            echo "<li>
                    <img src='{$cart_item['image']}' alt='{$cart_item['name']}' style='width: 50px; height: 50px; margin-right: 10px;' />
                    {$cart_item['name']} - {$cart_item['quantity']} x \${$cart_item['price']}
                    <a href='cart.php?remove={$cart_item['id']}'>Remove</a>
                  </li>";
            $total_price += $cart_item['quantity'] * $cart_item['price'];
        }
        echo "</ul>";
        echo "<h3>Total: \${$total_price}</h3>";
        echo "<a href='checkout.php'>Proceed to Checkout</a>";
    } else {
        echo "<p>Your cart is empty.</p>";
    }
}
?>
