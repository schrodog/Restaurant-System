<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- <link rel="icon" href="../../../../favicon.ico"> -->
    <title>Signin Template for Bootstrap</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="custom_css/index.css" rel="stylesheet">
    <script src="lib/jquery-3.2.1.min.js"></script>
    <!-- <script src="index.js"></script> -->
    <!-- <script type="text/javascript">
      $("#")
    </script> -->
</head>

<body>

<div class="container">

  <h2 class="form-signin-heading">Restaurant Management System</h2>
  <form class="form-signin" action="tools/sign-in.php" method="post">
    <label for="inputEmail" class="sr-only">Email address</label>
    <input type="text" name="inputName" class="form-control" placeholder="User Name" required autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" name="inputPassword" class="form-control" placeholder="Password" required>
    <button class="btn btn-lg btn-primary btn-block" type="submit" id="sign-in">Sign in</button>
  </form>

  <!-- <a href="user-management.php">User Management</button>
<a href="report.php?dr[]=2017&dr[]=10&dr[]=1&dr[]=2018&dr[]=6&dr[]=3&op=1">Report</button> -->

</div>

<?php 

// echo $_SERVER["SERVER_ADDR"];
?>

</body>
</html>
