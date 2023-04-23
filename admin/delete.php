<?php
    require("../config/config.php");

    $id = $_GET["id"];
    $sql = "DELETE FROM posts WHERE id='$id'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    header("location:index.php");