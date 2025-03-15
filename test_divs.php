<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Data Update</title>
    <style>
        .filterHere {border-radius: 5px; background:rgb(211, 211, 211); padding: 5px; margin: 3px 2px; display: inline-block; white-space: nowrap; cursor: move; user-select: none;}
        .landingZone {border-radius: 5px; background:rgb(145, 238, 142); padding: 5px; margin: 3px 2px; display: inline-block; white-space: nowrap; cursor: move; user-select: none;}
        .invisible {display: none!important;}
    </style>
    <script>
        var originalList = [];
        var landingList = [];

        function disableBtns() {
            document.getElementById('submitThis').disabled = true;
            document.getElementById('resetThis').disabled = true;
        }

        function arrayDiff(arr1, arr2) {
            return arr1.filter(element => !arr2.includes(element));
        }

        function landingZoneSet(nameList, selectedPersonList) {
            var landingParent = document.getElementById('landingZone');
            landingParent.innerHTML = '';
            nameList.forEach((item) => {
                landingParent.innerHTML += '<span id="' + item.id + '" class="invisible" draggable="true" ondragstart="drag(event)">' + item.name + '</span>';
            });

            var landingList = [];
            var initialList = [];
            selectedPersonList.forEach((item) => {
                initialList.push(parseInt(item.id));
                for (let i = 0; i < landingParent.children.length; i++) {
                    const child = landingParent.children[i];
                    if (child.id === item.id) {
                        landingList.push(parseInt(child.id));
                        child.className = 'landingZone';
                        break;
                    }
                }
            });
            return initialList;
        }

        function filterHereSet(nameList, selectedPersonList) {
            document.getElementById('searchList').value = '';
            var landingParent = document.getElementById('filterHere');
            landingParent.innerHTML = '';
            nameList.forEach((item) => {
                landingParent.innerHTML += '<span id="' + item.id + '" class="filterHere" draggable="true" ondragstart="drag(event)">' + item.name + '</span>';
            });

            selectedPersonList.forEach((item) => {
                for (let i = 0; i < landingParent.children.length; i++) {
                    const child = landingParent.children[i];
                    if (child.id === item.id) {
                        child.className = 'invisible';
                        break;
                    }
                }
            });
        }

        function setZones(nameList, selectedPersonList) {
            filterHereSet(nameList, selectedPersonList);
            var originalList = landingZoneSet(nameList, selectedPersonList);
        }

        function updateMovie(oc, ll, tb) {
            var deleteList = arrayDiff(oc, ll);
            var insertList = arrayDiff(ll, oc);
            var targetTable = tb;
            var outputURL = 'update_table.php?movie_id=' + movieId + '&table=' + targetTable;
            if (deleteList.length > 0) {
                outputURL += '&sub=' + deleteList;
            }
            if (insertList.length > 0) {
                outputURL += '&add=' + insertList;
            }
            //alert(outputURL);
            document.getElementById('phpUpdate').src = outputURL;
        }

        function selectMovie() {
            var selectList = document.getElementById('allMovies');
            var selectedMovie = selectList.options[selectList.selectedIndex].value;
            var selectEditList = document.getElementById('editType');
            var selectedEditType = selectEditList.options[selectEditList.selectedIndex].value;
            var outputURL = 'test_divs.php?movie_id=' + selectedMovie + '&table=' + selectedEditType;
            //alert(outputURL);
            window.location.href = outputURL;
        }

        function allowDrop(e) {
            e.preventDefault();
        }

        function drag(e) {
            e.dataTransfer.setData("element", e.target.id);
        }

        function drop(e, source, target) {
            e.preventDefault();
            var data = parseInt(e.dataTransfer.getData("element"));
            var landingParent = document.getElementById(target);
            for (let i = 0; i < landingParent.children.length; i++) {
                const child = landingParent.children[i];
                if (parseInt(child.id) === parseInt(data)) {
                    if (target === 'landingZone') {
                        child.className = 'landingZone';
                    } else {
                        child.className = 'filterHere';
                    }
                    break;
                }
            }
            var listParent = document.getElementById(source);
            for (let i = 0; i < listParent.children.length; i++) {
                const child = listParent.children[i];
                if (parseInt(child.id) === parseInt(data)) {
                    child.className = 'invisible';
                break;
                }
            }
            landingList = [];
            var landingParent = document.getElementById('landingZone');
            for (let i = 0; i < landingParent.children.length; i++) {
                const child = landingParent.children[i];
                if (child.className !== 'invisible' && child.id !== '') {
                    landingList.push(parseInt(child.id));
                    //console.log(landingList);
                }
            }
            if (arrayDiff(originalList, landingList).length > 0 || arrayDiff(landingList, originalList).length > 0) {
                document.getElementById('submitThis').disabled = false;
                document.getElementById('resetThis').disabled = false;
            }
            console.log("movie_id:" + movieId);
            console.log("to delete:" + arrayDiff(originalList, landingList));
            console.log("to insert:" + arrayDiff(landingList, originalList));
        }
