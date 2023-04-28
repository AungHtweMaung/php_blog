<?php
require("../config/config.php");
session_start();
if (empty($_SESSION["user_id"]) && empty($_SESSION["    ged_in"])) {
    header("location: ./login.php");
}

if ($_POST) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    if ($_POST["role"]) {
        $role = 1;
    } else {
        $role = 0;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->bindValue(":email", $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "<script>alert('Email already exist');</script>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users(name, email, password, role) VALUES (:name, :email, :password, :role)");
        $result = $stmt->execute(
            array(
                ":name" => $name,
                ":email" => $email,
                ":password" => $password,
                ":role" => $role
            )
        );
        if ($result) {
            echo "<script>alert('Created account Successfully!');window.location.href='user_manage.php'</script>";
        }
        
    }
}


?>

<?php
    $title = "Add user";
    include("header.php") 
?>


<!-- Content Wrapper. Contains page content -->

<div class="col-md-12">
    <div class="card p-3">
        <form action="user_add.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="" aria-describedby="helpId" required>

            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" name="email" id="email" class="form-control" placeholder="" aria-describedby="helpId" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="text" name="password" id="password" class="form-control" placeholder="" aria-describedby="helpId" required>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input " id="role" name="role">
                <label class="form-check-label" for="role">Admin</label>
            </div>
            <div>
                <input type="submit" value="Submit" class="btn btn-success">
                <a href="./user_manage.php" class="btn btn-warning">Back</a>
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