<html><head>
<script type="text/javascript">
    var rightList = Array();
<?php
require 'inc_pass.php';
$connect=mysqli_connect($sqlname, $sqluser, $sqlpass, "movie");
if (!$connect) die("No Database Connection!");
$query = "select * from person_list order by first_name, last_name";
$result = mysqli_query($connect, $query);
$list = mysqli_fetch_all($result, MYSQLI_ASSOC);
$comma = ''; $person_list = '';
foreach ($list as $each) {
    $name = trim($each['first_name'] . ' ' . $each['last_name']);
    $index = $each['id']; 
    $person_list .= $comma . '{ "name": "' . $name . '", "index": "' . $index . '" }';
    $comma = ", ";
}
echo "\tvar allList = Array($person_list);" . PHP_EOL;

$query = "select p.id from person_list p join cast c on c.person_id=p.id where movie_id=31";
$result = mysqli_query($connect, $query);
$list = mysqli_fetch_all($result, MYSQLI_ASSOC);
$comma = ''; $cast_list = '';
foreach ($list as $each) {
    //$name = trim($each['first_name'] . ' ' . $each['last_name']);
    $index = $each['id']; 
    //$cast_list .= $comma . '{ "name": "' . $name . '", "index": "' . $index . '" }';
    $cast_list .= $comma . $index;
    $comma = ", ";
}

echo "\tvar castList = Array($cast_list);" . PHP_EOL;
?>

    function launchList() {
        castListLength = castList.length;
        allListLength = allList.length;
        for (j = 0; j < castListLength; j++) {
            for (i = 0; i < allListLength; i++) {
                if (allList[i].index == castList[j]) {
                    rightList.push(i);
                    continue;
                }
            }
        }
        loadSelect();
    }

    function loadSelect() {
        newRightList = Array();
        allListLength = allList.length;
        leftSelectObj = document.forms[0].leftSelect;
        leftSelectObj.innerHTML = '';
        rightSelectObj = document.forms[0].rightSelect;
        rightSelectObj.innerHTML = '';
        for (i = 0; i < allListLength; i++) {
            locateRightValue = rightList.indexOf(i);
            if (locateRightValue > -1) {
                rightSelectObj.appendChild(new Option(allList[i].name,allList[i].index));
                newRightList.push(i);
            } else {
                leftSelectObj.appendChild(new Option(allList[i].name,allList[i].index));
            }
        }
        rightList = newRightList;
    }

    function listAdd () {
        allListLength = allList.length;
        leftSelectObj = document.forms[0].leftSelect;
        newListIndex = leftSelectObj[leftSelectObj.selectedIndex].value;
        for (i = 0; i < allListLength; i++) {
            if (allList[i].index == newListIndex) {
                rightList.push(i);
                continue;
            }
        }
        loadSelect();
    }

    function listRemove () {
        rightListLength = rightList.length;
        rightSelectObj = document.forms[0].rightSelect;
        newListIndex = rightSelectObj.selectedIndex;
        rightList.splice(newListIndex, 1);
        loadSelect();
    }

    function clearList() {
        rightList = Array();
        loadSelect();
    }

    function submitPerson() {
        rightListLength = rightList.length;
        comma = ''; personList = '';
        for (i = 0; i < rightListLength; i++) {
            personList += (comma + allList[i].index);
            comma = ',';
        }
        alert('personlist '+ personList);
    }

</script></head>
<body onLoad="launchList();">
<form onSubmit="handleSelectClick();return false;" action="#">
    <table style="margin:auto;">
            <tr><th>Person Names</th><th></th><th>List To Add</th></tr>
            <tr>
                <td>
                    <select name="leftSelect" id="leftSelect" size="10" style="font-size: 10pt; width: 34ex;"></select></td>
                <td valign="middle">
                        <input type="button" onClick="listAdd();"  value="-->"><br /><br />
                        <input type="button" onClick="listRemove();"  value="<--">
                </td>
                <td>
                        <select name="rightSelect" id="rightSelect" size="10" style="font-size: 10pt; width: 34ex;"></select>
                </td>
            </tr>
            <tr><td></td><td></td><td align="center">
                <input type="button" onClick="rightList.length = 0; launchList();" value="Reset List">&nbsp;
                <input type="button" onClick="clearList();" value="Clear List">&nbsp;
                <input type="button" onClick="submitPerson();" value="Submit List"></td></tr>
        </table>
</form>
</body></html>