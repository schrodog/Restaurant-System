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
		if (column==1) { // for column name
			var re = /^[0-9]+$/g;
			return !!value && value.trim().length > 0 && !!value.match(re);
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

	// update row
	$("#updateBtn").click(function(){
		var asso = {}, valueList=[], updateData=[], layer2=[], header=[], index=1, updateRowNo=[], match_insert=[];
		// var updateRows = $(idChange).get();
    var updateRows = idChange.filter(function(item, pos) {
      return idChange.indexOf(item) == pos;
    })

		console.log("late idChange="+updateRows);

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

		if (updateRowNo.length>0){
			$.ajax({
				type: "POST",
				url: "tools/update.php",
				data: { "valueList": updateData,"target_table":"table","headerList":["NumOfSeat","Available"] ,"idList":idChange,"idName":"TableNo", "operation": "update"},
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
		$("#newTableModal").modal('show');
	});

	// create new food code
	$("body").on('click','#newTableModal #OK',function(){
		var tableno = $("#newTableModal input[id='tableno']").val();
		var seats = $("#newTableModal input[id='numOfSeats']").val();
    var available = 'Y';

		// console.log(tableno+","+seats);
    // console.log(parseInt(seats,10));

		if (tableno==""){
			alert("Table No is empty !");
			$("#newTableModal").modal('show');
		} else if (seats!="" && !(seats==parseInt(seats,10)) ) {
			alert("Number of seats must be integer !");
			$("#newTableModal").modal('show');
		}
		else {
			$.ajax({
				type: "POST",
				url: "tools/update.php",
				data: { "operation": "insert","target_table":"table","headerList":["TableNo", "NumOfSeat","Available"],"valueList":[[tableno,seats,available]]},
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
				data: { "operation": "delete","target_table":"table","valueList":[delID],"idName":"TableNo", "override":"1"},
				success: function(data, txt, jqxhr){
					// alert(data);
					// thisRow.remove();
					refreshData();
				}
			}).fail(function(xhr, status, error){
				alert(error);
			});
		});
	});

  $(".changeTableBtn").click(function(){
    var thisRow = $(this).parent().parent();
		var oldID = thisRow.find("td").first().text();
		$("#changeNoModal").modal('show');

    $("#changeNoModal #OK").one('click',function(){
      var changeID = $("#changeNoModal input[id='newTableNo']").val();
      if (changeID==""){
        alert("Table No is empty !");
        $("#changeNoModal").one('hidden.bs.modal',function(){
					$("#changeNoModal").modal('show');
				});
      } else {
        $.ajax({
          type: "POST",
          url: "tools/update.php",
          data: { "operation": "update","target_table":"table","valueList":[[changeID]],"idName":"TableNo","headerList":["TableNo"],"idList":[oldID], "override":"1"},
          success: function(data, txt, jqxhr){
            // alert(data);
            refreshData();
          }
        }).fail(function(xhr, status, error){
          alert(error);
        });
      }
    });
  });



});



function GoToTable(tableNo,available){
  $("#assignNewModal").modal('show');
  console.log('table:'+tableNo);
  if (available=='N'){
    $("#assignNewModal #OK").one('click',function(){
      var d = new Date();
      var currentDate = d.getUTCFullYear()+'-'+ d.getUTCMonth()+'-'+ d.getUTCDate();
      $.ajax({
        type: "POST",
        url: "tools/update.php",
        data: { "operation": "insert","target_table":"masterorder","valueList":[[tableNo,currentDate]],"headerList":["TableNo","CheckOut Date"],"getLastID":"1" },
        success: function(data, txt, jqxhr){
          // alert(data);
          goToOrder(data,tableNo);
          // refreshData();
        }
      }).fail(function(xhr, status, error){
        alert(error);
      });
    });
  } else {
    $.ajax({
      type: "POST",
      url: "tools/update.php",
      data: { "operation": "insert","target_table":"masterorder","valueList":[[tableNo,currentDate]],"headerList":["TableNo","CheckOut Date"],"getLastID":"1" },
      success: function(data, txt, jqxhr){
        // alert(data);
        goToOrder(data,tableNo);
        // refreshData();
      }
    }).fail(function(xhr, status, error){
      alert(error);
    });
  }
}


function refreshData(){
	idChange=[];
  // window.location = "table-management.php";
	// $("#mainTable tbody").load("js/test.php");
	// refresh_buttons();
}

function goToOrder(masterOrderID, tableNo){
  $.ajax({
    type: "POST",
    url: "tools/save_session.php",
    data: { "value":[masterOrderID, tableNo],"name":["masterOrderID","tableNo"]},
    success: function(data, txt, jqxhr){
        window.location = "order.php";
    }
  }).fail(function(xhr, status, error){
    alert(error);
  });
}


