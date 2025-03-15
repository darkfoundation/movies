document.getElementById('searchList').addEventListener('input', handler, false);

function handler(event) {
    const whereAt = '#filterHere' + event.target.id.replace('searchList', '') + ' span';
    const searchTerm = event.target.value.toLowerCase();

    var searchPattern = '';
    for (let i = 0; i < searchTerm.length; i++) {
        searchPattern += searchTerm[i].replace(/[^\w]/g, '') + ".*"; 
    }
    //console.log(searchPattern);

    const searchString = new RegExp(searchPattern);
    const listItems = document.querySelectorAll(whereAt);
    const searchOrig = searchTerm.split("");

    listItems.forEach(function(item) {
        const itemOrig = item.textContent.split("");
        var itemOut = Array();
        for (i = 0; i < itemOrig.length; i++) {
            itemOut[i] = itemOrig[i].toLowerCase();
        }
        const itemText = item.textContent.toLowerCase();

        if (searchString.test(itemText)) {
            let searchPos = -1;
            let prevPos = 0;
            for (j = 0; j < searchOrig.length; j++) {
                searchPos = itemOut.indexOf(searchOrig[j], prevPos);
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
            item.innerHTML = itemOut.join("").trim();
            item.style.display = 'inline-block';
        } else {
            item.style.display = 'none';
        }
    });
};