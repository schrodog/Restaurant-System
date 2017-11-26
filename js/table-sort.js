function columnSort(n){
    var table, rows, switching, i, x,y,shouldSwitch, dir, switchcount=0;
    var ref = $("#mainTable thead tr th:nth-child("+(n+1)+")");
    var icon = $("#mainTable thead tr th:nth-child("+(n+1)+") #asc");
    $(".sort-img").remove();
    switch (ref.attr("value")) {
        case '2':
            dir = "asc";
            $("#mainTable thead tr th").attr("value","0");
            ref.attr("value",1);
            $("<span class='sort-img'>▲</span>").appendTo(ref);
            break;
        case '1':
            dir = "desc";
            $("#mainTable thead tr th").attr("value","0");
            ref.attr("value",2);
            $("<span class='sort-img'>▼</span>").appendTo(ref);
            break;
        default:
            dir = "asc";
            $("#mainTable thead tr th").attr("value","0");
            ref.attr("value",1);
            $("<span class='sort-img'>▲</span>").appendTo(ref);
            break;
    }
    function isNumber(n){
      return !isNaN(parseFloat(n)) && isFinite(n);
    }
    switching = true;
    while(switching){
        switching = false;
        rows = $("#mainTable tbody tr");
        // console.log(rows);
        for (i=0; i<(rows.length-1) ; i++ ){
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("td")[n];
            y = rows[i+1].getElementsByTagName("td")[n];
            // console.log(x+","+y);
            if (isNumber(x.innerHTML)){
              if ( (dir=="asc" && parseFloat(x.innerHTML) > parseFloat(y.innerHTML)) || (dir=="desc" && parseFloat(x.innerHTML) < parseFloat(y.innerHTML)) ){
                  shouldSwitch = true;
                  break;
              }
            } else {
                if ( (dir == "asc" && (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) ) || (dir == "desc" && (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) ) ){
                    shouldSwitch = true;
                    break;
            }}
        }
        if (shouldSwitch){
            rows[i].parentNode.insertBefore(rows[i+1], rows[i]);
            switching = true;
            switchcount ++;
        }
    }
    // console.log("count = "+switchcount);
}