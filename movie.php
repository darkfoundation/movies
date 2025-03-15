<html><head><title>Movie Data</title>
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
<script language="javascript">
function initialize() {
}
function div_show(name) {
	document.getElementById(name).style.display="block";
}   
function div_hide(name) {
	document.getElementById(name).style.display="none";
}
function reset_form(name) {
	document.getElementById(name).reset();
}
function check_boxes(name1, name2, action, table, title) {
    submit_button=''; cancel_button=''; div_output=''; id_array=[];
    action2='added'; if (action=='delete') { action2='deleted'; }
    submit_button=submit_button.concat("<input type=\"submit\" value=\"Proceed\" onclick=\"div_hide('", table, "_action_", action, "'); div_show('modify_", table, "');\">");
    cancel_button=cancel_button.concat("<input type=\"button\" value=\"Cancel\" onclick=\"div_hide('", table, "_action_", action, "'); div_show('modify_", table, "');\">");
    checkboxes=document.getElementById(name1).querySelectorAll('input[type=checkbox]:checked')
    if (checkboxes.length==0) {
        div_output=div_output.concat("Nobody found to ", action, "!<br/>", cancel_button);
    } else {
        div_output=div_output.concat("<u>The following will be ", action2, " from the ", table, " of  <i>", title, "</i>:</u>"); 
        for (var i=0; i<checkboxes.length; i++) {
            text_box=checkboxes[i].name;
            text_name=text_box.slice(text_box.indexOf('_')+1).replace('_',' ');
            div_output=div_output.concat("<br/>", text_name);
            id_array.push(checkboxes[i].value);
        }
    }
    div_output=div_output.concat("<input type=\"hidden\" name=\"person_id\" value=\"", id_array , "\"><br/>", submit_button, cancel_button);
    document.getElementById(table.concat("_stage_", action)).innerHTML=div_output;
}
function change_title(name1, name2, title) {
    submit_button=''; cancel_button=''; div_output=''; id_array=[];
    submit_button=submit_button.concat("<input type=\"submit\" value=\"Proceed\" onclick=\"div_hide('name1'); div_show('name2');\">");
    cancel_button=cancel_button.concat("<input type=\"button\" value=\"Cancel\" onclick=\"div_hide('name1'); div_show('name2');\">");
    div_output=div_output.concat("<u>The title will be updated to <i>", title, "</i>:</u>"); 
    div_output=div_output.concat("<input type=\"hidden\" name=\"update_title\" value=\"", title , "\"><br/>", submit_button, cancel_button);
    document.getElementById(table.concat("_stage_", action)).innerHTML=div_output;
}
function edit_movie(movie_id) {
	document.getElementById("edit_title").style.display="block";
	document.getElementById("edit_year").style.display="block";
	document.getElementById("edit_rating").style.display="block";
	document.getElementById("edit_runtime").style.display="block";
	document.getElementById("edit_plot").style.display="block";

	document.getElementById("modify_director").style.display="block";
	document.getElementById("modify_cast").style.display="block";
	document.getElementById("modify_music").style.display="block";
}
</script></head><body onload="initialize();"><table cellpadding="3" align="center">
<caption><form name="movie" method="post"><select name="id" onchange="this.form.submit();">
<option>--select movie--</option>
<?php
require 'inc_pass.php';
require 'list_inc.php';
$connect=mysqli_connect($sqlname, $sqluser, $sqlpass, "movie");
if (!$connect) die("No Database Connection!");
$rating_q="select * from rating_list";
$result_q=mysqli_query($connect, $rating_q);
$rating_sel=mysqli_fetch_all($result_q, MYSQLI_ASSOC);
$ratings='<select name="rating" id="rating"><option value=""></option>';
foreach ($rating_sel as $each_q) {
    $ratings.="<option value=\"{$each_q['rating']}\">{$each_q['rating']}</option>";
}
$ratings.='</option>' . PHP_EOL;
$query="select * from movie order by alpha_title, year, runtime";
$result=mysqli_query($connect, $query);
$sel=mysqli_fetch_all($result, MYSQLI_ASSOC);
$movie_id=''; if (isset($_POST["id"])) $movie_id=$_POST["id"];
$out_rating=''; foreach ($sel as $each) {
    $selected=''; if ($each['id']==$movie_id) {
        $selected=' selected';
        $out_title=$each['title']; $out_title_form=addcslashes($each['title'],"'");
        $out_year=$each['year']; $out_rating=$each['rating'];
        $out_plot=$each['plot']; $out_runtime=$each['runtime'];
    }
    echo "<option$selected value=\"{$each['id']}\">{$each['title']}</option>" . PHP_EOL;
}

