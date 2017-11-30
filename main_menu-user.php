<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Order System</title>

  <!-- Bootstrap core CSS -->

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Custom styles for this template -->

  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />

  <script src="lib/jquery-3.2.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

  <style>
  .btn-primary {

    margin:0 auto;
    width:600px;
    color: #333;
    background-color: #d3d3d3;
    border-color: #adadad;
    border-radius: 30px;

  }

  .icon-bar {
    width: 100px;
    background-color: #555;
  }
  .icon-bar a {
    display: block;
    text-align: center;
    transition: all 0.3s ease;
    color: white;
    font-size: 40px;
  }

  .title {
    padding-left:20px;
    color:white;
    font-size:40px;
    margin: 0 auto;
    font-weight: 600;

  }
  body {
    padding-top: 54px;

  }
  @media (min-width: 992px) {
    body {
      padding-top: 56px;
    }
  }
  </style>

</head>

<body>

  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <!-- <div class="icon-bar">
    <a class="active" href="index.html"><i class="fa fa-home"></i></a>
  </div> -->
  <!-- <button class="btn btn-primary" id="chPwdBtn">Change Password</button> -->

  <ul class="title" >Main Menu</p></ul>


  <button type="button" id="logoutBtn" class="btn btn-warning"><img src="icon/log-out.svg"> Logout </button>
</nav>
<!-- Page Content -->
<div class="container">
  <div class="row">
    <div class="col-lg-12 text-center">
      <br><br><br><br><br><br><br><br><br>
      <a class="btn btn-primary btn-lg btn-block" href="table-management.php"><h1 class="my-5">Search Table</h1></a>
      <br><br><br>

    </ul>
  </div>
</div>
</div>

<div id="newPasswordModal" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header">
    <h4 class="modal-title">New Password</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
  <div class="modal-body">
    <label for="pwd">Please enter new password:</label>
    <input type="password" class="form-control no_focus" id="pwd1">
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-primary" id="OK" data-dismiss="modal">OK</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
  </div>
</div></div></div>

<script src="js/logout.js"></script>

</body>

</html>