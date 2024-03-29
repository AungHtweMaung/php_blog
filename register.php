<?php
session_start();
require("config/config.php");
require("config/common.php");

if ($_POST) {
    if (empty($_POST["name"]) || empty($_POST["email"])  || empty($_POST["password"]) || strlen($_POST["password"]) < 4) {
        if (empty($_POST["name"])) {
            $nameErr = "*Name can't be blank!";
        }
        if (empty($_POST["email"])) {
            $emailErr = "*Email can't be blank!";
        }
        if (strlen($_POST["password"]) < 4) {
            $passwordErr = "*password must be at least 4 characters";
        }
        if (empty($_POST["password"])) {
            $passwordErr = "*password can't be blank!";
        }
    } else {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $role = 0;

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
        $stmt->bindValue(":email", $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // echo "<pre>";    
        if ($user) {
            echo "<script>alert('Email already exist');</script>";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users(name, email, password, role) VALUES(:name, :email, :password, :role)");
            $result = $stmt->execute(
                array(
                    ":name" => $name,
                    ":email" => $email,
                    ":password" => $password,
                    ":role" => $role
                )
            );
            if ($result) {
                echo "<script>alert('Registered Successfully! Please Login');window.location.href='login.php'</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog | Log in</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b>Blog</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <h5 class="text-center">Register New Account</h5>

                <form action="register.php" method="post">
                    <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">

                    <div class="input-group mb-3">
                        <p class="text-danger w-100"><?php echo empty($nameErr) ? '' : $nameErr; ?></p>
                        <input type="text" name="name" class="form-control" placeholder="Name">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <p class="text-danger w-100"><?php echo empty($emailErr) ? '' : $emailErr; ?></p>
                        <input type="email" name="email" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <p class="text-danger w-100"><?php echo empty($passwordErr) ? '' : $passwordErr; ?></p>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="">
                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                        <a href="login.php" class="btn btn-default btn-block">Login</a>
                    </div>
                    <!-- /.col -->

                </form>

                <!-- /.social-auth-links -->

                <!-- <p class="mb-1">
                    <a href="forgot-password.html">I forgot my password</a>
                </p>
                <p class="mb-0">
                    <a href="register.html" class="text-center">Register a new membership</a>
                </p> -->
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
</body>

</html>