<?php

include '.sessionStart.php';

$conn = pg_connect('user=ctf_t4');

if ($_SESSION['cart']) {
    $cartId = $_SESSION['cart'];
} else {
    $query = "INSERT INTO carts DEFAULT VALUES RETURNING id";
    $result = pg_query($conn, $query);
    $value = pg_fetch_object($result);
    $cartId = $value->id;
    $_SESSION['cart'] = $cartId;
}

$query = "
SELECT p.id, p.name, p.price, c.count 
FROM products AS p 
INNER JOIN products_in_cart AS c
ON p.id = c.product_id
WHERE c.cart_id = $1
";
$result = pg_query_params($conn, $query, array($cartId));
$products = array();
while ($value = pg_fetch_object($result)) {
    array_push($products, $value);
}

$query = "SELECT total FROM carts WHERE id = $1";
$result = pg_query_params($conn, $query, array($cartId));
$cart = pg_fetch_object($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" type="text/css" href="https://jfhr.de/assets/flatly.bootstrap.min.css">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="shop.php">Home</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="viewCart.php">Cart</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<main class="container">
    <h1>Cart</h1>
    <?php if (sizeof($products) === 0): ?>
    <div class="alert alert-info">
        Your cart is empty.
    </div>
    <?php else: ?>
    <ul class="list-group">
        <?php foreach ($products as $product): ?>
        <li class="list-group-item">
            <span class="text-primary pull-left"><?php echo $product->name; ?></span>
            <span class="text-secondary pull-right">USD <?php echo $product->price; ?></span>
            <?php if ($product->count > 0): ?>
            <span class="badge badge-primary badge-pill pull-right"><?php echo $product->count; ?></span>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ul>
    <div class="my-3">
        Total: <?php echo $cart->total; ?>
    </div>
    <button class="btn btn-primary">Checkout</button>
    <?php endif; ?>
</main>
</body>
</html>
