<!--
create database ctf_t1;
create user ctf_t1;
create table posts (id serial primary key, title text, content text, is_visible boolean default true);
grant select on posts to ctf_t1;
insert into posts (title, content, is_visible) values ('Monday', 'brite and fair, late to brekfast, but mother dident say nothing. father goes to boston and works in the custum house so i can get up as late as i want to. father says he works like time, but i went to boston once and father dident do anything but tell stories about what he and Gim Melcher usted to do when he was a boy. once or twice when a man came in they would all be wrighting fast, when the man came in again i sed why do you all wright so fast when he comes in and stop when he goes out, and the man sort of laffed and went out laffing, and the men were mad and told father not to bring that dam little fool again.', 'true');
insert into posts (title, content, is_visible) values ('Tuesday', 'Skinny Bruce got licked in school today. I told my granmother about it and she said she was glad i dident do enything to get punnished for and she felt sure i never wood. i dident tell her i had to stay in the wood box all the morning with the cover down, idident tell father either you bet.', 'true');
insert into posts (title, content, is_visible) values ('Wednesday', 'brite and fair. went to church today. Me and Pewt and Beany go to the Unitarial church. we all joined sunday school to get into the Crismas festerval. they have it in the town hall and have two trees and supper and presents for the scholars. so we are going to stay til after crismas anyway the unitarials have jest built a new church. Pewt and Beany''s fathers painted it and so they go there. i don''t know why we go there xcept because they don''t have any church in the afternoon. Nipper Brown and Micky Gould go there. we all went into the same class. our teacher is Mister Winsor a student. we call them stewdcats. after we had said our lesson we all skinned out with Mr. Winsor. when we went down Maple street we saw 2 roosters fiting in Dany Wingates yard, and we stoped to see it. i knew more about fiting roosters than any of the fellers, because me and Ed Towle had fit roosters lots. Mr. Winsor said i was a sport, well while the roosters were fiting, sunday school let out and he skipped acros the street and walked off with one of the girls and we hollered for him to come and see the fite out, and he turned red and looked mad. the leghorn squorked and stuck his head into a corner. when a rooster squorks he wont fite any more.', 'true');
insert into posts (title, content, is_visible) values ('Thursday', 'FLAG=36bb63a0-71ef-46fb-8de5-4168155b1392', 'false');
insert into posts (title, content, is_visible) values ('Friday', 'snowed today and school let out at noon. this afternoon went down to the library to plug stewdcats. there was me and Beany and Pewt, and Whacker and Pozzy Chadwick and Pricilla Hobbs. Pricilla is a feller you know, and Pheby Talor, Pheby is a feller too, and Lubbin Smith, and Tommy Tompson and Dutchey Seamans and Chick Chickering, and Tady Finton and Chitter Robinson.', 'true');
insert into posts (title, content, is_visible) values ('Saturday', 'Gim Wingate has got a new bobtail coat.', 'true');
insert into posts (title, content, is_visible) values ('Sunday', 'Got sent to bed last nite for smoking hayseed cigars and can''t go with Beany enny more. It is funny, my father wont let me go with Beany becaus he is tuf, and Pewts father wont let Pewt go with me becaus im tuf, and Beanys father says if he catches me or Pewt in his yard he will lick time out of us. Rany today.', 'true');
-->
<?php
$conn = pg_connect('user=ctf_t1');
$query = 'SELECT id, title, is_visible FROM posts';
$result = pg_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online diary</title>
</head>
<body>
<h1>Online diary</h1>
<ul>
    <?php
    while ($post = pg_fetch_object($result)) {
        if ($post->is_visible == 't') {
            echo '<li><a href="view.php?id=' . $post->id . '">' . $post->title . '</a></li>';
        }
    }
    ?>
</ul>
</body>
</html>