<?php

require("../config/config.php");
session_start();
if (empty($_SESSION["user_id"]) && empty($_SESSION["logged_in"])) {
    header("location: ./login.php");
}

if ($_POST) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];

    if (empty($_POST["role"])) {
        $role = 0;
    } else {
        $role = 1;
    }

    // id!=:id ထည့်တာက user email ကို မပြောင်းဘဲ update လုပ်လိုက်တဲ့အခါ error မပြအောင်လို့
    // မဟုတ်ရင် email မပြောင်းဘဲလုပ်တဲ့အခါ db ထဲမှာ အဲ့ email က ရှိပြီးသား အဲ့တော့ email already exist တက်ပြီ
    // Id မတူတဲ့ကောင်တွေ အခြား user တွေရဲ့ email နဲ့တူမှသာ error ပြမှာ 
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
    $stmt->execute(
        array(":email" => $email, ":id" => $id)
    );
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "<script>alert('Email already exist.');</script>";
    } else {

        $stmt = $pdo->prepare("UPDATE users SET name='$name', email='$email', role='$role' WHERE id='$id'");
        $result = $stmt->execute();

        if ($result) {
            echo "<script>alert('Successfully updated!');window.location.href='user_manage.php'</script>";
        }
    }
}

$id = $_GET["id"];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=$id");
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

// if ($result) {

// }

// if ($ans > 0) {
//     
// }
// echo "<pre>";
// var_dump($result);



?>

<?php



?>

<?php 
$title = "Update user";
include("header.php") ?>


<!-- Content Wrapper. Contains page content -->

<div class="col-md-12">
    <div class="card p-3">


        <form method="post" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label>User id</label><br>
                <p style="font-weight: bold;"><?php echo $result["id"] ?></p>

            </div>
            <div class="mb-3">
                <input type="hidden" name="id" value="<?php echo $result['id']; ?>">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" value="<?php echo $result["name"] ?>" class="form-control" aria-describedby="helpId" required>

            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input name="email" class="form-control" id="" value="<?php echo $result["email"] ?>" required>

            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input " <?php if ($result["role"] == 1) {
                                                                        echo "checked";
                                                                    } ?> id="role" name="role">
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