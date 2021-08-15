<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Allow: POST');
    http_response_code(405);
    echo('405 Method not allowed');
    die();
}

$flag = $_POST['flag'];
if (substr($flag, 0, 5) == 'FLAG=') {
    $flag = substr($flag, 5);
}
$username = strtolower($_POST['username']);
if (!$username || trim($username) == '') {
    $username = 'anonymous';
}
$conn = pg_connect('user=ctf');
$result = pg_query_params($conn, 'SELECT * FROM flags WHERE flag::text = $1', array($flag));
$value = pg_fetch_object($result);

if ($value) {
    $query = '
        INSERT INTO found_flags (flag, username)
        VALUES ($1, $2)
        ON CONFLICT DO NOTHING
';
    pg_query_params($conn, $query, array($flag, $username));
    header('Location: index.php?flag=valid');
}
else {
    header('Location: index.php?flag=invalid');
}

http_response_code(303);
die();
