<?php

include '.sessionStart.php';

function send400IfNot($value) {
    if (!$value) {
        http_response_code(400);
        echo('400 Bad request');
        die();
    }
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Allow: POST');
    http_response_code(405);
    echo('405 Method not allowed');
    die();
}

$cartId = $_SESSION['cart'];
send400IfNot($cartId);

$productId = $_POST['id'];
send400IfNot($productId);

$price = $_POST['price'];
send400IfNot($price);


$conn = pg_connect('user=ctf_t4');
$query = "UPDATE carts SET total = total + $1 WHERE id = $2";
pg_query_params($conn, $query, array($price, $cartId));

$query = "
INSERT INTO products_in_cart (product_id, cart_id, count) 
VALUES ($1, $2, 0)
ON CONFLICT DO NOTHING";
pg_query_params($conn, $query, array($productId, $cartId));

$query = "
UPDATE products_in_cart
SET count = count + 1
WHERE product_id = $1 AND cart_id = $2";
pg_query_params($conn, $query, array($productId, $cartId));

$query = "SELECT price, name FROM products WHERE id = $1";
$result = pg_query_params($conn, $query, array($productId));
$value = pg_fetch_object($result);


if ($value->price !== $price) {
    echo 'FLAG=9b734807-8ffc-4085-b974-3139339d2a3f';
    die();
} else {
    header('Location: shop.php?added=' . $value->name);
    http_response_code(303);
    die();
}

