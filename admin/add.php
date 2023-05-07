<?php
session_start();
require("../config/config.php");
require("../config/common.php");

if (empty($_SESSION["user_id"]) && empty($_SESSION["    ged_in"])) {
    header("location: ./login.php");
}
if ($_SESSION["role"] != 1) {
    header("location: login.php");
}

if (isset($_POST["submit"])) {  
    if (empty($_POST["title"]) || empty($_POST["content"])  || empty($_FILES["image"])) {
        if (empty($_POST["title"])) {
            $titleErr = "*Title can't be blank!";
        }
        if (empty($_POST["content"])) {
            $contentErr = "*Content can't be blank!";
        }
        if (empty($_FILES["image"])) {
            $imageErr = "*Image can't be blank!";
        }
    } else {
        $image = $_FILES["image"]["name"];  // get image name
        $target_file = "./image/" . $image;   // 
        $tmp_name = $_FILES["image"]["tmp_name"];

        $imageType = pathinfo($target_file, PATHINFO_EXTENSION);    // get file type extension

        if ($imageType != 'jpg' && $imageType != 'jpeg' && $imageType != 'png') {
            echo "<script>alert('Image must be png, jpg, or jpeg');</script>";
        } else {
            $title = $_POST['title'];
            $content = $_POST["content"];
            move_uploaded_file($tmp_name, $target_file);

            $stmt = $pdo->prepare("INSERT INTO posts(title, content, image, author_id) VALUES(:title, :content, :image, :author_id)");
            $result = $stmt->execute(
                array(
                    ":title" => $title,
                    ":content" => $content,
                    ":image" => $image,
                    ":author_id" => $_SESSION["user_id"]
                )
            );

            if ($result) {
                echo "<script>alert('Successfully added!');window.location.href='index.php';</script>";
            }
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
            <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <p class="text-danger"><?php echo empty($titleErr) ? '' : $titleErr; ?></p>
                <input type="text" name="title" id="title" class="form-control" placeholder="" aria-describedby="helpId">

            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <p class="text-danger"><?php echo empty($contentErr) ? '' : $contentErr; ?></p>
                <textarea name="content" class="form-control" id="" cols="30" rows="12"></textarea>

            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <p class="text-danger"><?php echo empty($imageErr) ? '' : $imageErr; ?></p>
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