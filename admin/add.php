<?php
require("../config/config.php");
session_start();
if (empty($_SESSION["user_id"]) && empty($_SESSION["    ged_in"])) {
    header("location: ./login.php");
}

if ($_POST) {
    $image = $_FILES["image"]["name"];  // get image name
    $target_file = "./image/". $image;   // 
    $tmp_name = $_FILES["image"]["tmp_name"];

    $imageType = pathinfo($target_file, PATHINFO_EXTENSION);    // get file type extension

    if ($imageType != 'jpg' && $imageType != 'jpeg' && $imageType != 'png') {
        echo "Image must be png, jpg, or jpeg.";
    } else {
        $title = $_POST['title'];
        $content = $_POST["content"];
        move_uploaded_file($tmp_name, $target_file);

        $stmt = $pdo->prepare("INSERT INTO posts(title, content, image, author_id) VALUES(:title, :content, :image, :author_id)");
        $result = $stmt->execute(
            array(
                ":title"=>$title,
                ":content"=>$content,
                ":image"=>$image,
                ":author_id"=>$_SESSION["user_id"]
            )
            );

        if ($result) {
            echo "<script>alert('Successfully added!');</script>";
            header("location:./index.php");
        }

    }



    
    
}


?>

<?php 
$title = "Create blog";
include("header.php") 
?>


<!-- Content Wrapper. Contains page content -->

<div class="col-md-12">
    <div class="card p-3">
        <form action="add.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="" aria-describedby="helpId" required>

            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea name="content" class="form-control" id="" cols="30" rows="12" required></textarea>

            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" name="image" id="image" class="" placeholder="" aria-describedby="helpId" required>
            </div>
            <div>
                <input type="submit" value="Submit" class="btn btn-success">
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