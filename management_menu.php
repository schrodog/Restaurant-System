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

    <!-- <link href="user-man.css" rel="stylesheet" type="text/css" /> -->
	  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <script src="lib/jquery-3.2.1.min.js"></script>

	<style>
		.container {
      margin-top: 70px;
    }
		.btn-primary {
					 margin:0px auto;
					  width:600px;
					  color: #333;
					  background-color: #d3d3d3;
					  border-color: #adadad;
					  border-radius: 30px;
            cursor: pointer;
		}
		button {
      cursor: pointer;
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
				padding-left:40px;
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
			<div class="icon-bar">
     			<a class="active" href="main_menu-manager.php"><i class="fa fa-home"></i></a>
   			 </div>
				<ul class="title" >Management</p></ul>
			<button type="button" id="logoutBtn" class="btn btn-warning"><img src="icon/log-out.svg"> Logout </button>
  </nav>
    <!-- Page Content -->
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
	          <br><br>
            <a class="btn btn-primary btn-lg btn-block" href="user-management.php"><h1 class="my-5">Edit user</h1></a>
            <br><br><br>

            <a class="btn btn-primary btn-lg btn-block" href="menu-management.php"><h1 class="my-5">Edit menu</h1></a>
            <br><br><br>

            <a class="btn btn-primary btn-lg btn-block" href="table-management.php"><h1 class="my-5">Edit tables</h1></a>
          </ul>
        </div>
      </div>
    </div>
    <script src="js/logout.js"></script>

</body>

</html>