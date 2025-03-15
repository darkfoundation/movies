<body onload="window.parent.location.reload();">
<?php
if (!isset($_GET)) die('Form Error!');

foreach ($_GET as $key=>$value) {
    if ($key == 'movie_id') {
        $movie_id = $value;
    // } elseif ($key == 'table') {
    //     $table=$value;
    } elseif ($key == 'sub') {
        $sub_ids = explode(',',$value);
    } elseif ($key == 'add') {
        $add_ids = explode(',',$value);
    }
}

require 'inc_pass.php';
$mysqli=mysqli_connect($sqlname,$sqluser,$sqlpass,"movie");
if (!$mysqli) die("No Database Connection!");

if (isset($sub_ids)) {
    foreach($sub_ids as $subEach) {
        $stmt = $mysqli->prepare("delete from music where movie_id=? and person_id=?");
        $stmt->bind_param('ii', $movie_id, $subEach);
        $stmt->execute();
        printf("%d row deleted.\n", $mysqli->affected_rows);
    }
}

if (isset($add_ids)) {
    foreach($add_ids as $addEach) {
        $stmt = $mysqli->prepare("insert into music (movie_id, person_id) values (?, ?)");
        $stmt->bind_param('ii', $movie_id, $addEach);
        $stmt->execute();
        printf("%d row inserted.\n", $mysqli->affected_rows);
    }
}
?></body>