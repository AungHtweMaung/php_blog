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
    if (empty($_POST["name"]) || empty($_POST["email"])) {
        if (empty($_POST["name"])) {
            $nameErr = "*Name can't be blank!";
        }
        if (empty($_POST["email"])) {
            $emailErr = "*Email can't be blank!";
        }
    } elseif (!empty($_POST["password"]) && strlen($_POST["password"]) < 4) {
        if (strlen($_POST["password"]) < 4) {
            $passwordErr = "*password must be at least 4 characters";
        }
    } else {
        $id = $_POST["id"];
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

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

            if ($password != null) {
                $stmt = $pdo->prepare("UPDATE users SET name='$name', email='$email', password='$password', role='$role' WHERE id='$id'");
                $result = $stmt->execute();

                if ($result) {
                    echo "<script>alert('Successfully updated!');window.location.href='user_manage.php'</script>";
                }
            } else {
                $stmt = $pdo->prepare("UPDATE users SET name='$name', email='$email', role='$role' WHERE id='$id'");
                $result = $stmt->execute();

                if ($result) {
                    echo "<script>alert('Successfully updated!');window.location.href='user_manage.php'</script>";
                }
            }
        }
    }
}

$id = $_GET["id"];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=$id");
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

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
            <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
            <div class="mb-3">
                <label>User id</label><br>
                <p style="font-weight: bold;"><?php echo $result["id"] ?></p>

            </div>
            <div class="mb-3">
                <input type="hidden" name="id" value="<?php echo $result['id']; ?>">
                <label for="name" class="form-label">Name</label>
                <p class="text-danger"><?php echo empty($nameErr) ? '' : $nameErr; ?></p>
                <input type="text" name="name" id="name" value="<?php echo escape($result["name"]) ?>" class="form-control" aria-describedby="helpId">

            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <p class="text-danger"><?php echo empty($emailErr) ? '' : $emailErr; ?></p>
                <input name="email" class="form-control" id="" value="<?php echo escape($result["email"]) ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <p class="text-danger"><?php echo empty($passwordErr) ? '' : $passwordErr; ?></p>
                <span class="text-sm text-muted">The user already has a password.</span>
                <input type="text" name="password" id="password" class="form-control" placeholder="" aria-describedby="helpId">
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input " <?php if ($result["role"] == 1) {
                                                                        echo "checked";
                                                                    } ?> id="role" name="role">
                <label class="form-check-label" for="role">Admin</label>
            </div>
            <div>
                <input type="submit" name="submit" value="Submit" class="btn btn-success">
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