<?php
$movie_id = 1; $table = 'cast';
if (isset($_GET)) {
    foreach ($_GET as $key=>$value) { 
        if ($key === 'movie_id') { $movie_id=$value; }
        if ($key === 'table') { $table = $value; }
    }
}
if (!isset($movie_id)) { $movie_id = 1; }
if (!isset($movie_id)) { $table = 'cast'; }
$UCtable = ucfirst($table);

require 'inc_pass.php';
require 'list_divs.php';
$connect=mysqli_connect($sqlname,$sqluser,$sqlpass,"movie");
if (!$connect) die("No Database Connection!");
$person_query = "select * from person_list order by first_name, last_name";
$result = mysqli_query($connect, $person_query);
$list = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
$comma = ''; $nameList = 'const nameList = [';
foreach ($list as $each) {
    $name = trim($each['first_name'] . ' ' . $each['last_name']);
    $index = $each['id'];
    $nameList .= "$comma{\"id\":\"$index\",\"name\":\"$name\"}";
    $comma = ', ';
}
echo "$nameList];" . PHP_EOL;

$movie_query = "select * FROM movie order by alpha_title, title";
$result = mysqli_query($connect, $movie_query);
$list = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
$comma = ''; $titleList = 'const movieList = [';
foreach ($list as $each) {
    $title = $each['title']; $index = $each['id'];
    $titleList .= "$comma{\"id\":\"$index\",\"title\":\"$title\"}";
    $comma = ', ';
    if ($index+0 === $movie_id+0) {
        echo "const movieId = $index;". PHP_EOL;
        echo "const movieTitle = \"$title\";". PHP_EOL;
    }
}
echo "$titleList];" . PHP_EOL;

$this_query = "select p.id, m.title, p.first_name, p.last_name FROM $table c join movie m on m.id = c.movie_id join person_list p on p.id = c.person_id where movie_id=$movie_id order by p.last_name, p.first_name";
$result = mysqli_query($connect, $this_query);
$list = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
$comma = ''; $thisList = 'const selectedPersonList = [';
foreach ($list as $each) {
    $name = trim($each['first_name'] . ' ' . $each['last_name']);
    $index = $each['id'];
    $thisList .= "$comma{\"id\":\"$index\"}";
    $comma = ', ';
}
echo "$thisList];" . PHP_EOL;

$count = 0; $comma = ''; $editList = 'const editList = [';
foreach (['cast', 'director', 'music'] as $edit) {
    $editList .= "$comma{\"id\":\"$count\",\"value\":\"$edit\"}";
    $count += 1; $comma = ', ';
}
echo "$editList];" . PHP_EOL;
echo "const UCtable = '$UCtable';". PHP_EOL;
?></script>
</head>
<body onload="disableBtns();" style="font-family: sans-serif"><div style="width: 500px;"><div style="text-align: center">
    <script>document.write(UCtable + ' List for <b><i>' + movieTitle + '</i></b>');</script>
    <br /><button id="submitThis" type="button" onclick="updateMovie(originalList, landingList, UCtable.toLowerCase());"><b>Submit Changes</b></button>
    &nbsp;<button id="resetThis" type="button" onclick="setZones(nameList, selectedPersonList); disableBtns();"><b>Reset Lists</b></button></div>
    <div id="landingZone" style="padding: 5px; border: thin solid black; width: 500px; height: 250px; overflow-y: auto;" ondrop="drop(event, 'filterHere', 'landingZone')" ondragover="allowDrop(event)">
        <script>var originalList = landingZoneSet(nameList, selectedPersonList);</script>
    </div><br/>
    <div style="text-align: center">Add from:&nbsp;<input type="text" id="searchList" placeholder="Search List..."  style="border: thin solid black;"></div>
    <div id="filterHere" style="padding: 5px; border: thin solid black; width: 500px; height: 250px; overflow-y: auto;" ondrop="drop(event, 'landingZone', 'filterHere')" ondragover="allowDrop(event)"></div>
    <script>filterHereSet(nameList, selectedPersonList);</script>
    <iframe id="phpUpdate" class="invisible" src=""></iframe>
    <div ><br />Movie Titles:&nbsp;
        <select id="allMovies"><script>
        movieList.forEach((item) => {
            var sel = ""; if (parseInt(item.id) === parseInt(movieId)) { var sel = " selected"; }
            document.write('<option value="' + item.id + '"' + sel + '>' + item.title + '</option>');
        });
        </script></select><br />Group to Edit:&nbsp;
        <select id="editType"><script>
        editList.forEach((item) => {
            console.log(item.value);
            console.log(UCtable.toLowerCase());
            var sel = ""; if (item.value === UCtable.toLowerCase()) { var sel = " selected"; }
            document.write('<option value="' + item.value + '"' + sel + '>' + item.value + '</option>');
        });
        </script></select>
        <button type="button" onclick="selectMovie();"><b>Select Movie</b></button>
    </form></div></div>
    <script src="script_divs.js"></script>
</body>
</html>