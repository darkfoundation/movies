<?php
if (!isset($_POST)) die('Form Error!');
$person_ids=[];
foreach ($_POST as $key=>$value) {
    if ($key=='movie_id') {
        $movie_id=$value;
    } elseif ($key=='table') {
        $table=$value;
    } else {
        $person_ids=explode(',',$value);
    }
}
require 'inc_pass.php';
require 'list_inc.php';
$connect=mysqli_connect($sqlname,$sqluser,$sqlpass,"movie");
if (!$connect) die("No Database Connection!");
$table_list=['director', 'cast', 'music'];
if (in_array($table, $table_list)) {
    foreach ($person_ids as $person_id) {
        $delete="delete from $table where movie_id=$movie_id and person_id=$person_id";
        mysqli_query($connect, $delete);
    }
}
$span_output_list=list_this($table, 'list', $movie_id);
$span_output_add=list_this($table, 'add', $movie_id);
$span_output_delete=list_this($table, 'delete', $movie_id);
print<<<END
<script language="javascript">
    top.document.getElementById('{$table}_data').innerHTML="$span_output_list";
    top.document.getElementById('add_update_$table').innerHTML='$span_output_add';
    top.document.getElementById('delete_update_$table').innerHTML='$span_output_delete';
</script>
END;
?>