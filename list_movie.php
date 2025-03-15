<html><head><title>Movie Data</title>
<style type="text/css">
BODY	{ font-family: sans-serif; font-size:11pt; }
DIV 	{ margin:3px; }
FORM	{ margin:0; }
SELECT	{ font-family: sans-serif; font-size:11pt; }
IFRAME	{ border:0; width: 20em; height:8em; }
.delete	{ background-color:#ff8888; width: 20em; }
.add	{ background-color:#88ff88; width: 20em; }
</style>
<script language="javascript">
function initialize() {
	document.getElementById("add_director").style.display="none";
	document.getElementById("delete_director").style.display="none";
	document.getElementById("director_action").style.display="none";
	document.getElementById("add_cast").style.display="none";
	document.getElementById("delete_cast").style.display="none";
	document.getElementById("cast_action").style.display="none";
	document.getElementById("add_music").style.display="none";
	document.getElementById("delete_music").style.display="none";
	document.getElementById("music_action").style.display="none";
}
function div_show(name) {
	document.getElementById(name).style.display="inline";
}
function div_hide(name) {
	document.getElementById(name).style.display="none";
}
function reset_form(name) {
	document.getElementById(name).reset();
}
function reset_list(name) {
	document.getElementById(name).reset();
	var sel_object=document.getElementById(name+'_person_select');
    for (i=0; i,sel_object.options.length; i++) {
        sel_object.options[i].selected=false;
    }
}
</script></head>
<body onload="initialize();">
<form name="movie" method="post"><select name="id" onchange="this.form.submit();">
<option value="0"></option><?php
require 'inc_pass.php';
$connect=mysqli_connect($sqlname,$sqluser,$sqlpass,"movie");
$movie_id=''; if (isset($_POST["id"])) $movie_id=$_POST["id"];
$query="select * from movie order by alpha_title";
$result=mysqli_query($connect,$query);
$sel=mysqli_fetch_all($result,MYSQLI_ASSOC);

foreach ($sel as $each) {
    $selected=''; if ($each['id']==$movie_id) {
        $selected=' selected'; $out_title=$each['title'];
        $out_year=$each['year']; $out_rating=$each['rating'];
        $out_plot=$each['plot']; $out_runtime=$each['runtime'];
    }
    echo "<option$selected value=\"{$each['id']}\">{$each['title']}</option>";
}

print <<<END
</select><input type="submit" value="Submit"></form>
END;
if ($movie_id!='') {
    echo "title: <b><i>$out_title</i></b><br/>";
    echo "year released: <b>$out_year</b><br/>";
    echo "rating: <b>$out_rating</b><br/>";
    if (!is_null($out_runtime) && $out_runtime>0) {
        echo "run time: <b>$out_runtime minutes</b><br/>";
    } else {
        echo "run time:<br/>";
    }
    echo "plot: <b>$out_plot</b><br/>";

    $person_query="select id, first_name, last_name from movie.person_list order by coalesce(last_name, 'zz'), first_name";
    $person_result=mysqli_query($connect,$person_query);
    $person_sel=mysqli_fetch_all($person_result,MYSQLI_ASSOC);
    $add_person='';
    foreach ($person_sel as $person_each) {
        $str_person=trim($person_each['first_name'] . ' ' . $person_each['last_name']);
        $add_person.="<option value=\"{$person_each['id']}\">$str_person</option>";
    }
    $add_person.="</select><input type=\"hidden\" name=\"movie_id\" value=\"$movie_id\"><br/>";  
    
    $director_query="select p.id, p.first_name, p.last_name from movie.director c left join person_list p on p.id=c.person_id where c.movie_id=$movie_id order by coalesce(p.last_name, 'zz'), p.first_name";
    $director_result=mysqli_query($connect,$director_query);
    $director_sel=mysqli_fetch_all($director_result,MYSQLI_ASSOC);
    $out_director=''; $comma='';
    $edit_director="<input type=\"hidden\" name=\"movie_id\" value=\"$movie_id\">";
    foreach ($director_sel as $director_each) {
        $str_director=trim($director_each['first_name'] . ' ' . $director_each['last_name']);
        $out_director.=$comma . $str_director; $comma=', ';
        $edit_director.="<input type=\"checkbox\" name=\"director{$director_each['id']}\" value=\"{$director_each['id']}\">$str_director<br/>";
    }

    print<<<END
    director: <b>$out_director</b><br/>
    <div id="modify_director">
    <input type="button" value="Add To Director(s)" onclick="div_show('add_director');div_hide('modify_director')"\>
    <input type="button" value="Delete From Director(s)" onclick="div_show('delete_director');div_hide('modify_director');">
    </div>

    <div id="add_director">
    <form class="add" name="addto_director" id="addto_director" method="post" action="add_director.php" target="director_action">
    <select id="addto_director_person_select" name="person[]" multiple>$add_person<input type="submit" value="Add To Director(s)" onclick="div_hide('add_director');div_show('director_action');">
    <input type="button" value="Cancel" onclick="div_hide('add_director');div_show('modify_director');reset_list('addto_director');">
    </form>
    </div>

    <div id="delete_director">
    <form class="delete" name="edit_director" id="edit_director" method="post" action="edit_director.php" target="director_action">$edit_director<input type="submit" value="Delete From Director(s)" onclick="div_hide('delete_director');div_show('director_action');">
    <input type="button" value="Cancel" onclick="div_hide('delete_director');div_show('modify_director');reset_form('edit_director');">
    </form>
    </div>

    <div><iframe id="director_action" name="director_action" scrolling="auto"></iframe></div>
    END;

    $cast_query="select p.id, p.first_name, p.last_name from movie.cast c left join person_list p on p.id=c.person_id where c.movie_id=$movie_id order by coalesce(p.last_name, 'zz'), p.first_name";
    $cast_result=mysqli_query($connect,$cast_query);
    $cast_sel=mysqli_fetch_all($cast_result,MYSQLI_ASSOC);
    $out_cast=''; $comma='';
    $edit_cast="<input type=\"hidden\" name=\"movie_id\" value=\"$movie_id\">";
    foreach ($cast_sel as $cast_each) {
        $str_cast=trim($cast_each['first_name'] . ' ' . $cast_each['last_name']);
        $out_cast.=$comma . $str_cast; $comma=', ';
        $edit_cast.="<input type=\"checkbox\" name=\"cast{$cast_each['id']}\" value=\"{$cast_each['id']}\">$str_cast<br/>";
    }

    print<<<END
    cast: <b>$out_cast</b><br/>
    <div id="modify_cast">
    <input type="button" value="Add To Cast" onclick="div_show('add_cast');div_hide('modify_cast')"\>
    <input type="button" value="Delete From Cast" onclick="div_show('delete_cast');div_hide('modify_cast');">
    </div>

    <div id="add_cast">
    <form class="add" name="addto_cast" id="addto_cast" method="post" action="add_cast.php" target="cast_action">
    <select id="addto_cast_person_select" name="person[]" multiple>$add_person<input type="submit" value="Add To Cast" onclick="div_hide('add_cast');div_show('cast_action');">
    <input type="button" value="Cancel" onclick="div_hide('add_cast');div_show('modify_cast');reset_list('addto_cast');">
    </form>
    </div>

    <div id="delete_cast">
    <form class="delete" name="edit_cast" id="edit_cast" method="post" action="edit_cast.php" target="cast_action">$edit_cast<input type="submit" value="Delete From Cast" onclick="div_hide('delete_cast');div_show('cast_action');">
    <input type="button" value="Cancel" onclick="div_hide('delete_cast');div_show('modify_cast');reset_form('edit_cast');">
    </form>
    </div>

    <div><iframe id="cast_action" name="cast_action" scrolling="auto"></iframe></div>
    END;

    $music_query="select p.id, p.first_name, p.last_name from movie.music c left join person_list p on p.id=c.person_id where c.movie_id=$movie_id order by coalesce(p.last_name, 'zz'), p.first_name";
    $music_result=mysqli_query($connect,$music_query);
    $music_sel=mysqli_fetch_all($music_result,MYSQLI_ASSOC);
    $out_music=''; $comma='';
    $edit_music="<input type=\"hidden\" name=\"movie_id\" value=\"$movie_id\">";
    foreach ($music_sel as $music_each) {
        $str_music=trim($music_each['first_name'] . ' ' . $music_each['last_name']);
        $out_music.=$comma . $str_music; $comma=', ';
        $edit_music.="<input type=\"checkbox\" name=\"music{$music_each['id']}\" value=\"{$music_each['id']}\">$str_music<br/>";
    }

    print<<<END
    music: <b>$out_music</b><br/>
    <div id="modify_music">
    <input type="button" value="Add To Music" onclick="div_show('add_music');div_hide('modify_music')"\>
    <input type="button" value="Delete From Music" onclick="div_show('delete_music');div_hide('modify_music');">
    </div>

    <div id="add_music">
    <form class="add" name="addto_music" id="addto_music" method="post" action="add_music.php" target="music_action">
    <select id="addto_music_person_select" name="person[]" multiple>$add_person<input type="submit" value="Add To Music" onclick="div_hide('add_music');div_show('music_action');">
    <input type="button" value="Cancel" onclick="div_hide('add_music');div_show('modify_music');reset_list('addto_music');">
    </form>
    </div>

    <div id="delete_music">
    <form class="delete" name="edit_music" id="edit_music" method="post" action="edit_music.php" target="music_action">$edit_music<input type="submit" value="Delete From Music" onclick="div_hide('delete_music');div_show('music_action');">
    <input type="button" value="Cancel" onclick="div_hide('delete_music');div_show('modify_music');reset_form('edit_music');">
    </form>
    </div>

    <div><iframe id="music_action" name="music_action" scrolling="auto"></iframe></div>
    END;
}
?></body></html>