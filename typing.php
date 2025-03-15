<html>
<head>
<script type="text/javascript">
    var rightList = Array(), rightIndex =Array();
    var leftList = Array(), leftIndex = Array();
<?php
require 'inc_pass.php';
$connect=mysqli_connect($sqlname, $sqluser, $sqlpass, "movie");
if (!$connect) die("No Database Connection!");
$query="select * from person_list order by first_name, last_name";
$result=mysqli_query($connect, $query);
$list=mysqli_fetch_all($result, MYSQLI_ASSOC);
$comma=''; $out_list=''; $out_index=''; $out_num=0;
foreach ($list as $each) {
    $name=trim($each['first_name'] . ' ' . $each['last_name']);
    $index=$each['id']; 
    $out_list .= $comma . '{ "name": "' . $name . '", "index": "' . $index . '" }';
    $comma=", "; $out_num++;
}

echo "\tvar allList = Array($out_list)";
?>

    function filterList() {
        leftList = allList;
        var rightListLength = rightList.length;
        for (i=0; i < rightListLength; i++) {
            locateRightValue = leftList.indexOf(rightList[i]);
            if (locateRightValue > -1) {
                leftList.splice(locateRightValue,1);
                leftIndex.splice(locateRightValue,1);
            }
        }
    }

    function handleKeyUp(maxNumToShow) {
        var selectObj,
            textObj,
            leftListLength;
        var i,
            searchPattern;

        selectObj = document.forms[0].leftSelect;
        textObj = document.forms[0].fullInput;
        leftListLength = leftList.length;
        if (document.forms[0].functionradio[0].checked == true) {
            searchPattern = "^" + textObj.value;
        } else {
            searchPattern = textObj.value;
        }
        re = new RegExp(searchPattern, "gi");
        selectObj.length = 0;
        numShown = 0;
        for (i = 0; i < leftListLength; i++) {
            if (leftList[i].search(re) != -1) {
                selectObj[numShown] = new Option(leftList[i], leftIndex[i]);
                numShown++;
            }
        }
    }

    function listAdded() {
        var selectObj,
            rightListLength,
            i;
        var sortArray=Array();
        selectObj = document.forms[0].leftSelect;
        newListIndex = selectObj.selectedIndex;
        newListItem = selectObj.options[selectObj.selectedIndex].text;
        rightListLength=rightList.length;
        matchList = 0;
        for (i = 0; i < rightListLength; i++) {
            alert('{ "name": "' + rightList[i] + '", "index": "' + rightIndex[i] + '" }');
            sortArray.push = '{ "name": "' + rightList[i] + '", "index": "' + rightIndex[i] + '" }';
            if (rightList[i] == newListItem) {
                matchList = 1;
            }
        }
        if (matchList == 0) {
            rightList[rightList.length]=newListItem;
            rightIndex[rightList.length]=newListIndex;
            alert('{ "name": "' + newListItem + '", "index": "' + newListIndex + '" }');
            sortArray.push = '{ "name": "' + newListItem + '", "index": "' + newListIndex + '" }';
        }
        alert(sortArray);
        sortArray.sort((a, b) => (a.name > b.name) ? 1: -1);
        alert(sortArray);
        sortArrayLength=sortArray.length;
        var rightSelectObj = document.getElementById('rightSelect');
        rightSelectObj.innerHTML = '';
        //rightSelectObj.length = 0;
        for (i = 0; i < sortArrayLength; i++) {
            rightSelectObj.appendChild(new Option(sortArray[i],i));
        }
        filterList();
        handleKeyUp();
    }

    function handleSelectClickLeft() {
        addSelectObj = document.forms[0].rightSelect;
        addTextObj = document.forms[0].rightList;
        addSelectedValue = addSelectObj.options[addSelectObj.selectedIndex].text;
        //rightList[] =selectedValue;

    }

    function handleSelectClickRight() {
        selectObj = document.forms[0].fullSelect;
        textObj = document.forms[0].fullInput;
        selectedValue = selectObj.options[selectObj.selectedIndex].text;

        selectedValue = selectedValue.replace(/_/g, '-');
        // document.location.href =
        // "http://www.php.net/manual/en/function." + selectedValue + ".php";

    }
    </script>
</head>
<?php
echo '<body onload="document.forms[0].fullInput.focus(); filterList(); handleKeyUp(' . $out_num . ');">';
?>
<table style="margin:auto;">
            <tr>
                <td valign="top">
                    <b>Search For Person Name</b>
                    <form onSubmit="handleSelectClick();return false;" action="#">
                        <input type="radio" name="functionradio" checked>Starting With<br />
                        <input type="radio" name="functionradio">Containing<br />
                        <input onKeyUp="handleKeyUp(20);" type="text" name="fullInput" VALUE="" style="font-size: 10pt; width: 34ex;"><br />
                        <select name="leftSelect" size="10" style="font-size: 10pt; width: 34ex;"></select>
                    </form>
                </td><td valign="middle">
                        <input type="button" onClick="listAdded();"  value="-->"><br /><br />
                        <input type="button" onClick="handleSelectClickRight();"  value="<--">
                </td><td valign="bottom">
                    List To Add
                    <form>
                        <select onClick="handleSelectClick();" name="rightSelect" id="rightSelect" size="10" style="font-size: 10pt; width: 34ex;"></select><br />
                        <input type="button" onClick="" value="Add These!">
                    </form>
                </td>
            </tr>
        </table>
</body></html>