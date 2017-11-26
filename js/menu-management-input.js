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
	
	// create new food code
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
		} else if ((price!="" && !Number.isInteger(price)) || (quantity!="" && !Number.isInteger(quantity)) ) {
			alert("Price or quantity must be integer !");
			$("#newFoodModal").modal('show');
		} 
		else {
			var header=["FoodID, Category"];
			var valueList=[foodCode,category];
			if (price != ""){
				header.push("Price");
				valueList.push(price);
			}
			if (quantity != ""){
				header.push("Quantity");
				valueList.push(quantity);
			}
			if (price != ""){
				header.push("FoodName");
				valueList.push(foodName);
			}
			$.ajax({
				type: "POST",
				url: "tools/update.php",
				data: { "operation": "insert","target_table":"menu","headerList":header,"valueList":[valueList]},
				success: function(data, txt, jqxhr){
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
	})


});

function refreshData(){
	idChange=[];
	$("#mainTable tbody").load("js/test.php");
	refresh_buttons();
}




