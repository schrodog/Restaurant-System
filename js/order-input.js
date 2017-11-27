
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
  $(document).on( "click","#total",function() {
    //e.preventDefault();
    var dat = $("#orderno").val();
    $.ajax({
      type     : "POST",
      // cache    : false,
      url      : "searchorderid.php",
      data     : {"orderno": dat},
      success  : function(data) {
        alert(data);
        //$("#total").empty();
        $("#total").val(data);
      }
    });
  });


  //Search Order Number
  $("#orderno").on( "keypress",function(e) {
    //e.preventDefault();
    var dat = $(this).val();
    if (e.keyCode == 13) {
      // e.preventDefault();
      $.ajax({
        type     : "POST",
        // cache    : false,
        url      : "searchorderid.php",
        data     : {"orderno": dat},
        success  : function(data) {
          //alert(data);
          $("#order").empty();
          $("#order").append("<tr>"+data+"</tr>");
        }
      });
    }
  });
  //
  //Search Table Number
  //  $("#tableno").on("keypress",function(e) {
  //    //e.preventDefault();
  //  var dat = $(this).val();
  //   if (e.keyCode == 13) {
  //     // e.preventDefault();
  //     $.ajax({
  //       type     : "POST",
  //       //cache    : false,
  //       url      : "searchtableid.php",
  //       data     : {"orderno1": dat},
  //       success  : function(data) {
  //         //alert(data);
  //         $("#order").empty();
  //         $("#order").append("<tr>"+data+"</tr>");
  //       }
  //     });
  //   }
  // });

});
//
// });