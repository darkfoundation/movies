<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Data Update</title>
</head>
<body style="font-family: sans-serif">
<?php
$movie_num=30;
require 'inc_pass.php';
require 'list_inc.php';
$connect=mysqli_connect($sqlname,$sqluser,$sqlpass,"movie");
if (!$connect) die("No Database Connection!");
$movie_query = "select title from movie where id=$movie_num limit 1";
$result = mysqli_query($connect, $movie_query);
$list = mysqli_fetch_all($result,MYSQLI_ASSOC);
echo '<table><caption><i>' . $list[0]['title'] . '</i></caption><tr>';
$person_query = "select * from person_list order by first_name, last_name";
$result = mysqli_query($connect, $person_query);
$list = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
$person_list = '';
foreach (['cast', 'director', 'music'] as $table) {
    $query = "select person_id from $table where movie_id=$movie_num";
    $query_result = mysqli_query($connect, $query);
    $target_list = mysqli_fetch_all($query_result, MYSQLI_ASSOC);
    $target_label = ucfirst($table);
    print <<< end
    <td><div style="padding-bottom: 5px; text-align: center;">Update $target_label:&nbsp;<input type="text" id="searchInput$target_label" placeholder="Search $target_label..."  style="border: thin solid black;"></div>
    <form><div style="padding: 5px; border: thin solid black; width: 300px; height: 150px; overflow-y: auto; font-family: sans-serif;">
    <ul id="itemList$target_label" style="list-style: none; padding-left: 5px; margin-top: 0px;">
    end;
    foreach ($list as $each) {
        $name = trim($each['first_name'] . ' ' . $each['last_name']);
        $index = $each['id'];
        $is_checked = '';
        foreach ($target_list as $target) {
            if ($target['person_id'] == $index) {
                $is_checked = ' checked';
            }
        }
        echo PHP_EOL . "\t<li><input type=\"checkbox\" id=\"$index\"$is_checked><span id=\"$table$index\">$name</span></li>";
    }
    echo PHP_EOL . '</ul></div><div style="padding-top: 5px; text-align: center;"><input type="reset" value="reset this form""></div></form></td>' . PHP_EOL;
}
?>
    </tr></table>
    <script src="script.js"></script>
</body>
</html>