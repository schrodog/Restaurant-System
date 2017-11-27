
if (confirm("Are you sure to logout?"==true)){
  $.ajax({
    type: "POST",
    url: "tools/save_session.php",
    data: { "value": ["Privilege","Passwword","Username"], "unset": "1"},
    success: function(data, txt, jqxhr){
      alert(data);
      window.location = "index.php";
    }
  }).fail(function(xhr, status, error){
    alert(error);
  });
}

