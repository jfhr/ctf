<?php
$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
$id = $queries['id'];

$conn = pg_connect('user=ctf_t1');
$query = 'SELECT title, content FROM posts WHERE id = $1';
$result = pg_query_params($conn, $query, array($id));
$post = pg_fetch_object($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $post->title ?></title>
</head>
<body>
<h1><?php echo $post->title ?></h1>
<p><?php echo $post->content ?></p>
<hr>
<a href="index.php">Go back</a>
</body>
</html>
