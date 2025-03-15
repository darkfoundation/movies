<body onload="window.parent.location.reload();">
<?php
if (!isset($_GET)) die('Form Error!');

foreach ($_GET as $key=>$value) {
    if ($key == 'movie_id') {
        $movie_id = $value;
    // } elseif ($key == 'table') {
    //     $table=$value;
    } elseif ($key == 'sub') {
        $sub_ids = explode('|',$value);
    } elseif ($key == 'add') {
        $add_ids = explode('|',$value);
    }
}

require 'inc_pass.php';
$mysqli=mysqli_connect($sqlname,$sqluser,$sqlpass,"movie");
if (!$mysqli) die("No Database Connection!");

if (isset($sub_ids)) {
    foreach($sub_ids as $subEach) {
        $subCast = explode(',', $subEach);
        foreach($subCast as $eachCast) {
            if (!empty($eachCast)) {
                $stmt = $mysqli->prepare("delete from cast where movie_id=? and person_id=?");
                //echo "delete from cast where movie_id=$movie_id and person_id=$eachCast<br />";
                $stmt->bind_param('ii', $movie_id, $eachCast);
                $stmt->execute();
                printf("%d row deleted.\n", $mysqli->affected_rows);
            }
        }
    }
}

if (isset($add_ids)) {
    foreach($add_ids as $key=>$addEach) {
        $subCast = explode(',', $addEach);
        foreach($subCast as $eachCast) {
            if (!empty($eachCast)) {
                $stmt = $mysqli->prepare("insert into cast (movie_id, person_id, level) values (?, ?, ?)");
                //echo "insert into cast (movie_id, person_id, level) values ($movie_id, $eachCast, $key)<br />";
                $stmt->bind_param('iii', $movie_id, $eachCast, $key);
                $stmt->execute();
                printf("%d row inserted.\n", $mysqli->affected_rows);

            }
        }
    }
}
?></body>