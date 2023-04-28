<?php

session_start();
require("config/config.php");
if (empty($_SESSION["user_id"]) && empty($_SESSION["logged_in"])) {
    header("location: ./login.php");
}

$stmt  = $pdo->prepare('SELECT * FROM posts ORDER BY id DESC');
$stmt->execute();
$rawResult = $stmt->fetchAll(); // get all records from db table 


if (!empty($_GET["pageno"])) {
    $pageno = $_GET["pageno"];
} else {
    $pageno = 1;
}

$no_of_records_per_page = 6;
$offset = ($pageno-1) * $no_of_records_per_page;
$total_pages = ceil(count($rawResult) / $no_of_records_per_page);

$stmt  = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset, $no_of_records_per_page");
$stmt->execute();
$result = $stmt->fetchAll(); // get all records from db table 



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="">
        <!-- Content Wrapper. Contains page content -->
        <div class="">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <h1 class="text-center my-3">Blog Site</h1>
                    <div class="row">
                        <?php
                        $i = 1;
                        if ($result) {
                            foreach ($result as $value) {
                        ?>
                                <div class="col-md-4">
                                    <!-- Box Comment -->
                                    <div class="card card-widget">
                                        <div class="card-header ">
                                            <h4 class=" w-100 text-center"><?php echo $value["title"] ?></h4>
                                            <!-- /.card-tools -->
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">

                                            <a href="blogdetail.php?id=<?php echo $value['id']; ?>"><img src="admin/image/<?php echo $value['image']; ?>" class="img-fluid pad" style="width: 100%; height: 220px !important;" alt="Photo"></a>
                                            <p>I took this photo this morning. What do you guys think?</p>

                                        </div>
                                        <!-- /.card-body -->

                                    </div>
                                    <!-- /.card -->
                                </div>
                        <?php
                                $i++;
                            }
                        }
                        ?>


                        <!-- /.col -->
                    </div>

                    <!-- pagination  -->
                    <div class="row">
                        <div class="col-12">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination float-right">
                                    <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                                    <li class="page-item <?php if($pageno<=1){echo 'disabled';} ?>">
                                        <a class="page-link" href="?pageno=<?php if($pageno<=1){echo '#';}else {echo $pageno-1;} ?>">Previous</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                                    <li class="page-item <?php if($pageno>=$total_pages){echo 'disabled';} ?>">
                                        <a class="page-link" href="?pageno=<?php if($pageno>=$total_pages){echo '#';}else {echo $pageno+1;} ?>">Next</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
            </section>
            <!-- /.content -->

            <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
                <i class="fas fa-chevron-up"></i>
            </a>
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer ml-0">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 3.2.0
            </div>
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="dist/js/demo.js"></script> -->
</body>

</html>