
$(document).ready(function () {
  //   //var table = $('.table').DataTable();
  //calculate change
  var change=0;
  $("#paid").keypress(function(e) {
    if(e.which == 13) {
      change = $('#total').val() - $(this).val();
      $("#change").val(change);
    }
  });
  //
  //calculate total
  // $(document).on( "click","#total",function() {
  //   //e.preventDefault();
  //   var dat = $("#orderno").val();
  //   $.ajax({
  //     type     : "POST",
  //     // cache    : false,
  //     url      : "searchorderid.php",
  //     data     : {"orderno": dat},
  //     success  : function(data) {
  //       alert(data);
  //       //$("#total").empty();
  //       $("#total").val(data);
  //     }
  //   });
  // });

  $("body").on('click','.delBtn',function(){
    var thisRow = $(this).parent().parent();
    var delID = thisRow.find("td").first().text();
    
    // console.log('delID:'+delID);
    // 
    // $("#deleteModal").modal('show');
		// 
    // $("#deleteModal #OK").one('click',function(){
    
    if (confirm("Are you sure to delete?") == true){
      $.ajax({
        type: "POST",
        url: "tools/update.php",
        data: { "operation": "delete","target_table":"order","valueList":[delID],"idName":"OrderID"},
        success: function(data, txt, jqxhr){
          // alert(data);
          thisRow.remove();
          calcTotal();
          // refreshData();
        }
      }).fail(function(xhr, status, error){
        alert(error);
      });
     
    }
		// });
  });
  
  $(".change-group #paid").on('change',function(){
    calcTotal();
    var total = Number( $(".total-calc #total").val());
    var paid = Number($(this).val());
    var change = paid-total;
    $(".change-group #change").val(change);
  });

  $('body').on('click','#confirmOrderBtn',function(){
    var masterOrderID = $("#data-masterOrderID").text().replace(/\n/g,'') ;
    var tableNo = $("#data-tableno").text().replace(/\n/g,'') ;
		// $("#checkoutModal").modal('show');
		// console.log('delID:'+delID);
		// $("#checkoutModal #OK").one('click',function(){
      // var payment = $("#checkoutModal #payment").val();
      // var change = $("#checkoutModal #change").val();
      var d = new Date();
      var timeStr = (d.getUTCHours()+8) +":"+d.getUTCMinutes()+":"+d.getUTCSeconds();
      var price = $(".total-calc #total").val();
      var paid = $(".change-group #paid").val();
      var change = $(".change-group #change").val();
      
      // console.log(timeStr+","+price+","+paid+","+change+","+masterOrderID);
      console.log(tableNo);
			$.ajax({
				type: "POST",
				url: "tools/update.php",
				data: { "operation":"update","target_table":"masterorder","valueList":[[paid,change,price,timeStr]],"idName":"masterOrderID","idList":[masterOrderID],"headerList":["Payment","Change","Price","CheckOut Time"]},
				success: function(data, txt, jqxhr){
					// alert(data);
				}
			}).fail(function(xhr, status, error){
				alert(error);
			});
		  
      $.ajax({
        type: "POST",
				url: "tools/update.php",
				data: { "operation":"update","target_table":"table","valueList":[['Y']],"idName":"TableNo","idList":[tableNo],"headerList":["Available"]},
				success: function(data, txt, jqxhr){
					// alert(data);
          window.location = "table-management.php";
				}
			}).fail(function(xhr, status, error){
				alert(error);
      });
    });


});


function calcTotal(){
  var sum = 0;
  $("tbody tr td:nth-child(5)").each(function(){
    // console.log(value);
    sum += Number($(this).text());
  });
  $(".total-calc #total").val(sum);
}

