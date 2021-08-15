<?php

// secrets in source code let's go
$serverPassword = 'letyoudown';

function fail_401()
{
    header('WWW-Authenticate: Basic realm="Please login as admin"');
    http_response_code(401);
    echo('401 Unauthorized');
    die();
}

$username = $_SERVER['PHP_AUTH_USER'];
$password = $_SERVER['PHP_AUTH_PW'];

if (!$username || !$password) {
    fail_401();
}

function fancyStringComparison($server, $client)
{
    for ($i = 0; $i < strlen($server); $i++) {
        if ($i >= strlen($client) || $server[$i] != $client[$i]) {
            return false;
        }
        usleep(100000);
    }
    if (strlen($server) != strlen($client)) {
        return false;
    }
    return true;
}

if ('admin' != $username) {
    fail_401();
}

if (!fancyStringComparison($serverPassword, $password)) {
    fail_401();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You did it :)</title>
</head>
<body>
<h1>You did it :)</h1>
<p>
    Flag=321337edd-0d09-4106-b194-2c8daa73e2ec
</p>
</body>
</html>
