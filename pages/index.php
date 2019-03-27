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

    <!-- Morris Charts CSS -->
    <link href="../vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Custom CSS-->
    <link href="../vendor/custom/custom.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<!-- Database connection -->
<?php
    $myPDO = new PDO('sqlite:../database/LibraryDatabase.db');
    $result = "SELECT COUNT(*) FROM Book";
    $res1 = $myPDO->query($result);
while ($row = $res1->fetch()) {
$Count_Books = $row[0];
}

// Another solution: N = count copies of book A, L = count loaned copies of book A, R = Count returned copis of book A.  check if N > (C-F)
//
$result = "SELECT COUNT (DISTINCT b.ISBN)
FROM Book b, Returns r, Loans l, Book_copies bc
WHERE b.ISBN = bc.ISBN
AND (l.Book_ID = bc.Copy_ID
AND (SELECT COUNT(bc2.Copy_ID) 
      FROM Book_copies bc2 
      WHERE b.ISBN = bc2.ISBN) > ((SELECT COUNT(ID) 
                                    FROM loans 
                                    WHERE Book_ID = bc.Copy_ID) - (SELECT COUNT(ID)
                                                                    FROM Returns
                                                                    WHERE Loans_ID = l.ID)) OR (bc.Copy_ID NOT IN (SELECT Book_ID FROM Loans)))";

$res1 = $myPDO->query($result);
while ($row = $res1->fetch()) {
$Available_Books = $row[0];
}

//Select number of borrowed book copies
$result = "SELECT (SELECT COUNT(ID) FROM loans) - (SELECT COUNT(l.ID) FROM loans l, returns r WHERE l.ID = r.loans_ID)";
$res1 = $myPDO->query($result);
while ($row = $res1->fetch()) {
$Borrowed_Books = $row[0];
}

//Select number of not returned books
$result = "SELECT COUNT(DISTINCT l.ID) 
           FROM loans l, returns r 
           WHERE l.Due_Date < date('now')
           AND l.ID NOT IN (SELECT loans_ID FROM Returns)";
$res1 = $myPDO->query($result);
while ($row = $res1->fetch()) {
$Late_Books = $row[0];
}
?>
<body>

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
                            <a href="edit.php"><i class="fa fa-edit fa-fw"></i> Edit </a>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Dashboard</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-tasks fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $Count_Books; ?></div>
                                    <div>All Books</div>
                                </div>
                            </div>
                        </div>
                        <a href="books.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-book fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $Available_Books; ?></div>
                                    <div>Available Books</div>
                                </div>
                            </div>
                        </div>
                        <a href="available_books.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-barcode fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $Borrowed_Books; ?></div>
                                    <div>Borrowed Books Copies</div>
                                </div>
                            </div>
                        </div>
                        <a href="borrowed_books.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-history fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $Late_Books; ?></div>
                                    <div>Not returned loans</div>
                                </div>
                            </div>
                        </div>
                        <a href="notdelivered_books.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-8">
                    <!-- /.panel -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-history fa-fw"></i> Latest Loans (History)
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                        Actions
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <li><a href="#">Action</a>
                                        </li>
                                        <li><a href="#">Another action</a>
                                        </li>
                                        <li><a href="#">Something else here</a>
                                        </li>
                                        <li class="divider"></li>
                                        <li><a href="#">Separated link</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-4 custom-sa">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Loan ID</th>
                                                    <th>Book ID</th>
                                                    <th>ISBN</th>
                                                    <th>Borrower</th>
                                                    <th>From Date</th>
                                                    <th>Due Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $result = "SELECT l.ID AS 'Loan ID', l.book_id AS 'Book ID', b.ISBN, 
                                                    u.First_name || ' ' || u.Last_name AS 'Borrower', 
                                                    l.Start_date AS 'From Date', l.Due_date AS 'Due Date' 
                                                    FROM loans l, book_copies b, user u 
                                                    WHERE u.ID = l.user_ID AND b.copy_ID = l.book_id
                                                    ORDER BY l.start_date DESC";
                                            $res1 = $myPDO->query($result);
                                            while ($row = $res1->fetch()) {
                                                $loan_id = $row[0];
                                                $book_id = $row[1];
                                                $ISBN = $row[2];
                                                $borrower = $row[3];
                                                $from = $row[4];
                                                $due = $row[5];
                                                echo '<tr>' . '<td>' . $loan_id . '</td>' . '<td>' . $book_id . '</td>' .
                                                    '<td>' . $ISBN . '</td>' . '<td>' . $borrower . '</td>' .
                                                    '<td>' . $from . '</td>' . '<td>' . $due . '</td>' . '</tr>';
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.col-lg-4 (nested) -->
                                <div class="col-lg-8">
                                    <div id="morris-bar-chart"></div>
                                </div>
                                <!-- /.col-lg-8 (nested) -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->

                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-8 -->
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-fire fa-fw"></i> Most Borrowed
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
                                <?php
                                $result = "SELECT b.title, (SELECT COUNT(l.ID)) AS num
                                            FROM book b, Book_copies bc, Loans l
                                            WHERE b.ISBN = bc.ISBN
                                            AND l.Book_ID = bc.Copy_ID
                                            GROUP BY b.ISBN
                                            ORDER BY num DESC
                                            LIMIT 8";
                                $res1 = $myPDO->query($result);
                                while ($row = $res1->fetch()) {
                                    $title = $row[0];
                                    $numOfLoans = $row[1];

                                    echo '<a href="#" class="list-group-item">
                                                <i class="fa fa-book fa-fw"></i>' . $title .
                                                '<span class="pull-right text-muted small"><em>' . $numOfLoans . '</em>
                                                </span>
                                            </a>';
                                }
                                ?>

                            </div>
                            <!-- /.list-group -->
                            <a href="borrowed_books.php" class="btn btn-default btn-block">View All</a>
                        </div>
                        <!-- /.panel-body -->
                    </div>

                    </div>
                    <!-- /.panel .chat-panel -->
                </div>
                <!-- /.col-lg-4 -->
            </div>
            <!-- /.row -->
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

    <!-- Morris Charts JavaScript -->
    <script src="../vendor/raphael/raphael.min.js"></script>
    <script src="../vendor/morrisjs/morris.min.js"></script>
    <script src="../data/morris-data.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

</body>

</html>
