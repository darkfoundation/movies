<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Data Update - Cast</title>
    <link rel="stylesheet" href="movie.css">
    <script>
        var originalListMain = [];
        var landingListMain = [];
        var originalListSupporting = [];
        var landingListSupporting = [];
        var originalListCameo = [];
        var landingListCameo = [];
        var mainDiff0 = [];
        var mainDiff1 = [];
        var supportingDiff0 = [];
        var supportingDiff1 = [];
        var cameoDiff0 = [];
        var cameoDiff1 = [];

        function allowDrop(e) {
            e.preventDefault();
        }

        function arrayDiff(arr1, arr2) {
            return arr1.filter(element => !arr2.includes(element));
        }

        function disableBtns() {
            document.getElementById('submitThis').disabled = true;
            document.getElementById('resetThis').disabled = true;
        }

        function drag(e) {
            e.dataTransfer.setData("element", e.target.id);
        }

        function drop(e, target) {
            e.preventDefault();
            var data = parseInt(e.dataTransfer.getData("element"));
            var landingParent = document.getElementById(target);
            for (let i = 0; i < landingParent.children.length; i++) {
                const child = landingParent.children[i];
                if (parseInt(child.id) === parseInt(data)) {
                    if (target !== 'filterHere') {
                        child.className = 'landingZone';
                    } else {
                        child.className = 'filterHere';
                    }
                    break;
                }
            }

            var allZones = ['landingZoneMain', 'landingZoneSupporting', 'landingZoneCameo', 'filterHere'];
            var someZones = allZones.filter(item => item !== target);
            someZones.forEach((item) => {
                    var listParent = document.getElementById(item);
                    for (let i = 0; i < listParent.children.length; i++) {
                        const child = listParent.children[i];
                        if (parseInt(child.id) === parseInt(data)) {
                        child.className = 'invisible';
                        break;
                    }
                }
            });

            var theseZones = ['Main', 'Supporting', 'Cameo'];
            theseZones.forEach((item) => {
                landingList = [];
                var landingParent = document.getElementById('landingZone' + item);
                for (let i = 0; i < landingParent.children.length; i++) {
                    const child = landingParent.children[i];
                    if (child.className !== 'invisible' && child.id !== '') {
                        landingList.push(parseInt(child.id));
                    }
                }
                switch (item) {
                    case 'Main':
                        landingListMain = landingList;
                        break;
                    case 'Supporting':
                        landingListSupporting = landingList;
                        break;
                    case 'Cameo':
                        landingListCameo = landingList;
                        break;
                }
            });

            mainDiff0 = arrayDiff(originalListMain, landingListMain);
            mainDiff1 = arrayDiff(landingListMain, originalListMain);
            supportingDiff0 = arrayDiff(originalListSupporting, landingListSupporting);
            supportingDiff1 = arrayDiff(landingListSupporting, originalListSupporting);
            cameoDiff0 = arrayDiff(originalListCameo, landingListCameo);
            cameoDiff1 = arrayDiff(landingListCameo, originalListCameo);

            if (mainDiff0.length > 0 || mainDiff1.length > 0 || supportingDiff0.length > 0 || supportingDiff1.length > 0 || cameoDiff0.length > 0 || cameoDiff1.length > 0) {
                document.getElementById('submitThis').disabled = false;
                document.getElementById('resetThis').disabled = false;
            } else  {
                disableBtns();
            }
            console.log(" ");
            console.log("main to delete:" + mainDiff0);
            console.log("main to insert:" + mainDiff1);
            console.log("supporting to delete:" + supportingDiff0);
            console.log("supporting to insert:" + supportingDiff1);
            console.log("cameo to delete:" + cameoDiff0);
            console.log("cameo to insert:" + cameoDiff1);
        }

        function filterHereSet(nameList, thisList) {
            document.getElementById('searchList').value = '';
            var landingParent = document.getElementById('filterHere');
            landingParent.innerHTML = '';
            nameList.forEach((item) => {
                landingParent.innerHTML += '<span id="' + item.id + '" class="filterHere" draggable="true" ondragstart="drag(event)">' + item.name + '</span>';
            });
            //console.log(thisList);

            thisList.forEach((item) => {
                for (let i = 0; i < landingParent.children.length; i++) {
                    const child = landingParent.children[i];
                    //console.log(parseInt(child.id) + ', ' + parseInt(item.id));
                    if (parseInt(child.id) === parseInt(item.id)) {
                        child.className = 'invisible';
                        // if (parseInt(child.id)==165) {
                        //     console.log(child);
                        // }
                        break;
                    }
                }
            });
        }

        function landingZoneSet(nameList, thisPersonList, thisZone) {
            var landingParent = document.getElementById(thisZone);
            landingParent.innerHTML = '';
            nameList.forEach((item) => {
                landingParent.innerHTML += '<span id="' + item.id + '" class="invisible" draggable="true" ondragstart="drag(event)">' + item.name + '</span>';
            });

            var landingList = [];
            var initialList = [];
            thisPersonList.forEach((item) => {
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

        function selectMovie() {
            var selectList = document.getElementById('allMovies');
            var selectedMovie = selectList.options[selectList.selectedIndex].value;
            var selectEditList = document.getElementById('editType');
            var selectedEditType = selectEditList.options[selectEditList.selectedIndex].value;
            var group_checked = document.getElementById('group_title').checked;
            var seriesOrder = '';
            if (group_checked) {
                seriesOrder = '&order=1';
            }
            var outputURL = selectedEditType + '.php?movie_id=' + selectedMovie + seriesOrder;
            //alert(outputURL);
            window.location.href = outputURL;
        }

        function setZones() {
            filterHereSet(nameList, allList);
            // filterHereSet(nameList, selectedPersonList0);
            // filterHereSet(nameList, selectedPersonList1);
            // filterHereSet(nameList, selectedPersonList2);
            var originalListMain = landingZoneSet(nameList, selectedPersonList0, 'landingZoneMain');
            var originalListSupporting = landingZoneSet(nameList, selectedPersonList1, 'landingZoneSupporting');
            var originalListCameo = landingZoneSet(nameList, selectedPersonList2, 'landingZoneCameo');
            //var originalList = originalListMain + '|' + originalListSupporting + '|' + originalListCameo;
            //console.log(originalList);
        }

        function updateMovie() {
            var deleteList = mainDiff0 + '|' + supportingDiff0 + '|' +cameoDiff0;
            var insertList = mainDiff1 + '|' + supportingDiff1 + '|' +cameoDiff1;
            var outputURL = 'update_cast.php?movie_id=' + movieId;
            if (deleteList.length > 2) {
                outputURL += '&sub=' + deleteList;
            }
            if (insertList.length > 2) {
                outputURL += '&add=' + insertList;
            }
            //alert(outputURL);
            document.getElementById('phpUpdate').src = outputURL;
        }
<?php
$movie_id = 1; $table = 'cast'; $is_checked = '';
if (isset($_GET)) {
    foreach ($_GET as $key=>$value) { 
        if ($key === 'movie_id') { $movie_id=$value; }
        if ($key === 'order') { $group_by = 'group_title'; }
        //if ($key === 'table') { $table = $value; }
    }
}
if (!isset($movie_id)) { $movie_id = 1; }
if (!isset($group_by)) {
    $group_by = 'alpha_title';
} else {
    $is_checked = ' checked';
}

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

$movie_query = "select * FROM movie order by $group_by, title";
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

$this_query = "select p.id, p.first_name, p.last_name, c.level FROM $table c join movie m on m.id = c.movie_id join person_list p on p.id = c.person_id where movie_id=$movie_id order by p.last_name, p.first_name";
$result = mysqli_query($connect, $this_query);
$list = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
$allList = '';
$thisList[0] = '';
$thisList[1] = '';
$thisList[2] = '';
foreach ($list as $each) {
    //$name = trim($each['first_name'] . ' ' . $each['last_name']);
    $index = $each['id'];
    $level = $each['level'];
    $entry = "{\"id\":\"$index\"}";
    if (strlen($thisList[$level])>1) {
        $thisList[$level] .= ', ' . $entry;
    } else {
        $thisList[$level] .= $entry;
    }
    if (strlen($allList)>1) {
        $allList .= ', ' . $entry;
    } else {
        $allList .= $entry;
    }
}
echo 'const allList = [' . $allList . '];' . PHP_EOL;
for ($i = 0; $i < 3; $i++) {
    echo "const selectedPersonList$i = [" . $thisList[$i] . '];' . PHP_EOL;
}
?></script>
</head>
<body onload="disableBtns();" style="font-family: sans-serif"><div style="width: 500px;"><div style="text-align: center">
    <script>document.write('Cast List for <b><i>' + movieTitle + '</i></b>');</script>
    <br /><button id="submitThis" type="button" onclick="updateMovie();"><b>Submit Changes</b></button>
    &nbsp;<button id="resetThis" type="button" onclick="setZones(); disableBtns();"><b>Reset Lists</b></button></div>
    <div style="width: 500px; text-align: center; font-weight: bold;">Main</div>
    <div id="landingZoneMain" style="padding: 5px; border: thin solid black; width: 500px; height: 5em; overflow-y: auto;" ondrop="drop(event, 'landingZoneMain')" ondragover="allowDrop(event)">
        <script>var originalListMain = landingZoneSet(nameList, selectedPersonList0, 'landingZoneMain');</script>
    </div><br />
    <div style="width: 500px; text-align: center; font-weight: bold;">Supporting</div>
    <div id="landingZoneSupporting" style="padding: 5px; border: thin solid black; width: 500px; height: 5em; overflow-y: auto;" ondrop="drop(event, 'landingZoneSupporting')" ondragover="allowDrop(event)">
        <script>var originalListSupporting = landingZoneSet(nameList, selectedPersonList1, 'landingZoneSupporting');</script>
    </div><br />
    <div style="width: 500px; text-align: center; font-weight: bold;">Cameos</div>
    <div id="landingZoneCameo" style="padding: 5px; border: thin solid black; width: 500px; height: 5em; overflow-y: auto;" ondrop="drop(event, 'landingZoneCameo')" ondragover="allowDrop(event)">
        <script>var originalListCameo = landingZoneSet(nameList, selectedPersonList2, 'landingZoneCameo');</script>
    </div><br />
    <div style="text-align: center"><b>Add from:&nbsp;</b><input type="text" id="searchList" placeholder="Search List..."  style="border: thin solid black;"></div>
    <div id="filterHere" style="padding: 5px; border: thin solid black; width: 500px; height: 11em; overflow-y: auto;" ondrop="drop(event, 'filterHere')" ondragover="allowDrop(event)"></div>
    <script>
        // var allSelected = Object.assign({}, selectedPersonList0, selectedPersonList2, selectedPersonList2)
        filterHereSet(nameList, allList);
        // filterHereSet(nameList, selectedPersonList0);
        // filterHereSet(nameList, selectedPersonList1);
        // filterHereSet(nameList, selectedPersonList2);
    </script>
    <iframe id="phpUpdate" class="invisible" src=""></iframe>
    <div ><br />Movie Titles:&nbsp;
        <select id="allMovies"><script>
        movieList.forEach((item) => {
            var sel = ""; if (parseInt(item.id) === parseInt(movieId)) { var sel = " selected"; }
            document.write('<option value="' + item.id + '"' + sel + '>' + item.title + '</option>');
        });
        </script></select><br />Group to Edit:&nbsp;
        <select id="editType">
            <option value="cast" selected>cast</option>
            <option value="director">director</option>
            <option value="music">music</option>
        </select>
        &nbsp;Order by Series:&nbsp;<input type="checkbox" id="group_title" name="group_title"<?php echo $is_checked;?> />
        <button type="button" onclick="selectMovie();"><b>Select Movie</b></button>
    </form></div></div>
    <script src="script_divs.js"></script>
</body>
</html>