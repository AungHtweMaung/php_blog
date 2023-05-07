<?php
session_start();
require("config/config.php");
require("config/common.php");

if (empty($_SESSION["user_id"]) && empty($_SESSION["logged_in"])) {
    header("location: ./login.php");
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=" . $_GET["id"]);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$blogId = $_GET["id"];

$cmstmt = $pdo->prepare("SELECT * FROM comments WHERE post_id=$blogId");
$cmstmt->execute();
$cmResult = $cmstmt->fetchAll();

$authorResult = [];

if ($cmResult) {
    // comment အားလုံးကို loop ပတ်ပြီး ထုတ်ဖို့။ 
    // author name တွေကိုပြရအောင်လို့ author_id တွေကို သက်သက်ထည့်ထားလိုက်တာ။ 
    foreach ($cmResult as  $value) {
        $authorId = $value["author_id"];
        $author_stmt = $pdo->prepare("SELECT * FROM users WHERE id=$authorId");
        $author_stmt->execute();
        $authorResult[] = $author_stmt->fetch(PDO::FETCH_ASSOC);
        // array_push($authorResult, $author_stmt->fetch(PDO::FETCH_ASSOC));
    }
}
// echo "<pre>";
// print_r($authorResult);
// exit();

if ($_POST) {
    if (empty(trim($_POST["comment"]))) {
        $commentErr = "*Comment can't be blank!";
    } else {
        $comment = $_POST["comment"];
        $stmt = $pdo->prepare("INSERT INTO comments(content, author_id, post_id) VALUES(:content, :author_id, :post_id)");
        $result = $stmt->execute(
            array(
                ":content" => $comment,
                ":author_id" => $_SESSION["user_id"],
                ":post_id" => $blogId,
            )
        );

        if ($result) {
            header("location: blogdetail.php?id=$blogId");
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $result['title'] ?></title>

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
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Box Comment -->
                            <div class="card card-widget">
                                <h2 class="text-center"><?php echo escape($result['title']); ?></h2>
                                <div class="card-body">
                                    <div>
                                        <img src="admin/image/<?php echo escape($result['image']); ?>" class="img-fluid pad" alt="Photo">
                                    </div>
                                    <p><?php echo escape($result['content']); ?></p>
                                    <div>
                                        <a type="button" href="index.php" class="btn btn-primary px-4">Back</a>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer card-comments">
                                    <h2 class="mr-3">Comment</h2>

                                    <div class="card-comment">
                                        <hr>
                                        <!-- User image -->
                                        <!-- <img class="img-circle img-sm" src="dist/img/user3-128x128.jpg" alt="User Image"> -->



                                        <?php if ($cmResult) { ?>

                                            <?php foreach ($cmResult as $key => $value) { ?>
                                                <div class="comment-text mb-3 " style="margin-left: 0;">
                                                    <span class="username">
                                                        <!-- $key က ရှိသလောက် 0, 1, 2 တစ်ခုဆီဖြစ်မယ်
                                                            အဲ့တော့ authorResult ထဲက record တွေထဲက တစ်ကြောင်းချင်းဆီက name တွေကို ဖော်ပြပေးတယ်။ 
                                                        -->
                                                        <span style="font-size: 20px;"><?php echo escape($authorResult[$key]["name"]); ?></span>
                                                        <span class="text-muted float-right"><?php echo date("Y-m-d h:ia", strtotime($value["created_at"])) ?></span><br>
                                                        <?php echo escape($value['content']); ?>
                                                    </span><!-- /.username -->
                                                </div>
                                        <?php
                                            }
                                        }
                                        ?>





                                        <!-- /.comment-text -->
                                    </div>
                                    <!-- /.card-comment -->

                                </div>
                                <!-- /.card-footer -->
                                <div class="card-footer">
                                    <form action="" method="post">
                                        <!-- <img class="img-fluid img-circle img-sm" src="dist/img/user4-128x128.jpg" alt="Alt Text"> -->
                                        <!-- .img-push is used to add margin to elements next to floating images -->
                                        <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                                        <p class="text-danger"><?php echo empty($commentErr) ? '' : $commentErr; ?></p>
                                        <div class="img-push d-flex">
                                            <input type="text" name="comment" class="form-control form-control-sm mr-3" placeholder="Press enter to post comment">
                                            <input type="submit" value="Submit" class="btn btn-sm btn-primary">
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card-footer -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->

                    </div>


            </section>
            <!-- /.content -->

            <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
                <i class="fas fa-chevron-up"></i>
            </a>
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <a href="./logout.php" class="btn btn-default">Logout</a>
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