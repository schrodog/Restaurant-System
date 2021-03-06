$.fn.reportInit = function(data){
	// console.log(data[0]);
	$("#startYear").text(data[0]);
	$("#startMonth").text(data[1]);
	$("#startDay").text(data[2]);
	$("#endYear").text(data[3]);
	$("#endMonth").text(data[4]);
	$("#endDay").text(data[5]);
};

function calcTotal(){
	var row = $("#mainTable tbody tr").length;
	var total = 0, index=0;
	var op = $("#data-op").text();
	if (op=="1"){
		index = 3;
	} else if (op=='2' || op=='3') {
		index = 2;
	}

	$("#mainTable tr td:nth-child("+index+")").each(function(){
		total += Number($(this).text());
	});
	$("#billTotal").text(row);
	$("#incomeTotal").text('$'+total);
}

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

	// function refreshTable(){
	// 	$.ajax({
	// 		type: "POST",
	// 		url: "user-management.php",
	// 		success: function(){
	// 			window.location = "user-management.php";
	// 		}
	// 	});
	// }

	$("#startYearList a").click(function(){
		var txt = $(this).text();
		$(".dropdown #startYear").text(txt);
	});
	$("#startMonthList a").click(function(){
		var txt = $(this).text();
		$(".dropdown #startMonth").text(txt);
	});
	$("#startDayList a").click(function(){
		var txt = $(this).text();
		$(".dropdown #startDay").text(txt);
	});
	$("#endYearList a").click(function(){
		var txt = $(this).text();
		$(".dropdown #endYear").text(txt);
		console.log(txt);
	});
	$("#endMonthList a").click(function(){
		var txt = $(this).text();
		$(".dropdown #endMonth").text(txt);
		console.log(txt);
	});
	$("#endDayList a").click(function(){
		var txt = $(this).text();
		$(".dropdown #endDay").text(txt);
		console.log(txt);
	});
	/* leave dropdown when click outside */
	window.onclick = function(event) {
		if (!event.target.matches('.dropbtn')) {
			$(".dropdown-content").hide();
	}};

	$("#time-filter").click(function(){
		refreshPage();
	});
	$("#dayMode").click(function(){
		$("#operation-code").text('1');
		refreshPage();
	});
	$("#monthMode").click(function(){
		$("#operation-code").text('2');
		refreshPage();
	});
	$("#yearMode").click(function(){
		$("#operation-code").text('3');
		refreshPage();
	});

	$("#viewOrderBtn").click(function(){
		$.ajax({
			type: "POST",
			url: "menu-order-count.php",
			data: {"dr": [$("#startYear").text(),$("#startMonth").text(),$("#startDay").text(),$("#endYear").text(),$("#endMonth").text(),$("#endDay").text()] },
			success: function(data, txt, jqxhr){
				url = 'menu-order-count.php';
				window.open(url,"_blank");
				// alert("You have successfully added.");
				// refreshTable();
			}
		}).fail(function(xhr, status, error){
			alert(error);
		});
	});

	// $("#viewOrderBtn").click(function(){
	// 	$.ajax({
	// 		type: "POST",
	// 		url: "menu-order-count.php",
	// 		data: {"operation", },
	// 		success: function(data, txt, jqxhr){
	// 			url = 'line-chart.php';
	// 			window.open(url,"_blank");
	// 			// alert("You have successfully added.");
	// 			// refreshTable();
	// 		}
	// 	}).fail(function(xhr, status, error){
	// 		alert(error);
	// 	});
	// });

	// view detailed daily bills
	$(".bill-file").click(function showBills(){
		var thisrow = $(this).parent();
		var reportID = thisrow.find("td").first().text();
		var billDate = thisrow.find("td:nth-child(4)").text();
		// console.log(thisrow.text());
		console.log('report:'+reportID);

		$.ajax({
			type: "POST",
			url: "tools/save_session.php",
			data: {"name": ["reportID","billDate"] , "value":[reportID,billDate] },
			success: function(data, txt, jqxhr){
				url = 'bill-details.php';
				window.open(url,"_blank");
			}
		}).fail(function(xhr, status, error){
			alert(error);
		});
	});

	// update report
	$("#updateBtn").click(function(){
		$("#updateModal").modal('show');
		$("#updateModal #OK").click(function(){
			var days = $("#updateModal #days-before").val();
			$("#updateModal").modal('hide');
			updateReport(days);
		});
	});

});

function refreshPage(){
	var dr = [$("#startYear").text(),$("#startMonth").text(),$("#startDay").text(),$("#endYear").text(),$("#endMonth").text(),$("#endDay").text()];
	var op = $("#operation-code").text();
	// console.log(dr);
	// $.ajax({
	// 	type: "GET",
	// 	url: "report.php",
	// 	success: function(){
	// 		window.location.href = 'report.php?dr[]='+dr[0]+'&dr[]='+dr[1]+'&dr[]='+dr[2]+'&dr[]='+dr[3]+'&dr[]='+dr[4]+'&dr[]='+dr[5]+'&op='+op;
	// 	}
	// });
	window.location.href = 'report.php?dr[]='+dr[0]+'&dr[]='+dr[1]+'&dr[]='+dr[2]+'&dr[]='+dr[3]+'&dr[]='+dr[4]+'&dr[]='+dr[5]+'&op='+op;
}

function showDropdown(n){
	$(".dropdown-content").hide();
	switch (n) {
		case 1:
			$("#startYearList").toggle();
			break;
		case 2:
			$("#startMonthList").toggle();
			break;
		case 3:
			$("#startDayList").toggle();
			break;
		case 4:
			$("#endYearList").toggle();
			break;
		case 5:
			$("#endMonthList").toggle();
			break;
		case 6:
			$("#endDayList").toggle();
			break;
	}
}

function updateReport(day){
	var days = parseInt(day,10);
	// console.log(days+1);
	$.ajax({
		type: "POST",
		url: "tools/update_report.php",
		data: {"days": days },
		success: function(data, txt, jqxhr){
			refreshPage();
			// alert(data);
			// alert('Report updated.');
		}
	}).fail(function(xhr, status, error){
		alert(error);
	});
}

// Close the dropdown menu if the user clicks outside of it
function GoHome(link){
	if (idChange.length>0){
		alert('You have unsaved changes. Are you sure to exit?');
	}
}
