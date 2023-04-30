<?php
require("../config/config.php");
session_start();
if (empty($_SESSION["user_id"]) && empty($_SESSION["logged_in"])) {
  header("location: ./login.php");
}

if ($_SESSION["role"] != 1) {
  header("location: login.php");
}

// search key ရှိခဲ့ရင် cookie ထဲမှာ search value သိမ်းလိုက်မယ်
if (!empty($_POST["search"])) {
  setcookie("search", $_POST["search"], time() + (86400 * 30), "/");
} else {
  // pageno ရှိနေတုန်းဆိုရင် pagination အတွက် cookie ထဲက value လေးနဲ့ တူတဲ့ကောင်ကို ရှာပေးမယ် 
    if (empty($_GET["pageno"])) {
      unset($_COOKIE["search"]);
      setcookie("search", null, -1, "/");
  }
}

?>

<?php
$title = "Blog Site";
include("header.php");

?>


<!-- Content Wrapper. Contains page content -->

<div class="col-md-12">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Blog List</h3>
    </div>
    <!-- /.card-header -->

    <?php

    if (!empty($_GET["pageno"])) {
      $pageno = $_GET["pageno"];
    } else {
      $pageno = 1;
    }

    $no_of_records_per_page = 1;  // records 2 ခုဆီပြမှာ 
    // below formula is that start taking the frist record from db.
    $offset = ($pageno - 1) * $no_of_records_per_page;

    // no search value and no cookie value ဆိုရင် all records ကို pagination အတိုင်းပြမယ်
    if (empty($_POST["search"]) && empty($_COOKIE["search"])) {
      $stmt  = $pdo->prepare('SELECT * FROM posts ORDER BY id DESC');
      $stmt->execute();
      $rawResult = $stmt->fetchAll(); // get all records from db table 

      $total_pages = ceil(count($rawResult) / $no_of_records_per_page); // get total pages
      // record 0 ကနေ တစ်ခါယူ 2ခု, နောက်တစ်ခါယူ 2 ခုဆီ ယူတယ်
      $stmt  = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$no_of_records_per_page");
      $stmt->execute();
      $result = $stmt->fetchAll();
    } else {
        $searchKey = $_POST['search'] ? $_POST['search'] : $_COOKIE['search'];
        // echo $searchKey;
        $stmt  = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC");
        $stmt->execute();
        $rawResult = $stmt->fetchAll(); // get all records from db table 

        $total_pages = ceil(count($rawResult) / $no_of_records_per_page); // get total pages
        // record 0 ကနေ တစ်ခါယူ 2ခု, နောက်တစ်ခါယူ 2 ခုဆီ ယူတယ်
        $stmt  = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$no_of_records_per_page");
        $stmt->execute();
        $result = $stmt->fetchAll();
      }
    }




    ?>
    <div class="card-body">
      <div class="mb-3">
        <a href="add.php" class="btn btn-success">New Blog Post</a>
      </div>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th style="width: 10px">#</th>
            <th>Title</th>
            <th>Content</th>
            <th style="width: 40px">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $i = 1;
          if ($result) {
            foreach ($result as $value) {
          ?>
              <tr>
                <td><?php echo $i ?></td>
                <td><?php echo $value['title'] ?></td>
                <td><?php echo substr($value['content'], 0, 100) ?></td>
                <td style="width: 15%;">
                  <a href="./edit.php?id=<?php echo $value['id']; ?>" type="button" class="btn btn-warning">Edit</a>
                  <a href="./delete.php?id=<?php echo $value['id']; ?>" onclick="return confirm('Are you sure you want to delete?');" type="button" class="btn btn-danger">Delete</a>
                </td>
              </tr>
          <?php
              $i++;
            }
          }
          ?>

        </tbody>
      </table>

      <nav aria-label="Page navigation example">
        <ul class="pagination float-right mt-3">
          <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
          <li class="page-item <?php if ($pageno <= 1) {
                                  echo "disabled";
                                } ?>">
            <a class="page-link" href="?pageno=<?php if ($pageno <= 1) {
                                                  echo "#";
                                                } else {
                                                  echo $pageno - 1;
                                                } ?>">Previous</a>
          </li>
          <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
          <li class="page-item <?php if ($pageno >= $total_pages) {
                                  echo "disabled";
                                } ?>">
            <a class="page-link" href="?pageno=<?php if ($pageno >= $total_pages) {
                                                  echo '#';
                                                } else {
                                                  echo $pageno + 1;
                                                } ?>">Next</a>
          </li>
          <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
        </ul>
      </nav>

    </div>


    <!-- /.card -->
  </div>
</div><!-- /.row -->
</div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
</div>
<!-- /.content-wrapper -->



<?php include("footer.html") ?>