<?php

session_start();
require("../config/config.php");
require("../config/common.php");


if (empty($_SESSION["user_id"]) && empty($_SESSION["logged_in"])) {
    header("location: ./login.php");
}
if ($_SESSION["role"] != 1) {
    header("location: login.php");
}

if (isset($_POST["submit"])) {

    if (empty($_POST["title"]) || empty($_POST["content"])) {
        if (empty($_POST["title"])) {
            $titleErr = "*Title can't be blank!";
        }
        if (empty($_POST["content"])) {
            $contentErr = "*Content can't be blank!";
        }
    } else {
        $id = $_POST["id"];
        $title = $_POST["title"];
        $content = $_POST["content"];

        if ($_FILES['image']['name'] != null) {
            $image = $_FILES["image"]["name"];  // get image name
            $target_file = "./image/" . $image;   // 
            $tmp_name = $_FILES["image"]["tmp_name"];

            $imageType = pathinfo($target_file, PATHINFO_EXTENSION);    // get file type extension

            if ($imageType != 'jpg' && $imageType != 'jpeg' && $imageType != 'png') {
                echo "wrong file type";
            } else {
                move_uploaded_file($tmp_name, $target_file);

                $stmt = $pdo->prepare("UPDATE posts SET title='$title', content='$content', image='$image' WHERE id='$id'");
                $result = $stmt->execute();

                if ($result) {
                    echo "<script>alert('Successfully updated!');window.location.href='index.php'</script>";
                }
            }
        } else {
            $stmt = $pdo->prepare("UPDATE posts SET title='$title', content='$content' WHERE id='$id'");
            $result = $stmt->execute();
            if ($result) {
                echo "<script>alert('Successfully updated!');window.location.href='index.php'</script>";
            }
        }
    }
}
$id = $_GET["id"];
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=$id");
$stmt->execute();

if ($stmt->rowCount()>0) {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    header("location:404_page.html");
}
// echo "<pre>";
// var_dump($result);



?>

<?php
$title  = "Update blog";
include("header.php");
?>

<!-- Content Wrapper. Contains page content -->

<div class="col-md-12">
    <div class="card p-3">
        <form method="post" enctype="multipart/form-data">
            <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
            <div class="mb-3">
                <input type="hidden" name="id" value="<?php echo $result['id']; ?>">
                <label for="title" class="form-label">Title</label>
                <p class="text-danger"><?php echo empty($titleErr) ? '' : $titleErr; ?></p>
                <input type="text" name="title" id="title" value="<?php echo escape($result["title"]); ?>" class="form-control" placeholder="" aria-describedby="helpId">

            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <p class="text-danger"><?php echo empty($contentErr) ? '' : $contentErr; ?></p>
                <textarea name="content" class="form-control" id="" cols="30" rows="12"><?php echo escape($result["content"]) ?></textarea>
            </div>
            <div class="mb-3">
                <img src="./image/<?php echo escape($result['image']); ?>" width="150px" height="150px" alt=""><br><br>
                <label for="image" class="form-label">Image</label>
                <input type="file" name="image" id="image" class="" placeholder="" aria-describedby="helpId">
            </div>
            <div>
                <input type="submit" name="submit" value="Submit" class="btn btn-success">
                <a href="./index.php" class="btn btn-warning">Back</a>
            </div>
        </form>

    </div>
</div><!-- /.row -->
</div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
</div>
<!-- /.content-wrapper -->


<?php include("footer.html") ?>