<?php
    require("../config/config.php");
    
    session_start();
    if (empty($_SESSION["user_id"]) && empty($_SESSION["    ged_in"])) {
        header("location: ./login.php");
    }
    if ($_SESSION["role"] != 1) {
        header("location: login.php");
    }
    $id = $_GET["id"];
    $sql = "DELETE FROM posts WHERE id='$id'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    header("location:index.php");