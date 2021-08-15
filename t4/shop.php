<!-- 
create database ctf_t4;
create user ctf_t4;
create table products (id serial, name text, price int);
create table products_in_cart (product_id int, cart_id int, count int, primary key (product_id, cart_id));
create table carts (id serial, total int default 0);
insert into products (name, price) values ('Cheap car', 5000);
insert into products (name, price) values ('Medium car', 21000);
insert into products (name, price) values ('Expensive car', 115000);
grant select on products to ctf_t4;
grant all on products_in_cart to ctf_t4;
grant all on carts to ctf_t4;
grant all on carts_id_seq to ctf_t4;
-->
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
LEFT OUTER JOIN products_in_cart AS c
ON p.id = c.product_id AND (c.cart_id = $1 OR c.cart_id IS NULL)
";
$result = pg_query_params($conn, $query, array($cartId));
$products = array();
while ($value = pg_fetch_object($result)) {
    array_push($products, $value);
}

$addedProductName = $_GET['added'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
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
    <h1>Shop</h1>
    <?php if ($addedProductName): ?>
    <div class="alert alert-success">
        Added <?php echo htmlspecialchars($addedProductName) ?> to cart!
        <a class="nav-link" href="viewCart.php">View cart</a>
    </div>
    <?php endif; ?>
    <ul class="list-group">
        <?php foreach ($products as $product): ?>
        <li class="list-group-item">
            <span class="text-primary"><?php echo $product->name; ?></span>
            <span class="text-secondary mx-2">USD <?php echo $product->price; ?></span>
            <?php if ($product->count > 0): ?>
                <span class="badge badge-primary badge-pill"><?php echo $product->count; ?></span>
            <?php endif; ?>
            <form class="form-inline mt-3" action="addToCart.php" method="post">
                <input type="hidden" name="id" value="<?php echo $product->id; ?>">
                <input type="hidden" name="price" value="<?php echo $product->price; ?>">
                <button type="submit" class="btn btn-primary pull-right">Add to cart</button>
            </form>
        </li>
        <?php endforeach; ?>
    </ul>
</main>
</body>
</html>
