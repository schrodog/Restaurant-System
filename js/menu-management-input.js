/* this is an example for validation and change events */
// $.fn is jquery's namespace; $.fn.abc() is extend abc function for jquery, so that every jquery instance can use. eg. $("#div").abc();
/**
deleted = (oldIDs-beforeChange)-(newIDs-afterChange)
inserted = (newIDs-afterChange)-(oldIDs-beforeChange)
updated = idChange-beforeChange-afterChange
**/
var idChange = [], insertRows=[], deleteRows=[], oldIDs=[], newIDs=[],beforeChange=[],afterChange=[], element={};
var associativeArray = {};
var currentCell = {};
var keyID,username,pwd,priv,delID;
// var originalText;

// $(document).ajaxComplete(function(){
// 		console.log('ajax complete');
// 	});

$.fn.numericInputExample = function () {
	'use strict';
	element = $(this);
		// footer = element.find('tfoot tr'),
		// dataRows = element.find('tbody tr');

	element.find('td').off().on('change', function (evt) { // when cell change
		// console.log("when cell change");

		var cell = $(this),	column = cell.index();
		var id = $(this).parent().find("td").first().text();
		idChange.push(id);
		console.log("idchange= "+idChange);

		/** Validation **/
	}).on('validate', function (evt, value) { // validate before change
		var cell = $(this), column = cell.index();
		if (value=='') {return;} // allow empty value
		if (column == 0 || column==1) { // for column name
			var re = /^[a-zA-Z0-9 ]+$/g;
			return !!value && value.trim().length > 0 && !!value.match(re);
		} else if (column == 2 || column==3) {
			var tmp = parseInt(value);
			return (value == parseInt(value,10));
		}

	}).on('click', function(){
		currentCell = element.find('td:focus');
		// originalText = currentCell.text();
		// console.log(currentCell.text());
	});

	/** get old IDs **/
	element.find('td:first-child').each(function(){
		oldIDs.push($(this).text())
	});
	// console.log(oldIDs);

	return this;
};

