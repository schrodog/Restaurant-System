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
	});

  // search function
  $(".search-block #searchCode").on('keyup',function(){
    var text=$(this).val();
    console.log('code:'+text);
    $(this).siblings().not($(this)).val('');
    if (text!=''){$("#mainTable tbody").load("tools/refreshMenu.php", {"foodCode":text,"skip":"1"});}
  });
  $(".search-block #searchName").on('keyup',function(){
    var text=$(this).val();
    $(this).siblings().not($(this)).val('');
    console.log('name:'+text);
    if (text!=''){$("#mainTable tbody").load("tools/refreshMenu.php", {"searchName":text,"skip":"1"});}
  });
  $(".search-block .price").on('keyup',function(){
    $(this).siblings().not($(".price")).val('');
    var text1 = Number($(".search-block #searchPrice1").val());
    var text2 = Number($(".search-block #searchPrice2").val());
    // console.log('price1:'+text1+"2:"+text2);
    if (text1!='' && text2=='' && !isNaN(text1)){
      $.post("tools/refreshMenu.php", {"searchPrice1":text1,"skip":"1"}, function(data){ $("#mainTable tbody").html(data) });
    } else if (text1=='' && text2!='' && !isNaN(text2)){
      $.post("tools/refreshMenu.php", {"searchPrice2":text2,"skip":"1"}, function(data){ $("#mainTable tbody").html(data) });
    } else if (text1!='' && text2!='' && !isNaN(text1)  && !isNaN(text2)){
      $.post("tools/refreshMenu.php", {"searchPrice1":text1,"searchPrice2":text2,"skip":"1" }, function(data){ $("#mainTable tbody").html(data) });
    }
  });

  $(".search-block .quan").on('keyup',function(){
    $(this).siblings().not($(".quan")).val('');
    var text3 = Number($(".search-block #searchQuantity1").val());
    var text4 = Number($(".search-block #searchQuantity2").val());
    // console.log('quantity1:'+text);
    if (text3!='' && text4=='' && !isNaN(text3)){
      $.post("tools/refreshMenu.php", {"searchQuantity1":text3,"skip":"1"}, function(data){ $("#mainTable tbody").html(data) });
    } else if (text3=='' && text4!='' && !isNaN(text4)){
      $.post("tools/refreshMenu.php", {"searchQuantity2":text4,"skip":"1"}, function(data){ $("#mainTable tbody").html(data) });
    } else if (text3!='' && text4!='' && !isNaN(text3)  && !isNaN(text4)){
      $.post("tools/refreshMenu.php", {"searchQuantity1":text3,"searchQuantity2":text4,"skip":"1" }, function(data){ $("#mainTable tbody").html(data) });
    }
  });


  $("#saveOrderBtn").click(function(){
    var result = [], tmp=[];
    var masterOrderID = document.head.querySelector("[name=data-masterOrderID]").content.replace(/\n/g,"");

    $("#orderBlock .card-block p.summary-group").each(function(){
      tmp=[];
      $(this).find("input").each(function(){
        tmp.push($(this).val());
      });
      $(this).find("span[name='price']").each(function(){
        // console.log( ($(this).text()).substring(2) );
        tmp.push( ($(this).text()).substring(2));
      });
      tmp.push(masterOrderID);
      result.push(tmp);
    });

    // result.forEach(function(value){
    //   console.log(value);
    // });
    $.ajax({
      type     : "POST",
      url      : "tools/update.php",
      data     : {"operation":"insert", "target_table":"order", "headerList": ["FoodID","Quantity","Price","MasterOrderID"],"valueList":result},
      success  : function(data) {
        // alert(data);
        window.location = "order.php";
      }
    }).fail(function(xhr, status, error){
			alert(error);
		});

  });

});


function calcTotal(){
  var sum = 0;
  $(".card-block").find("span[name ='price']").each(function(){
    sum += Number( ($(this).text()).substring(2) );
    $('#total').text('$' + sum.toFixed(2));
  });
  console.log('sum: ' + sum);
}
