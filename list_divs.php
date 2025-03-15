<?php
function list_this($table, $movie_id) {
    require 'inc_pass.php';
    $connect=mysqli_connect($sqlname, $sqluser, $sqlpass, "movie");
    if (!$connect) die("No Database Connection!");
    if (!isset($table) || !isset($movie_id) || !isset($action)) die("Form Error!");
    if (!in_array($table, ['director', 'cast', 'music'])) die("Table Error!");

    $title_query="select title from movie where id=$movie_id";
    $result=mysqli_query($connect, $title_query);
    $title_sel=mysqli_fetch_assoc($result);
    $title=addcslashes($title_sel['title'],"'");

    $query="select * from $table t join person_list p on p.id=t.person_id where t.movie_id=$movie_id order by coalesce(p.last_name, 'zz'), p.first_name";
        $result=mysqli_query($connect, $query);
        $list=mysqli_fetch_all($result, MYSQLI_ASSOC);
        $comma=''; $out_list='';
        foreach ($list as $each) {
            $name=trim($each['first_name'] . ' ' . $each['last_name']);
            $out_list.=$comma . $name; $comma=', ';
        }
    return $out_list;
}