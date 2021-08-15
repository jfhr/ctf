<?php
$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);

$conn = pg_connect('user=ctf');
$query = '
    select distinct username, count(username) as count
    from found_flags
    group by username
    order by count desc
    fetch first 10 rows only;
';
$leaderboard = pg_query($conn, $query);


$query = '
    select cid 
    from cids
    order by random()
    limit 1;
';
$cidResult = pg_query($conn, $query);
$cid = pg_fetch_object($cidResult)->cid;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTF</title>
    <link rel="stylesheet" type="text/css" href="https://jfhr.de/assets/darkly.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="index.css"/>
    <script src="index.js"></script>
</head>
<body>
<div class="container" id="ctx" data-cid="<?php echo($cid) ?>">
    <h1>CTF</h1>

    <hr>

    Open CTFs:
    <ul>
        <li><a href="t1/index.php" target="_blank">Online diary #1</a></li>
        <li><a href="t2/index.php" target="_blank">Login page #1</a></li>
        <li><a href="t3/image.jpg" target="_blank">Image #1</a></li>
        <li><a href="t5/index.php" target="_blank">Login page #2</a></li>
    </ul>
    <div class="form-check">
        <input type="checkbox" id="cookie-consent" class="form-check-input">
        <label for="cookie-consent">Agree to cookies and show CTFs that use them.</label>
        <span><a href="https://jfhr.de/privacy.html">Privacy policy</a></span>
    </div>
    <ul>
        <li class="uses-cookie"><a href="t4/shop.php" target="_blank">Shop #1</a></li>
    </ul>

    <hr>

    <?php if ($queries['flag'] == 'valid'): ?>
        <div class="alert alert-success">That flag is valid!</div>
    <?php elseif ($queries['flag'] == 'invalid'): ?>
        <div class="alert alert-danger">That flag is invalid.</div>
    <?php endif; ?>

    Submit flag:
    <form action="submit.php" method="post" class="form-inline">
        <div class="form-group mb-2">
            <label for="username">Username</label>
            <input id="username" type="text" name="username" required class="form-control mx-2">
        </div>
        <div class="form-group mx-2 mb-2">
            <label for="flag">Flag</label>
            <input id="flag" type="text" name="flag" required class="form-control mx-2">
        </div>
        <div class="form-group mx-2 mb-2">
            <input type="submit" value="Submit" class="btn btn-primary">
        </div>
    </form>

    <hr>

    Leaderboard:

    <?php $i = 0; ?>
    <?php while ($line = pg_fetch_object($leaderboard)): if ($line->count > 0): ?>
        <?php $i++; ?>
        <div class="list-group-item">
            #<?php echo $i; ?>
            : <?php echo htmlspecialchars($line->username) ?>
            (<?php echo $line->count ?> Flags)
        </div>
    <?php endif; endwhile; ?>
</div>
</body>
</html>
