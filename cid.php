<?php

$cid = $_GET['cid'];

$conn = pg_connect('user=ctf');
$query = '
    select 1 
    from cids
    where cid = $1;
';
$result = pg_query_params($conn, $query, array($cid));
$value = pg_fetch_object($result);

if (!$value) {
    http_response_code(400);
    echo('400 Bad request');
    die();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTF</title>
</head>
<body>
<div>
    FLAG=23ebdf9e-55d9-4838-a59b-5779a2f9f04e
</div>
</body>
</html>