if ($movie_id!='') {
    echo "</select><input type=\"submit\" value=\"Submit\">&nbsp;<input type=\"button\" value=\"Edit This Movie\" onclick=\"edit_movie('$movie_id');\">";
} else {
    echo '</select><input type="button" value="Submit">';
}
echo '&nbsp;<input type="button" value="Add Movie"></form></caption>';
if ($movie_id!='') {
    if (!is_null($out_runtime) && $out_runtime>0) {
        $out_runtime.=" minutes";
    } else {
        $out_runtime='';
    }
    $director_list=list_this('director', 'list', $movie_id);
    $cast_list=list_this('cast', 'list', $movie_id);
    $music_list=list_this('music', 'list', $movie_id);
    $add['director']=list_this('director', 'add', $movie_id);
    $add['cast']=list_this('cast', 'add', $movie_id);
    $add['music']=list_this('music', 'add', $movie_id);
    $delete['director']=list_this('director', 'delete', $movie_id);
    $delete['cast']=list_this('cast', 'delete', $movie_id);
    $delete['music']=list_this('music', 'delete', $movie_id);
    $div_list=[]; $word=['director'=>'Director', 'cast'=>'Cast', 'music'=>'Music'];
    foreach(['director', 'cast', 'music'] as $enum) {
        $word=ucfirst($enum);
        $div_list[$enum]="<div style=\"display: none;\" class=\"centered\" id=\"modify_$enum\"><input type=\"button\" value=\"Add To $word\" onclick=\"div_show('add_$enum');div_hide('modify_$enum');\"\>&nbsp;<input type=\"button\" value=\"Delete From $word\" onclick=\"div_show('delete_$enum'); div_hide('modify_$enum');\"></div>";

        $div_list[$enum].="<div style=\"display: none; height: 150px; overflow: auto;\" class=\"centered\" id=\"add_$enum\"><form class=\"add\" name=\"addto_$enum\" id=\"addto_$enum\"><span id=\"add_update_$enum\">{$add[$enum]}</span><input type=\"button\" value=\"Add To $word\" onclick=\"div_hide('add_$enum'); div_show('{$enum}_action_add'); check_boxes('addto_$enum', '{$enum}_action_add', 'add', '$enum', '$out_title_form');\"><input type=\"button\" value=\"Cancel\" onclick=\"div_hide('add_$enum'); div_show('modify_$enum');\"></form></div>";

        $div_list[$enum].="<div style=\"display: none;\" class=\"centered\" id=\"delete_$enum\"><form class=\"delete\" name=\"deletefrom_$enum\" id=\"deletefrom_$enum\"><span id=\"delete_update_$enum\">{$delete[$enum]}</span><input type=\"button\" value=\"Delete From $word\" onclick=\"div_hide('delete_$enum'); div_show('{$enum}_action_delete'); check_boxes('deletefrom_$enum', 'deleteout_$enum', 'delete', '$enum' ,'$out_title_form');\"><input type=\"button\" value=\"Cancel\" onclick=\"div_hide('delete_$enum'); div_show('modify_$enum');\"></form></div>";

        $div_list[$enum].="<div style=\"display: none;\" class=\"add-smaller\" id=\"{$enum}_action_add\" name=\"{$enum}_action_add\"><form class=\"add\" name=\"addout_$enum\" id=\"addout_$enum\" target=\"{$enum}_add_frame\" action=\"add.php\" method=\"post\"><input type=\"hidden\" name=\"movie_id\" value=\"$movie_id\"><input type=\"hidden\" name=\"table\" value=\"$enum\"><span id=\"{$enum}_stage_add\"></span></form><iframe id=\"{$enum}_add_frame\" name=\"{$enum}_add_frame\"></iframe></div>";

        $div_list[$enum].="<div style=\"display: none;\" class=\"delete-smaller\" id=\"{$enum}_action_delete\" name=\"{$enum}_action_delete\"><form class=\"delete\" name=\"deleteout_$enum\" id=\"deleteout_$enum\" target=\"{$enum}_delete_frame\" action=\"delete.php\" method=\"post\"><input type=\"hidden\" name=\"movie_id\" value=\"$movie_id\"><input type=\"hidden\" name=\"table\" value=\"$enum\"><span id=\"{$enum}_stage_delete\"></span></form><iframe id=\"{$enum}_delete_frame\" name=\"{$enum}_delete_frame\"></iframe></div>";
    }

    print<<<END
    <tr valign="top"><td class="description">title </td><td class="fact"><span id="this_title"><i>$out_title</i></span>
    <div class="centered" style="display: none;" id="edit_title"><input type="button" value="Edit Title" onclick="div_show('edit_title_field'); div_hide('this_title'); div_hide('edit_title');"></div>

    <div style="text-align: left; display: none;" class="edit" id="edit_title_field">
    <input id="new_title" size="50%" type="text" value="$out_title">
    <input type="button" value="Update Title" onclick="document.getElementById('').; div_show('edit_title_action'); div_hide('edit_title_field');">
    <input type="button" value="Cancel" onclick="div_show('this_title'); div_show('edit_title'); div_hide('edit_title_field');"></div>

    <div style="display: none;" class="edit" id="edit_title_action">
    <input type="button" value="Proceed" onclick="change_title(); "><input type="button" value="Cancel" onclick="div_show('this_title'); div_show('edit_title'); div_hide('edit_title_action');"></div>
    </td></tr>

    <tr valign="top"><td class="description">year </td><td class="fact"><span id="this_year">$out_year</span>
    <div class="centered" style="display: none;" id="edit_year"><input type="button" value="Edit Year" onclick="div_show('edit_year_field'); div_hide('this_year'); div_hide('edit_year');"></div>
    <div class="edit-smaller" style="text-align: left; display: none;" id="edit_year_field"><input size="8" type="text" value="$out_year"><input type="button" value="Update Year"><input type="button" value="Cancel" onclick="div_show('this_year'); div_show('edit_year'); div_hide('edit_year_field');"></div></td></tr>

    <tr valign="top"><td class="description">rating </td><td class="fact"><span id="this_rating">$out_rating</span>
    <div class="centered" style="display: none;" id="edit_rating"><input type="button" value="Edit Rating" onclick="div_show('edit_rating_field'); div_hide('this_rating'); div_hide('edit_rating'); select_rating();"></div>
    <div class="edit-smaller" style="text-align: left; display: none;" id="edit_rating_field">$ratings<input type="button" value="Update Rating"><input type="button" value="Cancel" onclick="div_show('this_rating'); div_show('edit_rating'); div_hide('edit_rating_field');"></div></td></tr>

    <tr valign="top"><td class="description">runtime </td><td class="fact"><span id="this_runtime">$out_runtime</span>
    <div class="centered" style="display: none;" id="edit_runtime"><input type="button" value="Edit Runtime" onclick="div_show('edit_runtime_field'); div_hide('this_runtime'); div_hide('edit_runtime');"></div>
    <div class="edit-smaller" style="text-align: left; display: none;" id="edit_runtime_field"><input size="8" type="text" value="$out_runtime">&nbsp;minutes <input type="button" value="Update Runtime"><input type="button" value="Cancel" onclick="div_show('this_runtime'); div_show('edit_runtime'); div_hide('edit_runtime_field');"></div></td></tr>

    <tr valign="top"><td class="description">plot </td><td class="fact"><span id="this_plot">$out_plot</span>
    <div class="centered" style="display: none;" id="edit_plot"><input type="button" value="Edit Plot" onclick="div_show('edit_plot_field'); div_hide('this_plot'); div_hide('edit_plot');"></div>
    <div class="edit-smaller" style="display: none;" id="edit_plot_field"><textarea rows="2">$out_plot</textarea><br/><input type="button" value="Update Plot"><input type="button" value="Cancel" onclick="div_show('this_plot'); div_show('edit_plot'); div_hide('edit_plot_field');"></div></td></tr>

    <tr valign="top"><td class="description">director </td><td class="fact"><span id="director_data">$director_list</span><br/>{$div_list['director']}</td></tr>
    <tr valign="top"><td class="description">cast </td><td class="fact"><span id="cast_data">$cast_list</span><br/>{$div_list['cast']}</td></tr>
    <tr valign="top"><td class="description">music </td><td class="fact"><span id="music_data">$music_list</span><br/>{$div_list['music']}</td></tr></table>

    <script>function select_rating() {
            for (let i=0; i<document.getElementById('rating').options.length; i++) {
                if (document.getElementById('rating').options[i].value == "$out_rating") {
                    document.getElementById('rating').options.selectedIndex=i;
                }
            }
        }
    </script>
    END;
    if (isset($_POST)) echo '<script>document.movie.id.remove(0);</script>';
    if ($out_rating!='') echo '<script>document.getElementById("rating").remove(0);</script>';
}
?></body></html>