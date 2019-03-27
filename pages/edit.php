<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Library Management System</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<?php
$conn = new PDO('sqlite:../database/LibraryDatabase.db');
$submitted = false;

// define variables and set to empty values
$ISBN = $title = $year = $language = $dewey = $notes = $publisher = $author = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submitted = true;
  $ISBN = validate_input($_POST["ISBN"]);
  $title = validate_input($_POST["title"]);
  $year = validate_input($_POST["year"]);
  $language = validate_input($_POST["language"]);
  $dewey = validate_input($_POST["dewey"]);
  $notes = validate_input($_POST["notes"]);
  $publisher = validate_input($_POST["publisher"]);
  $author = validate_input($_POST["author"]);

  $sql = "INSERT INTO Book(ISBN, Publisher_ID, Title, Year, Language, Dewey, Notes)"
        . "VALUES('" . $ISBN ."','" . $publisher . "','" . $title . "','" . $year."','".
        $language . "','" . $dewey . "','" . $notes . "')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
    }

}

function validate_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Library Management System</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="login.html"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-book fa-fw"></i> Books <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="books.php">All Books</a>
                                </li>
                                <li>
                                    <a href="available_books.php">Available Books</a>
                                </li>
                                <li>
                                    <a href="borrowed_books.php">Borrowed Books</a>
                                </li>
                                <li>
                                    <a href="notdelivered_books.php">Not delivered Books</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="tables.html"><i class="fa fa-table fa-fw"></i> View Tables</a>
                        </li>
                        <li>
                            <a href="edit.php"><i class="fa fa-edit fa-fw"></i> Edit </a>                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper" style="height: 1000px">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Editor / Manager</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Add a new book                        </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <form role="form" action="edit.php" method="post">
                                    <?php
                                    if($submitted){
                                      echo  '<div class="alert alert-success alert-dismissable">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                Book added to database sucessfully! 
                                                <a href="books.php" class="alert-link">View all Books</a>.
                                            </div>';
                                    }
                                    ?>
                                    <div class="form-group">
                                        <label>ISBN</label>
                                        <input class="form-control" placeholder="ISBN" name="ISBN">
                                    </div>

                                    <div class="form-group">
                                        <label>Select publisher</label>
                                        <select class="form-control" name="publisher">
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Select author</label>
                                        <select class="form-control" name="author">
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Title</label>
                                        <input class="form-control" placeholder="Book Title" name="title">
                                    </div>

                                    <div class="form-group">
                                        <label>Year</label>
                                        <input class="form-control" placeholder="Release Year" name="year">
                                    </div>

                                    <div class="form-group">
                                        <label>Language</label>
                                        <input class="form-control" placeholder="Language" name="language">
                                    </div>

                                    <div class="form-group">
                                        <label>Dewey</label>
                                        <input class="form-control" placeholder="Dewey" name="dewey">
                                    </div>

                                    <div class="form-group">
                                        <label>Notes</label>
                                        <textarea class="form-control" rows="3" name="notes"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-default">Add Book</button>
                                    <button type="reset" class="btn btn-default">Reset Form</button>
                                </form>
                            </div>
                            <!-- /.col-lg-6 (nested) -->

                            <!-- /.col-lg-6 (nested) -->
                        </div>
                        <!-- /.row (nested) -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

</body>

</html>
