<?php
function list_this($table, $action, $movie_id) {
    require 'inc_pass.php';
    $connect=mysqli_connect($sqlname, $sqluser, $sqlpass, "movie");
    if (!$connect) die("No Database Connection!");
    if (!isset($table) || !isset($movie_id) || !isset($action)) die("Form Error!");
    if (!in_array($table, ['director', 'cast', 'music'])) die("Table Error!");

    $title_query="select title from movie where id=$movie_id";
    $result=mysqli_query($connect, $title_query);
    $title_sel=mysqli_fetch_assoc($result);
    $title=addcslashes($title_sel['title'],"'");

    $word='add to'; if ($action=='delete') $word='delete from';

    switch($action) {
        case 'list':
            $query="select * from $table t join person_list p on p.id=t.person_id where t.movie_id=$movie_id order by coalesce(p.last_name, 'zz'), p.first_name";
            $result=mysqli_query($connect, $query);
            $list=mysqli_fetch_all($result, MYSQLI_ASSOC);
            $comma=''; $out_list='';
            foreach ($list as $each) {
                $name=trim($each['first_name'] . ' ' . $each['last_name']);
                $out_list.=$comma . $name; $comma=', ';
            }
            break;

        case 'add':
            $query="select * from person_list p where p.id not in (select person_id from $table where movie_id=$movie_id) order by p.first_name, p.last_name";
            $result=mysqli_query($connect, $query);
            $list=mysqli_fetch_all($result, MYSQLI_ASSOC);
            $count=0; $list_count=count($list);
            $col1=''; $col2=''; $col3='';
            foreach ($list as $each) {
                $name=trim($each['first_name'] . ' ' . $each['last_name']);
                $tag=trim($each['first_name'] . '_' . $each['last_name']);
                $out_put="<input class=\"smaller\" type=\"checkbox\" name=\"{$each['id']}_$tag\" value=\"{$each['id']}\"/>$name<br/>";
                if ($count>2*$list_count/3) {
                    $col3.=$out_put;
                } elseif ($count>$list_count/3) {
                    $col2.=$out_put;
                } else {
                    $col1.=$out_put;
                }
                $count++;
            }
            $out_list="<table width=\"100%\"><caption class=\"smaller\"><u>Select people to $word $table of <i>$title</i></u></caption><tr valign=\"top\"><td class=\"smaller\">$col1</td><td class=\"smaller\">$col2</td><td class=\"smaller\">$col3</td></tr></table>";
            break;

        case 'delete':
            $query="select * from person_list p where p.id in (select person_id from $table where movie_id=$movie_id) order by p.first_name, p.last_name";
            $result=mysqli_query($connect, $query);
            $list=mysqli_fetch_all($result, MYSQLI_ASSOC);
            $count=0; $list_count=count($list);
            $col1=''; $col2=''; $col3='';
            foreach ($list as $each) {
                $name=trim($each['first_name'] . ' ' . $each['last_name']);
                $tag=trim($each['first_name'] . '_' . $each['last_name']);
                $out_put="<input class=\"smaller\" type=\"checkbox\" name=\"{$each['id']}_$tag\" value=\"{$each['id']}\"/>$name<br/>";
                if ($count>2*$list_count/3) {
                    $col3.=$out_put; 
                } elseif ($count>$list_count/3) {
                    $col2.=$out_put;
                } else {
                    $col1.=$out_put;
                }
                $count++;
            }
            $out_list="<table width=\"100%\"><caption class=\"smaller\"><u>Select people to $word $table of <i>$title</i></u></caption><tr valign=\"top\"><td class=\"smaller\">$col1</td><td class=\"smaller\">$col2</td><td class=\"smaller\">$col3</td></tr></table>";
            break;
        default:
            die("Action Error!");
    }
    return($out_list);
}
?>