$(document).ready(function(){

	// filter words
	// $("header nav form #search").on("keyup", function(){
	// 	var value = $(this).val().toLowerCase();
	// 	// console.log('value:'+value);
	// 	$("#mainTable tbody tr").filter(function(){
	// 		console.log($(this).find('td:nth-child(5)').text());
	// 		$(this).toggle($(this).find('td:nth-child(5)').text().toLowerCase().indexOf(value) > -1);
	// 	});
	// });

	// update row
	$("#updateBtn").click(function(){
		var asso = {}, valueList=[], updateData=[], layer2=[], header=[], index=1, updateRowNo=[], match_insert=[];
		var updateRows = $(idChange).get();

		console.log("late idChange="+idChange);

		$("#mainTable tbody tr td:nth-child(1)").each(function(){
			// console.log($(this).text());
		    if ( updateRows.includes( $(this).text()) ){
		        updateRowNo.push(index);
		    }
		    index++;
		});
		// get updated data
		updateRowNo.forEach(function(j, index,ar){
		    valueList=[];
		    $("#mainTable tbody tr:nth-child("+j+") td:not(.no_focus)").each(function(){
		        valueList.push($(this).text());
		    });
		    updateData.push(valueList);
		});

		updateData.forEach(function(val,index,ar){
		    console.log("1:"+val);
		});

		origHeader = ["FoodName","Price","Quantity","Category"];
		// for(var i=0; i<)

		if (updateRowNo.length>0){
			$.ajax({
				type: "POST",
				url: "tools/update.php",
				data: { "valueList": updateData,"target_table":"menu","headerList":origHeader ,"idList":idChange,"idName":"FoodID", "operation": "update"},
				success: function(data, txt, jqxhr){
					// alert(data);
					refreshData();
					alert("Update success.");
				}
			}).fail(function(xhr, status, error){
				alert(error);
			});
		}
	});

	// function refreshTable(){
	// 	$.ajax({
	// 		type: "POST",
	// 		url: "user-management.php",
	// 		success: function(){
	// 			window.location = "user-management.php";
	// 		}
	// 	});
	// }

	$("#addBtn").click(function(){
		$("#newFoodModal").modal('show');
	});

	// create new food
	$("body").on('click','#newFoodModal #OK',function(){
		var foodCode = $("#newFoodModal input[id='foodCode']").val();
		var foodName = $("#newFoodModal input[id='foodName']").val();
		var price = $("#newFoodModal input[id='price']").val();
		var quantity = $("#newFoodModal input[id='quantity']").val();
		var category = $("#newFoodModal input[id='category']").val();

		console.log('food:'+foodCode+","+foodName+","+price+','+quantity+','+category);

		if (foodCode=="" || category==""){
			alert("Food code or category is empty !");
			$("#newFoodModal").modal('show');
			// $("#newFoodModal").one('hidden.bs.modal',function(){
			// });
		} else if ((price!="" && !$.isNumeric(price)) || (quantity!="" && !$.isNumeric(quantity)) ) {
			alert("Price or quantity must be integer !");
			$("#newFoodModal").modal('show');
		}
		else {
			$.ajax({
				type: "POST",
				url: "tools/update.php",
				data: { "operation": "insert","target_table":"menu","headerList":["FoodID", "Category","quantity","price","FoodName"],
					"valueList":[[foodCode,category,quantity,price,foodName]]},
				success: function(data, txt, jqxhr){
					// alert(data);
					refreshData();
				}
			}).fail(function(xhr, status, error){
				alert(error);
			});
		}
	});

	//delete row
	$('body').on('click','.delBtn',function(){
		var thisRow = $(this).parent().parent();
		var delID = thisRow.find("td").first().text();
		$("#deleteModal").modal('show');
		console.log('delID:'+delID);
		$("#deleteModal #OK").one('click',function(){
			$.ajax({
				type: "POST",
				url: "tools/update.php",
				data: { "operation": "delete","target_table":"menu","valueList":[delID],"idName":"FoodID"},
				success: function(data, txt, jqxhr){
					// alert(data);
					thisRow.remove();
					refreshData();
				}
			}).fail(function(xhr, status, error){
				alert(error);
			});
		});
	});


	// change type
	$("body").on('click','.type-btn',function(){
		var text=$(this).text();
			// console.log('btn:'+text);
		$.ajax({
			type: "POST",
			url: "tools/save_session.php",
			data: { "value":[text],"name":["Category"]},
			success: function(data, txt, jqxhr){
				// window.location="menu-management.php";
				refreshData();
			}
		}).fail(function(xhr, status, error){
			alert(error);
		});
	});

	$(".search-block #searchCode").on('keyup',function(){
		var text=$(this).val();
		console.log('code:'+text);
		$(this).siblings().not($(this)).val('');
		if (text!=''){$("#mainTable tbody").load("tools/refreshMenu.php", {"foodCode":text});}
	});
	$(".search-block #searchName").on('keyup',function(){
		var text=$(this).val();
		$(this).siblings().not($(this)).val('');
		console.log('name:'+text);
		if (text!=''){$("#mainTable tbody").load("tools/refreshMenu.php", {"searchName":text});}
	});
	$(".search-block .price").on('keyup',function(){
		$(this).siblings().not($(".price")).val('');
		var text1 = Number($(".search-block #searchPrice1").val());
		var text2 = Number($(".search-block #searchPrice2").val());
		// console.log('price1:'+text1+"2:"+text2);
		if (text1!='' && text2=='' && text1!=NaN){
			// $("#mainTable tbody").load("tools/refreshMenu.php", {"searchPrice1":text1});
			$.post("tools/refreshMenu.php", {"searchPrice1":text1}, function(data){ $("#mainTable tbody").html(data) });
		} else if (text1=='' && text2!='' && text2!=NaN){
			// $("#mainTable tbody").load("tools/refreshMenu.php", {"searchPrice2":text2});
			$.post("tools/refreshMenu.php", {"searchPrice2":text2}, function(data){ $("#mainTable tbody").html(data) });
		} else if (text1!='' && text2!='' && text1!=NaN  && text2!=NaN){
			// $("#mainTable tbody").load("tools/refreshMenu.php", {"searchPrice2":text2, "searchPrice1":text1 });
			$.post("tools/refreshMenu.php", {"searchPrice1":text1,"searchPrice2":text2 }, function(data){ $("#mainTable tbody").html(data) });
		}
	});
	// $(".search-block #searchPrice2").on('keyup',function(){
	// 	var text=$(this).val();
	// 	$(this).siblings().not($(".price")).val('');
	// 	console.log('price2:'+text);
	// 	if (text!=''){$("#mainTable tbody").load("tools/refreshMenu.php", {"searchPrice2":text});}
	// });
	$(".search-block #searchQuantity1").on('keyup',function(){
		var text=$(this).val();
		$(this).siblings().not($(".quan")).val('');
		console.log('quantity1:'+text);
		if (text!=''){$("#mainTable tbody").load("tools/refreshMenu.php", {"searchQuantity1":text});}
	});
	$(".search-block #searchQuantity2").on('keyup',function(){
		var text=$(this).val();
		$(this).siblings().not($(".quan")).val('');
		console.log('quantity2:'+text);
		if (text!=''){$("#mainTable tbody").load("tools/refreshMenu.php", {"searchQuantity2":text});}
	});


});

function refreshData(){
	idChange=[];
	$("#mainTable tbody").load("tools/refreshMenu.php");
	refresh_buttons();
}




