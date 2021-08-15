<!-- 
create database ctf_t2;
create user ctf_t2;
create table users (id serial primary key, username text not null, password_hash text not null);
grant all on users to ctf_t2;
insert into users (username, password_hash) values ('admin', '$2y$10$4S0sd2uvn2fAGjOLJdKLsu3g7X.fqlwnHUEZxvr5x39xwjEQGa4B2');
insert into users (username, password_hash) values ('user', '$2y$10$OUSjpqbWKmPlkMTXHN2z1e8fZUK3hOB8NDXpXY5J.ICDW1ozyD.le');
-->

<?php

function fail_401() {
    header('WWW-Authenticate: Basic realm="Login required"');
    http_response_code(401);
    echo('401 Unauthorized');
    die();
}

function fail_500() {
    http_response_code(500);
    echo('500 Internal server error');
    die();
}

$auth = $_SERVER['HTTP_AUTHORIZATION'];

if (!$auth || substr($auth, 0, 6) != 'Basic ') {
    fail_401();
}

$basic_values = preg_split('/:/', base64_decode(substr($auth, 6)), 2);
if (sizeof($basic_values) != 2) {
    fail_401();
}
$username = $basic_values[0];
$password = $basic_values[1];

$conn = pg_connect('user=ctf_t2');
$query = "
SELECT password_hash 
FROM users 
WHERE username = '" . $username . "'
FETCH FIRST ROW ONLY";
$result = pg_query($conn, $query);
if (!$result) {
    echo(pg_last_error() . '<br>FLAG=d17981a6-75a8-4130-b140-3afba532e45f<br>');
    fail_500();
}

$value = pg_fetch_object($result);

if (!$value->password_hash) {
    fail_500();
}

if (!password_verify($password, $value->password_hash)) {
    fail_401();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing page</title>
</head>
<body>
<h1>Landing page</h1>
<p>You are logged in as <?php echo htmlspecialchars($username) ?></p>
</body>
</html>
