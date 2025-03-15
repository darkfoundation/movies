<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Data</title>
    <style type="text/css">
        BODY	{ font-family: sans-serif; font-size: 11pt; }
        FORM	{ margin: 0; }
        IFRAME	{ border: 0; display:none; }
        CAPTION { white-space: nowrap; }
        textarea    { width: 100%; }
        .smaller	{ font-family: sans-serif; font-size: 10pt; }
        .delete	{ background-color: #ff8888; text-align: center; }
        .delete-smaller	{ background-color: #ff8888; text-align: center; font-size: 10pt; }
        .add	{ background-color: #88ff88; text-align: center; }
        .add-smaller	{ background-color: #88ff88; text-align: center; font-size: 10pt; }
        .edit	{ background-color: #4488ff; text-align: center; padding: 3px; }
        .edit-smaller	{ background-color: #4488ff; text-align: center; font-size: 10pt; padding: 3px; }
        .description	{ text-align: right; white-space: nowrap; }
        .fact	{ background-color:#cccccc; width: 35em; }
        .plot	{ background-color:#cccccc; width: 35em; font-size: 10pt; }
        .centered   { text-align: center; }
        .centered-smaller   { text-align: center; font-family: sans-serif; font-size: 10pt; }
        .smaller   { font-size: 10pt; }
    </style>
</head>
<body style="font-family: sans-serif">
    <table cellpadding="3" style="text-align: center; width: 600px"><form name="movie" method="post">
        <caption><nobr>Movie Title: <select name="id" onchange="this.form.submit();">
<?php
(isset($_POST["id"]))? ($movie_id = $_POST["id"]) : ($movie_id = 0);
echo "\t\t\t<option value=\"0\">----select movie----</option>";
require 'inc_pass.php';
require 'list_inc.php';
$connect = mysqli_connect($sqlname,$sqluser,$sqlpass,"movie");
if (!$connect) die("No Database Connection!");

$movie_query = "select * from movie order by alpha_title, year, runtime";
$movie_result = mysqli_query($connect, $movie_query);
$movie_list = mysqli_fetch_all($movie_result,MYSQLI_ASSOC);
$title = []; $year = []; $rating = []; $runtime = []; $plot = [];
$title[0] = ''; $year[0] = ''; $rating[0] = ''; $runtime[0] = ''; $plot[0] = '';
foreach ($movie_list as $each) {
    ($movie_id == $each['id']) ? ($sel = " selected") : ($sel = "");
    echo "\t\t\t<option{$sel} value=\"{$each['id']}\">{$each['title']}</option>\n";
    $title[$each['id']] = $each["title"];
    $year[$each['id']] = $each["year"];
    $rating[$each['id']] = $each["rating"];
    $runtime[$each['id']] = $each["runtime"];
    $plot[$each['id']] = $each["plot"];
}

echo"\t</select></nobr></caption>\n</form><tr class='fact' style='text-align: left'>";

echo "\t\t<td width='33%'> release year: $year[$movie_id] </td><td width='33%'>rating: $rating[$movie_id]</td><td width='33%'>runtime (minutes): $runtime[$movie_id]</td></tr>\n";
echo "<tr class='fact' width='100%' style='text-align: left'><td colspan=\"3\">plot summary: $plot[$movie_id] </td></tr>\n";

foreach (['cast', 'director', 'music'] as $table) {
    $query = "select * from person_list l join $table t on t.person_id=l.id where t.movie_id=$movie_id order by l.first_name, l.last_name";
    $query_result = mysqli_query($connect, $query);
    $target_list = mysqli_fetch_all($query_result, MYSQLI_ASSOC);
    $target_label = ucfirst($table);
    ${$table . '_list'} = "<li id='list$target_label' style='list-style-type: none;'>\n";
    foreach ($target_list as $target_each) {
        ${$table . '_list'} .= "\t\t<ul style='margin: 0 0 0 1em; padding: 0;'>" . trim($target_each['first_name'] . ' ' . $target_each['last_name']) . "</ul>\n";
    }
    ${$table . '_list'} .= "\t\t</li>\n";
}

echo "\t<tr class='fact' style='text-align: left; vertical-align: top;'>\n\t\t<td width='33%'>cast: $cast_list </td><td width='33%'>director: $director_list </td><td width='33%'>music: $music_list</td></tr>\n";

/*
$person_query = "select * from person_list order by first_name, last_name";
$person_result = mysqli_query($connect, $person_query);
$person_list = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
$person_list = '';

foreach (['cast', 'director', 'music'] as $table) {
    $query = "select person_id from $table where movie_id=$movie_id";
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
        echo "\t\t<li><input type=\"checkbox\" id=\"$index\"$is_checked> <span id=\"$table$index\">$name</span></li>" . PHP_EOL;
    }
    echo '</ul></div><div style="padding-top: 5px; text-align: center;"><input type="reset" value="reset this form""></div></form></td>';
}*/
?>
    <!--/tr></table>
    <script src="script.js"></script-->
</table>
</body>
</html>