document.getElementById('searchInputCast').addEventListener('input', handler, false);
document.getElementById('searchInputDirector').addEventListener('input', handler, false);
document.getElementById('searchInputMusic').addEventListener('input', handler, false);

function handler(event) {
    const whereAt = '#itemList' + event.target.id.replace('searchInput','') + ' li';
    const searchTerm = event.target.value.toLowerCase();

    var searchPattern = '';
    for (let i = 0; i < searchTerm.length; i++) {
        searchPattern += searchTerm[i] + ".*"; 
    }
    const searchString = new RegExp(searchPattern);

    //const listItems = document.querySelectorAll('#itemList li');
    const listItems = document.querySelectorAll(whereAt);
    const searchOrig = searchTerm.split("");
    console.clear();

    listItems.forEach(function(item) {
        const itemOrig = item.textContent.split("");
        var itemOut = Array();
        for (i = 0; i < itemOrig.length; i++) {
            itemOut[i] = itemOrig[i].toLowerCase();
        }
        const itemText = item.textContent.toLowerCase();
        const itemCheck = item.querySelectorAll('input[type="checkbox"]');

        if (searchString.test(itemText) || itemCheck[0].checked) {
            //if (!itemCheck[0].checked) {
            let searchPos = -1;
            let prevPos = 0;
            console.log(item.textContent);
            for (j = 0; j < searchOrig.length; j++) {
                searchPos = itemOut.indexOf(searchOrig[j],prevPos);
                if (searchPos >= prevPos) {
                    itemOut[searchPos] = "<span style='color:red'>" + itemOrig[searchPos] + "</span>";
                    prevPos = searchPos + 1;
                } else {
                    itemOut = itemOrig;
                    break;
                }
            }
            for (i = 0; i < itemOrig.length; i++) {
                if (itemOut[i] == itemOrig[i].toLowerCase()) {
                    itemOut[i] = itemOrig[i];
                }
            }
            item.lastChild.innerHTML = itemOut.join("").trim();
        //}
            item.style.display = 'list-item';
        } else {
            item.style.display = 'none';
        }
    });
};