<html><head><title>Movie Data</title>
<style type="text/css">
BODY	{ font-family: sans-serif; font-size: 11pt; }
FORM	{ margin: 0; }
IFRAME	{ border: 0; display:none; }
CAPTION { white-space: nowrap; }
.smaller	{ font-family: sans-serif; font-size: 10pt; }
.delete	{ background-color: #ff8888; text-align: center; }
.delete-smaller	{ background-color: #ff8888; text-align: center; font-size: 10pt; }
.add	{ background-color: #88ff88; text-align: center; }
.add-smaller	{ background-color: #88ff88; text-align: center; font-size: 10pt; }
.description	{ text-align: right; white-space: nowrap; }
.fact	{ background-color:#cccccc; width: 35em; }
.plot	{ background-color:#cccccc; width: 35em; font-size: 10pt; }
.centered   { text-align: center; }
.centered-smaller   { text-align: center; font-family: sans-serif; font-size: 10pt; }
</style></head><body><table cellpadding="3" align="center">
<caption><form name="movie" method="post"><select name="id" onchange="this.form.submit();">
<option>--select movie--</option>
<?php
require 'inc_pass.php';
require 'list_inc.php';
$connect=mysqli_connect($sqlname, $sqluser, $sqlpass, "movie");
if (!$connect) die("No Database Connecton!");
$query="select * from movie order by alpha_title, year, runtime";
$result=mysqli_query($connect, $query);
$sel=mysqli_fetch_all($result, MYSQLI_ASSOC);
$movie_id=''; if (isset($_POST["id"])) $movie_id=$_POST["id"];
foreach ($sel as $each) {
    $selected=''; if ($each['id']==$movie_id) {
        $selected=' selected'; $out_title=$each['title'];
        $out_year=$each['year']; $out_rating=$each['rating'];
        $out_plot=$each['plot']; $out_runtime=$each['runtime'];
    }
    echo "<option$selected value=\"{$each['id']}\">{$each['title']}</option>";
}
echo '</select><input type="submit" value="Submit"></form></caption>';
?></body></html>