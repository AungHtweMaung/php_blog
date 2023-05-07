<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    
        $pass = password_hash("admin", PASSWORD_DEFAULT);
        echo $pass;
        // echo password_verify('admin', $pass);
    ?>
    
</body>

</html>