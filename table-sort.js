function columnSort(n){
    var table, rows, switching, i, x,y,shouldSwitch, dir, switchcount=0;
    // table = $("#mainTable");
    var ref = $("#mainTable thead tr th:nth-child("+(n+1)+")");
    var icon = $("#mainTable thead tr th:nth-child("+(n+1)+") #asc");
    // console.log(ref.attr("value"));
    // $("#mainTable thead tr th #asc").attr("src","");
    var img = $("#mainTable thead tr th");
    // img.removeAttr("src");
    // img.empty();
    // $("#mainTable thead tr th #asc").css({"left","100"});
    switch (ref.attr("value")) {
        case '2':
            dir = "asc";
            $("#mainTable thead tr th").attr("value","0");
            ref.attr("value",1);
            // ref.val()
            // ref.add("<img width='30px' height='30px' src='icon/arrow-up.svg'>");
            // icon.attr("src","icon/arrow-up.svg");
            // icon.css({"display","inline"});
            break;
        case '1':
            dir = "desc";
            $("#mainTable thead tr th").attr("value","0");
            ref.attr("value",2);
            // ref.add("<img width='30px' height='30px' src='icon/arrow-down.svg'>");
            // icon.attr("src","icon/arrow-down.svg");
            // icon.css({"display","inline"});
            break;
        default:
            dir = "asc";
            $("#mainTable thead tr th").attr("value","0");
            ref.attr("value",1);
            // ref.add("<img width='30px' height='30px' src='icon/arrow-up.svg'>");
            // icon.attr("src","icon/arrow-up.svg");
            // icon.css({"display","inline"});
            break;
    }
    // icon.attr("class","glyphicon glyphicon-menu-up");
    // icon.attr("class","glyphicon glyphicon-menu-up");
    // icon.attr("class","glyphicon glyphicon-menu-down");

    switching = true;
    while(switching){
        switching = false;
        rows = $("#mainTable tbody tr");
        for (i=0; i<(rows.length-1) ; i++ ){
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("td")[n];
            y = rows[i+1].getElementsByTagName("td")[n];
            if (n==0){
                if ( (dir=="asc" && parseFloat(x.innerHTML) > parseFloat(y.innerHTML)) || (dir=="desc" && parseFloat(x.innerHTML) < parseFloat(y.innerHTML)) ){
                    shouldSwitch = true;
                    break;
                }
            } else {
                if ( (dir == "asc" && (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) ) || (dir == "desc" && (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) ) ){
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch){
            rows[i].parentNode.insertBefore(rows[i+1], rows[i]);
            switching = true;
            switchcount ++;
        }
    }
    // console.log("count = "+switchcount);
}