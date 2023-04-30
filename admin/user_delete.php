<?php
    session_start();
    require("../config/config.php");
    
    if (empty($_SESSION["user_id"]) && empty($_SESSION["    ged_in"])) {
        header("location: ./login.php");
    }
    if ($_SESSION["role"] != 1) {
        header("location: login.php");
    }
    $stmt = $pdo->prepare("DELETE FROM users WHERE id=".$_GET["id"]);
    $stmt->execute();
    header("location:user_manage.php");

?>