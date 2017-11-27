$(document).ready(function(){

  //Mini-order summary
  $("body").on('click','#menu tr',function() {
    var tableData = $(this).children("td").map(function() {
      return $(this).text();
    }).get();
    // console.log(tableData);
    $('#order-list .card-block').append('<p class="row summary-group" name="'+ '" id="summary"></p>')+
    $('#order-list .card-block p:last-child').append('<span class="col-6" name="foodname"> ' + tableData[1]+
    '</span><br /><input class="col" name="foodid[]" value='+ tableData[0]+' readonly>' +
    '<input class="col" name="quantity" value="1" required placeholder="Quantity" type="number">'+
    '<span class="col" name="price" class="price-order">$ '+ Number(tableData[2])+'</span>'+
    '<button type="button" class="delBtn btn btn-danger">&times;</button>'+
    '<span class="unit-price" style="display:none">'+ Number(tableData[2])+'</span>'
    );

    calcTotal();

  });

  $("body").on('change',"#orderBlock input[name='quantity']",function(){
    var quantity = parseInt($(this).val(),10);
    var unit_price = parseInt($(this).parent().find(".unit-price").text(),10);
    var final_price = Math.max(unit_price*quantity,0);
    $(this).parent().find("span[name='price']").text('$ '+final_price);
    // console.log(Math.max(unit_price*quantity,0));
    // console.log($(this).parent().text());
    calcTotal();
  });

  $("body").on('click','#orderBlock .delBtn', function(){
    $(this).parent().remove();
    calcTotal();
  });

  // change type
	$("body").on('click','.type-btn',function(){
		var text=$(this).text();
			console.log('btn:'+text);
		$.ajax({
			type: "POST",
			url: "tools/save_session.php",
			data: { "value":[text],"name":["Category"]},
			success: function(data, txt, jqxhr){
				// window.location="menu-management.php";
        $("#mainTable tbody").load("tools/menu-refreshTable.php");
			}
		}).fail(function(xhr, status, error){
			alert(error);
		});
	})

});


function calcTotal(){
  var sum = 0;
  $(".card-block").find("span[name ='price']").each(function(){
    sum += Number( ($(this).text()).substring(2) );
    $('#total').text('$' + sum.toFixed(2));
  });
  console.log('sum: ' + sum);
}
